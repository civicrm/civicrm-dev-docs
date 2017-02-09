# hook_civicrm_navigationMenu

## Description

This hook is called after the menus are rebuild. You can use this hook
to add new menu, add children to new menu and get the list of menu items
for any parent.

## Definition

     hook_civicrm_navigationMenu( &$params )

## Parameters

-   $params the navigation menu array

Attributes of the menu :

    1. label : Navigation Title

    2. Name : Internal Name

    3. url : url in case of custom navigation link

    4. permission : comma separated Permissions for menu item

    5. operator : Permission Operator ( AND/OR)

    6. seperator : 0 or null = No Separator, 1 = Separator after this
menu item, 2 = Separator before this menu item.

    7. parentID : Parent navigation item, used for grouping

    8. navID : ID of the menu

    9. active : is active ?

## Example

    function _getMenuKeyMax($menuArray) {
      $max = array(max(array_keys($menuArray)));
      foreach($menuArray as $v) {
        if (!empty($v['child'])) {
          $max[] = _getMenuKeyMax($v['child']);
        }
      }
      return max($max);
    }

    function civicrm_civicrm_navigationMenu( &$params ) {

        //  Get the maximum key of $params
        $maxKey = getMenuKeyMax($params);

        $params[$maxKey+1] = array (
                           'attributes' => array (
                                                  'label'      => 'Custom Menu Entry',
                                                  'name'       => 'Custom Menu Entry',
                                                  'url'        => null,
                                                  'permission' => null,
                                                  'operator'   => null,
                                                  'separator'  => null,
                                                  'parentID'   => null,
                                                  'navID'      => $maxKey+1,
                                                  'active'     => 1
                                                  ),
                           'child' =>  array (
                                              '1' => array (
           'attributes' => array (
                                  'label'      => 'Custom Child Menu',
                                  'name'       => 'Custom Child Menu',
                                  'url'        => 'http://www.testlink.com',
                                  'permission' => 'access CiviContribute',
                                  'operator'   => null,
                                  'separator'  => 1,
                                  'parentID'   => $maxKey+1,
                                  'navID'      => 1,
                                  'active'     => 1
                                   ),
           'child' => null
           ) ) );
    }

## Example: to add your menu item to an existing menu

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
          $params[$reportID]['child'][$navId] = array (
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
        )
      );
    }





Both of these examples were a bit dangerous - they each provide a way to
find the next available id, but the first one fails because it's not
finding the child menu id numbers, and the second one fails because it's
not taking into account the id's that other extensions might have added.
I've just added a little recursive function to the first one to fix it.