# hook_civicrm_optionValues

!!! warning "Deprecated"
    This hook is deprecated in 4.7 in favor of [hook_civicrm_fieldOptions](/hooks/hook_civicrm_fieldOptions.md). Use that instead for modifying all option lists, not limited to items in the `civicrm_option_values` table.


## Summary

This hook is called after a option group is loaded. You can use this
hook to add/remove options from the option group.

## Definition

```php
hook_civicrm_optionValues(&$options, $groupName)
```

## Parameters

-   array `$options` - the current set of options
-   string `$groupName` - the name of the option group

## Returns

-   `NULL`
