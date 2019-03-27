# hook_civicrm_aclWhereClause

## Summary

This hook is called when composing the ACL where clause to restrict
visibility of contacts to the logged in user.

## Notes

This hook is called only when filling up the
`civicrm_acl_contact_cache` table, and not every time a contact `SELECT`
query is performed. Those will join onto the
`civicrm_acl_contact_cache` table.

!!! caution "Caveat"
    It will not be called at all if the logged in user has access to the "edit all contacts" permission.

## Definition

    hook_civicrm_aclWhereClause( $type, &$tables, &$whereTables, &$contactID, &$where )

## Parameters

-   $type - Integer type of permission needed, matching these class constants:
    - `CRM_Core_Permission::EDIT`
    - `CRM_Core_Permission::VIEW`
    - `CRM_Core_Permission::DELETE`
    - `CRM_Core_Permission::CREATE`
    - `CRM_Core_Permission::SEARCH`
    - `CRM_Core_Permission::ALL`
    - `CRM_Core_Permission::ADMIN`
-   array $tables - (reference ) add the tables that are needed for the
    select clause
-   array $whereTables - (reference ) add the tables that are needed
    for the where clause
-   int $contactID - the contactID for whom the check is made, i.e. the ContactID of
    the user trying to access the contacts.
-   string $where - the currrent where clause

## Returns

-   void

## Example

    function civitest_civicrm_aclWhereClause( $type, &$tables, &$whereTables, &$contactID, &$where ) {
        if ( ! $contactID ) {
            return;
        }

        $permissionTable = 'civicrm_value_permission';
        $regionTable     = 'civicrm_value_region';
        $fields          = array( 'electorate' => 'Integer',
                                  'province'   => 'Integer',
                                  'branch'     => 'Integer' );

        // get all the values from the permission table for this contact
        $keys = implode( ', ', array_keys( $fields ) );
        $sql = "
    SELECT $keys
    FROM   {$permissionTable}
    WHERE  entity_id = $contactID
    ";
        $dao = CRM_Core_DAO::executeQuery( $sql,
                                           CRM_Core_DAO::$_nullArray );
        if ( ! $dao->fetch( ) ) {
            return;
        }

        $tables[$regionTable] = $whereTables[$regionTable] =
            "LEFT JOIN {$regionTable} regionTable ON contact_a.id = regionTable.entity_id";

        $clauses = array( );
        foreach( $fields as $field => $fieldType ) {
            if ( ! empty( $dao->$field ) ) {
                if ( strpos( CRM_Core_DAO::VALUE_SEPARATOR, $dao->$field ) !== false ) {
                    $value = substr( $dao->$field, 1, -1 );
                    $values = explode( CRM_Core_DAO::VALUE_SEPARATOR, $value );
                    foreach ( $values as $v ) {
                        $clauses[] = "regionTable.{$field} = $v";
                    }
                } else {
                    if ( $fieldType == 'String' ) {
                        $clauses[] = "regionTable.{$field} = '{$dao->$field}'";
                    } else {
                        $clauses[] = "regionTable.{$field} = {$dao->$field}";
                    }
                }
            }
        }

        if ( ! empty( $clauses ) ) {
            $where .= ' AND (' . implode( ' OR ', $clauses ) . ')';
        }
    }
