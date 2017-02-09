# hook_civicrm_optionValues

!!! warning "Deprecated"
    This hook is deprecated in 4.7 in favor of [hook_civicrm_fieldOptions](https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_fieldOptions). Use that instead for modifying all option lists, not limited to items in the civicrm_option_values table.


## Description

This hook is called after a option group is loaded. You can use this
hook to add/remove options from the option group.

## Definition

    civicrm_optionValues(&$options, $name)

## Parameters

-   $options - the current set of options
-   $name - the name of the option group

## Returns

-   null