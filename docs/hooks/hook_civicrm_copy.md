# hook_civicrm_copy

## Summary

This hook is called after a CiviCRM object (Event, ContributionPage,
Profile) has been copied.

## Definition

    hook_civicrm_copy( $objectName, &$object )

## Parameters

-   $objectName - the name of the object that is being copied (Event,
    ContributionPage, UFGroup)
-   $object - reference to the copied object

## Returns

-   null