# hook_civicrm_alterMenu

## Description

This hook is called when building CiviCRM's list of HTTP routes. This
hook should be used when you want to register custom paths or URLS. You
will need to visit <your_site>/civicrm/menu/rebuild?reset=1 to pick
up your additions.

Added in CiviCRM 4.7.11.



!!! note "Comparison of Related Hooks"
    This is one of three related hooks. The hooks:

    -   [hook_civicrm_navigationMenu](/hooks/hook_civicrm_navigationMenu.md) manipulates the navigation bar at the top of every screen
    -    [hook_civicrm_alterMenu](/hooks/hook_civicrm_alterMenu.md) manipulates the list of HTTP routes (using PHP arrays)
    -   [hook_civicrm_xmlMenu](/hooks/hook_civicrm_xmlMenu.md) manipulates the list of HTTP routes (using XML files)





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

## Returns

-   null

## Example



    function EXAMPLE_civicrm_alterMenu(&$items) {
      $items['civicrm/my-page'] = array(
        'page_callback' => 'CRM_Example_Page_AJAX::foobar',
        'access_arguments' => array(array('access CiviCRM'), "and"),
      );
    }