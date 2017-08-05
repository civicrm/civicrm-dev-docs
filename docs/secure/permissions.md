# Permissions Framework in CiviCRM

## Introcution

CiviCRM has a number of permissions that are able to be set through the relevant permissions page of your Content Management System. These are the primary way CiviCRM controls what parts of CiviCRM users are able to see. E.g. Accessing the Scheduled Reminders screen in CiviCRM requires the permission of Administer CiviCRM at least. Permissions are also there to control access to various entities such as contacts. There are generally 2 permissions `Access All Contacts` and `Edit All Contacts` These give the users that are granted those permissions the access to See and possibly edit all the contacts metioned.

## Key Permissions

As mentioned in the introduction there are some crucial permissions to get heads around

 - `Administer CiviCRM` - This is a very broad permisison and generally speaking is designed to grant access to any administrative parts of CiviCRM
 - `Edit All Contacts`, `View All Contacts` - This grants access to all contacts within the database for editing purposes
 - `Access All Customdata` - This grants the user access to view and edit all the custom data in the system. 
 - `Access n` - These permissions are for each core module e.g. CiviContribute CiviEvent. Where N will be the title of that module. These permissions grant access to those areas of CiviCRM. These permissions will only show up if the modules are enabled. 

## Implementing Permissions logic

When in an extension you create a page or form there will be an XML routing file that will be needed to register your path, within the xml document you can put in a key of `access_arguments` This allows developers to specify what should be the default permissions that are needed to access this url. It should be noted if you want to specify an or contition e.g. Administer CiviCRM or Access CiviEvent you need to put it as ```xml<access_arguments>Administer CiviCRM;Access CiviEvent</access_arguments>```. If you want to do an And i.e. the user needs both permissions you should change the ; for a , in that example. 

The other form of permissions implementation happens within generally speaking run functions of pages as normally pages have linked to the various forms and the links list is controlled on permissions. The main way this is done is by looking at the function `getIdAndAction` in `CRM\Core\Page\Basic.php` This determines what action e.g. Update, Browse, Delete etc and checks if the user has permission do to that Action.

Another function that does similar work but is more useful is `CRM_Core_Permission::checkActionPermission` This generally gets used in BAO functions when perparing list of links.

When developers are writing code it is useful to use `CRM_Core_Permission::check` to see if the user holds the required permissions.

## API Permissions

Depending on how the API is called it is either called with a "check_permissions" flag turned off or turned on. When it is turned off, It will run the API without checking if the user has the necessary permissions to perform the action needed. If you turn check_permissions on then there will be tests done. By default code in CLI tools e.g. drush or WP-cli or within core code or extension code that is done at run time, the default in CiviCRM APIv3 is that the check_permissions flag is turned off. If you call the CiviCRM API through the rest interface then by default the check_permissions flag will be turned on. The permissions need to make various API calls are defined in `CRM_Core_Permission::getEntityActionPermissions()`

## Extending Permissions

If developers want to add a permission to the list in their CMS, developers can implement `hook_civicrm_permission` This allows developers to specifiy new permissions that would then be avaliable to then select within the content management system permissions. See the [hook documentation](/hooks/hook_civicrm_permission/) for more information and example implementation.

## Altering API Permissions

If a developer wants to alter the permissions that are used in the API permissions check to alter what permissions are needed. Developers can implement the `hook_civicrm_alterAPIPermissions` this will alter permissions. See the [hook documentation](/hooks/hook_civicrm_alterAPIPermissions/) for examples. It should be important to note that developers should be very careful when altering any permissions as they may have unintended consequences.

