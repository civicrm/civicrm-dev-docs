# hook_civicrm_dupeQuery

## Summary

This hook is called during the dedupe lookup process, and can be used to
alter the parameters and queries used to determine if two contacts are
duplicates.

## Notes

The dedupe mechanism is triggered in four places:

1.  when a CMS user account is created and connected to an existing or
    new CiviCRM contact;
2.  during contact imports, where the incoming file records are compared
    with the existing database;
3.  when a contact record is created through the interface; and
4.  when a find duplicate contacts rule is run (comparing a group of
    contacts or the entire database with itself).

Using the hook parameters, you can isolate how and when your rule
modifications are used.

Note that this hook depends upon the existence of a dedupe rule created
in the interface in order for it to be called. It works by allowing
access to the queries constructed by an interface-create rule.You can
modify or completely replace the query or queries that would be run at
the point the hook is called, as illustrated below.

You cannot define rule groups with this hook.

## Definition

    hook_civicrm_dupeQuery( $obj, $type, &$query )

## Parameters

-   @param string $obj object of rulegroup class
-   @param string $type type of queries e.g table / threshold (I'm
    pretty sure these correspond to strict and fuzzy in the UI)
-   @param array  $query set of queries
-   @access public

## Returns

-   null

## Availability

-   Available since 3.3

## Example

The example below rewrites the queries for the individual rule group
entitled "My Dedupe Rule Group Name" when performing site-wide deduping.
It combines six fields into a single query, thus speeding up the
duplicate search process.

    function example_civicrm_dupeQuery( $obj, $type, &$query ) {
      //don't run these during user account/contact creation
      if( $obj->noRules || $type != 'table')
          return;

      if ( $obj->title === 'My Dedupe Rule Group Name' ) {

          //first unset existing queries
          $query = array();

          //now set threshold to match our revised rule
          $obj->threshold = 5;

          if ( empty($obj->params) ) {
              //set new query when doing an internal dedupe (finding duplicate contacts)
              $query['civicrm_contact.last_name.5'] = "
    SELECT t1.id id1, t2.id id2, 5 weight
    FROM civicrm_contact t1
      JOIN civicrm_contact t2 ON ( t1.first_name = t2.first_name AND
                                   t1.last_name = t2.last_name AND
                                   IFNULL(t1.middle_name,0) = IFNULL(t2.middle_name,0) AND
                                   IFNULL(t1.suffix_id,0) = IFNULL(t2.suffix_id,0) )
      INNER JOIN civicrm_address a1 on t1.id=a1.contact_id
      INNER JOIN civicrm_address a2 on t2.id=a2.contact_id AND
                 a1.postal_code = a2.postal_code AND
                 a1.street_address = a2.street_address
    WHERE t1.contact_type = 'Individual'
      AND t2.contact_type = 'Individual'
      AND t1.id < t2.id
      AND t1.last_name IS NOT NULL
      AND t1.first_name IS NOT NULL
      AND a1.postal_code IS NOT NULL
      AND a1.street_address IS NOT NULL";
         }
      }
    }
