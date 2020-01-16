# hook_civicrm_postProcess

## Summary

This hook is invoked when a CiviCRM form is submitted.

## Notes

If the module has injected any form elements, this hook should save the
values in the database.

This hook is not called when using the API, only when using the regular
forms. If you want to have an action that is triggered no matter if it's
a form or an API, use the pre and post hooks instead.

## Definition

    /**
     * Implements hook_civicrm_postProcess().
     *
     * @param string $formName
     * @param CRM_Core_Form $form
     */
    hook_civicrm_postProcess($formName, $form)

## Parameters

-   string $formName - the name of the form
-   CRM_Core_Form $form - form object

## Returns

-   null - the return value is ignored

## Example

     <?php

    // drupalptsav2.module
    // Store last modified timestamp when user clicks SAVE button on a profile form.

    // Add a field called, e.g., "Last modified", to your profile form, and code it as
    // HTML "Date"; make it view only.  I put mine at the end of the form, and
    // added a help field indictating that the field was set bv the program.

    function drupalptsav2_help( ) {
        switch ($section) {
        case 'admin/modules#description':
    //      Put your module info here
            return t('Implements hooks for CiviCRM to customize the PTSA site');
        default :
            return;
        }
    }

    /**
     * Implements hook_civicrm_postProcess().
     *
     * @param string $formName
     * @param CRM_Core_Form $form
     */
    function drupalptsav2_civicrm_postProcess($formName, $form) {
        if ( is_a( $form, 'CRM_Profile_Form_Contact' ) ) {
            $gid = $form->getVar( '_gid' );
    //      Get your profile id from Administer CiviCRM >> Profile; I'm using  3 and 4
            if ( $gid == 3 ) {
    //          Need your profile # in the call to the edit routine, too!
                drupalptsav2_civicrm_postProcess_CRM_Profile_Form_Edit_3( $formName, $form, $gid );
                return;
            }
            elseif ( $gid == 4 ) {
    //          Need your profile # in the call to the edit routine, too!
                drupalptsav2_civicrm_postProcess_CRM_Profile_Form_Edit_4( $formName, $form, $gid );
                return;
            }
        }
    }


    function drupalptsav2_civicrm_postProcess_CRM_Profile_Form_Edit_3($formName, $form, $gid) {

        $userID   = $form->getVar( '_id' );

    //  directory_info_fields_2 is the actual name of the sql record that holds my profile form
    //  info.  Go into phpMyAdmin or however you browse your sql database to get the name
    //  (look at the civicrm_value schema).
    //  You can do the same thing to get the field name (mine is last_modified_47).  If you
    //  have multiple fields named something similar, you can determine which field you're looking
    //  for by viewing the source code of the Web page that displays your form.
        $query = "
    UPDATE civicrm_value_directory_info_fields_2
    SET last_modified_47 = %1
    WHERE entity_id = %2
    ";

        $params = array(1  => array(CRM_Utils_Date::getToday( null, 'YmdHis' ), 'Timestamp'),
                      2  => array( $userID, 'Integer'));
        CRM_Core_DAO::executeQuery($query, $params);
    }


    function drupalptsav2_civicrm_postProcess_CRM_Profile_Form_Edit_4($formName, $form, $gid) {

        $userID   = $form->getVar('_id');

        $query = "
    UPDATE civicrm_value_volunteer_info_3
    SET last_modified_65 = %1
    WHERE entity_id = %2
    ";

        $params = array(1  => array(CRM_Utils_Date::getToday(null, 'YmdHis'), 'Timestamp'),
                      2  => array( $userID, 'Integer'));
        CRM_Core_DAO::executeQuery($query, $params);
    }
