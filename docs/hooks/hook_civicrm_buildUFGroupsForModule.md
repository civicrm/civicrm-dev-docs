# hook_civicrm_buildUFGroupsForModule

## Summary

This hook is called when ufgroups (profiles) are being built for a
module.

## Notes

The most common use case for this hook is to edit which profiles are
visible on the Contact Dashboard or (Drupal) user registration page
based on arbitrary criteria (e.g. whether the contact has a particular
contact subtype).

## Availability

This hook is available in CiviCRM 4.1+.

## Definition

    buildUFGroupsForModule($moduleName, &$ufGroups)

## Parameters

-   $moduleName - a string containing the module name (e.g. "User
    Registration", "User Account", "Profile", "CiviEvent").
-   &$ufGroups - an array of ufgroups (profiles) available for the
    module.

## Returns

-   null