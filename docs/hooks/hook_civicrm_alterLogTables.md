# hook_civicrm_alterLogTables

## Summary

This hook allows you to amend the specification of the log tables to be
created when logging is turned on.

## Notes

You can adjust the specified tables and the engine and define indexes and exceptions. Note that CiviCRM creates log tables according to the specification at the point of creation. It does not update them if you change the specification, except with regards to adding additional tables. Tables are never automatically dropped.

Turning logging on and off will cause any adjustments to the exceptions to be enacted as that information is in the triggers not the log tables, which are recreated.

There is, however, a function that will convert Archive tables to log tables (one way) if the hook is in play. This has to be done deliberately by calling the `system.updatelogtables` api and it can be a slow process.

## Availability

This hook was first available in CiviCRM 4.7.7.

## Definition

    hook_civicrm_alterLogTables(&$logTableSpec)

## Parameters

-   @param array $logTableSpec

## Example

    This defines all tables as INNODB and adds indexes.

    https://github.com/eileenmcnaughton/nz.co.fuzion.innodbtriggers

    /**
     * Implements hook_alterLogTables().
     *
     * @param array $logTableSpec
     */
    function innodbtriggers_civicrm_alterLogTables(&$logTableSpec) {
      $contactReferences = CRM_Dedupe_Merger::cidRefs();
      foreach (array_keys($logTableSpec) as $tableName) {
        $contactIndexes = array();
        $logTableSpec[$tableName]['engine'] = 'INNODB';
        $logTableSpec[$tableName]['engine_config'] = 'ROW_FORMAT=COMPRESSED KEY_BLOCK_SIZE=4';
        $contactRefsForTable = CRM_Utils_Array::value($tableName, $contactReferences, array());
        foreach ($contactRefsForTable as $fieldName) {
          $contactIndexes['index_' . $fieldName] = $fieldName;
        }
        $logTableSpec[$tableName]['indexes'] = array_merge(array(
          'index_id' => 'id',
          'index_log_conn_id' => 'log_conn_id',
          'index_log_date' => 'log_date',
        ), $contactIndexes);
      }
    }