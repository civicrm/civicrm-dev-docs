# hook_civicrm_summaryActions

## Summary

This hook allows you to customize the context menu actions on the Contact
Summary Page.

## Definition

    hook_civicrm_summaryActions( &$actions, $contactID )

## Parameters

-   `$actions` Array of all Actions in contextMenu. Each action item is
    itself an array that can contain the items below **(this is not the
    full list)**:\
     \
    -   `title:` the text that appears in the action menu
    -   `weight:` a number defining the "weight" - i.e where should the
        item sit in the action menu
    -   `ref:` this is appended to the string "crm-action-record-" and
        becomes the list items CSS class (each action is a `li` element)
    -   `key:` this is the array key that identifies the action
    -   `href:` a URL that you want the link to navigate to
    -   `permissions:` an array that contains permissions a user must
        have in order to use this action\
         \
-   `$contactID` contactID for the summary page.

## Example

**Removing some action items**

    function civitest_civicrm_summaryActions( &$actions, $contactID ){
    $customizeActions = array('contribution', 'note', 'rel');
        foreach( $actions as $key => $value ) {
            if( in_array( $key, $customizeActions ) ) {
                unset( $actions[$key] );
            }
        }
    }

**Add an item to the action list**

    function mymodulename_civicrm_summaryActions(&$actions, $contactID)
    {
      $actions['casework'] = array(
        'title' => 'Record casework',
        'weight' => 999,
        'ref' => 'record-casework',
        'key' => 'casework',
        'href' => '/casework/recording_form
      );
    }

**Add an item to the third column of action list**

    function mymodulename_civicrm_summaryActions(&$actions, $contactID)
    {
      $actions['otherActions']['casework'] = array(
        'title' => 'Record casework',
        'weight' => 999,
        'ref' => 'record-casework',
        'key' => 'casework',
        'href' => '/casework/recording_form
        );
     }
