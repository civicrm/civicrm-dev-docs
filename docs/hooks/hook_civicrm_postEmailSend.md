# hook_civicrm_postEmailSend

## Summary

This hook is called when an email has been successfully sent by CiviCRM,
but not on an error.


## Notes

This is only triggered by activity emails, not bulk mailings.

## Definition

    hook_civicrm_postEmailSend( &$params )

## Parameters

-   $params the mailing params

## Details

-   $params array fields include: groupName, from, toName, toEmail,
    subject, cc, bcc, text, html, returnPath, replyTo, headers,
    attachments (array)

## Example

    /**
     * Implementation of hook_civicrm_postEmailSend( )
     * Update the status of activity created in hook_civicrm_alterMailParams, and add target_contact_id
     */
    function mte_civicrm_postEmailSend(&$params) {
      // check if an activityId was added in hook_civicrm_alterMailParams
      // if so, update the activity's status and add a target_contact_id
      if(CRM_Utils_Array::value('activityId', $params)){
        $activityParams = array(
          'id' => $params['activityId'],
          'status_id' => 2,
          'version' => 3,
          'target_contact_id' => CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Email', $params['toEmail'], 'contact_id', 'email'),
        );
        $result = civicrm_api( 'activity','create',$activityParams );
      }
    }