# Routing

CiviCRM's routing system is built based on XML files. These XML files define what class gets loaded when a specific path is arrived at, what permissions are required to access that route.

The standard menu XML files can be found in `CRM/Core/xml/Menu/`. Each route is defined as an "Item" Within the menu. In extensions you should add your menu to `<extension folder>/xml/Menu/<extensionName>.xml`

!!! note
    For historical reasons, the routing files live in a `Menu` folder, but the contents of these files do *not* affect the navigation menu at the top of the screen.

    Extension authors can add new menu entires by using [hook_civicrm_navigationMenu](../hooks/hook_civicrm_navigationMenu.md).

## Example

```xml
<menu>
  <item>
    <path>civicrm/payment/form</path>
    <access_callback>1</access_callback>
    <page_callback>CRM_Financial_Form_Payment</page_callback>
    <is_public>true</is_public>
    <weight>0</weight>
  </item>
  <item>
    <path>civicrm/payment/edit</path>
    <page_callback>CRM_Financial_Form_PaymentEdit</page_callback>
    <access_arguments>access CiviContribute</access_arguments>
    <component>CiviContribute</component>
  </item>
</menu>
```

## Elements

The XML will contain a structure made up of the following elements.

!!! tip
    The [`<menu>`](#menu) element must be the root element of the document.

### `<access_arguments>` {:#access_arguments}

* Containing element: [`<item>`](#item)
* Description: Arguments to be passed to the permission checking function
* Example: `access CiviCRM;administer CiviCRM`
* Contains: Text
* Notes:
    * If you want the permissions to be an "or" situation i.e. User needs either access CiviCRM or administer CiviCRM put a `;` between the permissions. If you want it so that users need multiple permissions put a `,` between
    * This value will be inherited from the parent path if not defined.

### `<access_callback>` {:#access_callback}

* Containing element: [`<item>`](#item)
* Description: Function to be used to check access to the route
* Example: `CRM_Core_Permission::checkMenu`
* Contains: Text
* Notes:
    * If you wish for this route to be public you can set it to be 1.
    * This value will be inherited from the parent path if not defined.

### `<adminGroup>` {:#adminGroup}

* Containing element: [`<item>`](#item)
* Description: Is this menu part of a Administration group and if so which one
* Example: `Manage`
* Contains: Text

### `<comment>` {:#comment}

* Containing element: [`<item>`](#item)
* Description: ???
* Contains: Text

### `<component>` {:#component}

* Containing element: [`<item>`](#item)
* Description: ???
* Example: `CiviContribute`
* Contains: Text

### `<desc>` {:#desc}

* Containing element: [`<item>`](#item)
* Description: What is the description of this route
* Example: `This sets up specific eway settings`
* Contains: Text

### `<icon>` {:#icon}

* Containing element: [`<item>`](#item)
* Description: What icon should display next to the title in the menu
* Example: `admin/small/duplicate_matching.png`
* Contains: Text

### `<is_public>` {:#is_public}

* Containing element: [`<item>`](#item)
* Description: This determines whether the path is considered to be "frontend" (`true`) or "backend" (`false`, the default) in CMSes that make such a distinction (Joomla! and WordPress).  Attempting to visit the path via the wrong end of the site may result in denied permission or the site acting like the path doesn't exist.
* Contains: `true` or `false`

!!! tip
    The word "public" is a bit of a misnomer.  The `<is_public>` element determines whether the path works on the public side of the website, but it *does not* grant permission for it to be seen by the public.  Permissions are handled by `<access_callback>` and `<access_arguments>`.

### `<is_ssl>` {:#is_ssl}

* Containing element: [`<item>`](#item)
* Description: If `true`, HTTP visitors will be redirected to HTTPS when they visit this path if the site has "Force Secure URLs (SSL)" set to "Yes".  The visitors will not be redirected if this element is set to `false` or omitted.
* Contains: `true` or `false`
* Notes:
  * This value will be inherited from the parent path if not defined.

### `<item>` {:#item}

* Containing element: [`<menu>`](#menu)
* Contains: Elements

Elements acceptable within `<item>`

| Element | Acceptable instances |
| -- | -- |
| [`<access_arguments>`](#access_arguments) | 0 or 1 |
| [`<access_callback>`](#access_callback) | 0 or 1 |
| [`<adminGroup>`](#adminGroup) | 0 or 1 |
| [`<comment>`](#comment) | 0 or 1 |
| [`<component>`](#component) | 0 or 1 |
| [`<desc>`](#desc) | 0 or 1 |
| [`<icon>`](#icon) | 0 or 1 |
| [`<is_public>`](#is_public) | 0 or 1 |
| [`<is_ssl>`](#is_ssl) | 0 or 1 |
| [`<page_arguments>`](#page_arguments) | 0 or 1 |
| [`<page_callback>`](#page_callback) | 0 or 1 |
| [`<page_type>`](#page_type) | 0 or 1 |
| [`<path>`](#path) | 1 |
| [`<path_arguments>`](#path_arguments) | 0 or 1 |
| [`<return_url>`](#return_url) | 0 or 1 |
| [`<skipBreadCrumb>`](#skipBreadCrumb) | 0 or 1 |
| [`<title>`](#title) | 1 |
| [`<weight>`](#weight) | 0 or 1 |

### `<menu>` {:#menu}

* Containing element: none (this is the root element)
* Contains: Elements

Elements acceptable within `<menu>`

| Element | Acceptable instances |
| -- | -- |
| [`<item>`](#item) | 1+ |


### `<page_arguments>` {:#page_arguments}

* Containing element: [`<item>`](#item)
* Description: Arguments passed to the function generating the content of the route
* Example: `addSequence=1`
* Contains: Text
* Notes:
  * This value will be inherited from the parent path if not defined.

### `<page_callback>` {:#page_callback}

* Containing element: [`<item>`](#item)
* Description: Function called to generate the content of the route
* Example: `CRM_Contact_Page_Dashboard`
* Contains: Text
* Notes:
  * This value will be inherited from the parent path if not defined.

### `<page_type>` {:#page_type}

* Containing element: [`<item>`](#item)
* Description: What type of a page is this
* Example: `1 or contribute`
* Contains: Text
* Notes:
    * If this is not set the default is 0

### `<path>` {:#path}

* Containing element: [`<item>`](#item)
* Description: The path is the url route that this menu item is for
* Example: `civicrm/admin/eway/settings`
* Contains: Text

!!! Caution "Caution: Wild card sub-paths"
    One path can match all sub-paths.  For example, `<path>civicrm/admin</path>` can match `http://example.org/civicrm/admin/f/o/o/b/a/r`.  However, one should avoid designs which rely on this because it's imprecise and it can be difficult to integrate with some frontends.

### `<path_arguments>` {:#path_arguments}

* Containing element: [`<item>`](#item)
* Description:  These are arguments to be added to the url when it is loaded. These are generally useful when redirecting people after filling in the page or form
* Example `ct=Household`
* Contains: Text

### `<return_url>` {:#return_url}

* Containing element: [`<item>`](#item)
* Description: ???
* Contains: Text

### `<skipBreadCrumb>` {:#skipBreadCrumb}

* Containing element: [`<item>`](#item)
* Description: Should this route not generate any breadcrumbs
* Contains: `true` or `false`

### `<title>` {:#title}

* Containing element: [`<item>`](#item)
* Description: Title of the route
* Example: `Eway Settings`
* Contains: Text

### `<weight>` {:#weight}

* Containing element: [`<item>`](#item)
* Description: Set a weight on the route to change the order of it in the menu
* Example: `105`
* Contains: Integer
* Notes:
    * Items with heavier weights will appear lower in the menu
