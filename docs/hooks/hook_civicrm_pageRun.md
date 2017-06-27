# hook_civicrm_pageRun

## Summary

This hook is called before a CiviCRM page is rendered.


## Notes

This does **not** execute on every CiviCRM *page* in the
general sense. CiviCRM's pages are classified as either 'Forms' or
'Pages', and this only runs on pages classified as 'Pages'. If you are
not sure if a particular page is a Page, test it by adding some
temporary debug code to `/CRM/Utils/Hook.php`

## Definition

    hook_civicrm_pageRun( &$page )

## Parameters

-   $page the page being rendered

## Returns

-   null

## Example

The example below is for the enhanced tags extension. In this extension
a coordinator can be assigned to a CiviCRM tag. In the pageRun hook
below the coordinators are added to an array which is sent to the page
template

    function enhancedtags_civicrm_pageRun(&$page) {
      $pageName = $page->getVar('_name');
      if ($pageName == 'CRM_Admin_Page_Tag') {
        /*
         * retrieve all tag enhanced data and put in array with tag_id as index
         */
        $enhancedTags = CRM_Enhancedtags_BAO_TagEnhanced::getValues(array());
        $coordinators = array();
        foreach ($enhancedTags as $enhancedTag) {
          $coordinators[$enhancedTag['tag_id']] = CRM_Enhancedtags_BAO_TagEnhanced::getCoordinatorName($enhancedTag['coordinator_id']);
        }
        $page->assign('coordinators', $coordinators);
      }
    }