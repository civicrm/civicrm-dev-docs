# hook_civicrm_xmlMenu

## Description

This hook is called when building CiviCRM's menu structure, which is
used to render urls in CiviCRM. This hook should be used when you want
to register your custom module url's in CiviCRM. You will need to visit
<your_site>/civicrm/menu/rebuild?reset=1 to pick up your additions.

!!! note "Comparison of Related Hooks"
    This is one of three related hooks. The hooks:

    -   [hook_civicrm_navigationMenu](https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu) manipulates the navigation bar at the top of every screen
    -    [hook_civicrm_alterMenu](https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterMenu) manipulates the list of HTTP routes (using PHP arrays)
    -   [hook_civicrm_xmlMenu](https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu) manipulates the list of HTTP routes (using XML files)



## Definition

    hook_civicrm_xmlMenu( &$files )

## Parameters

-   $files the array for files used to build the menu. You can append
    or delete entries from this file. You can also override menu items
    defined by CiviCRM Core.

## Returns

-   null

## Example

Here's how you can override an existing menu item. First create an XML
file like this, and place it in the same folder as your hook
implementation:

    <?xml version="1.0" encoding="iso-8859-1" ?>
    <menu>
      <item>
         <path>civicrm/ajax/contactlist</path>
         <page_callback>CRM_Contact_Page_AJAX::getContactList</page_callback>
         <access_arguments>my custom permission</access_arguments>
      </item>
    </menu>

    <?xml version="1.0" encoding="iso-8859-1" ?>
    <menu>
      <item>
         <path>civicrm/ajax/contactlist</path>
         <page_callback>CRM_Contact_Page_AJAX::getContactList</page_callback>
         <access_arguments>access CiviCRM AJAX contactlist</access_arguments>
      </item>
    </menu>

\
 Drupal developers can define 'my custom permission' using
[hook_perm](http://api.drupal.org/api/function/hook_perm) . Then create
a hook implementation like this:

    function EXAMPLE_civicrm_xmlMenu( &$files ) {
        $files[] = dirname(__FILE__)."/my_file_name_above.xml";
    }