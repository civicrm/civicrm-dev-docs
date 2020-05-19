# hook_civicrm_alterReportVar

## Summary

This hook is used to add or modify display columns and filters.

## Definition

    alterReportVar($varType, &$var, $reportForm) {

## Parameters

-   $varType - a string containing the value "columns", "rows", or
    "sql", depending on where the hook is called.
-   &$var - a mixed var containing the columns, rows, or SQL, depending
    on where the hook is called
-   $reportForm - a reference to the CRM_Report_Form object.

## Returns

-   null
  
!!! note "Performance Considerations"
It is often more performant to change the report query on $varType == 'sql' than
to do database lookups on each row in the rows in the $var array on 
$varType == 'rows'. 

## Example

From the [Mandrill Transaction
Email](https://github.com/JMAConsulting/biz.jmaconsulting.mte)
extension, this code checks to see if this is a mailing
bounce/open/clicks/detail report.  If so, it uses mailing data stored in
a custom table "civicrm_mandrill_activity", and sets the SQL and
columns appropriately.

    /**
     * Implementation of hook_civicrm_alterReportVar
     */
    function mte_civicrm_alterReportVar($varType, &$var, $reportForm) {
      $instanceValue = $reportForm->getVar('_instanceValues');
      if (!empty($instanceValue) &&
        in_array(
          $instanceValue['report_id'],
          array(
            'Mailing/bounce',
            'Mailing/opened',
            'Mailing/clicks',
            'mailing/detail',
          )
        )
      ) {
        if ($varType == 'sql') {
          if (array_key_exists('civicrm_mailing_mailing_name', $var->_columnHeaders)) {
            $var->_columnHeaders['civicrm_mandrill_activity_id'] = array(
              'type' => 1,
              'title' => 'activity',
            );
            $var->_columnHeaders['civicrm_mailing_id'] = array(
              'type' => 1,
              'title' => 'mailing id',
            );
            $var->_select .= ' , civicrm_mandrill_activity.activity_id as civicrm_mandrill_activity_id, mailing_civireport.id as civicrm_mailing_id ';

            $from = $var->getVar('_from');
            $from .= ' LEFT JOIN civicrm_mandrill_activity ON civicrm_mailing_event_queue.id = civicrm_mandrill_activity.mailing_queue_id';
            $var->setVar('_from', $from);
            if ($instanceValue['report_id'] == 'Mailing/opened') {
              $var->_columnHeaders['opened_count'] = array(
                'type' => 1,
                'title' => ts('Opened Count'),
              );
              $var->_select .= ' , count(DISTINCT(civicrm_mailing_event_opened.id)) as opened_count';
              $var->_groupBy = ' GROUP BY civicrm_mailing_event_queue.id';
            }
          }
        }
        if ($varType == 'rows') {
          $mail = new CRM_Mailing_DAO_Mailing();
          $mail->subject = "***All Transactional Emails***";
          $mail->url_tracking = TRUE;
          $mail->forward_replies = FALSE;
          $mail->auto_responder = FALSE;
          $mail->open_tracking = TRUE;
          $mail->find(true);
          if (array_key_exists('civicrm_mailing_mailing_name', $reportForm->_columnHeaders)) {
            foreach ($var as $key => $value) {
              if (!empty($value['civicrm_mandrill_activity_id']) && $mail->id == $value['civicrm_mailing_id']) {
                $var[$key]['civicrm_mailing_mailing_name_link'] = CRM_Utils_System::url(
                  'civicrm/activity',
                  "reset=1&action=view&cid={$value['civicrm_contact_id']}&id={$value['civicrm_mandrill_activity_id']}"
                );
                $var[$key]['civicrm_mailing_mailing_name_hover'] = ts('View Transactional Email');
              }
            }
            unset($reportForm->_columnHeaders['civicrm_mandrill_activity_id'], $reportForm->_columnHeaders['civicrm_mailing_id']);
          }
        }
      }
    }
