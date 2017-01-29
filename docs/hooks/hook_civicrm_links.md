# hook_civicrm_links

## Description

This hook is an opportunity to modify action links in CiviCRM.  These
include the actions at the end of a search result row, the Create New
dropdown, and the Actions dropdown at the top of a contact record.

Note: remember to use the string processing functions of your host
framework ( ts() for CiviCRM extensions, t() for Drupal modules, etc).

**This hook has had major changes for 4.5
([CRM-13434](http://issues.civicrm.org/jira/browse/CRM-13434)****).
Please [see the 4.4
documentation](https://wiki.civicrm.org/confluence/display/CRMDOC44/hook_civicrm_links) for
previous usage of this hook.\
**

## Definition

    hook_civicrm_links( $op, $objectName, $objectId, &$links, &$mask, &$values )

##  Parameters

-   $op = the context in which the links appear, such as
    'view.contact.activity', 'survey.dashboard.row', or
    'pdfFormat.manage.action'
-   $objectName = the entity the links relate to (or null if $op ==
    'create.new.shorcuts')
-   $objectId = the CiviCRM internal ID of the entity (or null if $op
    == 'create.new.shorcuts')
-   $links = the links array to modify in-place\
     each item in the array may have:
    -   name: the link text
    -   url: the link URL base path (like 'civicrm/contact/view', and
        fillable from $values)
    -   qs: the link URL query parameters to be used by sprintf() with
        $values (like 'reset=1&cid=%%id%%' when $values['id'] is the
        contact ID)
    -   title (optional): the text that appears when hovering over the
        link
    -   extra (optional): additional attributes for the <a> tag
        (fillable from $values)
    -   bit (optional): a binary number that will be fitered by $mask
        (sending nothing as $links['bit'] means the link will always
        display)
    -   ref (optional, recommended): a CSS class to apply to the <a>
        tag.
    -   class (optional): Any other CSS classes to apply to the <a>
        tag (e.g. no-popup).
-   $mask = a bitmask that will fiter $links
-   $values = values to fill $links['url'], $links['qs'], and/or
    $links['extra'] using sprintf()-style percent signs

## Returns

-   null

## Example

    function MODULENAME_civicrm_links( $op, $objectName, $objectId, &$links, &$mask, &$values ) {
      $myLinks = array();
      switch ($objectName) {
        case 'Contact':
          switch ($op) {
            case 'view.contact.activity':
              // Adds a link to the main tab.
              $links[] = array(
                'name' => ts('My Module Actions'),
                'url' => 'mymodule/civicrm/actions/%%myObjId%%',
                'title' => 'New Thing',
              );
              $values['myObjId'] = $objectId;
              break;

            case 'contact.selector.row':
              // Add a similar thing when a contact appears in a row
              $links[] = array(
                'name' => ts('My Module'),
                'url' => 'mymodule/civicrm/actions/%%myObjId%%',
                'title' => 'New Thing',
                'qs' => 'reset=1&tid=%%thingId%%',
              );
              $values['myObjId'] = $objectId;
              $values['thingId'] = 'mything';
              break;

            case 'create.new.shorcuts':
              // add link to create new profile
               $links[] = array(
                'url'   => '/civicrm/admin/uf/group?action=add&reset=1',
                'name' => ts('New Profile'), // old extensions using 'title' will still work
               );
               break;
          }
      }
      return $myLinks;
    }





**Adding contextual links to the rows of a contact's Events tab and Find
Participants search result**

    //part of a native Civi extension, not CMS-specfic

    function mymodule_civicrm_links( $op, $objectName, $objectId, &$links, &$mask, &$values ) {
      //create a Send Invoice link with the context of the participant's order ID (a custom participant field)
      switch ($objectName) {
        case 'Participant':
          switch ($op) {
            case 'participant.selector.row':
              $cid = $values['cid'];
              $order_id = lg_get_order_id_by_pid($objectId);

              //check if this participant is a student with a parent, for saving the email.
              //if not, fall back to current contact record
              $result = civicrm_api3('Relationship', 'get', array(
                'sequential' => 1,
                'return' => "contact_id_b",
                'relationship_type_id' => 1,
                'contact_id_a' => $cid,
              ));

              $parent_id = $result['values'][0]['contact_id_b'];

              if ($parent_id > 0) {
                $cid = $parent_id;
              }

              $links[] = array(
                  'name' => ts('Send Invoice'),
                  'title' => ts('Send Invoice'),
                  'url' => 'civicrm/activity/email/add',
                  'qs' => "action=add&reset=1&cid=$cid&selectedChild=activity&atype=3&order_id=$order_id"
                );
              break;
          }
      }
    }

    function lg_get_order_id_by_pid($pid) {
          $result = civicrm_api3('Participant', 'get', array(
            'sequential' => 1,
            'return' => "custom_11",
            'id' => $pid,
          ));
          return $result['values'][0]['custom_11'];
    }