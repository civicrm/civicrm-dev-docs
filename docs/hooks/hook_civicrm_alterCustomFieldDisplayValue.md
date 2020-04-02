# hook_civicrm_alterCustomFieldDisplayValue

## Summary

This hook allows modification of custom field value for an entity eg Individual, Contribution etc before its displayed on screen. This might be useful if you want to alter the value of the custom field that's being displayed on the screen based on some condition.

## Definition

    hook_civicrm_alterCustomFieldDisplayValue(&$displayValue, $value, $entityId, $fieldInfo) {

## Parameters

-   $displayValue - String that will be displayed on screen.
-   $value - Value from the database for the entity id.
-   $entityId - Entity Id.
-   $fieldInfo - Array having details of custom field like name, label, custom_group_id etc.

## Returns

-   null

## Example

```php
  /**
   * Implementation of hook_civicrm_alterCustomFieldDisplayValue
   */
  function extension_civicrm_alterCustomFieldDisplayValue(&$displayValue, $value, $entityId, $fieldInfo) {
    if ($fieldInfo['name'] == 'alter_cf_field') {
      $displayValue = 'New value';
    }
  }
```
