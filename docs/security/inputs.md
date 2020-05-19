# Securing your inputs

## `GET` parameters

If you have a page or a form which reads parameters from the URL (aka `GET` parameters) like `?cid=1234` or `?action=add`, it's important to understand that attackers can somewhat easily deceive *privileged users* into submitting malicious `GET` requests by directing the user to an email or website with content like: 

```html
<img width="0" height="0" src="https://example.org/civicrm/page?foo=ATTACK" >
```

This means that we can *never* trust `GET` parameters, even if the page has tight [permissions](permissions.md) or [ACLs](access.md)! A common security vulnerability which arises from insecure `GET` inputs is [reflected XSS](https://excess-xss.com/#reflected-xss), but `GET` inputs can also find their way into all sort of other sensitive outputs, like SQL queries.

### Validating `GET` parameters

Use the function `CRM_Utils_Request::retrieve()` to retrieve and validate `GET` parameters. This works great for simple types like integers. For example:

```php
$cid = CRM_Utils_Request::retrieve('cid', 'Positive');
```

Here we have specified `'Positive'` as the type. The acceptable types can be found in [CRM_Utils_Type::validate](https://github.com/civicrm/civicrm-core/blob/60050425316acb3726305d1c34908074cde124c7/CRM/Utils/Type.php#L378).

If you find yourself wanting to use the `'String'` type, beware that this type offers very little validation and hence almost no protection against attacks. Thus, for strings it's important to *add additional validation*, as demonstrated in the following example.

```php
$angPage = CRM_Utils_Request::retrieve('angPage', 'String', $this);
if (!preg_match(':^[a-zA-Z0-9\-_/]+$:', $angPage)) {
  CRM_Core_Error::fatal('Malformed return URL');
}
```

## `POST` parameters

When accepting `POST` parameters through forms, it's important to validate the data using the form validation tools provided by `CRM_Core_Form`.


## When saving to the database

Despite the [current recommended best-practices](index.md#input-vs-output), CiviCRM *does* sanitize some of its *inputs*. This section describes how.

### Input encoding {:#input-encoding}

For almost all inputs which are saved to the database, CiviCRM automatically uses `CRM_Utils_API_HTMLInputCoder::encodeInput()` to apply a *partial* encoding for HTML output. This encoding step happens at a low level for inputs passed through the API or the BAO (except for fields noted in `CRM_Utils_API_HTMLInputCoder::getSkipFields()`). So if you're using the API or the BAO to process your input you don't need to do anything special.

If, for some strange reason, you happen to be writing untrusted data to the database directly with SQL, you should encode this data in a fashion consistent with `CRM_Utils_API_HTMLInputCoder::encodeInput()`.

Note that `CRM_Utils_API_HTMLInputCoder::encodeInput()` only encodes `<` and `>`. It does *not* encode quotes. This has some special implications for how you should [encode your HTML outputs](outputs.md#html).

### Input purification {:#input-purification}

When accepting untrusted data with rich text (uncommon), pass the data through `CRM_Utils_String::purifyHTML` to remove XSS.

## PHPIDS

CiviCRM Implements the PHP Intrusion Detection System to automatically assist in preventing harmful inputs. The PHPIDS system is triggered on all fields. There are standard suite of fields that are excluded and they can be found in the `CRM_Core_IDS` class. The PHPIDS system scans the submitted content and returns a numerical value as to how dangerous the submitted content is from 0  - 100. Three type of actions can be taken based on the numerical score. Either the content is not saved and a message is given out to the user saying there is suspect content which is known as kick. The next action down is just to present a warning to the user. This indicates to the user that there may be some XSS in the content but the context gets saved to the database. The next step down is that the report is logged in the CiviCRM logs and no message is displayed to the user. The PHPIDS is implemented in a bid to assist in preventing XSS, sqli and other dangerous code being saved in the database. More information on PHPIDS can be found in the [documentation](https://github.com/PHPIDS/PHPIDS). Developers are able to alter the list of Exceptions through [hook_civicrm_idsException](../hooks/hook_civicrm_idsException.md). Fields can also be altered through the Menu hooks [hook_civicrm_xmlMenu](../hooks/hook_civicrm_xmlMenu.md#xml-ids) and [hook_civicrm_alterMenu](../hooks/hook_civicrm_alterMenu.md)).
