# hook_civicrm_triggerInfo

## Summary

This hook allows you to define MySQL triggers.

## Notes

Using the hooks causes them not to clash with
core or other extension triggers. They are compiled into one trigger
with core triggers.

!!! note
    Once the function is created, visit the following URL to rebuild triggers
    and create to create the new trigger:

    `http://example.com/civicrm/menu/rebuild?reset=1&triggerRebuild=1`


## Definition

```php
hook_civicrm_triggerInfo(&$info, $tableName)
```

## Parameters

* array `$info` - array of triggers to be created
* string `$tableName` - not sure how this bit works



## Returns

-   ??


## Example

Add trigger to update custom region field based on postcode (using a lookup 
table)
 
Note that this example uses hard-coded a prioritisation of location types
(since it was customer specific code and unlikely to change).

```php
function regionfields_civicrm_triggerInfo(&$info, $tableName) {
  $table_name = 'civicrm_value_region_13';
  $customFieldID = 45;
  $columnName = 'region_45';
  $sourceTable = 'civicrm_address';
  $locationPriorityOrder = '1, 3, 5, 2, 4, 6'; // hard coded prioritisation of addresses
  $zipTable = 'CANYRegion';
  if(civicrm_api3('custom_field', 'getcount', array('id' => $customFieldID, 'column_name' => 'region_45', 'is_active' => 1)) == 0) {
    return;
  }

  $sql = "
    REPLACE INTO `$table_name` (entity_id, $columnName)
    SELECT  * FROM (
      SELECT contact_id, b.region
      FROM
      civicrm_address a INNER JOIN $zipTable b ON a.postal_code = b.zip
      WHERE a.contact_id = NEW.contact_id
      ORDER BY FIELD(location_type_id, $locationPriorityOrder )
    ) as regionlist
    GROUP BY contact_id;
  ";
  $sql_field_parts = array();

  $info[] = array(
      'table' => $sourceTable,
      'when' => 'AFTER',
      'event' => 'INSERT',
      'sql' => $sql,
  );
  $info[] = array(
      'table' => $sourceTable,
      'when' => 'AFTER',
      'event' => 'UPDATE',
      'sql' => $sql,
  );
  // For delete, we reference OLD.contact_id instead of NEW.contact_id
  $sql = str_replace('NEW.contact_id', 'OLD.contact_id', $sql);
  $info[] = array(
      'table' => $sourceTable,
      'when' => 'AFTER',
      'event' => 'DELETE',
      'sql' => $sql,
  );
}
```
