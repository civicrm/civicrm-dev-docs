# hook_civicrm_import

## Summary

This hook is called after contacts have been imported into the system,
and before the temp import table has been destroyed.

## Notes

This hook can be used to
take custom action on the imported records or handle special columns in
the import file. It currently is only applicable to contact import, but
in future versions may extend to other objects.

## Definition

    hook_civicrm_import( $object, $usage, &$objectRef, &$params )

## Parameters

-   @param string  $object     - object being imported (for now Contact
    only, later Contribution, Activity, Participant and Member)\
     @param string  $object     - object being imported (for now
    Contact only, later Contribution, Activity, Participant and Member)
-   @param string  $usage      - hook usage/location (for now process
    only, later mapping and others)
-   @param string  $objectRef  - import record object
-   @param array   $params     - array with various key values:
    currently\
                              contactID       - contact id\
                              importID        - row id in temp table\
                              importTempTable - name of tempTable\
                              fieldHeaders    - field headers

## Returns

-   null

## Availability

-   This hook was first available in CiviCRM 3.4.1

## Example

     /*
     * import hook allows you to interact with the import data post process
     * this example retrieves a special column (tag_import) with pipe-separated tags and
     * create/tags the imported contact record to a predefined tagset (id=5)
     */

    function civitest_civicrm_import( $object, $usage, &$objectRef, $params ) {

        if ( $object != 'Contact' &&
             $usage  != 'process' ) {
            return;
        }

        //during import, accept special tag_import column for processing
        $contactID       = $params['contactID'];
        $importID        = $params['importID'];
        $importTempTable = $params['importTempTable'];

        require_once 'CRM/Core/DAO.php';
        $sqlTags = "SELECT tag_import
                    FROM $importTempTable
                    WHERE _id = $importID;";
        $taglist = CRM_Core_DAO::singleValueQuery( $sqlTags );

        if ( $taglist ) {

            require_once 'api/v2/Tag.php';
            require_once 'api/v2/EntityTag.php';
            require_once 'CRM/Core/BAO/EntityTag.php';

            $keywords = array();
            $keywords = explode( '|', $taglist );

            foreach ( $keywords as $keyword ) {
                $params = array( 'name' => $keyword, 'parent_id' => '5' ); //import tags to tagset 5

                //lookup tag; create new if nonexist
                $tag = civicrm_tag_get($params);
                if ( $tag['is_error'] ) {
                    $tag = civicrm_tag_create($params);
                    $tagid = $tag['tag_id'];
                } else {
                    $tagid = $tag['id'];
                }

                //only add tag to contact if not already present
                $entityTags =& CRM_Core_BAO_EntityTag::getTag($contactID);
                if ( !in_array($tagid, $entityTags) ) {
                    $entityParams = array('tag_id' => $tagid, 'contact_id' => $contactID );
                    $entityTag = civicrm_entity_tag_add( $entityParams );
                }
            }
        }
    }