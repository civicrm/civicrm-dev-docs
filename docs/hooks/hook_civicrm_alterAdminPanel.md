# hook_civicrm_alterAdminPanel

## Summary

This hook is invoked after all the panels and items on Administer CiviCRM screen have been
generated and allows for direct manipulation of these items and panels.

## Definition

    hook_civicrm_alterAdminPanel(&adminPanel)

## Parameters

-   array adminPanel - array of panels on Adminster CiviCRM screen

## Returns

## Example

    /**
     * Alter panels on administer CiviCRM screen
     */
    function example_civicrm_alterAdminPanel(&$adminPanel) {
      // don't want to show Multi Site Settings to users as a configuration option
      unset($adminPanel['System_Settings']['fields']['weight}.Multi Site Settings']);
    }
