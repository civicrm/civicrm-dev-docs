# hook_civicrm_optionValues

## Summary

This hook was deprecated in 4.7 in favor of [hook_civicrm_fieldOptions](hook_civicrm_fieldOptions.md).

## Notes

This hook is called after a option group is loaded. You can use this
hook to add/remove options from the option group.

Use [hook_civicrm_fieldOptions](hook_civicrm_fieldOptions.md) instead for modifying all option lists, not limited to items in the `civicrm_option_values` table.

## Definition

```php
hook_civicrm_optionValues(&$options, $groupName)
```

## Parameters

-   array `$options` - the current set of options
-   string `$groupName` - the name of the option group

## Returns

-   `NULL`
