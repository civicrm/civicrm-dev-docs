# Menu XML Structure

CiviCRM's menu routeing system is built based on XML files. These XML files define what class gets loaded when a specific path is arrived at, what permissions are required to access that route.

The standard menu XML files can be found in `CRM/Core/xml/Menu/`. Each route is defined as an "Item" Within the menu. In extensions you should add your menu to `<extension folder>/xml/Menu/<extensionName>.xml`

!!! Caution "Caution: Wild card sub-paths"
    One path can match all subpaths.  For example, `<path>civicrm/admin</path>` can match `http://example.org/civicrm/admin/f/o/o/b/a/r`.  However, one should avoid designs which rely on this because it's imprecise and it can be difficult to integrate with some frontends.

## XML Item Structure

The XML will contain a structure made up of the following fields

| Key | Contains | Example | Acceptable Instances | Required | Purpose |
| --- | --- | --- | --- | --- | --- |
| path | text | `civicrm/admin/eway/settings` | 1 | Yes | The URL path that this menu item is for |
| Title | text | `Eway Settings` | 1 | Yes | The Page title |
| `access_callback` | text | `CRM_Core_Permission::checkMenu` | 0 or 1 | No | Function to be called when checking access to this route, if you wish for this route to be public set it to be 1 |
| `access_arguments` | text | `access CiviCRM` | 0 or 1 | No | Arguments to be passed to to the function called to check access. To set up an Or i.e either this permission or this one use a `;` as the separator otherwise for AND purpose use `,`  | 
| `page_callback` | text | `CRM_Contact_Page_Dashboard` | 0 or 1 | No | Name of class to be called when loading this route |
| `page_arguments` | text | `addSequence=1` | 0 or 1 | No | Arguments to be passed to the Run function within the class being called | 
| `path_arguments` | text | `ct=Household` | 0 or 1 | No | Arguments to be added to the URL path. These can be useful as the code may call to set variables in the template based on what is passed in the URL |
| `is_public` | Boolean | `true` | 0 or 1 | No | Is this route public i.e. doesn't need authentication |
| `is_ssl` | Boolean | `true` | 0 or 1 | No | Does this route need SSL to work. Main example of route that need it is wherever your submitting credit card information |
| `weight` | integer | `105` | 0 or 1 | No | Order various menu items with lighter weights appearing higher up |
| `adminGroup` | text | `Manage` | 0 or 1 | No | What Administration group does this route fit under |
| `dsc` | text | `This sets up specific eway settings` | 0 or 1 | No | Page Description |
| `icon` | text | `admin/small/duplicate_matching.png` | 0 or 1 | No | Icon to display next to menu text | 
| `page_type` | text | `1` | 1 | Yes | What type of page is this e.g. 0 for non set or contribute or event pages | 
| skipBreadCrumb | Boolean | `True` | 0 or 1 | No | Should we not add breadcrumbs for this menu item |

