Goals and background
--------------------

-   This documents how to extend CiviCRM to meet your needs. CiviCRM
    uses hooks in a very similar manner to Drupal, primarily because
    (based on our experience with Drupal's extension architecture) we
    think it is clean and non-intrusive, yet incredibly powerful. For a
    simple example module check [civitest
    module](http://svn.civicrm.org/trunk/drupal/civitest.module.sample)
-   See Drupal [hook documentation](http://drupal.org/node/292) for a
    description of how hooks are implemented.
-   See [CRM/Utils/Hook.php](http://svn.civicrm.org/civicrm/trunk/CRM/Utils/Hook.php)
    in CiviCRM for the source code that invokes these hooks.

Implementing hooks
------------------

-   In Drupal or CiviCRM, hooks can be implemented within a module or
    extension. In general, implement a hook by declaring a global
    function that starts with the name of your module and ends with the
    name of the hook.

In Drupal module or a CiviCRM extension for example: to implement hook\_civicrm\_buildForm from within the "my\_custom" module/extension you would add the following function to your main .php or .module file (or a file always included by that script):
                
````
function my_custom_civicrm_buildForm($formName, &$form) {
  // since the $form object was passed by reference, modifying it here will change it permanently
  $form->assign('intro_text', ts('hello world'));
 }

````


As long as the module/extension is enabled, this function will be called every time CiviCRM builds a form.


In WordPress, hooks can be implemented in a variety of ways. You can write a plugin or include them in your theme's 'functions.php' file - where you place them depends largely on whether they are theme-dependent or theme-independent. The general rule for targeting the hook is to remove the 'hook\_' prefix when you create the filter or action, but see the "For a WordPress Plugin" section below for more details.

The following code block shows the simplest form of a hook implementation in WordPress. In this case, the code receives callbacks from 'hook\_civicrm\_pre':

```
// hook into civicrm_pre
add_filter( 'civicrm_pre', 'my_plugin_pre_callback', 10, 4 );

function my_plugin_pre_callback( $op, $objectName, $objectId, &$objectRef ) {
  // your code here
}
```
                                                      
As long as the plugin is active (or - if the code is in 'functions.php' - as long as your theme is active), this function will be called every time CiviCRM is about to save data to the database.


For A CiviCRM (native) Extension
--------------------------------

-   See this page for [creating an extension and implementing hooks
    within it](https://wiki.civicrm.org/confluence/display/CRMDOC/Create+a+Module+Extension).

For A Drupal Module
-------------------

-   See this page for [creating a module in Drupal](http://drupal.org/node/1074360) esp. the section on implementing hooks.
-   For a working example, see the hook\_civicrm\_postProcess documentation below.
-   You can also find examples in your CiviCRM install in [drupal/civitest.module.sample](http://svn.civicrm.org/civicrm/trunk/drupal/civitest.module.sample)

For a Joomla Plugin
-------------------

-   See this page for [creating a Joomla plugin](http://docs.joomla.org/Plugin). Joomla plugins implement the
    observer design pattern.
-   Hook plugins should be placed in the civicrm plugin group in a subfolder with the same name as the plugin.
-   Once created plugins may be packaged and installed with the Joomla installer or the files can be placed in the appropriate folder and installed with the discover method.
-   See this [sample Joomla plugin for CiviCRM hooks](https://wiki.civicrm.org/confluence/display/CRMDOC/Example+Joomla+Plugin+for+implementing+hooks)

For a WordPress Plugin
----------------------

-   For a detailed overview of the updated relationship between WordPress and CiviCRM, see the blog post [Working with CiviCRM 4.6 in WordPress](https://civicrm.org/blogs/haystack/working-civicrm-46-wordpress) on the CiviCRM website.
-   In summary, as of CiviCRM 4.6 there is (almost) full compatibility with the WordPress actions and filters system.
-   Any PHP file that will be included by WordPress can be used to contain your hook implementations. Use an in-house plugin, your site's 'functions.php' file, or place a file named 'civicrmHooks.php' in your CiviCRM custom php path (as specified in
    Administer -\> System Settings -\> Directories -\> Custom PHP Path Directory).
-   Prior to CiviCRM 4.6, hooks had to use the prefix "wordpress\_" as
    the replacement for the "hook\_" part of the hook name. So to implement 'hook\_civicrm\_pre' you had to write: 
```
    function wordpress_civicrm_pre($op, objectName, $objectId, &$objectRef)
```
This method still works, so if you have legacy modifications, they will not break.

-   As of CiviCRM 4.6, the general rule for targeting the hook is to remove the 'hook\_' prefix when you create the filter or action. So, if your plugin or theme wants to receive callbacks from 'hook\_civicrm\_pre', the filter should be written as

```
add_filter('civicrm_pre', 'my_callback_function', 10, 4 ) 
```
or if your callback method is declared in a class, the filter should be written as 

```
add_filter( 'civicrm_pre', array( $this, 'my_callback_method', 10, 4 )
```

For more details (as well as the exceptions to this rule) see the [blog post](https://civicrm.org/blogs/haystack/working-civicrm-46-wordpress) mentioned above.

Inspecting hooks
----------------

The documentation about hooks can be somewhat abstract, and it sometimes
helps to see interactively how the hooks run.

-   If you use Drupal, then you can inspect some hooks by installing
    these two Drupal modules:
    -   [devel](http://drupal.org/project/devel)
    -   [civicrm\_developer](https://github.com/eileenmcnaughton/civicrm_developer)
-   If you use WordPress, you can inspect hooks by installing the following plugin:
    -   [Query Monitor](https://wordpress.org/plugins/query-monitor/)


Example Drupal module for implementing hooks
---
I found it useful, when implementing hooks, to write wrapper code for most of them.  The [attached zip](http://wiki.civicrm.org/confluence/download/attachments/86213379/callhooks.zip?version=1&modificationDate=1372586243000&api=v2) file is an example Drupal module that illustrates this technique.  From the README file:

This is just example code to implement custom hooks for CiviCRM.

Each custom hook defined by CiviCRM is implemented here.

To use:

1. Install this module or incorporate its code into your own custom module.
2. Uncomment some/all the watchdog lines.
3. Visit the page you need to modify via a CiviCRM custom hook.
4. Re-comment the watchdog lines.
5. Visit admin/reports/dblog and look at the comments there.
6. Write a function as described by one of the comments.



Example Joomla Plugin for implementing hooks
---

This is a simple example of a plugin for Joomla that implements CiviCRM hooks. It consists of two file tabs.php and tabs.xml along with the blank index.html file which is considered good Joomla coding practice.

Note: Somewhere around Joomla 2.5.20 the JPlugin class was moved to cms.plugin.plugin from joomla.plugin.plugin (see the jimport call in Tab.php below). If you have not remained current with the latest Joomla revision, you may need to reference the correct location. If you find your plugins fail after updating to the latest release, be sure to check and fix that reference (it will often fail silently).

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

You can make plugins that include multiple hooks or you can make separate plugins. What is appropriate will depend on the application.

Setting and getting custom field values from within hooks
---

To get a custom field ID given the custom field name and custom group name, you can use the following code:

```
require_once 'CRM/Core/BAO/CustomField.php';

$customFieldID = CRM_Core_BAO_CustomField::getCustomFieldID( $fieldLabel, $groupTitle );

```

For setting and getting custom field values in hooks, you need to know the field ID of the custom field(s) you want to work with. The easiest place to find these IDs is in the civicrm_custom_field table in the database. You'll then access these fields as "custom_ID". So if you have a field holding a custom value whose ID in the civicrm_custom_field table is 34, you'll use "custom_34" to access it.

Once you have the ID(s), you'll want to use the setValues and getValues functions in the CRM/Core/BAO/CustomValueTable.php file. Here are a couple of examples of their use:

Setting values (on a Contribution object's custom fields):

```
$custom_fields = array('foo' => 'custom_1', 'bar' => 'custom_2');
function modulename_civicrm_pre ($op, objectName, $objectId, &$objectRef) {
  if ($objectName != 'Contribution' || ($op != 'edit' && $op != 'create')) {
    return;
  }
  $contribution_id = $objectId;
  require_once 'CRM/Core/BAO/CustomValueTable.php';
  $my_foo = 'blah';
  $my_bar = 'baz';
  $set_params = array('entityID' => $contribution_id,
    $custom_fields['foo'] => $my_foo, $custom_fields['bar'] => $my_bar);
  CRM_Core_BAO_CustomValueTable::setValues($set_params);
}

```

Getting values (from a Contribution's associated Contact object):

```
$custom_fields = array('contact_foo' => 'custom_3', 'contact_bar' => 'custom_4');
function modulename_civicrm_pre ($op, objectName, $objectId, &$objectRef) {
  if ($objectName != 'Contribution' || ($op != 'edit' && $op != 'create')) {
    return;
  }
  // set the field names to 1 that we want to get back
  $get_params = array('entityID' => $objectRef['contact_id'],
    $custom_fields['contact_foo'] => 1, $custom_fields['contact_bar'] => 1);
  require_once 'CRM/Core/BAO/CustomValueTable.php';
  $values = CRM_Core_BAO_CustomValueTable::getValues($get_params);
  $my_cfoo = $values[$custom_fields['contact_foo']];
  $my_cbar = $values[$custom_fields['contact_bar']];
}

```

Note that custom field values may not always be available when you might expect. For instance, you can't retrieve custom field values in the 'create' operation in the _pre and _post hooks, because the custom field values haven't been stored yet. However, you can retrieve values in the 'edit' operation.