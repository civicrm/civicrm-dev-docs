# hook_civicrm_idsException

## Summary

This hook allows you to modify the list of form or page paths where
submitted data should not be sent through PHPIDS, the intrusion
detection system (IDS).

## Notes

This is one of two ways to bypass the IDS. The other is a CMS-level permission "skip IDS check".

## Definition

    hook_civicrm_idsException(&$skip)

## Parameters

-   $skip - an array of paths that should be skipped.

The initial value of $skip is defined in CRM_Core_IDS::check(), which
is where this hook is invoked.

## Returns

-   null

## Example

    /**
     * Implementation of hook_civicrm_idsException().
     *
     * Prevent values on my form from being processed by the IDS
     */
    function myextension_civicrm_idsException(&$skip) {
      $skip[] = 'civicrm/myform';
    }