If you're starting out with CiviCRM and Joomla! you might want to 
check the [Joomla guide for creating a plugin](http://docs.joomla.org/Plugin).

Once created plugins may be packaged and installed with the Joomla installer
or the files can be placed in the appropriate folder and installed with the 
discover method.

You can implement your hooks using a single hooks file, or by creating a Joomla
plugin. In general, implementing through a plugin is preferred as you can 
benefit from the native access control within the plugin structure, include 
code that responds to other Joomla events, organize your hook 
implementations into multiple plugins which may be enabled/disabled as 
desired, and roughly follow the event-observer pattern intended by Joomla 
plugins.

## Single File Implementation

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

## Plugin Implementation

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

## Sample Joomla Plugin With Hooks

This is a simple example of a plugin for Joomla that implements CiviCRM 
hooks. It consists of two file tabs.php and tabs.xml along with the blank 
index.html file which is considered good Joomla coding practice.

Note: Somewhere around Joomla 2.5.20 the JPlugin class was moved to cms
.plugin.plugin from joomla.plugin.plugin (see the jimport call in Tab.php 
below). If you have not remained current with the latest Joomla revision, 
you may need to reference the correct location. If you find your plugins 
fail after updating to the latest release, be sure to check and fix that 
reference (it will often fail silently).

Tabs.php
```
<?php
/**
 * @version
 * @package     Civicrm
 * @subpackage  Joomla Plugin
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
 
// No direct access
defined('_JEXEC') or die;
 
jimport('cms.plugin.plugin');
class plgCivicrmTabs extends JPlugin
{
 
/**
 * Example Civicrm Plugin
 *
 * @package     Civicrm
 * @subpackage  Joomla plugins
 * @since       1.5
 */
    public function civicrm_tabs(&$tabs, $contactID)
    {
 
    // unset the contribition tab, i.e. remove it from the page
    unset( $tabs[1] );
 
    // let's add a new "contribution" tab with a different name and put it last
    // this is just a demo, in the real world, you would create a url which would
    // return an html snippet etc.
    $url = CRM_Utils_System::url( 'civicrm/contact/view/contribution',
                                  "reset=1&snippet=1&force=1&cid=$contactID" );
    $tabs[] = array( 'id'    => 'mySupercoolTab',
                     'url'   => $url,
                     'title' => 'Contribution Tab Renamed',
                     'weight' => 300 );
    }
}

```

Tabs.xml

```
<?xml version="1.0" encoding="UTF-8"?>
<extension version="1.6" type="plugin" group="civicrm">
    <name>plg_civcrm_tabs</name>
    <author>Joomla! Project</author>
    <creationDate>November 2005</creationDate>
    <copyright>Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.</copyright>
    <license>GNU General Public License version 2 or later; see LICENSE.txt</license>
    <authorEmail>admin@joomla.org</authorEmail>
    <authorUrl>www.joomla.org</authorUrl>
    <version>1.6.0</version>
    <description>PLG_CIVICRM_TABS_XML_DESCRIPTION</description>
    <files>
        <filename plugin="tabs">tabs.php</filename>
        <filename>index.html</filename>
    </files>
    <config>
    </config>
    </extension>

```

You can make plugins that include multiple hooks or you can make separate 
plugins. What is appropriate will depend on the application.