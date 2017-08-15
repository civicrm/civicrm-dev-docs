# hook_civicrm_validate

## Summary

**(Removed)** This hook is invoked during all CiviCRM form validation. An array of errors
detected is returned. Else we assume validation succeeded.

## Availability

This hook was **removed in v4.7**.

## Definition

```php
hook_civicrm_validate($formName, &$fields, &$files, &$form)
```
## Parameters

* string `$formName` - The name of the form.
* array `&$fields` - the POST parameters as filtered by QF
* array `&$files` - the FILES parameters as sent in by POST
* array `&$form` - the form object

## Returns

* mixed - formRule hooks return a boolean or an array of error messages which display a QF Error
