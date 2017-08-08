# Securing your inputs

## `GET` parameters

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

## `POST` parameters

TODO

