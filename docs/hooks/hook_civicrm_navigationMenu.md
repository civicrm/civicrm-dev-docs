# hook_civicrm_navigationMenu

## Summary

This hook is called after the menus are rebuild. You can use this hook
to add new menu, add children to new menu and get the list of menu items
for any parent.

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
  $maxKey = getMenuKeyMax($params);

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

To add your menu item to an existing menu

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
      'active' => 1
    ),
  );
}
```

The second example is a bit dangerous because it isn't taking into account the IDs that other extensions might have added.
