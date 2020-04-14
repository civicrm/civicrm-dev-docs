# Setting Reference

## What is a Setting?

Applications like CiviCRM often need to be 'configured' with small bits of persistent data, which may be different for each installation. Examples are:

 - The url of the CiviCRM site.
 - The path on the server where the CiviCRM code lives
 - MySQL connection information
 - The primary language of the install.

Sites can configure the settings through the UI or [put in overrides](https://docs.civicrm.org/sysadmin/en/master/customize/settings/)

This page describes the CiviCRM standard tool for managing these configuration settings – the 'Settings' system. As a developer, you'll want to understand this system so you can access CiviCRM 'core' settings (e.g. if you're sending out a system email, you'll want to set an appropriate From name and address). You may also want to use this system for storing and retrieving your own settings for your extension. If you're a Drupal developer, this system is analogous to the Drupal variables table and tools.

Not all configuration-type values need to use this system - in particular, if you need to store persistent data that changes frequently and/or may grow indefinitely in size, this may not be the right tool. For example: if you want to have a custom set of allowable values for your extension, you'll want to use the 'Option Group' system. If you want to temporarily cache a value for the duration of a session, then the Cache system is the right tool. And if you're saving persistent data that may grow indefinitely over time, you'll want to look into creating and managing your own tables.

## Background

In early versions of CiviCRM, all settings were stored either in the `CRM_Core_Config` object or else globally in civicrm.settings.php. v4.x introduced a more open "Settings" system. This documentation covers the new way of managing settings.

The "Settings" system developed incrementally:

- v4.1 – Added `civicrm_setting` table, `$civicrm_settings` global, and `CRM_Core_BAO_Setting` class. Migrated some settings data from `civicrm_domain` to `civicrm_setting`.
- v4.2 – Added public Settings API. Included support for CRUD'ing data from multiple sources (`civicrm_domain` and `civicrm_setting`). Migrated more settings data from `civicrm_domain` to `civicrm_setting`.
- v4.3 to v4.6 – Incrementally migrate more settings data.
- v4.7 – Added `Civi::settings()` and refactored init system. Finished migrating data from `civicrm_domain` to `civicrm_setting`.
- v5.7 - Added `Civi::contactSettings()` as a facade to allow for managing of Contact Settings rather than domain level settings.
- v5.8 - Added Generic CiviCRM settings form.
- v5.21 - `CRM_Contact_BAO_Setting::setItem()` will now emit deprecation warnings. Use `Civi::settings()`, `Civi::contactSettings()`, or the Setting API instead.

## Settings Definition

Settings are defined in the /settings directory, in files ending with `.setting.php`

Each file consists of a php snippet which returns an array. Array keys are strings corresponding with each setting's name. Values are an array of metadata properties for that setting. An example array is as follows:

```php
  'remote_profile_submissions' => array(
    'name' => 'remote_profile_submissions',
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => FALSE,
    'html_type' => 'radio',
    'add' => '4.7',
    'title' => ts('Accept profile submissions from external sites'),
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => ts('If enabled, CiviCRM will permit submissions from external sites to profiles. This is disabled by default to limit abuse.'),
  ),
```

### Supported Properties

The Supported Properties for settings are:

| property | Usage | Example Notes |
| --- | --- | --- |
| `name` | Name of this setting| This is the same as the array key. Required |
| `type` | Data type| String, Integer, Boolean (Array is discouraged as current uses of it do not have full handling and String  + a serialize key addresses most uses) |
| `title` | Title (admin form)| note: use ts() or E::ts() for extensions|
| `description` | Description (admin form)| note: use ts() or E::ts() for extensions|
|`serialize`|define how an array of values are stored|ie CRM_Core_DAO::SERIALIZE_JSON is recommended. Other constants exist on the CRM_Core_DAO class|
| `default` | Default value| Value to use if setting is not set. |
| `add` | Version added to CiviCRM| |
| `html_type` | Html type (admin form)| This is the preferred way to describe the html type as it is not quick form specific. It will be used it present. Syntax is lower case. e.g 'select', 'radio', 'checkboxes', 'checkbox', 'text', 'textarea', 'entity_reference|
| `quick_form_type` | Widget type (admin form)| YesNo, CheckBox, CheckBoxes, Select, EntityRef. This is not required if html_type (preferred) is set|
|`settings_pages`|Admin Pages to render this setting on|e.g  ['event' => ['weight' => 10]]. This works if the Generic form is used (see further down)|
|`pseudoconstant`|Provides information to build a list of available options| This is the preferred methodology for lists of options and currently supports either a callback - e.g ```['callback' => 'CRM_Core_SelectValues::geoProvider']``` or an option group name [`'optionGroupName' => 'advanced_search_options'`]. When specifying an `optionGroupName` you can optionally specify `keyColumn` to return a column from `civicrm_option_value` to use as the key.  By default the `keyColumn` is the `value` column. The format is the same as that used for [DAO](database/schema-definition.md#table-field-pseudoconstant)|
|`options`|provides an array of available options|This is not the preferred methodology but make make sense for very simple lists. |
|`entity_reference_options`|extra data to pass when adding an entity reference|e.g if the entity is not contact this make be needed as in `['entity' => 'group', 'select' => array('minimumInputLength' => 0)]`|
|`documentation_link`|Array of information to build the 'learn more' link| 'page' is required, if on the wiki 'resource' is also needed - e.g 'documentation_link' => ['page' => 'Multi Site Installation', 'resource' => 'wiki'],|
| `help_text` | Intended to populated. Popup Help (admin form)| note: use ts(), not working as intended as of 5.8 |
| `html_attributes` |  | size, style, class, etc. |
| `validate_callback` | A string, the name of a callback function to validate the setting value. | The callback is fired whether the setting is updated via admin form or API. The callback should accept the proposed setting value as its only argument. It should return TRUE if the value is valid, otherwise FALSE. In the latter case the API request will fail (`is_error` will be set to 1 in the result). If the callback takes the value by reference, it can modify the setting value before it is saved -- it remains to be seen whether this is wise. Example: 'CRM_Utils_Rule::url' |
| `on_change` | Callback function when this setting is altered e.g when you enable a component or logging| |
| `is_domain` | Domain setting| Setting is_domain to 1 indicates that the setting applies to the entire installation (in single site mode) or to a single domain in multi-site mode. If is_domain is set to 1, then is_contact must be set to 0. |
| `is_contact` | Contact setting| Setting is_contact to 1 indicates that the setting applies to a single contact and can be different for each contact. If is_contact is set to 1, is_domain must be set to 0. |

### Deprecated Properties

Deprecated settings properties are :

| property | Usage | Example /Notes |
| --- | --- | --- |
|`config_only` | Super legacy support - only store in the `$config` object (Removed/unnecessary in v4.7+) | And this |
|`config_key` | If the config key differs from the settings key (rarely used) (Removed/unnecessary in v4.7+) | used in conversions & for 'debug' where the config key name can't be used in the api |
| `legacy_key` | Used for conversions when upgrading and moving data from existing config array to being a setting (Removed/unnecessary in v4.7+) | This happens on upgrade and when `civicrm_api3('system','flush')` is called |
| `prefetch` | Legacy support - will store the setting in the $config object| We need to migrate away from this |
| `group` | none | This is redundant now |
| `group_name` |  Name of group this setting belongs to.| This has been deprecated as of 4.7. Since 4.7, the two main options are `domain` or `contact`. The older names such as `CiviCRM Preferences` are treated as aliases for `domain` or `contact`.|

## Setting Storage and Retrieval

### Domain Settings

To set a setting for a whole domain, developers can use either the Setting.Create API or use `Civi::settings()->set()` to set the value e.g. `Civi::settings()->set('editor_id', 'CKEditor');`. To Retrieve a setting value, developers can use the Setting.Get API or use `Civi::settings()->get()` e.g. `Civi::settings()->get('max_attachments');`

### Contact Settings

After 5.7.0 developers are able to use the `Civi::contactSettings` facade to set contact specific settings. You can also use the API to set it but must pass in a contact_id parameter. To set a contact setting it would be like `Civi::contactSettings($contact_id)->set('navigation', $value)`. To retrieve the setting developers can use `Civi::contactSettings($contact_id)->get('navigation')`

## API actions

The following api actions have been introduced into CiviCRM

 - `civicrm_api3('setting', 'getfields', array());`
 - `civicrm_api3('setting', 'get', array());`
 - `civicrm_api3('setting', 'getvalue', array());` (see note)
 - `civicrm_api3('setting', 'create', array());`
 - `civicrm_api3('setting', 'getdefaults', array());`
 - `civicrm_api3('setting', 'revert', array());`
 - `civicrm_api3('setting', 'fill', array());`

!!! note 
    `'getvalue'` is not a pseudonym for get - it is intended for runtime whereas get is a heavier function intended for configuration

As with all CivicRM API you can access it through your standard CLI tools e.g. 

```
drush civicrm-api setting.create debug_enabled =1 userFrameworkLogging=1
drush civicrm-api setting.revert filters.group=localization domain_id = 3
```

## Smarty / Template Layer

crmAPI will interact with settings but a specific option exists. This will access settings stored in $config or settings

```
{crmSetting name="search_autocomplete_count" group="Search Preferences"}
```
(Group is still required here but .... fixing that)

## Multiple Domains

The settings api supports the following values for `domain_id` :

- `current_domain` (default)
- integer
- array of integers
- `all`

It is desirable to make this api handling of domain id part of the api layer for all api that involve domains

## Adding a new Setting to CiviCRM Core.

1. Add metadata to the appropriate `.setting.php` file about the new setting
2. Add it to the appropriate setting page per below
3. Your setting will not be active until you run flush all caches (e.g `cv flush` or `drush cvapi System.flush`)

!!! warning
    Do not use the "URL Preferences" group to store any setting that is not a url string.
    
## Adding a new Setting to an admin form

The preferred way of doing this is by using the Generic form. To do this the xml that declares
the path should be like this

```xml
  <item>
     <path>civicrm/admin/setting/preferences/event</path>
     <title>CiviEvent Component Settings</title>
     <page_callback>CRM_Admin_Form_Generic</page_callback>
  </item>
```

The last parameter of the path is the designated form and it will pick up any settings
with that parameter in their metadata - e.g to place an item on that page it should have 
the following in it's metadata.

```php
`settings_pages` => ['event' => ['weight' => 10]]
```

- If you are adding to an existing form and that form is not yet using the generic form (
see below) attempt to convert it. Failing that you may have to follow the protocol already 
in place on that form - generally add it to the $_settings array. Usually you should try
to at least progress the form conversion. See the legacy method at the bottom.
- If the template for that form is not set up to handle new items automatically, add the necessary markup, after attempting to progress the conversion first

## Creating a new setting in an Extension

As with core settings, all settings declared in extensions should have appropriate metadata attached to them.

To avoid naming conflicts, it makes sense to prefix settings defined in an extension with the extension shortname. For example, a 'Rate limit' setting in a 'My Extension' should be named.`my_extension_rate_limit`.

1. Ensure that the settings folder is declared [Multisite extension example](https://github.com/eileenmcnaughton/org.civicrm.multisite/blob/master/multisite.php#L347).
2. Declare settings as in the same standard as CiviCRM Core [Multisite extension example](https://github.com/eileenmcnaughton/org.civicrm.multisite/blob/master/settings/Multisite.setting.php).
3. Create the admin page for the settings using xml similar to

```xml
  <item>
     <path>civicrm/admin/setting/myextension</path>
     <title>Really important settings</title>
     <page_callback>CRM_Admin_Form_Generic</page_callback>
  </item>
```

4. Add a menu item by implementing [hook_civicrm_navigationMenu](../hooks/hook_civicrm_navigationMenu.md).
5. Use `cv api system.flush` or `Admin → System Settings → Cleanup Caches` to flush CiviCRM caches and register your new settings metadata.

## Legacy method Adding Setting Config to Admin Forms.


```php
/**  
 * This class generates form components for Miscellaneous  
 *  
 */ 
class CRM_Admin_Form_Setting_Miscellaneous extends CRM_Admin_Form_Setting {
  protected $_settings = array(
   'max_attachments' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,  
   'contact_undelete' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,  
   'versionAlert' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,   
   'versionCheck' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,  
   'empoweredBy' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME, 
   'maxFileSize' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
   'doNotAttachPDFReceipt' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
   'secondDegRelPermissions' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
   'checksumTimeout' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
  );
}
```
