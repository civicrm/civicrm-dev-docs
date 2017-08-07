# Secure Coding Standards

## Introduction

CiviCRM maintains a number of standard practices which help ensure that CiviCRM is as secure as possible. This chapter will aim to help give developers guidance on the best way to write code for CiviCRM core and Extensions etc in a secure way. 

## Inputs and outputs

Like any large application, CiviCRM has many inputs and many outputs &mdash; and for adequate security, it must ensure that all data which flows from untrusted inputs to sensitive outputs receives *sanitizing* at some point along the way to protect against attacks.

![Inputs vs outputs diagram](/img/security-inputs-and-outputs.svg)

### Bad example

Consider the following PHP code:

```php
$contactId = $_GET['cid']; // Untrusted input
$sql = "
 SELECT display_name
 FROM civicrm_contact
 WHERE id = $contactID;
";
$query = CRM_Core_DAO::executeQuery($query); // Sensitive output
```

This is bad because because a user can send the following string for the `cid` parameter:

```text
0 UNION SELECT api_key FROM civicrm_contact WHERE id = 4
```

With this attack, the response page would display the API key (for any contact the attacker chooses) anywhere the page would normally display the contact's name. This is an information disclosure vulnerability.

!!! note
    You might think that an input like ``0; DROP TABLE `civicrm_contact` `` would present an [even more serious a vulnerability](https://xkcd.com/327/), but fortunately CiviCRM does not allow [query stacking](http://www.sqlinjection.net/stacked-queries/) which means `executeQuery()` can only execute one query at a time.

### A improvement using sanitizing

In order to fix this security vulnerability, we need to sanitize either (or both!) the input or output as follows:

```php
$contactId = CRM_Utils_Request::retrieve(
  'cid',
  'Positive' // <-- Input sanitizing
);
$sql = "
  SELECT display_name
  FROM civicrm_contact
  WHERE contact_id = %1;
";
$displayName = CRM_Core_DAO::executeQuery($query, array(
  1 => array($contactId, 'Integer'), // <-- Output sanitizing
));
```

Now, users will only be able to send integers in, and CiviCRM will only be able to send integers out. This is obviously a simplified example, but it illustrates the concepts of inputs, outputs, and sanitizing.

## Escape on Input v Escape on Output

Escaping on input means that developers ensure that every single input from their Interface(s) are properly escaped before passing them into the database. This has a major issue for an application like CiviCRM because there are too many various interfaces to try and do proper escape on Input. There is also a risk that when you escape on input you can dramatically change the value and strip out some data through the escaping process.  Where as escaping on output means you have to cover all your various interfaces, ensure that all of them properly and safely account for the possibility that there maybe unsafe data in your database and sanitise it for safe viewing / usage in for example HTML or AngularJS templating. 

CiviCRM has long been confused and staggered in regards to whether to escape on output or escape on input. CiviCRM are slowly moving towards escaping on output for most purposes however there is still a need for escaping on input when dealing with writing queries against the database. At present the simplest way to escape on output is to use inbuilt escape functions within our templating engine Smarty. For example:

```
<a  href="{$item.url}" title="{$item.title|escape:'html'}">
```

This will ensure that the variable title within the item key when generating a list of recently viewed items won't have any Cross Site Scripting as it will be escaped for use within HTML. For more information on the types of escaping you can do with Smarty see the [Smarty Documentation](https://www.smarty.net/docsv2/en/language.modifier.escape)

However sometimes to escape on output you need to ensure that because of the complex nature of the variable that the variable is properly escaped when passed to Smarty. For example, when building a json encoded blob of data for use in an contribution form it was necessary to escape before passing onto the Smarty Template.

```php
$form->assign('submittedOnBehalfInfo', json_encode(str_replace('"', '\"', $form->_submitValues['onbehalf']), JSON_HEX_APOS));
```

For AngularJS templates, developers should consult the AngularJS [$sanitize documentation](https://docs.angularjs.org/api/ngSanitize/service/$sanitize).

## Handling Request variables

Through the CiviCRM code base you will find that there are a number of times where CiviCRM takes variables passed to it through the URL e.g. `?cid=1234` or `?id=1234`. CiviCRM has put in place some inbuilt functions that help to ensure that no dangerous values are able to be passed through.

```php
$cid = CRM_Utils_Request::retrieve('cid', 'Positive', $this);
$id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE, NULL, 'GET');
$angPage = CRM_Utils_Request::retrieve('angPage', 'String', $this);
if (!preg_match(':^[a-zA-Z0-9\-_/]+$:', $angPage)) {
  CRM_Core_Error::fatal('Malformed return URL');
}
$backUrl = CRM_Utils_System::url('civicrm/a/#/' . $angPage);
```

What you will notice above is that one of the key things there is the usage of `CRM_Utils_Request::retrieve` This function takes in whatever request variables have been passed to the page or form etc, gets the key requested out of it, then ensures that it meets a specific type of value. The acceptable types can be found in [CRM_Utils_Type::validate](https://github.com/civicrm/civicrm-core/blob/60050425316acb3726305d1c34908074cde124c7/CRM/Utils/Type.php#L378). 

## Passing variables into SQL

Developers should ensure that whenever they pass variables into SQL statements that they do it in the proper standard. More information can be found in the [SQL Coding Standards](/standards/sql/).

## References

 - Escape on Input v Escape on output [Stack exchange](https://security.stackexchange.com/questions/95325/input-sanitization-vs-output-sanitization) [Stack Overflow](https://stackoverflow.com/questions/11253532/html-xss-escape-on-input-vs-output).
