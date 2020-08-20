# Civix

## Introduction

The [`civix`](https://github.com/totten/civix/) command-line tool is the community-endorsed method for building your CiviCRM extensions.

Follow the installation instructions in the [GitHub repository](https://github.com/totten/civix/).

After fulfilling the [Pre-Requisites](index.md#pre-requisites), you can verify that all your configuration is correct by running the following command from within your extensions directory with:

```bash
civix civicrm:ping
```

This command should reply with "Ping successful". If the ping is unsuccessful, re-read the civix README.md and do the post-installation configuration.

It is also useful to examine the output of the `civix help` command to read about what `civix` can do for you.

Help is available on individual commands, e.g.,:

```bash
civix help civicrm:ping
```

## Generate a skeletal extension {:#generate-module}

To generate a skeletal extension module, we will use `civix generate:module` and pass in the name for our extension. See [here](index.md#extension-names) for details of naming conventions.

Start with:

```bash
cd $YOUR_EXTENSIONS_DIR
civix generate:module --help
```

Then use a command like this:

```bash
civix generate:module com.example.myextension --license=AGPL-3.0
```

This command will report that it has created three files, following the [standard extension structure](structure.md).

The command attempts to auto-detect authorship information (your name and email address) by reading your [`git`](https://git-scm.com/book/en/v2/Getting-Started-First-Time-Git-Setup) configuration. If this fails or is otherwise incorrect, then you may pass explicit values with `--author` and `--email`.

You can now update your `info.xml`. This file initially contains some examples and placeholders which you need to fix. You can edit most of these fields intuitively.  If you need detailed specifications, see [Extension Reference](index.md).

Now that you have created your extension, you need to activate it by navigating to:

**Administer » System Settings » Extensions**

For more detailed instructions, see [Extensions](index.md).

## Add features

There are many different features that you can add to a module-extension at your discretion. A few possibilities:

### Add a basic web page {:#generate-page}

CiviCRM uses a typical web-MVC architecture. To implement a basic web page, you must create a PHP controller class, create a Smarty template file, and create a routing rule. You can create the appropriate files by calling `civix generate:page`.

Once again you can review the output of this command to see the available options:

```bash
civix generate:page --help
```

Generally you will only need to supply the PHP class name and web-path, for example:

```bash
civix generate:page MyPage civicrm/my-page
```

This creates three files:

-   `xml/Menu/myextension.xml` defines request-routing rules and associates the controller ("CRM_Myextension_Page_Greeter") with the web path `civicrm/my-page`.
-   `CRM/Myextension/Page/MyPage.php` is the controller which coordinates any parsing, validation, business-logic, or database operations.
-   `templates/CRM/Myextension/Page/MyPage.tpl` is loaded automatically after the controller executes. It defines the markup that is eventually displayed. For more information on the syntax of this file, see [the smarty guide](http://www.smarty.net/docs/en/).

The auto-generated code for the controller and view demonstrate a few basic operations, such as passing data from the controller to the view.

!!! note
    After adding or modifying a route in the XML file, you must reset CiviCRMs "menu cache". Do this in a web browser by visiting `/civicrm/menu/rebuild?reset=1` or, if using Drupal & Drush, by running `drush cc civicrm`.

**Edit In Place**

If the data on the page is read and updated through the API, then you may want to consider using the [in-place editing](../framework/ui.md#in-place-field-editing) API.

### Add a basic web form {:#generate-form}

!!! caution
    The form system is not well documented and may undergo significant revision after the CiviCRM 4.x series. In general, migrating basic pages will be easier than migrating basic forms, so you may want to consider building your data-input interface using basic pages, the AJAX API, and the [in-place editing](../framework/ui.md#in-place-field-editing) API.

CiviCRM uses a typical web-MVC architecture. To implement a basic web form, you must create a PHP controller class, create a Smarty template file, and create a routing rule. You can create the appropriate files by calling `civix generate:form`.

The form generation command has similar arguments to `civix generate:page`, requiring a class name and a web route:

```bash
civix generate:form FavoriteColor civicrm/favcolor
```

This creates three files:

-   `xml/Menu/myextension.xml` defines request-routing rules and associates the controller `CRM_Myextension_Form_FavoriteColor`
    with the web path `civicrm/favcolor`.
-   `CRM/Myextension/Form/FavoriteColor.php` is the controller which coordinates any parsing, validation, business-logic, or database operations. For more details on how this class works, see [QuickForm Reference](../framework/quickform/index.md).
-   `templates/CRM/Myextension/Form/FavoriteColor.tpl` is loaded automatically after the controller executes. It defines the markup that is eventually displayed. For more information on the syntax of this file, see [the smarty guide](http://www.smarty.net/docs/en/).

The auto-generated code for the controller and view demonstrate a few basic operations, such as adding a `<select>` element to the form.

!!! note
    After adding or modifying a route in the XML file, you must reset CiviCRMs "menu cache". This can be done in a web browser by visiting `/civicrm/menu/rebuild?reset=1`.

### Add a new entity {:#generate-entity}

If you want your extension to store data in the database, then you will need to create a new entity.

1. Pick a name for your entity.

    * In some places, CiviCRM expects a `CamelCaseName`, in others, an `snake_case_name`. Be absolutely consistent in your naming, because CiviCRM needs to translate between those two naming conventions.

    * Also consider that all entity names (including yours) should be unique across all core entities as well as all extension entities (for all installed extensions). Thus in many cases it's best to prefix your entity name with the short name of your extension.

    * For the remainder of this tutorial, we will use `MyEntity` as the name of the entity.

1. Generate the skeletal files 

    ```bash
     civix generate:entity MyEntity
    ```

    Make sure to use CamelCase here.
    
    This creates a skeletal file for your XML schema, your BAO, and your API.

1. Edit the [XML schema definitions](../framework/database/schema-definition.md) that you just generated (in the `xml` folder). Define your desired fields.

1. Generate your [DAO](../framework/codebase.md#dao) and SQL files.


    ```bash
     civix generate:entity-boilerplate
    ```

    You can safely re-run this command after you make changes to your XML schema definition. But if your schema changes require database migrations for existing installations, then you'll need to write a migration manually in addition to re-generating your boilerplate.
    
1. Generate a database upgrader.

    ```bash
     civix generate:upgrader
    ```
    
    Even though you're not yet creating any upgrades for your extension, you need to do this step now so that CiviCRM will pick up `auto_install.sql` and `auto_uninstall.sql` later on.
    
1. Re-install your extension.

    ```bash
     cv ext:uninstall myextension
     cv ext:enable myextension
    ```
    
Now your entity should be ready to use. Try it out with `cv api MyEntity.create` and `cv api MyEntity.get`. Then [add some tests](#generate-test).

By default when you generate an entity you will be generating an APIv4 entity. To generate an APIv3 (or both) interface you need to specify `--api-version 3,4` or just `--api-version 3`

!!! note "Troubleshooting"

    If you've generated an entity within an extension that you created with `civix` v18.01.0 or earlier, then you'll need to add this hook to your `myextension.php` file (changing `myextension` to your extension's short name).

    ```php
    /**
     * Implements hook_civicrm_entityTypes().
     *
     * Declare entity types provided by this module.
     *
     * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
     */
    function myextension_civicrm_entityTypes(&$entityTypes) {
      _myextension_civix_civicrm_entityTypes($entityTypes);
    }
    ```
        
    (Starting from `civix` v18.02.0, this hook is automatically added when you generate a new extension.)

### Add a database upgrader, installer and uninstaller {:#generate-upgrader}

If your module requires creating or maintaining SQL tables, then you should create a class for managing database upgrades. The upgrader adds a class for managing installs and upgrades but you then need to edit the file comment out the various upgrade and uninstall functions to make it work. Generally your install script belongs in an `sql/` folder in the root of your extension with a name like 'install'.

This `civix` command does not require arguments:

```bash
civix generate:upgrader
```

This creates two files and one directory:

-   `CRM/Myextension/Upgrader.php` stores a series of upgrade functions based on a function naming pattern similar to
    Drupal's [hook_update_N](http://api.drupal.org/api/drupal/modules%21system%21system.api.php/function/hook_update_N/7).     After examining the file's comments for example upgrade functions you can then write your own.
-   `CRM/Myextension/Upgrader/Base.php` contains helper functions and adapters which make it easier to write the upgrader. This file may be overwritten from time-to-time to provide new helpers or adapters.
-   sql

After reviewing the examples and creating your own upgrade functions, you can execute the upgrades through the web interface by visiting the "Manage Extensions" screen. This screen will display an alert with an action-link to perform the upgrades.

!!! note
    The "upgrader" class is a wrapper for [hook_civicrm_upgrade](../hooks/hook_civicrm_upgrade.md) which aims to be easy-to-use for developers with Drupal experience. If you need to organize the upgrade logic differently, then consider providing your own implementation of hook_civicrm_upgrade.

!!! caution
    Only use the upgrade system to manage new SQL tables. Do not manipulate core schema. 

If you need to create triggers on core SQL tables, use [hook_civicrm_triggerInfo](../hooks/hook_civicrm_triggerInfo.md). This allows your triggers to coexist with triggers from other modules.

### Add a case type {:#generate-case-type}

*(from CiviCRM v4.4+)*

If you want to develop a custom case-type for CiviCase, then you can generate a skeletal CiviCase XML file.

Once again `civix` will give the details of options and arguments with this command:

```bash
civix help generate:case-type
```

This reports that civix expects a label argument and an optional name:

```bash
civix generate:case-type "Volunteer Training" Training
```

This creates two files:

-   `xml/case/Training.xml` defines the roles, activity types, and timelines associated with the new case type. For more in depth discussion of CiviCase XML, see [CiviCase Configuration](https://wiki.civicrm.org/confluence/display/CRMDOC/CiviCase+Configuration).
-   `alltypes.civix.php`(which may already exist) defines implementations of various hooks (notably hook_civicrm_caseTypes).

### Add custom fields

*(from CiviCRM v4.4+)*

Your extension can create one or more sets of custom fields at installation. There are two methods depending on whether the custom data set extends an entity (e.g. "Individual" – without any specific subtype) or extends a specific subtype of an entity, (e.g. Activities of type 'Volunteer').

*{from CiviCRM v.5.0}*

If you wish to create custom fields for an entity that does not support custom fields out of the box you will need to add a new option value to the cg_group_extends option group. It will then be possible to create custom fields for this entity per above or via the UI. However, for most fields you will only be able to interact with these fields via the UI. Exceptions are those like RelationshipType that use an Entity Form in core.

#### Extending a base entity

This is the simplest scenario. Start by creating a custom fields using the web interface and then export them for use with the extension:

!!! note
    Before you begin verify that civix is connected to your instance of CiviCRM by running `civix civicrm:ping`.

-   On your development instance of CiviCRM, create the new custom fields using the web interface. Note the unique ID of the custom data group (also known as the "Custom Fieldset", "CustomGroup" or "civicrm_custom_group").
-   Create an XML file with `civix generate:custom-xml` specifying the custom-data group ID.
-   Create an upgrader file with `civix generate:upgrader` to load the XML file during installation.

Check the full range of options with the `civix` help command:

```bash
civix help generate:custom-xml
```

So to created your XML for a custom data group ID of 7:

```bash
civix generate:custom-xml --data=7
```

Most of the [CiviHR](https://github.com/civicrm/civihr/tree/master) modules rely on the first approach (e.g.: [Upgrader/Base.php](https://github.com/civicrm/civihr/blob/master/hrqual/CRM/HRQual/Upgrader/Base.php#L244) and [auto_install.xml](https://github.com/civicrm/civihr/blob/master/hrqual/xml/auto_install.xml)).

#### Extending a subtype

<!-- This section still is not really clear to me. (Erich Schulz) Is this really about sub-types in an OO sense?? or subtypes in as a base entity of a particular type as defined by its properties? -->

The automatic export does not work too well when the custom-data group extends a specific subtype. The "HR Emergency Contact" extension provides and example that creates a custom-data group that describes Relationships with type "Emergency Contact". Internally, Civi uses "relationship type IDs", but those are not portable. As a quick work-around, this extension uses Smarty: ([HREmerg/Upgrader.php](https://github.com/civicrm/civihr/blob/master/hremerg/CRM/HREmerg/Upgrader.php#L14) and [hremerg-customdata.xml.tpl](https://github.com/civicrm/civihr/blob/master/hremerg/templates/hremerg-customdata.xml.tpl#L11)).

To create this extension, the author used `civix generate:custom-xml` and then:

1. Renamed the `xml/auto_install.xml` to `templates/hremerg-customdata.xml.tpl`
1. Changed the value of `<extends_entity_column_value>` in the `.tpl file` using a variable instead of a hard-coded type ID.
1. Added logic in the upgrader to create the relationship type.
1. Added logic in the upgrader to evaluate the Smarty template.

#### Accessing Custom Fields

For setting and getting custom field values in hooks, you need to know the field ID of the custom field(s) you want to work with. You'll then access these fields as "custom_<ID>". So if you have a field holding a custom value whose ID in the civicrm_custom_field table is 34, you'll use "custom_34" to access it.

To get a custom field ID given the custom field name and custom group name, you can use the following code:

```php
require_once 'CRM/Core/BAO/CustomField.php';
$customFieldID = CRM_Core_BAO_CustomField::getCustomFieldID($field, $group);
```

Once you have the ID(s), you'll want to use the setValues and getValues functions in the CRM/Core/BAO/CustomValueTable.php file. Here are a couple of examples of their use:

Setting values:

```php
require_once 'CRM/Core/BAO/CustomValueTable.php';
$params = array('entityID' => $contribution_id, 'custom_34' => 'new val');
CRM_Core_BAO_CustomValueTable::setValues($params);
```

Getting values:

```php
$params = array( 'entityID' => 1327, 'custom_13' => 1, 'custom_43' => 1);
require_once 'CRM/Core/BAO/CustomValueTable.php';
$values = CRM_Core_BAO_CustomValueTable::getValues($params);
```

!!! caution
    Note that custom field values may not always be available when you might expect. For instance, you can't retrieve custom field values in the 'create' operation in the _pre and _post hooks, because the custom field values haven't been stored yet. However, you can retrieve values in the 'edit' operation.

### Add a hook function

CiviCRM [hook functions](../hooks/index.md) allow extensions to run extra logic as part of the normal CiviCRM processing. For example, `hook_civicrm_buildForm()` allows a module to run logic whenever a web-form is displayed, and `hook_civicrm_post()` allows a module to run logic after any entity is saved.

Hook function names follow the Drupal convention of being the module's short-name concatenated to the hook name. This strict but simple naming convention is what allows the CiviCRM core to locate your hook functions and call them at the appropriate times. For example, if our module's main file is `myextension.php` and we want to use `hook_civicrm_post()` to write to a log file every time a contribution is saved, then our function must be called `myextension_civicrm_post()`.

To implement a hook, add a function to the module's main `.php` file created earlier with `civix generate:module`:

```php
/**
 * Implementation of hook_civicrm_post
 */
function myextension_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  switch ($objectName) {
    case 'Contribution':
      $file = '/tmp/contributions.log';
      $message = strtr("Performed \"@op\" at @time on contribution #@id\n", array(
        '@op' => $op,
        '@time' => date('Y-m-d H:i:s'),
        '@id' => $objectId,
      ));
      file_put_contents($file, $message, FILE_APPEND);
      break;
    default:
      // nothing to do
  }
}
```

!!! note
    When you first created the skeletal project, several hook functions were auto-generated in `myextension.php`. These functions are usually about one line long – they simply delegate the work to another function. For example `myextension_civicrm_config()` delegates work to `_myextension_civix_civicrm_config()`. You should feel free to add more code to `myextension_civicrm_config()`, but you should preserve the call to `_myextension_civix_civicrm_config()`. <!-- fixme! why?? -->

### Add a resource file

To include static resources such as stylesheets, Javascript files, or images place them in your extension directory. To load the files at runtime, see the examples in the [Resource Reference](../framework/resources.md).

### Add a report {:#generate-report}

CiviReport enables developers to define new business reports using customizable SQL logic and form layouts. This command is available if you want to create a new report. It also provides an option, if another existing report is close to your needs, to easily copy and modify it.

In many cases you can take advantage of the [alterReportVar hook](../hooks/hook_civicrm_alterReportVar) to adjust the columns, sql, or event rows of an existing report to modify it to suit your needs instead of creating a new report. 

To see the available report generation options activate the `civix` help:

```bash
civix generate:report --help
```

To create a new report specify the report PHP class name and the CiviCRM component, for example:

```bash
civix generate:report MyReport CiviContribute
```

This creates three files:

-   `CRM/Myextension/Form/Report/MyReport.mgd.php` stores metadata about the report in a format based on [hook_civicrm_managed](../hooks/hook_civicrm_managed.md) and the [API](../api/index.md).
-   `CRM/Myextension/Form/Report/MyReport.php` contains the form-builder and query-builder for the report. For details about its
    structure, see the [CiviReport Reference](../framework/civireport.md).
-   `templates/CRM/Myextension/Form/Report/MyReport.tpl` contains the report's HTML template. This template usually delegates responsibility to a core template and does not need to be edited.

If one of the existing reports is close to meeting your needs, but requires further PHP or SQL customization, you may simply make a new report based on that report. To copy a report, find the class-name of the original report within the `civicrm/CRM/Report/Form/` directory in the CiviCRM repository. Then run the `civix generate:report` command using the copy option from within your extension directory.

For example, this command will copy the activity report in the class `CRM_Report_Form_Activity` to a new report within your extension: 

```bash
civix generate:report --copy CRM_Report_Form_Activity MyActivity Contact
```

!!! note
    Copying a report like this and modifying it is likely to lead to maintenance issues similar to those related to overriding core files in an extension. In particular, bug fixes or other changes to code that you have copied will not automatically be applied to the copied code your new report. Often a better approach is to extend the class of the core report in your extension, then selectively override its functions. In the functions that you override, if possible run the original code and then just tweak the behaviour afterwards, i.e: at the beginning of `thisFn()`, call `parent::thisFn()` then add your code. For example:
    
    ```php
    class CRM_myExtension_Form_Report_ExtendContributionDetails extends CRM_Report_Form_Contribute_Detail {
      public function from() {
        parent::from();
        // your code
      }
      ...
    }
    ```

!!! note
    To have your extension create an instance of your report template with configurations for columns, groups, filtering, etc. and then put it into the menu system, you need to make an additional entry in the `CRM/Myextension/Form/Report/MyReport.mgd.php` file. The additional entry needs some serialized data that is not easy to code but is easy to lookup for a configured report instance. So manually configure a report instance appropriately on a CiviCRM install running your extension (ie go to Administer > CiviReport > Create New Report from Template, then configure and save the instance). Then use APIv3 to get the ReportInstance values you need for your .mgd.php entry. Note that you need to specify the return fields as navigation_id is not returned by default. If you are not emailing the report, then the following should be sufficient, substituting the id for your report for 8 below:
    ```php
    $result = civicrm_api3('ReportInstance', 'get', [
      'sequential' => 1,
      'return' => ["title", "report_id", "name", "description", "permission", "grouprole", "is_active", "navigation_id", "is_reserved", "form_values"],
      'id' => 8,
    ]);
    ```
    Add an additional array entry into your .mgd.php that includes the elements of values array, adding a module entry, eg:
    
    ```
    array(
    'module' => 'com.example.myextension',
    'name' => 'My Enhanced Contribution Detail',
    ...
      'is_reserved' =>  0,
    ),
    ```
    
### Add a custom search {:#generate-search}

CiviCRM enables developers to define new search forms using customizable SQL logic and form layouts. Use this command to get started:

```bash
civix help generate:search
```

Then you could generate your basic search code for a `MySearch` class with: 

```bash
civix generate:search MySearch
```

This command will create two files:

-   `CRM/Myextension/Form/Search/MySearch.mgd.php` stores metadata about the custom search. The format of the file is based on
    [hook_civicrm_managed](../hooks/hook_civicrm_managed.md) and the [API](../api/index.md).
-   `CRM/Myextension/Form/Search/MySearch.php` contains the form-builder and query-builder for the custom search.

#### Copying an existing search

If one of the existing searches is close to meeting your needs you may copy it instead and then customise the
PHP, SQL and templates.

To make a new search based on an existing search first determine the name of the original search class within the `civicrm/CRM/Contact/Form/Search/Custom` directory of CiviCRM source tree. Then run the `generate:search` command from within your module directory.

For example, the zipcode search is in the class `CRM_Contact_Form_Search_Custom_ZipCodeRange`, so you can copy it with:

```bash
civix generate:search --copy CRM_Contact_Form_Search_Custom_ZipCodeRange MySearch
```

The "copy" option will create either two or three files depending on whether the original search screen defines its own Smarty template.

#### Using your search

1. Disable and re-enable your extension.
1. Go to **Search > Custom Searches...** and find your new custom search listed at the bottom.

#### Building your custom search

Now you have a working custom search, but how do you make it *do* something *custom*?

See this (somewhat outdated) [wiki page](https://wiki.civicrm.org/confluence/display/CRMDOC46/Create+a+Custom-Search+Extension) for more information.


### Add an API function {:#generate-api}

The [CiviCRM API](../api/index.md) provides a way to expose functions for use by other developers. API functions allow implementing AJAX interfaces (using the CRM.$().crmAPI() helper), and they can also be called via REST, PHP, Smarty, Drush CLI, and more. Each API requires a two-part name: an entity name (such as "Contact", "Event", or "MyEntity") and an action name (such as "create" or "myaction").

Get started by accessing the `civix` help:

```bash
civix help generate:api
```

!!! note
    Action names must be lowercase. The javascript helpers `CRM.api()` and `CRM.api3()` force actions to be lowercase. This issue does not present itself in the API Explorer or when the api action is called via PHP, REST, or SMARTY.

!!! note
    The API loader is very sensitive to case on some platforms.  Be careful with capitalization, underscores and multiword action names.  API calls can work in one context (Mac OSX filesystem, case-insensitive) and fail in another (Linux filesystem, case-sensitive).

You can make your API code with a command in this pattern:

```bash
civix generate:api NewEntity newaction
```

This creates one file:

-   `api/v3/NewEntity/Newaction.php` provides the API function `civicrm_api3_new_entity_newaction()` and the specification function `_civicrm_api3_new_entity_newaction_spec()`.  Note that the parameters and return values must be processed in a particular way (as demonstrated by the auto-generated file).

For use with CiviCRM 4.3 and later, you can also add the `--schedule` option (e.g., `--schedule Hourly`). This will create another file:

-   `api/v3/NewEntity/Newaction.mgd.php` provides the scheduling record that will appear in the CiviCRM's job-manager.

When calling the API, follow these rules:

-    Entity-names are UpperCamelCase
-    Action-names are lowersmashedcase
-    Other variations may work when you call them. Some docs/explorers may show these in cases which work. However, if you try to do the same in new code, you may get a headache.

For example: `cv api NewEntity.newaction`  `civicrm_api3('NewEntity', 'newaction')`
    
!!! tip
    Read more about [APIv4 architecture](../api/v4/architecture.md) for help writing custom APIv4 implementations.

### Add a unit-test class {:#generate-test}

Unit-testing is essential to maintain quality-control over your extension. When developing a test case for a CiviCRM extension, it is useful to run the test case within an active, clean CiviCRM environment. The combined CiviCRM `civix` testing tools will automate this as long as you follow a few basic conventions.

The following steps will create and run a test in your extension.

!!! note
    Before preparing unit-tests with extensions, you must first [configure you personal testing sandbox](https://wiki.civicrm.org/confluence/display/CRM/Setting+up+your+personal+testing+sandbox+HOWTO) and enable your extension on the sandbox. 

Explore the full options with:

```bash
civix help generate:test
```

To create a skeletal test-class choose a class name in your extension's namespace (*CRM_Myextension*) that ends with the word *Test*: 

```bash
civix generate:test CRM_Myextension_MyTest
```

This creates a new directory and a new PHP file:

-   `tests/phpunit` is the base directory for all test classes.
-   `tests/phpunit/CRM/Myextension/MyTest.php` is the actual test class. It should be written according to the conventions of [PHPUnit](http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html).

To make sure you can run the test civix needs to know where the CiviCRM base install is located.

The skeletal test class does not do anything useful. For more details on how to write a test class:

-   Read [PHP Unit Manual: Writing Tests for PHPUnit.](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html).
-   Review the example code in [org.civicrm.exampletests](https://github.com/totten/org.civicrm.exampletests).

To run the tests see the instructions [for running PHPUnit tests](/testing/phpunit.md#running-tests).

## Upgrade civix {:#upgrade}

What happens when a new version of `civix` comes out? Your extension should continue to work as-is -- after all, `civix` is just a code-generator.

However, if you generate an extension with one version of `civix` (e.g. v16.03), and if you upgrade (e.g. v18.03), and if you run a new generator,
then you *could* encounter problems. This is because generators and templates evolve over time -- in particular, new templates may rely on new
helpers and stubs in `<mymodule>.php` and `<mymodule>.civix.php`.

Fortunately, these changes are rare; they tend to have limited impact; and there's documentation for them. From time-to-time, you should check
[UPGRADE.md](https://github.com/totten/civix/blob/master/UPGRADE.md) for suggestions on updating your code to match the current templates.
