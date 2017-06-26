# hook_civicrm_queryObjects

## Summary

This hook is called while building the core search query, allowing you to provide your own query objects which alter or extend the core search.

## Definition

    hook_civicrm_queryObjects(&$queryObjects, $type = 'Contact')

## Parameters

-   $queryObjects - An array of Query Objects
-   $type - Search Context \
     \

## Example

    /** Taken from civiHR:/hrjob/hrjob.php **/

    function hrjob_civicrm_queryObjects(&$queryObjects, $type) {
      if ($type == 'Contact') {
        $queryObjects[] = new CRM_HRJob_BAO_Query();
      }
      elseif ($type == 'Report') {
        $queryObjects[] = new CRM_HRJob_BAO_ReportHook();
      }
    }