# hook_civicrm_navigationMenu

## Summary

This hook is called after the menus are rebuilt.

## Notes

!!! note "Comparison of Related Hooks"
    This is one of three related hooks. The hooks:

    -   [hook_civicrm_navigationMenu](hook_civicrm_navigationMenu.md) manipulates the navigation bar at the top of every screen
    -   [hook_civicrm_alterMenu](hook_civicrm_alterMenu.md) manipulates the list of HTTP routes (using PHP arrays)
    -   [hook_civicrm_xmlMenu](hook_civicrm_xmlMenu.md) manipulates the list of HTTP routes (using XML files)

You can use this hook to add new menu, add children to new menu and get the list of menu items for any parent.

!!! warning "Use the Civix implementation"
    [Civix](../extensions/civix.md) comes with helper functions `_EXTENSION_NAME_civix_insert_navigation_menu` and `_EXTENSION_NAME_civix_navigation_menu` that simplify the process of inserting menu items. Consider using these functions rather than using the examples below or writing your own implementation of this hook.

## Definition

```php
hook_civicrm_navigationMenu(&$params)
```

## Parameters

-   `$params` the navigation menu array

    Attributes of the menu :

    -   string `label`: navigation title

    -   string `name`: internal name

    -   string `url`: URL in case of custom navigation link

    -   string `permission`: comma separated permissions for menu item

    -   string `operator`: permission operator (`AND` or `OR`)

    -   int `separator`: whether to insert a Separator

        -   `0` or `NULL` = No Separator,
        -   `1` = Separator after this menu item,
        -   `2` = Separator before this menu item.

    -   int `parentID`: parent navigation item, used for grouping

    -   int`navID`: ID of the menu

    -   bool `active`: whether the item is active

## Examples
* Civix example (recomended) - adds a menu item under 'Administer/System Settings'*

```
function omnipaymultiprocessor_civicrm_navigationMenu(&$menu) {
  _omnipaymultiprocessor_civix_insert_navigation_menu($menu, 'Administer/System Settings', [
    'label' => E::ts('Omnipay Developer Settings'),
    'name' => 'omnpay-dev',
    'url' => 'civicrm/settings/omnipay-dev',
    'permission' => 'administer payment processors',

  ]);
}
```

*Legacy method if for some reason you cannot use Civix.*

```php
function _getMenuKeyMax($menuArray) {
  $max = array(max(array_keys($menuArray)));
  foreach($menuArray as $v) {
    if (!empty($v['child'])) {
      $max[] = _getMenuKeyMax($v['child']);
    }
  }
  return max($max);
}

function civicrm_civicrm_navigationMenu(&$params) {

  //  Get the maximum key of $params
  $maxKey = _getMenuKeyMax($params);

  $params[$maxKey+1] = array(
    'attributes' => array(
      'label'      => 'Custom Menu Entry',
      'name'       => 'Custom Menu Entry',
      'url'        => null,
      'permission' => null,
      'operator'   => null,
      'separator'  => null,
      'parentID'   => null,
      'navID'      => $maxKey + 1,
      'active'     => 1
    ),
    'child' => array(
      '1' => array(
        'attributes' => array(
          'label'      => 'Custom Child Menu',
          'name'       => 'Custom Child Menu',
          'url'        => 'http://www.testlink.com',
          'permission' => 'access CiviContribute',
          'operator'   => NULL,
          'separator'  => 1,
          'parentID'   => $maxKey + 1,
          'navID'      => 1,
          'active'     => 1
        ),
        'child' => NULL,
      ),
    ),
  );
}
```

Legacy example of adding your menu item to an existing menu

```php
function donortrends_civicrm_navigationMenu(&$params) {

  // Check that our item doesn't already exist
  $menu_item_search = array('url' => 'civicrm/trends');
  $menu_items = array();
  CRM_Core_BAO_Navigation::retrieve($menu_item_search, $menu_items);

  if ( ! empty($menu_items) ) {
    return;
  }

  $navId = CRM_Core_DAO::singleValueQuery("SELECT max(id) FROM civicrm_navigation");
  if (is_integer($navId)) {
    $navId++;
  }
  // Find the Report menu
  $reportID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_Navigation', 'Reports', 'id', 'name');
  $params[$reportID]['child'][$navId] = array(
    'attributes' => array (
      'label' => ts('Donor Trends',array('domain' => 'org.eff.donortrends')),
      'name' => 'Donor Trends',
      'url' => 'civicrm/trends',
      'permission' => 'access CiviReport,access CiviContribute',
      'operator' => 'OR',
      'separator' => 1,
      'parentID' => $reportID,
      'navID' => $navId,
      'active' => 1,
      'attr' => ['target' => '_blank'],
    ),
  );
}
```

The second example is a bit dangerous because it isn't taking into account the IDs that other extensions might have added.
