# hook_civicrm_tabset

## Summary

This hook is called when composing the tabs interface used for contacts,
contributions and events.


## Definition

    hook_civicrm_tabset($tabsetName, &$tabs, $context)

## Parameters

-   $tabset   - name of the screen or visual element

-   $tabs      - the array of tabs that will be displayed

-   $context   - extra data about the screen or context in which the
    tab is used



## Returns

-   null - the return value is ignored

## Example

    function civitest_civicrm_tabset($tabsetName, &$tabs, $context) {
      //check if the tab set is Event manage
      if ($tabsetName == 'civicrm/event/manage') {
        if (!empty($context)) {
          $eventID = $context['event_id'];
          $url = CRM_Utils_System::url( 'civicrm/event/manage/volunteer',
            "reset=1&snippet=5&force=1&id=$eventID&action=update&component=event" );
          //add a new Volunteer tab along with url
          $tab['volunteer'] = array(
            'title' => ts('Volunteers'),
            'link' => $url,
            'valid' => 1,
            'active' => 1,
            'current' => false,
          );
        }
        else {
          $tab['volunteer'] = array(
          'title' => ts('Volunteers'),
            'url' => 'civicrm/event/manage/volunteer',
          );
        }
        //Insert this tab into position 4
        $tabs = array_merge(
          array_slice($tabs, 0, 4),
          $tab,
          array_slice($tabs, 4)
        );
      }

      //check if the tabset is Contribution Page
      if ($tabsetName == 'civicrm/admin/contribute') {
        if (!empty($context['contribution_page_id'])) {
          $contribID = $context['contribution_page_id'];
          $url = CRM_Utils_System::url( 'civicrm/admin/contribute/newtab',
            "reset=1&snippet=5&force=1&id=$contribID&action=update&component=contribution" );
          //add a new Volunteer tab along with url
          $tab['newTab'] = array(
            'title' => ts('newTab'),
            'link' => $url,
            'valid' => 1,
            'active' => 1,
            'current' => false,
          );
        }
        if (!empty($context['urlString']) && !empty($context['urlParams'])) {
          $tab[] = array(
            'title' => ts('newTab'),
            'name' => ts('newTab'),
            'url' => $context['urlString'] . 'newtab',
            'qs' => $context['urlParams'],
            'uniqueName' => 'newtab',
          );
        }
        //Insert this tab into position 4
        $tabs = array_merge(
          array_slice($tabs, 0, 4),
          $tab,
          array_slice($tabs, 4)
        );
      }

      //check if the tabset is Contact Summary Page
      if ($tabsetName == 'civicrm/contact/view') {
        // unset the contribition tab, i.e. remove it from the page
        unset( $tabs[1] );
        $contactId = $context['contact_id'];
        // let's add a new "contribution" tab with a different name and put it last
        // this is just a demo, in the real world, you would create a url which would
        // return an html snippet etc.
        $url = CRM_Utils_System::url( 'civicrm/contact/view/contribution',
                                      "reset=1&snippet=1&force=1&cid=$contactID" );
        // this needs to be encoded in json. E.g. json_encode(array('content' => <html form snippet>));
        // or CRM_Core_Page_AJAX::returnJsonResponse($content) where $content is the html code
        // in the first cases you need to echo the return and then exit, if you use CRM_Core_Page method you do not need to worry about this.
        $tabs[] = array( 'id'    => 'mySupercoolTab',
          'url'   => $url,
          'title' => 'Contribution Tab Renamed',
          'weight' => 300,
        );
      }
    }
