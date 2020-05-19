# hook_civicrm_merge

## Summary

This hook allows modification of the data used to perform merging of
duplicates. It can be useful if your custom module has added its own
tables related to CiviCRM contacts.

## Availability

This hook was first available in CiviCRM 3.2.3.

## Definition

```php
hook_civicrm_merge($type, &$data, $mainId = NULL, $otherId = NULL, $tables = NULL)
```

## Parameters

-   string `$type` - the type of data being passed
    (`cidRefs` (deprecated, hook will no longer be called at some point for this), `eidRefs`, `relTables`, or `sqls`)
-   array `$data` - the data, which depends on the value of `$type` (see Details)
-   int `$mainId` - ID of the contact that survives the merge (only
    when `$type` is `sqls`)
-   int `$otherId` - ID of the contact that will be absorbed and
    deleted (only when `$type` is `sqls`)
-   array `$tables` - when `$type` is `sqls`, an array of tables as it may have
    been handed to the calling function

## Details

The contents of `$data` will vary based on the `$type` of data being
passed:

-   `relTables`:
    an array of tables used for asking user which elements to merge,
    as used at `civicrm/contact/merge`.  Each table in the array has
    this format:

    ```php
    'rel_table_UNIQUE-TABLE-NICKNAME' => array(
      'title' => ts('TITLE'),
      'tables' => array('TABLE-NAME' [, ...]),
      'url' => CRM_Utils_System::url(PATH, QUERY),
    ),
    ```

-   `sqls`:
    a one-dimensional array of SQL statements to be run in the final
    merge operation.  These SQL statements are run within a single transaction.

-   `cidRefs`:
    this is deprecated in favour of the entityTypes hook. If you alter cidRefs you will get a deprecation warning
    an array of tables and their fields referencing
    civicrm_contact.contact_id explicitly.  Each table in the array has this format:

    ```php
    'TABLE-NAME' => array('COLUMN-NAME' [, ...])
    ```

-   `eidRefs`:
    an array of tables and their fields referencing
    `civicrm_contact.contact_id` with `entity_id`.  Each table in the array has this format:

    ```php
    'TABLE-NAME' => array('entity_table-COLUMN-NAME' => 'entity_id-COLUMN-NAME')
    ```

## Example

```php
/** In this example we assume our module has created its own tables to store extra data on CiviCRM contacts:
 * Table civitest_foo stores a relationship between two contacts, and contains the two relevant columns:
 *       civitest_foo.contact_id and civitest_foo.foo_id, both of which are foreign keys to civicrm_contact.id
 * Table civitest_bar stores extra properties for contacts, and contains one relevant column:
 *       civitest_bar.contact_id is a foreign key to civicrm_contact.id
 *
 * This hook ensures that data in these two tables is included in CiviCRM merge operations.
 */
function civitest_civicrm_merge($type, &$data, $mainId = NULL, $otherId = NULL, $tables = NULL) {

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
        // Title as shown to user for this type of data
        'title'  => ts('Foos'),                 
        // Name of database table holding these records
        'tables' => array($db_default .'civitest_foo'),      
        // URL to view this data for this contact,
        // in this case using CiviCRM's native URL utility
        'url'    => CRM_Utils_System::url('civicrm/civitest/foo', 'action=browse&cid=$cid'),
        // NOTE: '$cid' will be replaced with correct CiviCRM contact ID.
      );
      break;

    case 'cidRefs':
      // Use entityTypes hook instead as cidRefs is deprecated in this hook.
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
      }
      else {
        // Nothing to do in our case. In some cases, you might want to find and
        // modify existing SQL statements in $data.
      }
      break;

  }
}
```
