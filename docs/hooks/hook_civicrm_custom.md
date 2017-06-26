# hook_civicrm_custom

## Summary

This hook is called *after* the database write on a custom table.

## Definition

    hook_civicrm_custom( $op, $groupID, $entityID, &$params )

## Parameters

-   string $op - the type of operation being performed
-   string $groupID - the custom group ID
-   object $entityID - the entityID of the row in the custom table
-   array $params - the parameters that were sent into the calling
    function

## Returns

-   null - the return value is ignored

## Example

    /**
     * This example generates a custom contact ID (year + number, ex: 20080000001)
     */

    function MODULENAME_civicrm_custom( $op, $groupID, $entityID, &$params ) {
        if ( $op != 'create' && $op != 'edit' ) {
            return;
        }

        if ($groupID == 1) {
            $needs_update = false;
            $tableName = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_CustomGroup',
         $groupID,
         'table_name' );


            $sql = "SELECT member_id_4 FROM $tableName WHERE entity_id = $entityID";
            $dao = CRM_Core_DAO::executeQuery( $sql, CRM_Core_DAO::$_nullArray );

            if (! $dao->fetch()) {
                $needs_update = true;
            }

            // Value may also be empty. i.e. delete the value in the interface to reset the field.
            if (! $dao->member_id_4) {
                $needs_update = true;
            }

            if ($needs_update) {
                    $member_id = date('Y') . sprintf('%07d', $entityID);

                    $sql = "UPDATE $tableName SET member_id_4 = $member_id WHERE entity_id = $entityID";
                    CRM_Core_DAO::executeQuery( $sql, CRM_Core_DAO::$_nullArray );
            }
        }
    }