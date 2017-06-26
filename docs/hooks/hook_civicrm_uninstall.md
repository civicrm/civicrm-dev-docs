# hook_civicrm_uninstall

## Summary

This hook is called when an extension is uninstalled.

## Notes

To be specific, when its status changes from ***disabled*** to ***uninstalled***.

Each module will receive `hook_civicrm_uninstall` during its own
uninstallation (but not during the uninstallation of unrelated modules).

## Parameters

-   None

## Returns

-   Void