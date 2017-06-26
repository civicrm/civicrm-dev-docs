# hook_civicrm_install

## Summary

This hook is called when an extension is installed.

## Notes

To be specific, this hook is called when an extension's
status changes from ***uninstalled*** to ***enabled***

It is *NOT* called when an extension moves from ***disabled*** to ***enabled***
(but `hook_civicrm_enable` is called at this event). Each module will
receive `hook_civicrm_install` during its own installation (but not
during the installation of unrelated modules).

For more background, see [API and the Art of
Installation](http://civicrm.org/blogs/totten/api-and-art-installation).

## Parameters

-   None

## Returns

-   Void