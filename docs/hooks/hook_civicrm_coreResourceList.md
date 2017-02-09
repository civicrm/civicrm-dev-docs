# hook_civicrm_coreResourceList

## Description

This hook is called when the list of core js/css resources is about to
be processed. Implementing this hook gives you the opportunity to modify
the list prior to the resources being added, or add your own.

Added in v4.6.6.

See [Resource Reference](https://wiki.civicrm.org/confluence/display/CRMDOC/Resource+Reference)
for more information.

## Parameters

$list: an array of items about to be added to the page. Items in the
list may be:

-   A string ending in .js
-   A string ending in .css
-   An array of settings (will be added to the javascript CRM object).

$region: target region of the page - normally this is "html-header"

## Example

    /**
     * Implements hook_coreResourceList
     *
     * @param array $list
     * @param string $region
     */
    function myextension_civicrm_coreResourceList(&$list, $region) {
      // Prevent navigation.css from loading
      $cssWeDontWant = array_search('css/navigation.css', $list);
      unset($list[$cssWeDontWant]);

      // Add some css of our own to the page
      // Note that if the file we want to add is outside civicrm directory (e.g. in an extension) we can't just append it to the list
      // But we can add it directly, which is what happens to items in this list anyway.
      Civi::resources()
        ->addStyleFile('myextension', 'css/my_style.css', 0, $region);

      // Add a setting - in this example we override the CKEditor config file location
      $list[] = array('config' => array('CKEditorCustomConfig' => Civi::resources()->getUrl('org.foo.myextension', 'js/my-ckeditor-config.js')));
    }