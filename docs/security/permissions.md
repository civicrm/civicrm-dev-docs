# Permissions Framework in CiviCRM

## Introcution

CiviCRM has a number of permissions that are able to be set through the relevant permissions page of your Content Management System. These are the primary way CiviCRM controls what parts of the application users are able to see. For example, accessing the "Scheduled Reminders" screen in CiviCRM requires the permission of `Administer CiviCRM` at least. Permissions are also there to control access to various entities such as contacts. There are generally 2 permissions `Access All Contacts` and `Edit All Contacts`. Users which have those permissions will be able to see and possibly edit all contacts.

## Key Permissions

As mentioned in the introduction there are some crucial permissions to get heads around

* `Administer CiviCRM` - This is a very broad permission and generally speaking is designed to grant access to any administrative parts of CiviCRM
* `Edit All Contacts`, `View All Contacts` - This grants access to all contacts within the database for editing purposes
* `Access All Customdata` - This grants the user access to view and edit all the custom data in the system. 
* `Access X` - These permissions are for each core module e.g. CiviContribute CiviEvent. Where `X` will be the title of that module. These permissions grant access to those areas of CiviCRM. These permissions will only show up if the modules are enabled. 

## Implementing Permissions logic

When in an extension you create a page or form there will be an XML routing file that will be needed to register your path, within the XML document you can put in a key of `access_arguments` This allows developers to specify what should be the default permissions that are needed to access this url. It should be noted if you want to specify an "OR" condition e.g. "Administer CiviCRM *or* Access CiviEvent" you need to put it as `xml<access_arguments>Administer CiviCRM;Access CiviEvent</access_arguments>`. If you want to do an "AND" i.e. the user needs both permissions you should change the ; for a , in that example. 

Permissions are also implemented within `run` functions of pages. Normally pages have permissions associated with the various forms and links. The main way this is done is by looking at the function `getIdAndAction` in `CRM\Core\Page\Basic.php` This determines what action e.g. Update, Browse, Delete etc and checks if the user has permission do to that Action.

Another function that does similar work but is more useful is `CRM_Core_Permission::checkActionPermission`. This generally gets used in BAO functions when preparing a list of links.

When you write code, you can look at `CRM_Core_Permission::check` to see if the user holds the required permissions.

## API Permissions

Depending on how the API is called, it is either called with a `check_permissions` flag turned off or turned on. When it is turned off, it will run the API without checking if the user has the necessary permissions to perform the action needed. If you turn `check_permissions` on then there will be tests done. By default code in CLI tools e.g. drush or WP-cli or within core code or extension code that is done at run time, the default in CiviCRM APIv3 is that the `check_permissions` flag is turned off. If you call the CiviCRM API through the rest interface then by default the `check_permissions` flag will be turned on. The permissions needed to make various API calls are defined in `CRM_Core_Permission::getEntityActionPermissions()`

## Extending Permissions

If you want to add a permission to the list in the CMS, you can implement [hook_civicrm_permission](/hooks/hook_civicrm_permission/). Here, you can specify new permissions that will then be available to select within the CMS permissions.

## Altering API Permissions

If you want to alter the permissions the API uses during its permissions check, you can implement the [hook_civicrm_alterAPIPermissions](/hooks/hook_civicrm_alterAPIPermissions/). Note that you should be very careful when altering any permissions because they may have unintended consequences.
