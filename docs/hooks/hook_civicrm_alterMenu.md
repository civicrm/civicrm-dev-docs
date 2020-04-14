# hook_civicrm_alterMenu

## Summary

This hook is called when building CiviCRM's list of HTTP routes and should be used when you want to register custom paths or URLS.

## Notes

!!! note "Comparison of Related Hooks"
    This is one of three related hooks. The hooks:

    -   [hook_civicrm_navigationMenu](hook_civicrm_navigationMenu.md) manipulates the navigation bar at the top of every screen
    -   [hook_civicrm_alterMenu](hook_civicrm_alterMenu.md) manipulates the list of HTTP routes (using PHP arrays)
    -   [hook_civicrm_xmlMenu](hook_civicrm_xmlMenu.md) manipulates the list of HTTP routes (using XML files)

!!! tip "Applying changes"

    Menu data is cached. After making a change to the menu data, [clear the system cache](../tools/debugging.md#clearing-the-cache).

## Availability

Added in CiviCRM 4.7.11.


## Definition

    hook_civicrm_alterMenu(&$items)

## Parameters

-   *"$items*" the array of HTTP routes, keyed by relative path. Each
    includes some combination of properties:
    -   "*page_callback*": This should refer to a page/controller class
        ("*CRM_Example_Page_Example*") or a static function
        ("*CRM_Example_Page_AJAX::foobar*").
    -   "*access_callback*": (usually omitted)
    -   "*access_arguments*": Description of required permissions. Ex:
        *array(array('access CiviCRM'), 'and')*
    -   "*ids_arguments*": This array defines any [page-specific PHPIDS exceptions](hook_civicrm_xmlMenu.md#xml-ids). It includes any of these three child elements:
        - "*json*": Array of input fields which may contain JSON data.
        - "*html*": Array of input fields which may contain HTML data.
        - "*exceptions*": Array of input fields which are completely exempted from PHPIDS.

## Returns

-   null

## Example



    function EXAMPLE_civicrm_alterMenu(&$items) {
      $items['civicrm/my-page'] = array(
        'page_callback' => 'CRM_Example_Page_AJAX::foobar',
        'access_arguments' => array(array('access CiviCRM'), "and"),
      );
    }