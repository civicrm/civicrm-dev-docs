# hook_civicrm_xmlMenu

## Summary

This hook is called when building CiviCRM's menu structure, which is
used to render urls in CiviCRM.

!!! note "Comparison of Related Hooks"
    This is one of three related hooks. The hooks:

    -   [hook_civicrm_navigationMenu](/hooks/hook_civicrm_navigationMenu.md) manipulates the navigation bar at the top of every screen
    -   [hook_civicrm_alterMenu](/hooks/hook_civicrm_alterMenu.md) manipulates the list of HTTP routes (using PHP arrays)
    -   [hook_civicrm_xmlMenu](/hooks/hook_civicrm_xmlMenu.md) manipulates the list of HTTP routes (using XML files)

!!! tip "Applying changes"

    Menu data is cached. After making a change to the menu data, [clear the system cache](/tools/debugging.md#clearing-the-cache).

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