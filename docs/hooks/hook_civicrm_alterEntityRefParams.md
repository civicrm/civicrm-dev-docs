# hook_civicrm_alterEntityRefParams

## Summary

This hook is called when an `entityRef` field is rendered in a form, which allows you to modify the parameters used to fetch options for this kind of field.

## Availability

This hook is available in CiviCRM 4.7.28 and later.

## Definition

     hook_civicrm_alterEntityRefParams(&$params, $formName)

## Parameters

- array `$params` - parameters of entityRef field
- string `$formName` - form name

## Example

```php
function myextension_civicrm_alterEntityRefParams(&$params, $formName) {
  // use your custom API to fetch tags of your choice on specific form say on 'New Individual'
  if ($formName == 'CRM_Contact_Form_Contact' && $params['entity'] == 'tag') {
    $params['entity'] = 'my_tags';
    $params['api'] = array('params' => array('parent_id' => 292));
  }
  // ...
}
```
