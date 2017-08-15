# hook_civicrm_caseSummary

## Summary

This hook is called when the manage case screen is displayed, and it allows
the injection of label/value pairs which are rendered inside divs
underneath the existing summary table.

## Definition

    hook_civicrm_caseSummary( $caseID )

## Parameters

-   string $caseID - the case ID

## Returns

-   array - an array where the key is a custom id that can be used for
    CSS styling the div and the value is an array with 'label' and
    'value' elements.

## Example

     <?php
    function myModule_civicrm_caseSummary($caseID) {
        /* Quick way to test what some results look like.
        return array(
            array(
                'some_unique_id' => array(
                    'label' => ts('Some Date'),
                    'value' => '2009-02-11',
                 ),
                'some_other_id' => array(
                    'label' => ts('Some String'),
                    'value' => ts('Coconuts'),
                ),
            ),
        );
        */

        // More realistic example, but will return nothing unless you have these activities in your database.
        // TIP: Put these queries into methods in a custom class. You will likely want to re-use them elsewhere, such as in a CiviReport.

        // This query finds the earliest date of return to modified duties in a workplace disability case.
        $params = array( 1 => array( $caseID, 'Integer' ) );
        $sql = "SELECT min(activity_date_time) as mindate
    FROM civicrm_activity a
    INNER JOIN civicrm_case_activity ca on a.id=ca.activity_id
    LEFT OUTER JOIN civicrm_option_group og on og.name='activity_type'
    LEFT OUTER JOIN civicrm_option_value ov on
    (og.id=ov.option_group_id AND ov.name='Return to modified duties')
    WHERE ca.case_id=%1
    AND ov.value=a.activity_type_id
    LIMIT 1";
        $modrtw = CRM_Core_DAO::singleValueQuery( $sql, $params );

        // This query returns the current status of the medical consent as determined by
        // the presence or absence of related activities.
        $sql = "SELECT CASE WHEN count(received.case_id) > 0 THEN 'Received'
    WHEN count(sent.case_id) > 0 AND DATEDIFF(CURRENT_TIMESTAMP, sent.activity_date_time) > 14 THEN 'Overdue'
    WHEN count(sent.case_id) > 0 THEN 'Sent'
    ELSE 'Not Sent'
    END
    FROM
    (SELECT ca.case_id, a1.activity_date_time FROM civicrm_activity a1
    INNER JOIN civicrm_case_activity ca on a1.id=ca.activity_id
    LEFT OUTER JOIN civicrm_option_group og on og.name='activity_type'
    LEFT OUTER JOIN civicrm_option_value ov on
    (og.id=ov.option_group_id AND ov.name='Send Consent Letter')
    WHERE ca.case_id=%1
    AND ov.value=a1.activity_type_id
    LIMIT 1
    ) AS sent

    LEFT OUTER JOIN

    (SELECT ca2.case_id FROM civicrm_activity a2
    INNER JOIN civicrm_case_activity ca2 on a2.id=ca2.activity_id
    LEFT OUTER JOIN civicrm_option_group og2 on og2.name='activity_type'
    LEFT OUTER JOIN civicrm_option_value ov2 on
    (og2.id=ov2.option_group_id AND ov2.name='File Received Consent')
    WHERE ca2.case_id=%1
    AND ov2.value=a2.activity_type_id
    LIMIT 1
    ) AS received
    ON received.case_id=sent.case_id";

        $mcstat = CRM_Core_DAO::singleValueQuery( $sql, $params );

        return array(
            array(
                'modrtw' => array(
                    'label' => ts('Mod RTW:'),
                    'value' => $modrtw,
                ),
                'mcstat' => array( 'label' => ts('Consent Status:'),
                    'value' => ts($mcstat),
                ),
            ),
        );

    Put this in css/extras.css:

    #caseSummary {display: table;}
    #modrtw {display: table-row; border: 1px solid #999999; width: 200px;}
    #mcstat {display: table-row; border: 1px solid #999999; border-left: 0; width: 200px;}
    #caseSummary label {display: table-cell;}
    #caseSummary div {display: table-cell; padding-left: 5px; padding-right: 5px;}
