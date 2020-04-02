# hook_civicrm_alterUFFIelds

## Summary

This hook allows for the modification of the available fields that are permissible for use within a profile. This might be useful for when you have an extension that has defined it's own entities or it is seeking to permit a core component that doesn't show up in profiles by default e.g. Grants

## Definition

    hook_civicrm_alterUFFields(&$fields) {

## Parameters

-   $fields - a multidimential array of Entities and their fields.
      For each field contained within the array there are at the following properties
        - name
        - title
        - export
        - import
        - hasLocationType
        - field_type - Entity of the field
        - other field keys as returned by entity.getfields

## Returns

-   null

## Example

```php
  /**
   * Implementation of hook_civicrm_alterUFFIelds
   */
  function mte_civicrm_alterUFFields(&$fields) {
    // Include grant fields in the permissible array
    $fields['Grant'] = CRM_Grant_DAO_Grant::export();
  }
```
