# hook_civicrm_caseChange

## Summary

This hook fires whenever a record in a case changes.

## Notes

See also the documentation for [CiviCase Util](https://wiki.civicrm.org/confluence/display/HR/CiviCase+Util).

## Availability

This hook is available in CiviCRM 4.5+.

## Definition

    function caseChange(\Civi\CCase\Analyzer $analyzer)

## Parameters

-   $analyzer - A bundle of data about the case (such as the case and
    activity records).

## Returns

-   null