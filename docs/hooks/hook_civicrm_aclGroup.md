# hook_civicrm_aclGroup

## Summary

This hook is called when composing the ACL to restrict access to civicrm
entities (civicrm groups, profiles and events).

## Notes

In order to use this hook you must uncheck "View All Contacts" AND "Edit All Contacts"
in Drupal Permissions for the user role you want to limit. You can then
go into CiviCRM and grant permission to Edit or View "All Contacts" or
"Certain Groups". See the Forum Topic at:
[http://forum.civicrm.org/index.php/topic,14595.0.html](http://forum.civicrm.org/index.php/topic,14595.0.html)
for more information.

## Definition

    hook_civicrm_aclGroup( $type, $contactID, $tableName, &$allGroups, &$currentGroups )

## Parameters

-   $type the type of permission needed
-   $contactID the contactID for whom the check is made
-   $tableName the tableName which is being permissioned
-   $allGroups the set of all the objects for the above table
-   $currentGroups the set of objects that are currently permissioned
    for this contact . This array will be modified by the hook

## Returns

-   null - the return value is ignored

## Example

Check [HRD Module](http://svn.civicrm.org/hrd/trunk/drupal/hrd.module)