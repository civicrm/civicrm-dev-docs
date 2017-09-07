# Setting Reference

## What is a Setting?

Applications like CiviCRM often need to be 'configured' with small bits of persistent data, which may be different for each installation. Examples are:

 - The url of the CiviCRM site.
 - The path on the server where the CiviCRM code lives
 - MySQL connection information
 - The primary language of the install.

Sites can configure the settings through the UI or [put in overrides](https://wiki.civicrm.org/confluence/display/CRMDOC/Override+CiviCRM+Settings);

This page describes the CiviCRM standard tool for managing these configuration settings – the 'Settings' system. As a developer, you'll want to understand this system so you can access CiviCRM 'core' settings (e.g. if you're sending out a system email, you'll want to set an appropriate From name and address). You may also want to use this system for storing and retrieving your own settings for your extension. If you're a Drupal developer, this system is analogous to the Drupal variables table and tools.

Not all configuration-type values need to use this system - in particular, if you need to store persistent data that changes frequently and/or may grow indefinitely in size, this may not be the right tool. For example: if you want to have a custom set of allowable values for your extension, you'll want to use the 'Option Group' system. If you want to temporarily cache a value for the duration of a session, then the Cache system is the right tool. And if you're saving persistent data that may grow indefinitely over time, you'll want to look into creating and managing your own tables.

## Background

In early versions of CiviCRM, all settings were stored either in the `CRM_Core_Config` object or else globally in civicrm.settings.php. v4.x introduced a more open "Settings" system. This documentation covers the new way of managing settings.

The "Settings" system developed incrementally:

- v4.1 – Added `civicrm_setting` table, `$civicrm_settings` global, and `CRM_Core_BAO_Setting` class. Migrated some settings data from `civicrm_domain` to `civicrm_setting`.
- v4.2 – Added public Settings API. Included support for CRUD'ing data from multiple sources (`civicrm_domain` and `civicrm_setting`). Migrated more settings data from `civicrm_domain` to `civicrm_setting`.
- v4.3 to v4.6 – Incrementally migrate more settings data.
- v4.7 – Added `Civi::settings()` and refactored init system. Finished migrating data from `civicrm_domain` to `civicrm_setting`.

## Settings Definition

Settings are defined in the /settings directory, in files ending with `.settings.php`

Each file consists of a php snippet which returns an array. Array keys are stings corresponding with each setting's name. Values are an array of metadata properties for that setting. Note that for radio buttons or similar the options will be retrieved if there is an option group of the same name. An example array is as follows:

```php
  'remote_profile_submissions' => array(
    'group_name' => 'CiviCRM Preferences',
    'group' => 'core',
    'name' => 'remote_profile_submissions',
    'type' => 'Boolean',
    'quick_form_type' => 'YesNo',
    'default' => FALSE,
    'html_type' => 'radio',
    'add' => '4.7',
    'title' => 'Accept profile submissions from external sites',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'If enabled, CiviCRM will permit submissions from external sites to profiles. This is disabled by default to limit abuse.',
    'help_text' => NULL,
  ),
```

The Supported Properties for settings are:

| property | Usage | Example Notes |
| --- | --- | --- |
| group | ? | Appears to correspond with the name of the file, doesn't that make it redundant? |
| `group_name` | Name of group this setting belongs to.  These are defined as constants in the class `CRM_Core_BAO_Setting` |  Uses a string & not a constant as it might be defined outside of core too (& the constants are particularly ugly for these) |
| name | Name of this setting| This is the same as the array key. Definitely redundant! |
| type | Data type| String, Array, Integer, Boolean |
| default | Default value| Value to use if setting is not set. |
| add | Version added to CiviCRM| |
| `quick_form_type` | Widget type (admin form)| e.g YesNo, Element  |
| `html_type` | Html type (admin form)| Used when `quick_form_type` is "Element". |
| `html_attributes` |  | size, style, class, etc. |
| title | Title (admin form)| note: do not use ts() |
| description | Description (admin form)| note: do not use ts()  |
| `help_text` | Popup Help (admin form)| note: do not use ts() |
| `validate_callback` | Function to call for checking when submitted (admin form)| e.g `CRM_Utils_Rule::url` |
| `on_change` | Callback function when this setting is altered e.g when you enable a component or logging| |
| `is_domain` | Domain setting| see `civicrm_setting` table |
| `is_contact` | Contact setting| see `civicrm_setting` table |
| prefetch | Legacy support - will store the setting in the $config object| We need to migrate away from this |

Deprecated settings properties are :

| property | Usage | Example Notes |
| --- | --- | --- |
|`config_only` | Super legacy support - only store in the `$config` object (Removed/unnecessary in v4.7+) | And this |
|`config_key` | If the config key differs from the settings key (rarely used) (Removed/unnecessary in v4.7+) | used in conversions & for 'debug' where the config key name can't be used in the api |
| `legacy_key` | Used for conversions when upgrading and moving data from existing config array to being a setting (Removed/unnecessary in v4.7+) | This happens on upgrade and when `civicrm_api3('system','flush')` is called |

## Setting Storage and Retrieval

To set a setting, developers can use either the Setting.Create API or use `Civi::settings->set()` to set the value e.g. `Civi::settings->set('editor_id', 'CKEditor');`. To Retrieve a setting value, developers can use the Setting.Get API or use `Civi::settings->get()` e.g. `Civi::settings()->get('max_attachments');`

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

## Multiple Domains

The settings api supports the following values for `domain_id` :

- `current_domain` (default)
- integer
- array of integers
- all

It is desirable to make this api handling of domain id part of the api layer for all api that involve domains

## Adding a new Setting to CiviCRM Core.

1. Choose a group of settings to add the new setting to
2. Add metadata to the appropriate `.settings.php` file about the new setting
3. Choose an admin setting page to add the configuration option to.
4. Add the name and group to the `$_settings` array at the top of the file. The rest happens automatically.
5. If the template for that form is not set up to handle new items automatically, add the necessary markup.
6. Your setting will not be active until you run `bin/regen.sh` - run it and commit the changes to git.

!!! warning
    Do not use the "URL Preferences" group to store any setting that is not a url string.

## Converting a config object ot a setting

1. remove `config_only` from the settings - this is simply a case of removing the flag - e.g. [Core Example](https://github.com/eileenmcnaughton/civicrm-core/commit/a5617bcc7dc59065dcf5309a9c62aafe25d2ec77) The upgrade process will do the conversion when it next runs. Probably calling `civicrm_api3('system', 'flush', ())` or rebuilding menus will too. You might need to ensure the civicrm_cache table is truncated first.
2.  fix the admin form to still set the setting -  you need to declare the settings on the form
    - the parent class should add them appropriately e.g [Core Example](https://github.com/eileenmcnaughton/civicrm-core/commit/8f0551201883036cac63d720149953c007d4f10d)
    - you may need to do other fixups - e.g fix the metadata on the setting e.g. [Core Example](https://github.com/eileenmcnaughton/civicrm-core/commit/fd0e547bffc97e7acfc90e280cfe6fceb00d3a29)
3.  As long as the 'prefetch' key is set you don't need to alter how the setting is accessed or used as it will still be cached in the `$config` object. If it's not really appropriate for the setting to be cached into config & it really is legacy you can grep for it & change how it is retrieved to use the api or setting retrieval functions above. Note that if you use getvalue api if will work on all stages on the conversion - ie. getvalue will retrieve 'config_only' settings as well as those that are 'prefetch' or simply settings. Using getvalue can be a migration strategy. If you do this you should also remove the property declaration from the config object.
4. If you think a setting should be removed from config but are not going to take the time to do it at this stage think about adding a phpdoc comment to the relevant comment block in the config object to that effect.

## Creating a new setting in an Extension

As with core settings, all settings declared in extensions should have appropriate metadata attached to them. 

1. Provide that the settings folder is declared [Multisite extension example](https://github.com/eileenmcnaughton/org.civicrm.multisite/blob/master/multisite.php#L268).
2. Declare settings as in the same standard as CiviCRM Core [Multisite extension example](https://github.com/eileenmcnaughton/org.civicrm.multisite/blob/master/settings/Multisite.setting.php).
3. Create Settings form, a [good example of generic metadata based setings form in an extension](https://github.com/eileenmcnaughton/nz.co.fuzion.civixero/blob/master/CRM/Civixero/Form/XeroSettings.php) - note that only the setting filter is non-generic
4. Use `cv api system.flush` or `Admin → System Settings → Cleanup Caches` to flush CiviCRM caches and register your new settings metadata.

## Adding Setting Config to Admin Forms.

We are working towards it being sufficient to simply declare the settings on the relevant form & have the rest be done by the metadata - e.g if adding your setting like this doesn't work then try diagnosing & fixing so it wil in future

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

