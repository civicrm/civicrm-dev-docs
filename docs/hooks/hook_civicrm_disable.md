# hook_civicrm_disable

## Summary

This hook is called when an extension is disabled.

## Notes

To be specific, this hook is called when
an extension's status changes from ***enabled*** to ***disabled**.* Each module
will receive `hook_civicrm_disable` during its own disablement (but not
during the disablement of unrelated modules).

## Parameters

-   None

## Returns

-   Void