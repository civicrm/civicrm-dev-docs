# hook_civicrm_batchQuery

## Description

This hook is called when the query of CSV batch export is generated,\
 so hook implementers can provide their own query objects which
alters/extends original query.

## Definition

    hook_civicrm_batchQuery( &$query )

## Parameters

-   $query - A string of SQL Query\
     \

## Example


    function hook_civicrm_batchQuery(&$query) {
      $query = "SELECT * FROM civicrm_financial_item";
    }