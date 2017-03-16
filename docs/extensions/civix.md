# Civix

## Introduction

The [`civix`](https://github.com/totten/civix/) command-line tool is the
community-endorsed method for building your CiviCRM extensions.

Follow the installation instructions in the
[GitHub repository](https://github.com/totten/civix/).

After fulfilling the [Pre-Requisites](/extensions/basics/#pre-requisites), you can verify that all your configuration is correct using ping CiviCRM from
within your extensions directory with:

```bash
civix civicrm:ping
```

This command should reply with "Ping successful".
If the ping is unsuccessful, re-read the civix README.md and do the
post-installation configuration.

It is also useful to examine the output of the `civix help` command to read
about what `civix` can do for you.

Help is available on individual commands, e.g.:

```bash
civix help civicrm:ping
```

## Generating a skeletal extension

To generate a skeletal extension module, we will use `civix generate:module`
and pass in the name for our extension. See [here](/extensions/basics/#extension-names)
for details of naming conventions.

Start with:

```bash
cd $YOUR_EXTENSIONS_DIR
civix generate:module --help
```

Then use a command like this:

```bash
civix generate:module com.example.myextension --license=AGPL-3.0
```

This command will report that it has created three files, following the
[standard extension structure](/extensions/files).

The command attempts to auto-detect authorship information (your name and
email address) by reading your
[`git`](https://git-scm.com/book/en/v2/Getting-Started-First-Time-Git-Setup)
configuration. If this fails or is
otherwise incorrect, then you may pass explicit values with `--author`
and `--email`.

You can now update your `info.xml`. This file initially contains some
examples and placeholders which you need to fix. You can edit most of these
fields intuitively.  If you need detailed specifications, see [Extension
Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/Extension+Reference).

Now that you have created your extension, you need to activate it by navigating
to:

**Administer » System Settings » Manage Extensions**

or

**Administer » Customize Data and Screens » Manage Extensions.**

For more detailed instructions, see
[Extensions](http://wiki.civicrm.org/confluence/display/CRMDOC/Extensions).

## Add features

There are many different features that you can add to a module-extension
at your discretion. A few possibilities:

### Add a basic web page

CiviCRM uses a typical web-MVC architecture. To implement a basic web
page, you must create a PHP controller class, create a Smarty template
file, and create a routing rule. You can create the appropriate files by
calling `civix generate:page`

Once again you can review the output of this command to see the available options:

```bash
civix generate:page --help
```

Generally you will only need to supply the PHP class name and web-path,
for example:

```bash
civix generate:page MyPage civicrm/my-page
```

This creates three files:

-   `xml/Menu/myextension.xml` defines request-routing rules and
    associates the controller ("CRM_Myextension_Page_Greeter") with
    the web path `civicrm/my-page`.
-   `CRM/Myextension/Page/MyPage.php` is the controller which
    coordinates any parsing, validation, business-logic, or database
    operations.
-   `templates/CRM/Myextension/Page/MyPage.tpl` is loaded
    automatically after the controller executes. It defines the markup
    that is eventually displayed. For more information on the syntax of
    this file, see
    [the smarty guide](http://www.smarty.net/docsv2/en/).

The auto-generated code for the controller and view demonstrate a few
basic operations, such as passing data from the controller to the view.

!!! note
    After adding or modifying a route in the XML file, you must reset
    CiviCRMs "menu cache". Do this in a web browser by visiting
    `/civicrm/menu/rebuild?reset=1` or by running
    `drush cc civicrm` if using Drupal & Drush.

**Edit In Place**

If the data on the page is read and updated through the API, then you
may want to consider using the
[in-place editing](https://wiki.civicrm.org/confluence/display/CRMDOC/In-Place+Field+Editing) API.

### Add a basic web form

!!! caution
    The form system is not well documented and may undergo significant
    revision after the CiviCRM 4.x series. In general, migrating basic pages
    will be easier than migrating basic forms, so you may want
    to consider building your data-input interface  using basic pages, the AJAX
    API, and the
    [in-place editing](https://wiki.civicrm.org/confluence/display/CRMDOC/In-Place+Field+Editing) API.

CiviCRM uses a typical web-MVC architecture. To implement a basic web
form, you must create a PHP controller class, create a Smarty template
file, and create a routing rule. You can create the appropriate files by
calling `civix generate:form`.

The form generation command has similar arguments to `civix generate:page`,
requiring a class name and a web route:

```bash
civix generate:form FavoriteColor civicrm/favcolor
```

This creates three files:

-   `xml/Menu/myextension.xml` defines request-routing rules and
    associates the controller `CRM_Myextension_Form_FavoriteColor`
    with the web path `civicrm/favcolor`.
-   `CRM/Myextension/Form/FavoriteColor.php` is the controller which
    coordinates any parsing, validation, business-logic, or database
    operations. For more details on how this class works, see [QuickForm
    Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference).
-   `templates/CRM/Myextension/Form/FavoriteColor.tpl` is loaded
    automatically after the controller executes. It defines the markup
    that is eventually displayed. For more information on the syntax of
    this file, see
    [the smarty guide](http://www.smarty.net/docsv2/en/).

The auto-generated code for the controller and view demonstrate a few
basic operations, such as adding a `<select>` element to the form.

!!! note
    After adding or modifying a route in the XML file, you must reset
    CiviCRMs "menu cache". This can be done in a web browser by visiting
    `/civicrm/menu/rebuild?reset=1`

### Add a database upgrader, installer and uninstaller

If your module requires creating or maintaining SQL tables, then you
should create a class for managing database upgrades. The upgrader adds
a class for managing installs and upgrades but you then need to edit the file
comment out the various upgrade and uninstall functions
to make it work. Generally your install script belongs in an `sql/` folder
in the root of your extension with a name like 'install'

This `civix` command does not require arguments:

```bash
civix generate:upgrader
```

This creates two files and one directory:

-   `CRM/Myextension/Upgrader.php` stores a series of upgrade
    functions based on a function naming pattern similar to
    Drupal's
    [hook_update_N](http://api.drupal.org/api/drupal/modules%21system%21system.api.php/function/hook_update_N/7).
    After examining the file's comments for example upgrade functions
    you can then write your own.
-   `CRM/Myextension/Upgrader/Base.php` contains helper functions
    and adapters which make it easier to write the upgrader. This file
    may be overwritten from time-to-time to provide new helpers or
    adapters.
-   sql

After reviewing the examples and creating your own upgrade functions,
you can execute the upgrades through the web interface by visiting the
"Manage Extensions" screen. This screen will display an alert with an
action-link to perform the upgrades.

!!! note
    The "upgrader" class is a wrapper for
    [hook_civicrm_upgrade](https://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference)
    which aims to be easy-to-use for developers with Drupal experience. If
    you need to organize the upgrade logic differently, then consider
    providing your own implementation of hook_civicrm_upgrade.

!!! caution
    Only use the upgrade system to manage new SQL
    tables. Do not manipulate core schema.

If you need to create triggers on core SQL tables, use
[hook_civicrm_triggerInfo](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference).
This allows your triggers to coexist with triggers from other modules.

### Add a case type
*(from CiviCRM v4.4+)*

If you want to develop a custom case-type for CiviCase, then you can
generate a skeletal CiviCase XML file.

Once again `civix` will give the details of options and arguments with this
command:

```bash
civix help generate:case-type
```

This reports that civix expects a label argument and an optional name:

```bash
civix generate:case-type "Volunteer Training" Training
```

This creates two files:

-   `xml/case/Training.xml` defines the roles, activity types, and
    timelines associated with the new case type. For more in depth
    discussion of CiviCase XML, see
    [CiviCase Configuration](https://wiki.civicrm.org/confluence/display/CRMDOC/CiviCase+Configuration).
-   `alltypes.civix.php`(which may already exist) defines
    implementations of various hooks (notably hook_civicrm_caseTypes).

### Add custom fields
*(from CiviCRM v4.4+)*

Your extension can create one or more sets of custom
fields at installation. There are two
methods depending on whether the custom data set extends an entity
(e.g. "Individual" – without any specific subtype)
or extends a specific subtype of an entity.
(e.g. Activities of type 'Volunteer')

#### Extending a base entity

This is the simplest scenario.
Start by creating a custom fields using the web interface and then export
them for use with the extension:

!!! note
    Before you begin verify that civix is connected to your instance of
    CiviCRM by running `civix civicrm:ping`.

-   On your development instance of CiviCRM, create the new custom
    fields using the web interface. Note the unique ID of the custom data
    group (also known as the "Custom Fieldset", "CustomGroup" or
    "civicrm_custom_group")
-   Create an XML file with `civix generate:custom-xml`
    specifying the custom-data group ID.
-   Create an upgrader file with `civix generate:upgrader` to
    load the XML file during installation.

Check the full range of options with the `civix` help command:

```bash
civix help generate:custom-xml
```

So to created your XML for a custom data group ID of 7:

```bash
civix generate:custom-xml --data=7
```

Most of the [CiviHR](https://github.com/civicrm/civihr/tree/master) modules rely on the first approach (e.g.:
[Upgrader/Base.php](https://github.com/civicrm/civihr/blob/master/hrqual/CRM/HRQual/Upgrader/Base.php#L244) and
[auto_install.xml](https://github.com/civicrm/civihr/blob/master/hrqual/xml/auto_install.xml)).

#### Extending an subtype

<!-- This section still is not really clear to me. (Erich Schulz)
Is this really about sub-types in an OO sense?? or subtypes in as a base
entity of a particular type as defined by its properties?
-->

The automatic export does not work too well when the
custom-data group extends a specific subtype. The
"HR Emergency Contact" extension provides and example that creates
a custom-data group that describes
Relationships with type "Emergency Contact". Internally, Civi uses
"relationship type IDs", but those are not portable. As a quick
work-around, this extension uses Smarty:
([HREmerg/Upgrader.php](https://github.com/civicrm/civihr/blob/master/hremerg/CRM/HREmerg/Upgrader.php#L14)
and
[hremerg-customdata.xml.tpl](https://github.com/civicrm/civihr/blob/master/hremerg/templates/hremerg-customdata.xml.tpl#L11)).

To create this extension, the author used `civix generate:custom-data` and
then:

1. Renamed the `xml/auto_install.xml` to
 `templates/hremerg-customdata.xml.tpl`
2. Changed the value of `<extends_entity_column_value>` in the
  `.tpl file` using  a variable instead of a hard-coded type ID.
3. Added logic in the upgrader to create the relationship type
4. Added logic in the upgrader to evaluate the Smarty template

### Add a hook function

CiviCRM
[hook functions](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference)
allow extensions to run extra logic as
part of the normal CiviCRM processing. For example,
`hook_civicrm_buildForm()` allows a module to run logic whenever a
web-form is displayed, and `hook_civicrm_post()` allows a module to
run logic after any entity is saved.

Hook function names follow the Drupal convention of being
the module's short-name concatenated to the hook name. This strict but simple
naming convention is what allows the CiviCRM core to locate your hook
functions and call them at the appropriate times.
For example, if our module's main file is
`myextension.php` and we want to use `hook_civicrm_post()`
to write to a log file every time a contribution is saved, then our function must be called `myextension_civicrm_post()`:

To implement a hook, add a function to the module's main `.php`
file created earlier with `civix generate:module`:

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
    When you first created the skeletal project, several hook functions were
    auto-generated in `myextension.php`. These functions are usually about
    one line long – they simply delegate the work to another function. For
    example `myextension_civicrm_config()` delegates work to
    `_myextension_civix_civicrm_config()`. You should feel free to add
    more code to `myextension_civicrm_config()`, but you should preserve
    the call to `_myextension_civix_civicrm_config()`. <!-- fixme! why?? -->

### Add a resource file

To include static resources such as stylesheets, Javascript files, or
images place them in your extension directory. To load
the files at runtime, see the examples in the [Resource
Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/Resource+Reference).

### Add a report

CiviReport enables developers to define new business reports using
customizable SQL logic and form layouts.
If another existing report is close to your needs you may copy and modify it.

To to see the available report generation options activate the `civix` help:

```bash
civix generate:report --help
```

To create a new report specify the report PHP class name and the CiviCRM component, for example:

```bash
civix generate:report MyReport CiviContribute
```

This creates three files:

-   `CRM/Myextension/Form/Report/MyReport.mgd.php` stores metadata
    about the report in a format based on
    [hook_civicrm_managed](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference)
    and the
    [API](http://wiki.civicrm.org/confluence/display/CRMDOC/API+Reference).
-   `CRM/Myextension/Form/Report/MyReport.php` contains the
    form-builder and query-builder for the report. For details about its
    structure, see the [CiviReport
    Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/CiviReport+Reference).
-   `templates/CRM/Myextension/Form/Report/MyReport.tpl` contains
    the report's HTML template. This template usually delegates
    responsibility to a core template and does not need to be edited.

If one of the existing reports is close to meeting
your needs, but requires further PHP or SQL customization, you may
simply make a new report based on that report.
To copy a report, find the class-name of the original report within the
`civicrm/CRM/Report/Form/` directory in the CiviCRM repository.
Then run the `civix generate:report` command using the copy option
from with your extension directory.

For example, this command will copy the activity report in the class
`CRM_Report_Form_Activity` to a new report within your extension:

```bash
civix generate:report --copy CRM_Report_Form_Activity MyActivity Contact
```

### Add a custom search

CiviCRM enables developers to define new search forms using customizable
SQL logic and form layouts. Use this command to get started:

```bash
civix help generate:search
```

Then you could generate your basic search code for a MySearch class with:

```bash
civix generate:search MySearch
```

This command will create two files:

-   `CRM/Myextension/Form/Search/MySearch.mgd.php` stores metadata
    about the custom search. The format of the file is based on
    [hook_civicrm_managed](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference)
    and the
    [API](http://wiki.civicrm.org/confluence/display/CRMDOC/API+Reference).
-   `CRM/Myextension/Form/Search/MySearch.php` contains the
    form-builder and query-builder for the custom search.


If one of the existing searches is close to
meeting your needs you may copy it instead and then customise the
PHP, SQL and templates.

To make a new search based on an existing search first determine the name of
the original search class within the `civicrm/CRM/Contact/Form/Search/Custom`
directory of CiviCRM source tree. Then run the `generate:search` command from
within your module directory.

For example, the zipcode search is in the class
`CRM_Contact_Form_Search_Custom_ZipCodeRange`, so you can copy it with:

```bash
civix generate:search --copy CRM_Contact_Form_Search_Custom_ZipCodeRange MySearch
```

The "copy" option will create either two or three files depending
on whether the original search screen defines its own Smarty template.

### Add an API function

The [CiviCRM API](/api/general)
provides a way to expose functions for use by other developers. API
functions allow implementing AJAX interfaces (using the
cj().crmAPI() helper), and they can also be called via REST, PHP,
Smarty, Drush CLI, and more. Each API requires a two-part name: an
entity name (such as "Contact", "Event", or "MyEntity") and an action
name (such as "Create" or "MyAction").

Get started by accessing the `civix` help:

```bash
civix help generate:api
```

!!! note
    Action names must be lowercase. The javascript helpers `CRM.api()` and
    `CRM.api3()` force actions to be lowercase. This issues does not present
    itself in the API Explorer or when the api action is called via PHP,
    REST, or SMARTY.

You can make your API code with a command in this pattern:

```bash
civix generate:api NewEntity NewAction
```

This creates one file:

-   `api/v3/NewEntity/NewAction.php` provides the API function.
    that the parameters and return values must be processed in a
    particular way (as demonstrated by the auto-generated file).

<!-- fixme - clarify is this 4.3 and later? -->
For use with CiviCRM 4.3, you can also add the `–schedule` option (e.g.
`–schedule Hourly`). This will create another file:

-   `api/v3/NewEntity/NewAction.mgd.php` provides the scheduling
    record that will appear in the CiviCRM's job-manager.

### Add a new entity

!!! caution
    This functionality is considered "experimental and incomplete".

You may have a need to create a new entity that does not exist in
CiviCRM. For this, you can use the command `civix generate:entity`

-   Pick a name for your entity. In some places, CiviCRM expects a
    *FirstLetterCapitalizedName*, in others, an *underscore_name*. Be
    absolutely consistent in your naming, because CiviCRM needs to
    translate between those two naming conventions.

-   Run `civix generate:entity <name of entity>` (entity name should be
    FirstLetterCapitalized here). This creates a skeletal file for your
    XML schema, your BAO, and your API. It does NOT create a skeletal
    SQL file to create your table or DAO files at this time.
-   Edit the XML schema in the `xml` folder to match the fields you
    want. Minimal documentation is available
    [here](https://wiki.civicrm.org/confluence/display/CRMDOC/Database+Reference),
    but you are better off looking at the [existing XML
    schemata](https://github.com/civicrm/civicrm-core/tree/master/xml/schema).
-   Create a DAO file. For now, civix does not handle this. You can
    create this by hand. Alternatively, use [this
    technique](http://civicrm.stackexchange.com/a/3536/12). Copy your
    XML schema into a development copy of CiviCRM. Edit Schema.xml to
    include your XML file, then from the xml folder, run
    `php ./GenCode.php` (In CiviCRM 4.7.12+, run
    `<civiroot>/bin/setup.sh -g` instead). This will generate a DAO file
    for you in the CiviCRM core code; copy it into the
    `CRM/<Entityname>/DAO` folder of your extension.
-   Currently, `civix` does not generate the SQL to create and
    drop your table(s). You can create these by hand, or, if
    you used the `<civiroot>/bin/setup.sh -g` technique to create your
    DAO, SQL will have been generated for you in
    `<civiroot>/sql/civicrm.mysql`. Once you have the SQL statements for
    creating and dropping your SQL tables, create
    `auto_install.sql` and `auto_uninstall.sql`
    respectively in your 'sql/' folder. CiviCRM will run them
    automatically on install if you generated an upgrader. Note that
    using `auto_install.sql` and `auto_uninstall.sql`
    is not best practice if you have multiple statements in each file,
    since you cannot error check each statement separately.
    <!-- fixme update and clarify -->
-   Run `civix generate:upgrader` from within your extension.
-   Define your entity using
    [hook_civicrm_entityTypes](https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes).

### Add a unit-test class

Unit-testing is essential to maintain quality-control over
your extension. When developing a test case for a CiviCRM extension, it
is useful to run the test case within an active, clean CiviCRM
environment. The combined CiviCRM `civix` testing tools will automate this as
long as you follow a few basic conventions.

The following steps will create and run a test in your extension.

!!! note
    Before preparing unit-tests with extensions, you must first
    [configure you personal testing sandbox](https://wiki.civicrm.org/confluence/display/CRM/Setting+up+your+personal+testing+sandbox+HOWTO) and enable your extension on the sandbox.

Explore the full options with:

```bash
civix help generate:test
```

To create a skeletal test-class choose a class name in
your extension's namespace (*CRM_Myextension*) that ends with the
word *Test*:

```bash
civix generate:test CRM_Myextension_MyTest
```

This creates a new directory and a new PHP file:

-   `tests/phpunit` is the base directory for all test classes.
-   `tests/phpunit/CRM/Myextension/MyTest.php` is the actual test
    class. It should be written according to the conventions of
    [PHPUnit](http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html).

To make sure you can run the test civix needs to know where the CiviCRM
base install is:

The skeletal test class does not do anything useful. For more details on
how to write a test class:

-   Read [PHP Unit Manual: Writing Tests for
    PHPUnit.](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html)
-   Review the example code in
    [org.civicrm.exampletests](https://github.com/totten/org.civicrm.exampletests)

!!! caution
    The following instructions are deprecated. Updated instructions coming soon.

```bash
# Check if civix can connect to the civi api
civix civicrm:ping
# Look at your current config
civix config:get
# Tell civix where to read the civicrm.settings.php
# EG. For Drupal
civix config:set civicrm_api3_conf_path /your/path/to/sites/default
```

To run this test-class, change to your extension folder and run the following:

```bash
civix test CRM_Myextension_MyTest
```

