# hook_civicrm_customPre

## Summary

This hook is called *before* the database write on a custom table.

## Definition

    hook_civicrm_customPre($op, $groupID, $entityID, &$params)

## Parameters

-   string $op - the type of operation being performed
-   string $groupID - the custom group ID
-   object $entityID - the entityID of the row in the custom table
-   array $params - the parameters that were sent into the calling function

## Returns

-   null - the return value is ignored

## Example

    /**
     * This example compares the submitted value of a field with its current value
     */
    function MODULENAME_civicrm_customPre($op, $groupID, $entityID, &$params) {
      foreach ($params as $field) {
        if ($field['column_name'] == 'pipeline_stage') {
          //get existing value
          try {
            $existingValue = civicrm_api3('Contact', 'getvalue', [
              'id' => $field['entity_id'],
              'return' => "custom_{$field['custom_field_id']}",
            ]);

            if ($existingValue != $field['value']) {
              //create Activity to record the change
            }
          }
          catch (CiviCRM_API3_Exception $e) {}
        }
      }
    }
