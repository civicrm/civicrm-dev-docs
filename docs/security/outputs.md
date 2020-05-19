# Securing your outputs

## HTML/Smarty {:#html}

Untrusted data placed in HTML must be [encoded](index.md#encoding) for HTML output at some point. The PHP function [htmlentities()](http://php.net/manual/en/function.htmlentities.php) does this, and the Smarty variable modifier [escape](https://www.smarty.net/docsv2/en/language.modifier.escape) behaves similarly.

### Between tags {:#db-between-tags}

#### Database data between tags {:#db-between-tags}

Data which comes out of MySQL has already been [partially encoded for HTML output](inputs.md#input-encoding). This means that when you place this data between HTML tags, you don't need to perform any output encoding. For example:

```html
<div>{$displayName}</div>
```

#### Direct user input between tags {:#inputs-between-tags}

Here we have a bit of a grey area where CiviCRM does not have a consistent approach. If untrusted inputs are placed into HTML before being saved to the database, you need to ensure to perform HTML output encoding *at some point*.

You can perform the output encoding in PHP as follows:

```php
$userInput = htmlentities($userInput);
```

Or you can perform the output encoding in Smarty as follows:

```html
<div>{$userInput|escape}</div>
``` 

!!! tip
    Be wary of using user input in *error messages*. This is a common scenario wherein untrusted user input can end up in HTML with no HTML output encoding.

### HTML attributes {:#in-attributes}

When placing data within attributes, always use Smarty's [escape](https://www.smarty.net/docsv2/en/language.modifier.escape) variable modifier to encode HTML entities.

```html
<a href="#" title="{$displayName|escape}">Foo</a>
```

!!! note
    HTML output encoding *is always* necessary for attribute data (but *not* always necessary for data between tags) because of the intentionally incomplete [input encoding](inputs.md#input-encoding) that CiviCRM performs. 
    
### Javascript in Smarty {:#javascript-smarty}

If you have a PHP variable that you'd like to use in Javascript, you can assign it to a Javascript variable in a Smarty template as follows

```html
<div>...</div>
{literal}
<script type="text/javascript">
  var data = {/literal}{$data|@json_encode}{literal};
</script>
{/literal}
<div>...</div>
```

Notice the use of the `@json_encode` variable modifier. This provides output encoding for JSON which is important to prevent XSS. 

## AngularJS templates {:#angularjs}

The [AngularJS Security Guide](https://docs.angularjs.org/guide/security) says:
    
> Do not use user input to generate templates dynamically

This means that if you put an `ng-app` element in a Smarty template, it's very important that you do not use Smarty to put any user input inside the `ng-app` element.

For example, the following Smarty template would be a security risk:

```html
<div ng-app="crmCaseType">
  <div ng-view=""></div>
  <div>{$untrustedData}</div>
</div>
```

This is bad because the `$untrustedData` PHP variable can contain a string like `{{1+2}}` which AngularJS will execute, opening the door to XSS vulnerabilities. 


## SQL {:#sql}

When writing SQL, it is very important to protect against [SQL injection](https://en.wikipedia.org/wiki/SQL_injection) by ensuring that all variables are passed into SQL with sufficient validation and encoding. CiviCRM has several functions to help with this process, as described below.

### `CRM_Core_DAO::executeQuery` {:#executeQuery}

```php
$name = 'John Smith'; /* un-trusted data */
$optedOut = 0;        /* un-trusted data */

$query = "
  SELECT id
  FROM civicrm_contact
  WHERE
    display_name like %1 AND
    is_opt_out = %2";

$result = CRM_Core_DAO::executeQuery($query, array(
  1 => array('%' . $name . '%', 'String'),
  2 => array($optedOut, 'Integer'),
));
```

This example ensures that variables are safely escaped before being inserted into the query. CiviCRM also allows developers to specify the type of variable that should be allowed. In the case of the `%2` ($optedOut) parameter, only an *Integer* input will be permitted.

The variable types available for this can be found in [CRM_Utils_Type::validate](https://github.com/civicrm/civicrm-core/blob/60050425316acb3726305d1c34908074cde124c7/CRM/Utils/Type.php#L378). The query engine then applies appropriate escaping for the type.

### `CRM_Utils_Type::escape` {:#escape}

In some circumstances you may find that a complex query is easier to build by directly escaping values using the `CRM_Utils_Type::escape()` method. It is prefereable to use the form above or the `CRM_Utils_SQL_Select` format

```php
$name = CRM_Utils_Type::escape('John Smith', 'String');
$column = CRM_Utils_Type::escape('civicrm_contact.display_name', 'MysqlColumnNameOrAlias');
$result = CRM_Core_DAO::executeQuery("SELECT FROM civicrm_contact WHERE $column like '%$name%'");
```

### `CRM_Utils_SQL_Select`

Since CiviCRM 4.7 version there has been an alternate way of generating SQL -- use `CRM_Utils_SQL_Select`. Compared to plain `CRM_Core_DAO`, it has three advantages:

 * The syntax uses pithy [sigils](https://en.wikipedia.org/wiki/Sigil_(computer_programming)) for escaping strings (`@value`), numbers (`#value`) and literal SQL (`!value`).
 * The escaping for array-data is transparent (e.g. `field IN (#listOfNumbers)` or `field IN (@listOfStrings)`).
 * It supports more sophisticated `JOIN`, `GROUP BY`, and `HAVING` clauses.
 * You can build and combine queries in piecemeal fashion with `fragment()` and `merge()`.
 * The general style of query-building is fluent.

A typical example might look like this:

```php
$dao = CRM_Utils_SQL_Select::from('civicrm_contact c')
  ->join('cm', 'INNER JOIN civicrm_membership cm ON cm.contact_id = c.id')
  ->where('c.contact_type = @ctype', array(
    'ctype' => 'Individual',
  ))
  ->where('cm.membership_type_id IN (#types)', array(
    'types' => array(1, 2, 3, 4),
  ))
  ->where('!column = @value', array(
    'column' => CRM_Utils_Type::escape('cm.status_id', 'MysqlColumnNameOrAlias'),
    'value' => 15,
  ))
  ->execute();

while ($dao->fetch()) { ... }
```

Equivalently, you may pass all parameters as a separate array:

```php
$dao = CRM_Utils_SQL_Select::from('civicrm_contact c')
  ->join('cm', 'INNER JOIN civicrm_membership cm ON cm.contact_id = c.id')
  ->where('c.contact_type = @ctype')
  ->where('cm.membership_type_id IN (#types)')
  ->where('!column = @value')
  ->param(array(
    'ctype' => 'Individual',
    'types' => array(1, 2, 3, 4),
    'column' => CRM_Utils_Type::escape('cm.status_id', 'MysqlColumnNameOrAlias'),
    'value' => 15,
  ))
  ->execute();

while ($dao->fetch()) { ... }
```

For convenience, you can chain the `execute()` with other DAO functions like `fetchAll()`, `fetchValue()` or `fetchMap()`.

```php
$records = CRM_Utils_SQL_Select::from('mytable')
  ->select('...')
  ->execute()
  ->fetchAll();
```

Further information on this method can be found in the [CRM_Utils_SQL_Select class](https://github.com/civicrm/civicrm-core/blob/6db7061/CRM/Utils/SQL/Select.php#L33)

## PHP

PHP functions like `eval()` and [many others](https://stackoverflow.com/questions/3115559/exploitable-php-functions/3697776#3697776) will convert strings stored in PHP variables into executable PHP code. If untrusted inputs ever make their way into such strings, critical [code injection](https://www.owasp.org/index.php/Code_Injection) vulnerabilities can arise. It's best to avoid these functions entirely &mdash; and fortunately modern PHP developers almost never need to use such functions. In the rare event that you find yourself needing to convert a string to PHP code, you must make certain that untrusted data is strictly validated.


## Shell commands {:#shell}

Here are some PHP functions which execute shell commands: 

* `exec()`
* `passthru()`
* `system()`
* `shell_exec()`
* `popen()`
* `proc_open()`
* `pcntl_exec()`
* ``` `` ``` (backticks) 

Using these functions can be very risky! If you're inclided to use one of these functions, it's best to spend some time looking a way to *not* use one of the functions. If you really can't find a way around it, then make sure to use [escapeshellarg](http://php.net/manual/en/function.escapeshellarg.php) (and in some cases [escapeshellcmd](http://php.net/manual/en/function.escapeshellcmd.php)) to properly encode data sent to the shell.

