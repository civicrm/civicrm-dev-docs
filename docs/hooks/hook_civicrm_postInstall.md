# hook_civicrm_postInstall

## Summary

This hook is called immediately after an extension is installed.

## Notes

-   Unlike most CiviCRM hooks, hook_civicrm_postInstall is defined not
    in CRM_Utils_Hook but in CRM_Extension_Manager_Module.
-   Each module will receive hook_civicrm_postInstall after its own
    installation (but not following the installation of unrelated
    modules).

## Definition

    hook_civicrm_postInstall()

## Parameters

-   None

## Returns

-   Void

## Example

This hook may be useful as a final installation step. Use it to perform
tasks which depend on something that is a product of the installation
itself.

For example, as of civix version 16.9.0, it is used to record the schema
version number (i.e., which upgrade_N methods have run) in the
civicrm_extension table. This step has to be performed in
hook_civicrm_postInstall because the record doesn't yet exist to be
updated in hook_civicrm_install.

Another potential use is to act on settings or managed entities that are
created during the installation (but not necessarily in order that you
want them to be created):

    function hook_civicrm_postInstall() {
      $customFieldId = civicrm_api3('CustomField', 'getvalue', array(
        'return' => array("id"),
        'name' => "customFieldCreatedViaManagedHook",
      ));
      civicrm_api3('Setting', 'create', array(
        'myWeirdFieldSetting' => array('id' => $customFieldId, 'weirdness' => 1),
      ));
    }






\