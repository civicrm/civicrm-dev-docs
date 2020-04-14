# hook_civicrm_tabs

## Summary

This hook was deprecated in 4.7 in favor of [hook_civicrm_tabset](hook_civicrm_tabset.md).

## Notes

This hook is called when composing the tabs to display when viewing a
contact

## Definition

    hook_civicrm_tabs( &$tabs, $contactID )

## Parameters

-   $tabs - the array of tabs that will be displayed
-   $contactID - the contactID for whom the view is being rendered

## Returns

-   null - the return value is ignored

## Example

    function civitest_civicrm_tabs( &$tabs, $contactID ) {

        // unset the contribition tab, i.e. remove it from the page
        unset( $tabs[1] );

        // let's add a new "contribution" tab with a different name and put it last
        // this is just a demo, in the real world, you would create a url which would
        // return an html snippet etc.
        $url = CRM_Utils_System::url( 'civicrm/contact/view/contribution',
                                      "reset=1&snippet=1&force=1&cid=$contactID" );
        // $url should return in 4.4 and prior an HTML snippet e.g. '<div><p>....';
        // in 4.5 and higher this needs to be encoded in json. E.g. json_encode(array('content' => <html form snippet as previously provided>));
        // or CRM_Core_Page_AJAX::returnJsonResponse($content) where $content is the html code
        // in the first cases you need to echo the return and then exit, if you use CRM_Core_Page method you do not need to worry about this.
        $tabs[] = array( 'id'    => 'mySupercoolTab',
                         'url'   => $url,
                         'title' => 'Contribution Tab Renamed',
                         'weight' => 300 );
    }