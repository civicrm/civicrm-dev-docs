# hook_civicrm_batchQuery

## Summary

This hook is called when the query of CSV batch export is generated

## Notes

With this hook you can provide your own query objects which alter or extend the original query.

## Definition

    hook_civicrm_batchQuery( &$query )

## Parameters

-   $query - A string of SQL Query

## Example


    function hook_civicrm_batchQuery(&$query) {
      $query = "SELECT * FROM civicrm_financial_item";
    }