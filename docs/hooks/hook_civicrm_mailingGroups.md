# hook_civicrm_mailingGroups

## Summary

This hook is called when composing a mailing allowing you to include or exclude
other groups as needed.

## Definition

```php
hook_civicrm_mailingGroups(&$form, &$groups, &$mailings)
```

## Parameters

-   object `$form` - the form object for which groups / mailings being displayed

-   array `$groups` - the list of groups being included / excluded

-   array `$mailings` - the list of mailings being included / excluded

## Returns

-   `NULL` - the return value is ignored

## Example

```php
function civitest_civicrm_mailingGroups( &$form, &$groups, &$mailings ) {

  // unset group id 4
  unset( $groups[4] );

  // add a fictitious mailing
  $mailings[1] = 'This mailing does not exist';
}
```
