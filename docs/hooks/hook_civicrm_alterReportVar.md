# hook_civicrm_alterReportVar

## Summary

This hook is used to add or modify display columns and filters.

## Definition

    alterReportVar($varType, &$var, $reportForm) {

## Parameters

* $varType - a string containing the value "columns", "rows", "sql", "actions", or "outputhandlers" depending on where the hook is called.
* &$var - a mixed var containing the columns, rows, or SQL, depending on where the hook is called
* $reportForm - a reference to the CRM_Report_Form object.

### Explanation of varType values

* columns - this is called early in report processing and allows you to add/change the columns in the report. `$var` will contain the existing `$reportForm->_columns` member.
*   rows - this is called near the end of report processing, but before custom fields have been formatted. `$var` will contain an array of rows indexed sequentially.
*   sql - this is called shortly before the query is executed. `$var` here is the same as `$reportForm`. You can alter `$var->_select`, `$var->_from`, etc.
*   actions - this is called after the actions dropdown is built. `$var` is an array of rows of the form `'report_instance.csv' => ['title' => 'Export to CSV']`.
    * The value arrays may contain other entries besides 'title', e.g. for save/copy operations.
    * If you need your additions to be integrated with outputhandlers below, then the top-level keys should start with `report_instance.` the same as the core entries.
* outputhandlers - this is called when building the list of possible candidate implementations for a given output format, e.g. the built-in CSV, PDF, and Print formats. `$var` is an array of class names with rows of the form `'\CRM_Report_OutputHandler_Csv' => '\CRM_Report_OutputHandler_Csv'`.
    * The value is duplicated in the key to both avoid duplicates and make it easier to unset one you might want to remove.

## Returns

* null
  
!!! note "Performance Considerations"
    It is often more performant to change the report query on $varType == 'sql' than to do database lookups on each row in the rows in the $var array on $varType == 'rows'. 

## Example 1

From the [Mandrill Transactional Email](https://github.com/JMAConsulting/biz.jmaconsulting.mte) extension, this code checks to see if this is a mailing bounce/open/clicks/detail report.  If so, it uses mailing data stored in a custom table "civicrm_mandrill_activity", and sets the SQL and columns appropriately.

``` php
/**
 * Implementation of hook_civicrm_alterReportVar
 */
function mte_civicrm_alterReportVar($varType, &$var, $reportForm) {
  $instanceValue = $reportForm->getVar('_instanceValues');
  if (!empty($instanceValue) && in_array($instanceValue['report_id'], array(
    'Mailing/bounce',
    'Mailing/opened',
    'Mailing/clicks',
    'mailing/detail',
  ))
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

```

## Example 2

Implementing $varType 'actions' and 'outputhandlers'.

``` php
/// FILE: example.php

/**
 * Implementation of hook_civicrm_alterReportVar.
 */
function example_civicrm_alterReportVar($varType, &$var, $reportForm) {
  switch ($varType) {
    case 'outputhandlers':
      // Add my own
      $var['\My\Namespace\SampleOutputHandler'] = '\My\Namespace\SampleOutputHandler';

      // Don't let people use the built-in pdf output.
      // Disabling it here as well as 'actions' will also prevent the
      // mail_report job from using format=pdf.
      unset($var['\CRM_Report_OutputHandler_Pdf']);

      // Override the built-in csv with my own (or you could unset and add
      // your own, and then also add in 'actions')
      $var['\CRM_Report_OutputHandler_Csv'] = '\My\Namespace\CsvOutputHandler';
      break;

    case 'actions':
      // Add my own
      $var['report_instance.sample'] = ['title' => 'Export Sample'];

      // Don't let people use the built-in pdf output
      unset($var['report_instance.pdf']);
      break;
  }
}

/**
 * You may also want to implement hook_civicrm_links to update the
 * links on the report instance listing pages.
 *
 * Implementation of hook_civicrm_links.
 */
function example_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if ($op == 'view.report.links') {
    // These have slightly different array keys than other links.
    // See CRM/Report/Page/InstanceList.php
    $links['sample'] = [
      'label' => E::ts('Export to Sample'),
      'url' => CRM_Utils_System::url(
        "civicrm/report/instance/{$objectId}",
        'reset=1&force=1&output=sample'
      ),
    ];
  }
}

/// FILE: My/Namespace/CsvOutputHandler.php
///
/// Note we're extending the original built-in CSV class in order to
/// override part of its functionality.

namespace My\Namespace;
use CRM_Example_ExtensionUtil as E;
class CsvOutputHandler extends \CRM_Report_OutputHandler_Csv {
  // We just want to override the email body text because here we don't
  // want to include the link to the report and the stock wording,
  // otherwise this class works the same as the core one, so this is all
  // we need to do.
  public function getMailBody():string {
    return $this->getForm()->getReportHeader()
      . E::ts('Here is my custom email body text.')
      . $this->getForm()->getReportFooter();
  }
}

/// FILE: My/Namespace/SampleOutputHandler.php
///
/// Here we're adding a whole new type of output format.

namespace My\Namespace;
use CRM_Example_ExtensionUtil as E;
class SampleOutputHandler extends \Civi\Report\OutputHandlerBase {
  /**
   * Override functions from the base class as needed.
   *
   * If you really want to start from scratch you don't have to extend,
   * but then the class declaration needs to implement
   * \Civi\Report\OutputHandlerInterface.
   */

  public function isOutputHandlerFor(\CRM_Report_Form $form):bool {
    // You can have more complicated decision-making, e.g. based on the
    // report classname, or the instance id which you can get from
    // $form->getID(), but it should also be related somehow to what
    // you used in the $varType == 'actions' hook.
    return ($form->getOutputMode() === 'sample');
  }

  // override other functions based on what this format is ...
}

```

There is also a working [sample extension](https://lab.civicrm.org/DaveD/examplereportoutputhandler) available.
