# hook_civicrm_membershipTypeValues

## Summary

This hook is called when composing the array of membership types and
their costs during a membership registration (new or renewal).

## Notes

The hook is called on initial page load and also reloaded after submit ([PRG
pattern](https://en.wikipedia.org/wiki/Post/Redirect/Get)). You can use it to alter the membership types when first
loaded, or after submission (for example if you want to gather data in
the form and use it to alter the fees).

## Definition

```php
hook_civicrm_membershipTypeValues(&$form, &$membershipTypeValues)
```

## Parameters

-   object `$form` - the form object that is presenting the page
-   array `$membershipTypeValues` - the membership types and their amounts

## Examples

Give a 50% discount to some memberships in the sample data

```php
function civitest_civicrm_membershipTypeValues(&$form, &$membershipTypeValues) {
  $membershipTypeValues[1]['name'] = 'General (50% discount)';
  $membershipTypeValues[1]['minimum_fee'] = '50.00';

  $membershipTypeValues[2]['name'] = 'Student (50% discount)';
  $membershipTypeValues[2]['minimum_fee'] = '25.00';
}
```

Modify specific fee values

```php
function mymodule_civicrm_membershipTypeValues(&$form, &$membershipTypeValues) {
  foreach ($membershipTypeValues as &$values) {
    if ($values['name'] == 'General') {
      $values['minimum_fee'] = "5.55";
    }
    if ($values['name'] == 'Student') {
      $values['minimum_fee'] = "2.22";
    }
  }
}
```
