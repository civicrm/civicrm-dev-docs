# hook_civicrm_unhandledException

## Summary

This hook fires when an unhandled exception (fatal error) occurs.

## Notes

A use case is to show an alternative page to donors rather than a fatal
error screen if a fatal error occurs during a donation.

## Availability

This hook is available in CiviCRM 4.6+.

## Definition

    unhandledException($exception, $request = NULL)

## Parameters

-   $exception - An object of type CRM_Core_Exception Exception.
-   $request - Reserved for future use.

## Returns

-   null