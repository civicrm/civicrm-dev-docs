# hook_civicrm_postMailing

## Summary

This hook is called at the successful completion of a bulk mailing done through CiviMail.

## Definition

    hook_civicrm_postMailing( $mailingId )

## Parameters

-   $mailingId : the ID for the mailing

## Example

    /**
     * Implementation of hook_civicrm_postMailing()
     */
    function myextension_civicrm_postMailing($mailingId) {
      $report = CRM_Mailing_BAO_Mailing::report($mailingId);
      if (!empty($report['created_id'])) {
        // Store activity in mailing creator's record
        $params = array(
          'status_id' => 2,
          'target_contact_id' => $report['created_id'],
          'source_contact_id' => 1,
          'activity_type_id' => 1,
          'subject' => "Mailing $mailingId has completed.",
          'activity_date_time' => 'now',
        );
        civicrm_api3('Activity', 'create', $params);
      }
    }