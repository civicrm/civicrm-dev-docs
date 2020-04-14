# hook_civicrm_postSave_table_name

## Summary

This hook is called after writing to a database table that has an
associated DAO, including core tables but not custom tables or log
tables.

## Parameters

$dao: The object that has been saved

## Definition

    `hook_civicrm_postSave_[table_name]($dao)`

## Example

    hook_civicrm_postSave_civicrm_contact($dao) {
      $contact_id = $dao->id;
      // Do something with this contact, but be careful not to create an infinite loop if you update it via the api! This hook will get called again with every update.
    }