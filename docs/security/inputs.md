# Securing your inputs

## `GET` parameters

If you have a page or a form which reads parameters from the URL (aka `GET` parameters) like `?cid=1234` or `?action=add`, it's important to understand that attackers can somewhat easily deceive *privileged users* into submitting malicious `GET` requests by directing the user to an email or website with content like: 

```html
<img width="0" height="0" src="https://example.org/civicrm/page?foo=ATTACK" >
```

This means that we can *never* trust `GET` parameters, even if the page has tight [permissions](/security/permissions.md) or [ACLs](/security/access.md)! A common security vulnerability which arises from insecure `GET` inputs is [reflected XSS](https://excess-xss.com/#reflected-xss), but `GET` inputs can also find their way into all sort of other sensitive outputs, like SQL queries.

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

TODO


## When saving to the database

### Input encoding {:#input-encoding}

For almost all inputs which are saved to the database, CiviCRM automatically uses `CRM_Utils_API_HTMLInputCoder::encodeInput()` to apply a *partial* encoding for HTML output. This encoding step happens at a low level for inputs passed through the API or the BAO (except for fields noted in `CRM_Utils_API_HTMLInputCoder::getSkipFields()`). So if you're using the API or the BAO to process your input you don't need to do anything special.

If, for some strange reason, you happen to be writing untrusted data to the database directly with SQL, you should encode this data in a fashion consistent with `CRM_Utils_API_HTMLInputCoder::encodeInput()`.

Note that `CRM_Utils_API_HTMLInputCoder::encodeInput()` only encodes `<` and `>`. It does *not* encode quotes. This has some special implications for how you should [encode your HTML outputs](/security/outputs.md#html).

### Input purification {:#input-purification}

When accepting untrusted data with rich text (uncommon), pass the data through `CRM_Utils_String::purifyHTML` to remove XSS.
