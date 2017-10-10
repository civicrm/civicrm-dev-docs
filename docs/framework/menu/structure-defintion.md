# Menu XML Structure

CiviCRM's menu routing system is built based on XML files. These XML files define what class gets loaded when a specific path is arrived at, what permissions are required to access that route.

The standard menu XML files can be found in `CRM/Core/xml/Menu/`. Each route is defined as an "Item" Within the menu. In extensions you should add your menu to `<extension folder>/xml/Menu/<extensionName>.xml`

!!! Caution "Caution: Wild card sub-paths"
    One path can match all sub-paths.  For example, `<path>civicrm/admin</path>` can match `http://example.org/civicrm/admin/f/o/o/b/a/r`.  However, one should avoid designs which rely on this because it's imprecise and it can be difficult to integrate with some frontends.

## XML Item Structure

The XML will contain a structure made up of the following fields

* path
  The path is the url route that this menu item is for
  * Example: `civicrm/admin/eway/settings`
  * Type: Text
  * Required: Yes

* `path_arguments`
   These are arguments to be added to the url when it is loaded. These are generally useful when redirecting people after filling in the page or form
  * Example `ct=Household`
  * Type: Text
  * Required: No

* `title`
  Title of the route
  * Example: `Eway Settings`
  * Type: Text
  * Required: Yes

* `acess_callback`
  Function to be used to check access to the route
  * Example: `CRM_Core_Permission::checkMenu`
  * Type: Text
  * Required: No
  * If you wish for this route to be public you can set it to be 1.

* `access_arguments`
  Arguments to be passed to the permission checking function
  * Example: '`access CiviCRM;administer CiviCRM`
  * Type: Text
  * Required: No
  * If you want the permissions to be an "or" situation i.e. User needs either access CiviCRM or administer CiviCRM put a `;` between the permissions. If you want it so that users need multiple permissions put a `,` between

* `page_callback`
  Function called to generate the content of the route
  * Example: `CRM_Contact_Page_Dashboard`
  * Type: Text
  * Required: No

* `page_arguments`
  Arguments passed to the function generating the content of the route
  * Example: `addSequence=1`
  * Type: Text
  * Required: No

* `is_public`
  Is this route Public?
  * Example: `true`
  * Type: Boolean
  * Required No

* `is_ssl`
  Does this route need SSL
  * Example: `true`
  * Type: Boolean
  * Required: No

* weight
  Set a weight on the route to change the order of it in the menu
  * Example: `105`
  * Type: Integer
  * Required: No
  * Items with heavier weights will appear lower in the menu

* `admin_group`
  Is this menu part of a Administration group and if so which one
  * Example: `Manage`
  * Type: Text
  * Required: No

* `dsc`
  What is the description of this route
  * Example: `This sets up specific eway settings`
  * Type: Text
  * Required: No

* `icon`
  What icon should display next to the title in the menu
  * Example: `admin/small/duplicate_matching.png`
  * Type: Text
  * Required: No

* `page_type`
  What type of a page is this
  * Example: `1 or contribute`
  * Type: Text
  * Required: No
  * If this is not set the default is 0

* skipBreadCrumb
  Should this route not generate any breadcrumbs
  * Example: `true`
  * Type: Boolean
  * Required: No
