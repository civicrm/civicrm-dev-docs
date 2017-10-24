# hook_civicrm_alterEntityRefParams

## Summary

This hook is called when an `entityRef` field is rendered in a form, which allows you to modify the parameters used to fetch options for this kind of field.

## Availability

This hook is available in CiviCRM 4.7.27+ (maybe depends on the commit).

## Definition

     hook_civicrm_alterEntityRefParams(&$params)

## Parameters

- array `$params` - parameters of entityRef field

## Example

```php
function myextension_civicrm_alterEntityRefParams(&$params) {
  // use your custom API to fetch tags of your choice
  if ($params['entity'] == 'tag') {
    $params['entity'] = 'my_tags';
    $params['api'] = array('params' => array('parent_id' => 292));
  }
  // ...
}
```
