# Permissions Framework in CiviCRM

## Introduction

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

Depending on how the API is called, it is either called with a `check_permissions` flag turned on or turned off. When it is turned off, it will run the API call without checking if the user has the necessary permissions to perform the action(s) needed.

If the API call is made with `check_permissions` turned on then the permissions of the user making the API call will be evaluated to ensure the user has the correct permissions to perform the action(s) they are attempting to. 

The **APIv3** interfaces (JavaScript/PHP API interfaces) used by CLI tools (e.g: drush or WP-cli), CiviCRM Core and extensions by default run with the `check_permissions` flag turned off.

The **APIv3** rest interface, however, defaults to running with the `check_permissions` flag turned on. 

The permissions required to make various API calls are defined in [`CRM_Core_Permission::getEntityActionPermissions()`](https://lab.civicrm.org/dev/core/blob/master/CRM/Core/Permission.php#L935). 

By default in **APIv4** the `check_permissions` flag is turned on regardless of the API interface being used, for further details see the [API wrapper differences between API v3 and APIv4](../api/v4/differences-with-v3.md#api-wrapper).

## Extending and Implementing Permission Structure {:#extensions}

In an extension, authors have a wide ability to implement the same permissions structure as in CiviCRM Core but also to extend in a number of ways.

### Implementing Permissions in extensions

[hook_civicrm_navigationMenu](../hooks/hook_civicrm_navigationMenu.md) allows for extension providers to define new menu items and the associated permissions to that menu item. However this does not specifically grant access to the end point just decides whether the menu item or not is visible to the user based on the permissions of that user.

To implement access to a specific url that you are creating as part of your extension. Extension authors should create a `MyExtension.xml` file in `MyExtension/xml/Menu/`. This file should be structure like the core menu XML files and this will determine the permissions to actually access the page not just whether a user can see the menu item or not.

### Extending Permissions

If you want to add a permission to the list in the CMS, you can implement [hook_civicrm_permission](../hooks/hook_civicrm_permission.md). Here, you can specify new permissions that will then be available to select within the CMS permissions.

### Altering API Permissions

If you want to alter the permissions the API uses during its permissions check, you can implement the [hook_civicrm_alterAPIPermissions](../hooks/hook_civicrm_alterAPIPermissions.md). Note that you should be very careful when altering any permissions because they may have unintended consequences.
