# hook_civicrm_referenceCounts

## Summary

This hook is called to determine the reference-count for a record.

## Notes

For example, when counting references to the activity type "Phone Call", one
would want a tally that includes:

-   The number of activity records which use "Phone Call"
-   The number of surveys which store data in "Phone Call" records
-   The number of case-types which can embed "Phone Call" records

The reference-counter will automatically identify references stored in
the CiviCRM SQL schema, including:

-   Proper SQL foreign-keys (declared with an SQL constraint)
-   Soft SQL foreign-keys that use the "entity_table"+"entity_id"
    pattern
-   Soft SQL foreign-keys that involve an OptionValue

However, if you have references to stored in an external system (such as
XML files or Drupal database), then you may want write a custom
reference-counters.

## Definition

    hook_civicrm_referenceCounts($dao, &$refCounts)

## Parameters

-   $dao: ***CRM_Core_DAO***, the item for which we want a reference
    count
-   $refCounts: ***array***, each item in the array is an array with
    keys:
    -   name: ***string***, eg
        "sql:civicrm_email:contact_id"
    -   type: ***string***, eg "sql"
    -   count: ***int***, eg "5" if there are 5 email addresses that
        refer to $dao

## Returns

-   None

## Example

Suppose we've written a module ("familytracker") which relies on the
"Child Of" relationship-type. Now suppose an administrator considered
deleting "Child Of" -- we might want to determine if anything depends on
"Child Of" and display a warning about possible breakage. This code
would allow the "familytracker" to increase the reference-count for
"Child Of".

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