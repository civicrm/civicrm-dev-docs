hook_civicrm_copy
=================

This hook is called after a CiviCRM object (Event, ContributionPage, Profile) has been copied

* Parameters: 
 	* $objectName - the name of the object that is being copied (Event, ContributionPage, UFGroup)
 	* $object - reference to the copied object

* Returns: 
	* null

* Definition/Example: 
```
hook_civicrm_copy( $objectName, &$object )
```


hook_civicrm_custom
===================
This hook is called AFTER the db write on a custom table

* Parameters

    * string $op - the type of operation being performed
    * string $groupID - the custom group ID
    * object $entityID - the entityID of the row in the custom table
    * array $params - the parameters that were sent into the calling function

* Returns: 
	* null - the return value is ignored

* Definition/Example:

```
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

```

hook_civicrm_managed
====================

This hook allows a module to declare a list of 'managed' entities using the CiviCRM API - a managed entity will be automatically inserted, updated, deactivated, and deleted in tandem with enabling, disabling, and uninstalling the module. The hook is called periodically during cache-clear operations.

For more background, see [API and the Art of Installation](http://civicrm.org/blogs/totten/api-and-art-installation).

* Parameters

    * array $entities - the list of entity declarations; each declaration is an array with these following keys:
        * 'module': string; for module-extensions, this is the fully-qualifed name (e.g. "com.example.mymodule"); for Drupal modules, the name is prefixed by "drupal" (e.g. "drupal.mymodule")
        * 'name': string, a symbolic name which can be used to track this entity (Note: Each module creates its own namespace)
        * 'entity': string, an entity-type supported by the CiviCRM API (Note: this currently must be an entity which supports the 'is_active' property)
        * 'params': array, the entity data as supported by the CiviCRM API
        * 'update' (v4.5+): string, a policy which describes when to update records
            * 'always' (default): always update the managed-entity record; changes in $entities will override any local changes (eg by the site-admin)
            * 'never': never update the managed-entity record; changes made locally (eg by the site-admin) will override changes in $entities
        * 'cleanup' (v4.5+): string, a policy which describes whether to cleanup the record when it becomes orphaned (ie when $entities no longer references the record)
            * 'always' (default): always delete orphaned records
            * 'never': never delete orphaned records
            * 'unused': only delete orphaned records if there are no other references to it in the DB. (This is determined by calling the API's "getrefcount" action.)

* Returns

    * void - the return value is ignored

* Definition/Example: 

```
/**
 * Declare a report-template which should be activated whenever this module is enabled
 */
function modulename_civicrm_managed(&$entities) {
  $entities[] = array(
    'module' => 'com.example.modulename',
    'name' => 'myreport',
    'entity' => 'ReportTemplate',
    'params' => array(
      'version' => 3,
      'label' => 'Example Report',
      'description' => 'Longish description of the example report',
      'class_name' => 'CRM_Modulename_Report_Form_Sybunt',
      'report_url' => 'mymodule/mysbunt',
      'component' => 'CiviContribute',
    ),
  );
}
```

hook_civicrm_merge
==================

This hook allows modification of the data used to perform merging of duplicates. This can be useful if your custom module has added its own tables related to CiviCRM contacts.
Availability

This hook was first available in CiviCRM 3.2.3.

* Parameters

    * $type the type of data being passed (cidRefs|eidRefs|relTables|sqls)
    * $data the data, which depends on the value of $type (see Details)
    * $mainId contact_id of the contact that survives the merge (only when $type == 'sqls')
    * $otherId contact_id of the contact that will be absorbed and deleted (only when $type == 'sqls')
    * $tables when $type is "sqls", an array of tables as it may have been handed to the calling function

* Details

The contents of $data will vary based on the $type of data being passed:

* relTables:

    an array of tables used for asking user which elements to merge, as used at civicrm/contact/merge; each table in the array has this format:
    
    `'rel_table_UNIQUE-TABLE-NICKNAME' => array(
         'title'  => ts('TITLE'),
         'tables' => array('TABLE-NAME' [, ...]),
         'url'    => CRM_Utils_System::url(PATH, QUERY),
    )`
    
* sqls:
    a one-dimensional array of SQL statements to be run in the final merge operation;
    These SQL statements are run within a single transaction.
* cidRefs:
    an array of tables and their fields referencing civicrm_contact.contact_id explicitely;

    each table in the array has this format:
    `'TABLE-NAME' => array('COLUMN-NAME' [, ...])`
* eidRefs:
    an array of tables and their fields referencing civicrm_contact.contact_id with entity_id;

    each table in the array has this format:
    `'TABLE-NAME' => array('entity_table-COLUMN-NAME' => 'entity_id-COLUMN-NAME')`


* Definition/Example:
```
hook_civicrm_merge ( $type, &$data, $mainId = NULL, $otherId = NULL, $tables = NULL )
```
```
/* In this example we assume our module has created its own tables to store extra data on CiviCRM contacts:
 * Table civitest_foo stores a relationship between two contacts, and contains the two relevant columns:
 *       civitest_foo.contact_id and civitest_foo.foo_id, both of which are foreign keys to civicrm_contact.id
 * Table civitest_bar stores extra properties for contacts, and contains one relevant column:
 *       civitest_bar.contact_id is a foreign key to civicrm_contact.id
 *
 * This hook ensures that data in these two tables is included in CiviCRM merge operations.
 */
function civitest_civicrm_merge ( $type, &$data, $mainId = NULL, $otherId = NULL, $tables = NULL ) {
 
    // If you are using Drupal and you use separate DBs for Drupal and CiviCRM, use the following to prefix
    // your tables with the name of the Drupal database.
    global $db_url;
    if (!empty($db_url) {
        $db_default = is_array($db_url) ? $db_url['default'] : $db_url;
        $db_default = ltrim(parse_url($db_default, PHP_URL_PATH), '/');
    }
    else {
      $db_default = '';
    }
 
    switch ($type) {
        case 'relTables':
            // Allow user to decide whether or not to merge records in `civitest_foo` table
            $data['rel_table_foo'] = array(
                'title'  => ts('Foos'),                 // Title as shown to user for this type of data
                'tables' => array($db_default .'civitest_foo'),      // Name of database table holding these records
                'url'    => CRM_Utils_System::url('civicrm/civitest/foo', 'action=browse&cid=$cid'),
                                                        // URL to view this data for this contact,
                                                        // in this case using CiviCRM's native URL utility
                                                        // NOTE: '$cid' will be replaced with correct
                                                        // CiviCRM contact ID.
            );
        break;
 
        case 'cidRefs':
            // Add references to civitest_foo.contact_id, and civitest_foo.foo_id, both of which
            // are foreign keys to civicrm_contact.id.  By adding this to $data, records in this
            // table will be automatically included in the merge.
            $data[$db_default . 'civitest_foo'] = array('contact_id', 'foo_id');
        break;
 
        case 'eidRefs':
            // Add references to civitest_bar table, which is keyed to civicrm_contact.id
            // using `bar_entity_id` column, when `entity_table` is equal to 'civicrm_contact'. By
            // adding this to $data, records in this table will be automatically included in
            // the merge.
            $data[$db_default . 'civitest_bar'] = array('entity_table' => 'bar_entity_id');
        break;
 
        case 'sqls':
            // Note that this hook can be called twice with $type = 'sqls': once with $tables
            // and once without. In our case, SQL statements related to table `civitest_foo`
            // will be listed in $data when $tables is set; SQL statements related to table
            // `civitest_bar` will be listed in $data when $tables is NOT set.  The deciding
            // factor here is that `civitest_foo` was referenced above as part of the 'relTables'
            // data, whereas `civitest_bar` was not.
            if ($tables) {
                // Nothing to do in our case. In some cases, you might want to find and
                // modify existing SQL statements in $data.
            } else {
                // Nothing to do in our case. In some cases, you might want to find and
                // modify existing SQL statements in $data.
            }
        break;
 
    }
}

```

hook_civicrm_post
=================

This hook is called after a db write on some core objects.

pre and post hooks are useful for developers building more complex applications and need to perform operations before CiviCRM takes action. This is very applicable when you need to maintain foreign key constraints etc (when deleting an object, the child objects have to be deleted first).

* Parameters

    * $op - operation being performed with CiviCRM object. Can have the following values:
        * 'view' : The CiviCRM object is going to be displayed
        * 'create' : The CiviCRM object is created (or contacts are being added to a group)
        * 'edit' : The CiviCRM object is edited
        * 'delete' : The CiviCRM object is being deleted (or contacts are being removed from a group)

        * 'trash': The contact is being moved to trash (Contact objects only)
        * 'restore': The contact is being restored from trash (Contact objects only)

    * $objectName - can have the following values:
        * 'Activity'
        * 'Address'
        * 'Case'
        * 'Campaign' (from 4.6)
        * 'Contribution'
        * 'ContributionRecur'
        * 'CRM_Mailing_DAO_Spool'
        * 'Email'
        * 'Event'
        * 'EntityTag'
        * 'Individual'
        * 'Household'
        * 'Organization'
        * 'Grant'
        * 'Group'
        * 'GroupContact'
        * 'LineItem'
        * 'Membership'
        * 'MembershipPayment'
        * 'Participant'
        * 'ParticipantPayment'
        * 'Phone'
        * 'Pledge'
        * 'PledgePayment'
        * 'Profile' (while this is not really an object, people have expressed an interest to perform an action when a profile is created/edited)
        * 'Relationship'
        * 'Tag'
        * 'UFMatch' (when an object is linked to a CMS user record, at the request of GordonH. A UFMatch object is passed for both the pre and post hooks)

    * $objectId - the unique identifier for the object. tagID in case of EntityTag
    * $objectRef - the reference to the object if available. For case of EntityTag it is an array of (entityTable, entityIDs)

* Returns

    * None

* Definition/Example:
```
hook_civicrm_post( $op, $objectName, $objectId, &$objectRef )
```
Here is a simple example that will send you an email whenever an INDIVIDUAL Contact is either Added, Updated or Deleted:

Create a new folder called example_sendEmailOnIndividual in this directory /drupal_install_dir/sites/all/modules/civicrm/drupal/modules/ and then put the following two files in that directory (change the email addresses to yours).

```
FILE #1 /drupal_install_dir/sites/all/modules/civicrm/drupal/modules/example_sendEmailOnIndividual/example_endEmailOnIndividual.info
```
```
name = Example Send Email On Individual
description = Example that will send an email when an Individual Contact is Added, Updated or Deleted.
dependencies[] = civicrm
package = CiviCRM
core = 7.x
version = 1.0
```
```
FILE #2 /drupal_install_dir/sites/all/modules/civicrm/drupal/modules/example_sendEmailOnIndividual/example_sendEmailOnIndividual.module
```

```
<?php
function exampleSendEmailOnIndividual_civicrm_post($op, $objectName, $objectId, &$objectRef) {
 
  /**************************************************************
   * Send an email when Individual Contact is CREATED or EDITED or DELETED
   */
  $send_an_email = false; //Set to TRUE for DEBUG only
  $email_to = 'me@mydomain.com'; //TO email address
  $email_from = 'me@mydomain.com'; //FROM email address
  $email_sbj = 'CiviCRM exampleSendEmailOnIndividual';
  $email_msg = "CiviCRM exampleSendEmailOnIndividual was called.\n".$op." ".$objectName."\n".$objectId." ";
 
  if ($op == 'create' && $objectName == 'Individual') {
    $email_sbj .= "- ADDED NEW contact";
    $email_msg .= $objectRef->display_name."\n";
    $send_an_email = true;
  } else if ($op == 'edit' && $objectName == 'Individual') {
    $email_sbj .= "- EDITED contact";
    $email_msg .= $objectRef->display_name."\n";
    $send_an_email = true;
  } else if ($op == 'delete' && $objectName == 'Individual') {
    $email_sbj .= "- DELETED contact";
    $email_msg .= $objectRef->display_name."\n";
    $email_msg .= 'Phone: '.$objectRef->phone."\n";
    $email_msg .= 'Email: '.$objectRef->email."\n";
    $send_an_email = true;
  }
 
  if ($send_an_email) {
    mail($email_to, $email_sbj, $email_msg, "From: ".$email_from);
  }
 
}//end FUNCTION
?>
```

Once the files are in the directory, you need to login to Drupal admin, go to Modules and enable our new module and click Save. Now go and edit a contact and you should get an email!


hook_civicrm_postSave_table_name
================================

This hook is called after writing to a database table that has an associated DAO. This includes core tables but not custom tables or log tables.

* Parameters

	* $dao: The object that has been saved

* Definition/Example:
```
hook_civicrm_postSave_[table_name]($dao)
```
```
hook_civicrm_postSave_civicrm_contact($dao) {
  $contact_id = $dao->id;
  // Do something with this contact, but be careful not to create an infinite 
  // loop if you update it via the api! This hook will get called again with every update.
}
```

hook_civicrm_pre
================

This hook is called before a db write on some core objects. This hook does not allow the abort of the operation, use a form hook instead.

We suspect the pre hook will be useful for developers building more complex applications and need to perform operations before CiviCRM takes action. This is very applicable when you need to maintain foreign key constraints etc (when deleting an object, the child objects have to be deleted first). Another good use for the pre hook is to see what is changing between the old and new data.

* Parameters

    * $op - operation being performed with CiviCRM object. Can have the following values:
        * 'view' : The CiviCRM object is going to be displayed
        * 'create' : The CiviCRM object is created (or contacts are being added to a group)
        * 'edit' : The CiviCRM object is edited
        * 'delete' : The CiviCRM object is being deleted (or contacts are being removed from a group)
        * 'trash': The contact is being moved to trash (Contact objects only)
        * 'restore': The contact is being restored from trash (Contact objects only)

    * $objectName - can have the following values:
        * 'Individual'
        * 'Household'
        * 'Organization'
        * 'Group'
        * 'GroupContact'
        * 'Relationship'
        * 'Activity'
        * 'Contribution'
        * 'Profile' (while this is not really an object, people have expressed an interest to perform an action when a profile is created/edited)
        * 'Membership'
        * 'MembershipPayment'
        * 'Event'
        * 'Participant'
        * 'ParticipantPayment'
        * 'UFMatch' (when an object is linked to a CMS user record, at the request of GordonH. A UFMatch object is passed for both the pre and post hooks)
        * PledgePayment
        * ContributionRecur
        * Pledge
        * CustomGroup
        * 'Campaign' (from 4.6)
    * $id is the unique identifier for the object if available
    * &$params are the parameters passed

* Returns

    * None

* Definition/Example
```
hook_civicrm_pre($op, $objectName, $id, &$params)
```


hook_civicrm_referenceCounts
============================

This hook is called to determine the reference-count for a record. For example, when counting references to the activity type "Phone Call", one would want a tally that includes:

* The number of activity records which use "Phone Call"
* The number of surveys which store data in "Phone Call" records
* The number of case-types which can embed "Phone Call" records

The reference-counter will automatically identify references stored in the CiviCRM SQL schema, including:

* Proper SQL foreign-keys (declared with an SQL constraint)
* Soft SQL foreign-keys that use the "entity_table"+"entity_id" pattern
* Soft SQL foreign-keys that involve an OptionValue

However, if you have references to stored in an external system (such as XML files or Drupal database), then you may want write a custom reference-counters.

* Parameters

    * $dao: **CRM_Core_DAO**, the item for which we want a reference count
    * $refCounts: **array**, each item in the array is an array with keys:
        * name: **string**, eg "sql:civicrm_email:contact_id"
        * type: **string**, eg "sql"
        * count: **int**, eg "5" if there are 5 email addresses that refer to $dao

* Returns

    * None

* Definition/Examples:

```
hook_civicrm_referenceCounts($dao, &$refCounts)
```

Suppose we've written a module ("familytracker") which relies on the "Child Of" relationship-type. Now suppose an administrator considered deleting "Child Of" == we might want to determine if anything depends on "Child Of" and display a warning about possible breakage. This code would allow the "familytracker" to increase the reference-count for "Child Of".

```
<?php
function familytracker_civicrm_referenceCounts($dao, &$refCounts) {
  if ($dao instanceof CRM_Contact_DAO_RelationshipType && $dao->name_a_b == 'Child Of') {
    $refCounts[] = array(
      'name' => 'familytracker:childof',
      'type' => 'familytracker',
      'count' => 1,
    );
  }
}
```


hook_civicrm_trigger_info
=========================

efine MYSQL Triggers. Using the hooks causes them not to clash with core or other extension triggers. They are compiled into one trigger with core triggers.

Once the function is create, a trigger rebuild will have to be done to create the new trigger

`http://yoursite/civicrm/menu/rebuild&reset=1&triggerRebuild=1`

```
/**
 * hook_civicrm_triggerInfo()
 *
 * Add trigger to update custom region field based on postcode (using a lookup table)
 *
 * Note that we have hard-coded a prioritisation of location types into this (since it's customer specific code
 * and unlikely to change)
 *
 * @param array $info (reference) array of triggers to be created
 * @param string $tableName - not sure how this bit works
 *
 **/

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