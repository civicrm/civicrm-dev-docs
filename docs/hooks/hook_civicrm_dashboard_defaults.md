# hook_civicrm_dashboard_defaults

## Summary

This hook is called while a contact views their dashboard for the first time and can be used to enable or disable the set of default dashlets.


## Definition

    hook_civicrm_dashboard_defaults($availableDashlets, &$defaultDashlets);

## Parameters

-   $availableDashlets - list of dashlets
-   $defaultDashlets - list of existing default dashlets

## Returns

## Example

    <?php
    function civitest_civicrm_dashboard_defaults($availableDashlets, &$defaultDashlets){
       $contactID = CRM_Core_Session::singleton()->get('userID');
       $defaultDashlets[] = array(
        'dashboard_id' => 3,
        'is_active' => 1,
        'column_no' => 1,
        'contact_id' => $contactID,
       );
    }