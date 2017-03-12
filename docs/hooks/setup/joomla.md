## Using hooks with Joomla!

Hooks may be implemented in Joomla in two ways, depending on the version of
CiviCRM and Joomla you are using. For sites running Joomla 1.5 with CiviCRM up
to and including version 3.4, you implement hooks with a single civicrmHooks.php
in your php override directory. Sites running Joomla 1.6+ and CiviCRM 4+ may
implement with either that single hooks file, or by creating a Joomla plugin.
In general, implementing through a plugin is preferred as you can benefit from
the native access control within the plugin structure, include code that
responds to other Joomla events, organize your hook implementations into
multiple plugins which may be enabled/disabled as desired, and roughly follow
the event-observer pattern intended by Joomla plugins.

As you implement hooks in Joomla, be sure to check the CiviCRM wiki for the
most up-to-date information:

-   [http://tiny.booki.cc/?hooks-in-joomla](http://tiny.booki.cc/?hooks-in-joomla)
-   [http://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+hook+specification\#CiviCRMhookspecification-Proceduresforimplementinghooks%28forJoomla%29](http://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+hook+specification#CiviCRMhookspecification-Proceduresforimplementinghooks%28forJoomla%29)

To implement hooks with a single file, you will do the following:

1.  If you have not done so already, create a new directory on your server to
    store your PHP override files. In Joomla, that is commonly placed in the
    media folder, as it will not be impacted by Joomla and CiviCRM upgrades.
    For example, you might create the following
    folder: `/var/www/media/civicrm/customphp`.
2.  If you have not done so already, configure your system to reference the
    folder you've created as your override directory. Go to: CiviCRM
    Administer > Global Settings > Directories. Change the value of Custom
    PHP Path Directory to the absolute path for the new directory (e.g.,
    "/var/www/media/civicrm/customphp" if you used that suggestion in the
    earlier step). The custom override directory may also be used to store
    modified copies of core files -- thus overriding them. You may want to
    familiarize yourself with its purpose if you are not yet.
3.  Create a file named *civicrmHooks.php* to contain your hook
    implementations, and upload the file to the directory you just created.
4.  Within that file, your hooks will be implemented by calling the hook
    function prefaced by "joomla\_". For example, you would call the buildForm
    hook (used to modify form rendering and functionality) by adding the
    following function to your hook file:

```php
function joomla_civicrm_buildForm( $formName, &$form ) {
    //your custom code
}
```

If you are implementing hooks with a Joomla plugin, you will create a standard,
installable plugin package. At a minimum, a plugin extension will consist of an
xml file (defining the plugin and its parameters), and a php file. Within the
php file, define a class that extends the Joomla JPlugin class, and call your
hooks but adding the appropriate functions. For example, your plugin file may
look like the following:

```
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

class plgCiviCRMMyPlugin extends JPlugin {
    public function civicrm_tabs(&$tabs, $contactID) {
    	//your code to alter the contact summary tabs
    }
}
```

The first two lines are required -- the first is for security purposes, and
ensures the code will exit if it has not been called from within Joomla. The
second includes the necessary parent plugin class.

Joomla plugin classes follow standard naming conventions which you should
follow. By naming this plugin class "plgCiviCRMMyPlugin," I am stating that the
plugin resides in the plugin/civicrm/ folder, and the plugin file is named
"myplugin.php."

For more information about implementing hooks through plugins, see this [blog
article](http://civicrm.org/blogs/mcsmom/hooks-and-joomla)

Note the reference in the comments to a sample plugin which you can download
and modify.
