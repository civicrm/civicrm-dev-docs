# hook_civicrm_pre_case_merge

## Summary

This hook is called before a case merge happens.

## Notes

A case merge is
when two cases are merged or when a case is reassigned to another
client.

Added in CiviCRM 4.5

## Definition

    civicrm_pre_case_merge($mainContactId, $mainCaseId, $otherContactId, $otherCaseId, $changeClient)

## Parameters

-   $mainContactId - Contact ID of the new case (if set already)
-   $mainCaseId - Case ID of the new case (if set already)
-   $otherContactId - Contact ID of the original case
-   $otherCaseId - Case ID of the original case
-   $changeClient - boolean if this function is called to change
    clients

## Return

-   Returns null

## Example

See for an example the documentation of the
[hook_civicrm_post_case_merge](hook_civicrm_post_case_merge.md)

## See also

[hook_civicrm_post_case_merge](hook_civicrm_post_case_merge.md)