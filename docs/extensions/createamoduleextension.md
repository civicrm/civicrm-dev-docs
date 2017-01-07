# Create a Module Extension

![image](/confluence/images/icons/emoticons/information.png)

**Pre-Requisites**\

-   Have basic knowledge of PHP, Unix, and object-oriented programming
-   Install ***civix v14.01*** or newer. For instructions, see
    [https://github.com/totten/civix/](https://github.com/totten/civix/)
    . This wiki page assumes that "civix" is installed and registered in
    the PATH.
-   Configure an extensions directory. For instructions, see
    [Extensions](http://wiki.civicrm.org/confluence/display/CRMDOC/Extensions).
    This wiki page assumes the directory is "/var/www/extensions", but
    you should adapt as appropriate.\
     Your extensions directory must be under the CMS root directory so
    that civix can find and bootstrap the CMS. Otherwise, it will fail
    with an error like "Sorry, could not locate bootstrap.inc" on most
    operations.
-   The user account you use to develop the module must have permission
    to read all CMS files, including configuration files, and write to
    the extensions directory.\
     For example, Debian's drupal7 package saves database configuration
    to /etc/drupal/7/sites/default/dbconfig.php, which is only readable
    by the www-data user. You will need to make this file readable by
    your development user account for civix to work.

**Table of Contents**

-   [Generate a skeletal
    extension](#CreateaModuleExtension-Generateaskeletalextension)
-   [Update "info.xml"](#CreateaModuleExtension-Update"info.xml")
-   [Enable the extension](#CreateaModuleExtension-Enabletheextension)
-   [Add features](#CreateaModuleExtension-Addfeatures)

-   [Add a basic web page](#CreateaModuleExtension-Addabasicwebpage)
-   [Add a basic web form](#CreateaModuleExtension-Addabasicwebform)
-   [Add a database upgrader / Installer /
    uninstaller](#CreateaModuleExtension-Addadatabaseupgrader/Installer/uninstaller)
-   [Add a case type (CiviCRM
    v4.4+)](#CreateaModuleExtension-Addacasetype(CiviCRMv4.4+))
-   [Add custom fields (CiviCRM
    v4.4+)](#CreateaModuleExtension-Addcustomfields(CiviCRMv4.4+))
-   [Add a hook function](#CreateaModuleExtension-Addahookfunction)
-   [Add a resource file](#CreateaModuleExtension-Addaresourcefile)
-   [Add a report](#CreateaModuleExtension-Addareport)
-   [Add a custom search](#CreateaModuleExtension-Addacustomsearch)
-   [Add an API function](#CreateaModuleExtension-AddanAPIfunction)
-   [Add a new entity](#CreateaModuleExtension-Addanewentity)
-   [Add a unit-test class](#CreateaModuleExtension-Addaunit-testclass)

-   [Frequently asked
    questions](#CreateaModuleExtension-Frequentlyaskedquestions)

-   [How does one add an ajax/web-service
    callback?](#CreateaModuleExtension-Howdoesoneaddanajax/web-servicecallback?)
-   [How does one add a standalone PHP
    script?](#CreateaModuleExtension-HowdoesoneaddastandalonePHPscript?)
-   [How does one add a cron
    job?](#CreateaModuleExtension-Howdoesoneaddacronjob?)

-   [Troubleshooting](#CreateaModuleExtension-Troubleshooting)

# Generate a skeletal extension

To generate a skeletal extension module, we will use "civix
[generate:module](http://generatemodule)" and pass in the name for our
extension. All extension names follow the same convention as Java
package names – they look like reversed domain names. (e.g.
"com.example.myextension").

For module-extensions, the last word in the module name will be the
module's *short-name*. The short-name *must* be unique. It is possible
to pick a different short-name, but that requires extra work (which is
outside the scope of this document).

**Using "civix generate:module"**

This creates three files:

-   ***info.xml*** is a manifest that describes your extension – the
    name, license, version number, etc. You should edit most information
    in this file.
-   ***myextension.php*** stores source code for all your hooks. It
    includes a few default hook implementations which will make
    development easier. You can add and remove hooks as you wish. (Note:
    This file name is different in each module – it is based the
    module's *short-name*.)
-   ***myextension.civix.php*** contains auto-generated helper
    functions. These deal with common problems like registering your
    module in the template include-path. civix may automatically
    overwrite this file, so you generally should not edit it.

In addition, it creates some empty directories. These directories are
reminiscent of the directory structure in CiviCRM core:

-   ***CRM/Myextension/*** stores PHP class files; classes in this
    folder should be prefixed with "CRM\_Myextension\_"
-   ***templates/*** stores Smarty templates
-   ***xml/*** stores XML configuration files (such a URL routes)
-   ***build/*** stores exportable .zip files

The command attempts to autodetect authorship information (your name and
email address) by reading the git configuration. If this fails or is
otherwise incorrect, then you may pass explicit values with **--author**
and **--email**.

# Update "info.xml"

The default ***info.xml*** file contains some examples and placeholders
which should be fixed. Most of these fields can be edited intuitively.
If you need detailed specifications, see [Extension
Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/Extension+Reference).

# Enable the extension

Now that you've created your extension, you can activate by navigating
to "**Administer****»** **System Settings** **»** **Manage Extensions**"
or "**» Administer » Customize Data and Screens » Manage Extensions.**"
For more detailed instructions, see
[Extensions](http://wiki.civicrm.org/confluence/display/CRMDOC/Extensions).

# Add features

There are many different features that you can add to a module-extension
at your discretion. A few possibilities:

### Add a basic web page

CiviCRM uses a typical web-MVC architecture. To implement a basic web
page, one must create a PHP controller class, create a Smarty template
file, and create a routing rule. You can create the appropriate files by
calling "civix [generate:page](http://generatepage)"

**Using "civix generate:page"**

This creates three files:

-   ***xml/Menu/myextension.xml*** defines request-routing rules and
    associates the controller ("CRM\_Myextension\_Page\_Greeter") with
    the web path ("civicrm/greeter")
-   ***CRM/Myextension/Page/Greeter.php*** is the controller which
    coordinates any parsing, validation, business-logic, or database
    operations.
-   ***templates/CRM/Myextension/Page/Greeter.tpl*** is loaded
    automatically after the controller executes. It defines the markup
    that is eventually displayed. For more information on the syntax of
    this file, see
    [http://www.smarty.net/docsv2/en/](http://www.smarty.net/docsv2/en/)
    .

The auto-generated code for the controller and view demonstrate a few
basic operations, such as passing data from the controller to the view.

![image](/confluence/images/icons/emoticons/check.png)

After adding or modifying a route in the XML file, you must reset
CiviCRMs "menu cache". This can be done in a web browser by visiting
"/civicrm/menu/rebuild?reset=1" or by running
`drush                           cc civicrm` if using Drupal & Drush.

![image](/confluence/images/icons/emoticons/check.png)

**Edit In Place**\

If the data on the page is read and updated through the API, then you
may want to consider using the [in-place
editing](/confluence/display/CRMDOC/In-Place+Field+Editing) API.

### Add a basic web form

CiviCRM uses a typical web-MVC architecture. To implement a basic web
form, one must create a PHP controller class, create a Smarty template
file, and create a routing rule. You can create the appropriate files by
calling "civix [generate:form](http://generateform)"

**Using "civix generate:form"**

This creates three files:

-   ***xml/Menu/myextension.xml*** defines request-routing rules and
    associates the controller ("CRM\_Myextension\_Form\_FavoriteColor")
    with the web path ("civicrm/favcolor")
-   ***CRM/Myextension/Form/FavoriteColor.php*** is the controller which
    coordinates any parsing, validation, business-logic, or database
    operations. For more details on how this class works, see [QuickForm
    Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference).
-   ***templates/CRM/Myextension/Form/FavoriteColor.tpl*** is loaded
    automatically after the controller executes. It defines the markup
    that is eventually displayed. For more information on the syntax of
    this file, see
    [http://www.smarty.net/docsv2/en/](http://www.smarty.net/docsv2/en/)
    .

The auto-generated code for the controller and view demonstrate a few
basic operations, such as adding a <SELECT\> element to the form.

![image](/confluence/images/icons/emoticons/check.png)

After adding or modifying a route in the XML file, you must reset
CiviCRMs "menu cache". This can be done in a web browser by visiting
"/civicrm/menu/rebuild?reset=1"

![image](/confluence/images/icons/emoticons/forbidden.png)

The form system is not well documented and may undergo significant
revision after the CiviCRM 4.x series. In general, migrating basic pages
will be easier than migrating basic forms, so you may want to consider
to consider building your data-input UI using basic pages, the AJAX API,
and/or the [in-place
editing](/confluence/display/CRMDOC/In-Place+Field+Editing) API.

### Add a database upgrader / Installer / uninstaller

If your module requires creating or maintaining SQL tables, then you
should create a class for managing database upgrades. The upgrader adds
a class for managing installs and upgrades but you need to go in and
comment out the various upgrade and uninstall functions

to make it work. Generally your install script belongs in an sql folder
in the root of your extension with a name like 'install'

**Using "civix generate:upgrader"**

This creates two files and one directory:

-   ***CRM/Myextension/Upgrader.php*** stores a series of upgrade
    functions based on a function naming pattern. (These are similar to
    Drupal's
    [hook\_update\_N](http://api.drupal.org/api/drupal/modules%21system%21system.api.php/function/hook_update_N/7).)
    You should examine the file's comments for example upgrade functions
    – and then write your own.
-   ***CRM/Myextension/Upgrader/Base.php*** contains helper functions
    and adapters which make it easier to write the upgrader. This file
    may be overwritten from time-to-time to provide new helpers or
    adapters.
-   sql

After reviewing the examples and creating your own upgrade functions,
you can execute the upgrades through the web interface by visiting the
"Manage Extensions" screen. This screen will display an alert with an
action-link to perform the upgrades.

![image](/confluence/images/icons/emoticons/information.png)

The "upgrader" class is a wrapper for
[hook\_civicrm\_upgrade](/confluence/display/CRMDOC43/Hook+Reference)
which aims to be easy-to-use for developers with Drupal experience. If
you need to organize the upgrade logic differently, then consider
providing your own implementation of hook\_civicrm\_upgrade.

![image](/confluence/images/icons/emoticons/forbidden.png)

Only use the upgrade system to manage new SQL tables. Do not manipulate
core schema. (To discuss schema changes for the core system, go on IRC
or the forums.)

If you need to create triggers on core SQL tables, use
[hook\_civicrm\_triggerInfo](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference).
This allows your triggers to coexist with triggers from other modules.

### Add a case type (CiviCRM v4.4+)

If you want to develop a custom case-type for CiviCase, then you can
generate a skeletal CiviCase XML file.

**Using "civix generate:case-type"**

This creates two files:

-   ***xml/case/Training.xml*** defines the roles, activity types, and
    timelines associated with the new case type. For more in depth
    discussion of CiviCase XML, see [CiviCase
    Configuration](/confluence/display/CRMDOC/CiviCase+Configuration).
-   ***alltypes.civix.php***(which may already exist) defines
    implementations of various hooks (notably hook\_civicrm\_caseTypes).

### Add custom fields (CiviCRM v4.4+)

If your extension needs to instantiate one or more sets of custom data
fields at installation, use these steps. Note that there are two
qualitatively different examples for reference based on whether the
custom data set extends an entity vs. a specific subtype of that entity.

#### If extending a base entity (e.g. "Individual" – without any specific subtype):

We will create a custom fields using the web interface and then export
them for use with the extension. Steps:

-   On your development instance of CiviCRM, create the new custom
    fields using the web interface.
-   Note the unique ID of the custom data group (aka "Custom Fieldset",
    "CustomGroup" or "civicrm\_custom\_group") – you will need this in a
    minute.
-   Verify that civix is connected to your instance of CiviCRM by
    running "civix [civicrm:ping](http://civicrmping)". (If the ping is
    unsuccessful, re-read the civix README.md and do the
    post-installation configuration.)
-   Create an XML file with "civix
    [generate:custom-xml](http://generatecustom-xml)" – and be sure to
    specify the custom-data group ID. (In the example below, it assumes
    ID 7.)
-   Create an upgrader file with "civix
    [generate:upgrader](http://generateupgrader)" – this will load the
    XML file during installation. (Example below.)

**Using "civix generate:custom-xml"**

Most of the CiviHR modules rely on the first approach:\
 \

[https://github.com/civicrm/civihr/blob/master/hrqual/CRM/HRQual/Upgrader/Base.php\#L244](https://github.com/civicrm/civihr/blob/master/hrqual/CRM/HRQual/Upgrader/Base.php#L244)\

[https://github.com/civicrm/civihr/blob/master/hrqual/xml/auto\_install.xml](https://github.com/civicrm/civihr/blob/master/hrqual/xml/auto_install.xml)

#### \
 If extending an entity of a specific subtype (e.g. Activities of type 'Volunteer')

Unfortunately, the automatic export doesn't work too well when the
custom-data group extends a specific subtype -- e.g. the "HR Emergency
Contact" ext needs to create a custom-data group that describes
Relationships with type "Emergency Contact". Internally, Civi uses
"relationship type id\#s", but those aren't portable. As a quick
work-around, I used Smarty:

For example:\

[https://github.com/civicrm/civihr/blob/master/hremerg/CRM/HREmerg/Upgrader.php\#L14](https://github.com/civicrm/civihr/blob/master/hremerg/CRM/HREmerg/Upgrader.php#L14)\

[https://github.com/civicrm/civihr/blob/master/hremerg/templates/hremerg-customdata.xml.tpl\#L11](https://github.com/civicrm/civihr/blob/master/hremerg/templates/hremerg-customdata.xml.tpl#L11)\
 \
 To create this, I started by using "civix
[generate:custom-data](http://generatecustom-data)" and then:\
 \
 1. Rename the xml/auto\_install.xml to
templates/hremerg-customdata.xml.tpl

\2. In the .tpl file, change the value of
<extends\_entity\_column\_value\>. Instead of a hard-coded type id\#,
use a variable.\
 3. Add logic in the upgrader to create the relationship type\
 4. Add logic in the upgrader to evaluate the Smarty template

### Add a hook function

CiviCRM hook functions allow module-extensions to run extra logic as
part of the normal CiviCRM processing – for example,
***hook\_civicrm\_buildForm*** allows a module to run logic whenever a
web-form is displayed, and ***hook\_civicrm\_post*** allows a module to
run logic after any entity is saved. For detailed documentation about
available hooks, see [Hook
Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference).

To implement a hook, you must add a function to the module's main .php
file. (This file was created earlier by the
"[generate:module](http://generatemodule)" command.) The function name
is taken by combining the module's short-name with the hook's name.
(This is just like Drupal's hook convention.)

For example, suppose our module's main .php file is
***myextension.php*** and that we want to use ***hook\_civicrm\_post***
to write to a log file every time a contribution is saved. Then we would
add the following code:

**Implementing "hook\_civicrm\_post" in "myextension"**

![image](/confluence/images/icons/emoticons/information.png)

When you first created the skeletal project, several hook functions were
auto-generated in *myextension.php*. These functions are usually about
one line long – they simply delegate the work to another function. For
example *myextension\_civicrm\_config()* delegates work to
*\_myextension\_civix\_civicrm\_config()*. You should feel free to add
more code to *myextension\_civicrm\_config()*, but you should preserve
the call to **\_myextension**\_civix\_**civicrm*\_config().*

### Add a resource file

To include static resources – such as stylesheets, Javascript files, or
images – you should place the files in your extension directory. To load
the files at runtime, see the examples in the [Resource
Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/Resource+Reference).

### Add a report

CiviReport enables developers to define new business reports using
customizable SQL logic and form layouts. Use
"[generate:report](http://generatereport)" to get started:

**Using "civix generate:report"**

This creates three files:

-   ***CRM/Myextension/Form/Report/MyReport.mgd.php*** stores metadata
    about the report. The format of the file is based on
    [hook\_civicrm\_managed](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference)
    and the
    [API](http://wiki.civicrm.org/confluence/display/CRMDOC/API+Reference).
-   ***CRM/Myextension/******Form/Report/MyReport.php*** contains the
    form-builder and query-builder for the report. For details about its
    structure, see the [CiviReport
    Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/CiviReport+Reference).
-   ***templates/CRM/Myextension/Form/Report/MyReport.tpl*** contains
    the report's HTML template. (Note: This usually delegates
    responsibility to a core template and does not need to be edited.)

![image](/confluence/images/icons/emoticons/check.png)

**Copy an Existing Report**\

The reports included in CiviCRM are
[open-source](http://civicrm.org/licensing), and (pursuant to the AGPL
license) you have the right to derive new reports from existing reports.
This can be useful if one of the existing reports is close to meeting
your needs but requires further PHP/SQL customization. To make a new
report based on an existing report:

-   Navigate to the "*civicrm/CRM/Report/Form/"* within your CiviCRM
    source tree
-   Determine the class-name of the original report. (For example, the
    activity report is in the class "*CRM\_Report\_Form\_Activity*".)
-   Return to your module directory and run the
    "[generate:report](http://generatereport)" command, e.g.

**Using "generate:report --copy"**

### Add a custom search

CiviCRM enables developers to define new search forms using customizable
SQL logic and form layouts. Use
"[generate:search](http://generatesearch)" to get started:

**Using "civix generate:search"**

This creates two files:

-   ***CRM/Myextension/Form/Search/MySearch.mgd.php*** stores metadata
    about the custom search. The format of the file is based on
    [hook\_civicrm\_managed](http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference)
    and the
    [API](http://wiki.civicrm.org/confluence/display/CRMDOC/API+Reference).
-   ***CRM/Myextension/******Form/Search/MySearch.php*** contains the
    form-builder and query-builder for the custom search.

![image](/confluence/images/icons/emoticons/check.png)

**Copy an Existing Search**\

The custom search classes included in CiviCRM are
[open-source](http://civicrm.org/licensing), and (pursuant to the AGPL
license) you have the right to derive new searches from existing
searches. This can be useful if one of the existing searches is close to
meeting your needs but requires further PHP/SQL/TPL customization. To
make a new search based on an existing search:

-   Navigate to the "*civicrm/CRM/Contact/Form/Search/Custom"* within
    your CiviCRM source tree
-   Determine the class-name of the original search. (For example, the
    zipcode search is in the class
    "*CRM\_Contact\_Form\_Search\_Custom\_ZipCodeRange*".)
-   Return to your module directory and run the
    "[generate:search](http://generatesearch)" command, e.g.

**Using "generate:search --copy"**

The "copy" option will sometimes create two or three files – depending
on whether the original search screen defines its own Smarty template.

### Add an API function

The [CiviCRM
API](http://wiki.civicrm.org/confluence/display/CRMDOC/API+Reference)
provides a way to expose functions for use by other developers – API
functions can be useful for implementing AJAX interfaces (using the
cj().crmAPI() helper), and they can also be called via REST, PHP,
Smarty, Drush CLI, and more. Each API requires a two-part name: an
entity name (such as "Contact", "Event", or "MyEntity") and an action
name (such as "Create" or "MyAction").

**Using "civix generate:api"**

![image](/confluence/images/icons/emoticons/warning.png)

Action names should be lowercase. The javascript helpers CRM.api() and
CRM.api3() force actions to be lowercase. This issues does not present
itself in the API Explorer or when the api action is called via PHP,
REST, or SMARTY

This creates one file:

-   ***api/v3/NewEntity/NewAction.php*** provides the API function. Note
    that the parameters and return values must be processed in a
    particular way (as demonstrated by the auto-generated file).

For use with CiviCRM 4.3, one can also add the "–schedule" option (e.g.
"–schedule Hourly"). This will create another file:

-   ***api/v3/NewEntity/NewAction.mgd.php*** provides the scheduling
    record that will appear in the CiviCRM's job-manager.

### Add a new entity

You may have a need to create a new entity that doesn't exist in
CiviCRM. For this, you can use the command civix generate:entity - which
as of this writing is considered "experimental and incomplete". This
documentation will guide you through filling in the blanks.

-   Pick a name for your entity. In some places, CiviCRM expects a
    FirstLetterCapitalizedName, in others, an underscore\_name. Be
    absolutely consistent in your naming, because CiviCRM expects to be
    able to translate between those two naming conventions.
-   Run `civix generate:entity <name of entity>` (entity name should be
    FirstLetterCapitalized here). This creates a skeletal file for your
    XML schema, your BAO, and your API. It does NOT create a skeletal
    SQL file to create your table or DAO files at this time.
-   Edit the XML schema in the "xml" folder to match the fields you
    want. Minimal documentation is available
    [here](https://wiki.civicrm.org/confluence/display/CRMDOC/Database+Reference),
    but you're better off looking at the [existing XML
    schemata](https://github.com/civicrm/civicrm-core/tree/master/xml/schema).
-   Create a DAO file. For now, civix does not handle this. You can
    create this by hand; alternatively, use [this
    technique](http://civicrm.stackexchange.com/a/3536/12). Copy your
    XML schema into a development copy of CiviCRM. Edit Schema.xml to
    include your XML file, then from the xml folder, run
    `php ./GenCode.php` (In CiviCRM 4.7.12+, run
    `<civiroot>/bin/setup.sh -g` instead). This will generate a DAO file
    for you in the CiviCRM core code; copy it into the
    CRM/<Entityname\>/DAO folder of your extension.
-   At this time, civix also does not generate the SQL to create and
    drop your table(s). You can create these by hand; alternatively, if
    you used the `<civiroot>/bin/setup.sh -g` technique to create your
    DAO, SQL will have been generated for you in
    `<civiroot>/sql/civicrm.mysql`. Once you have the SQL statements for
    creating and dropping your SQL tables, you can name them
    `auto_install.sql                   `and `auto_uninstall.sql`
    respectively and drop them in your "sql" folder. They will be run
    automatically on install if you generated an upgrader. Note that
    using `auto_install.sql                   `and `auto_uninstall.sql`
    is not best practice if you have multiple statements in each file,
    since you can't error check each statement separately.
-   Run `civix generate:upgrader` from within your extension. [More
    details are
    here](https://wiki.civicrm.org/confluence/display/CRMDOC/Create+a+Module+Extension#CreateaModuleExtension-Addadatabaseupgrader/Installer/uninstaller).
-   Define your entity using
    [hook\_civicrm\_entityTypes](https://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes).

### Add a unit-test class

Unit-testing is an invaluable to way to maintain quality-control over
your extension. When developing a test case for a CiviCRM extension, it
is useful to run the test case within an active, clean CiviCRM
environment. The CiviCRM/Civix testing tools will automate this – as
long as you follow a few basic conventions. The following steps will
create and run a test in your extension.

![image](/confluence/images/icons/emoticons/check.png)

Before preparing unit-tests with extensions, you must first:

-   Configure the test system for core CiviCRM. See [Setting up your
    personal testing sandbox
    HOWTO](/confluence/display/CRM/Setting+up+your+personal+testing+sandbox+HOWTO).
-   Ensure that the extension is enabled on the linked CiviCRM site

First, create a skeletal test-class. The class name should be placed in
your extension's namespace (*CRM\_Myextension*) and should end with the
word *Test*.

**Using "civix generate:test"**

This creates a new directory and a new PHP file:

-   ***tests/phpunit*** is the base directory for all test classes.
-   ***tests/phpunit/CRM/Myextension/MyTest.php*** is the actual test
    class. It should be written according to the conventions of
    [PHPUnit](http://www.phpunit.de/manual/current/en/writing-tests-for-phpunit.html).

To make sure you can run the test civix needs to know where the CiviCRM
base install is:

To run this test-class, change to your extension folder and call "civix
test":

**Using "civix test"**

The skeletal test class doesn't do anything useful. For more details on
how to write a test class:

-   Read [PHP Unit Manual: Writing Tests for
    PHPUnit.](https://phpunit.de/manual/current/en/writing-tests-for-phpunit.html)
-   Review the example code in
    [org.civicrm.exampletests](https://github.com/totten/org.civicrm.exampletests)

# Frequently asked questions

### How does one add an ajax/web-service callback?

There are three options:

-   **Full control:**Add a basic page. Remove the parent::run() call
    from the run() function, and at the bottom of the run() function,
    perform your own output (eg "*echo json\_encode($data)*") and then
    short-circuit processing (eg "*CRM\_Utils\_System::civiExit()*") so
    that neither Smarty nor the CMS modify the output.
-   **Using ajax helpers (CiviCRM 4.5 and above**): Generate a page with
    civix as above. Build your data in the run() function. If the
    client-side request includes *snippet=json* in the url, just append
    your data to *$this-\>ajaxResponse* array and the rest will happen
    automatically. If not, you can directly call
    CRM\_Core\_Page\_AJAX::returnJsonResponse() at the bottom of the run
    function. See [Ajax Pages and
    Forms](/confluence/display/CRMDOC/Ajax+Pages+and+Forms)
    documentation.
-   **Using the API:** Add an API function using the instructions above.
    The API function can be called with the API's [AJAX
    Interface](http://wiki.civicrm.org/confluence/display/CRMDOC/AJAX+Interface).
    This automatically handles issues like encoding and decoding the
    request/response.

### How does one add a standalone PHP script?

This is tricky proposition. If the script is truly standalone and
doesn't require any services from the CRM/CMS, then you can just add a
new .php file to the extension... but it won't have access to CiviCRM's
APIs, databases, classes, etc. If the standalone script needs those
services, then it will need to ***bootstrap*** CiviCRM and the CMS. This
is challenging for several reasons:

-   The bootstrap mechanics are different in each CMS (Drupal, Joomla,
    etc).
-   The bootstrap mechanics are different for single-site installations
    and multi-site installations
-   To initiate a bootstrap from a script, one needs to determine the
    local-path to the CiviCRM settings. However, the local-path of the
    script is entirely independent of the local-path to the settings –
    these are determined at the discretion of the site administrator.

If you really need to do it, it's theoretically possibly to emulate an
example like
"[bin/deprecated/EmailProcessor.php](http://svn.civicrm.org/civicrm/branches/v4.1/bin/deprecated/EmailProcessor.php)".
The results will likely be difficult for downstream users to
install/use.

Instead of creating a standalone script, consider one of these options:

-   Add an API function (using the instructions above). The API function
    can be called many different ways – PHP, REST, AJAX, CLI, Drush,
    Smarty, cron, etc. CiviCRM used to include a number of standalone
    scripts – many of these have been migrated to API functions because
    this approach is simpler and more flexible.
-   Add a basic page (using the instructions above). At the bottom of
    the run() function, call "*CRM\_Utils\_System::civiExit()*" to
    short-circuit theming and CMS processing.

### How does one add a cron job?

One can add an API function (using the instructions above) and create a
schedule record. In CiviCRM 4.3, the schedule record can be
automatically created; to do this, call "civix
[generate:api](http://generateapi)" with the option "–schedule Daily"
(or "-schedule Hourly", etc). CiviCRM will make a best-effort to meet
the stated schedule.

In CiviCRM 4.2, one can use APIs as cron jobs, but the schedule record
won't be created automatically. The site administrator must manually
insert a scheduling record by navigating to "Administer =\> System
Settings =\> Scheduled Jobs".

# Troubleshooting

1.  I've created the files and edited them but I don't see the expected
    changes.\
     A: Did you install and enable your extension?
    (<site\>/civicrm/admin/extensions?reset=1)\
     \

2.  I get Error: "Cannot instantiate API client -- please set connection
    options in parameters.yml"\
     A: You might have missed the step about setting
    'civicrm\_api3\_conf\_path'
    ([https://github.com/totten/civix/](https://github.com/totten/civix/)),
    or it didn't get set properly for some reason.\
     \
3.  I've tried to generate a page/report/search/upgrader/etc but it's
    not working.\
     A: For all of the various types, you must first run
    [generate:module](http://generatemodule), and then \`cd\` into the
    folder (e.g. com.example.myextension) before running one of the
    other \`generate:\` commands.

A few questions:

1.  My developer used PHP to create SQL for the install, as well as a
    .sql file with a few statements.
    CRM\_Myextension\_Upgrader\_Base::onInstall only seems to execute
    files like /sql/REV\#\_install.sql. Can we change this so some PHP
    calling protocol is also possible? Or is one expected to put install
    code into an CRM\_Myextension\_Upgrader::upgrade\_NNNN function?
2.  Is there a required or recommended practice on how to name
    'upgrades'? I like 4200 as the first upgrade to 4.2.
3.  A MySQL / upgradability best practice question: if one wants to add
    additional enums to a core enum, what is the best practice?
    Currently we are just running:\
     ALTER TABLE \`civicrm\_mailing\_bounce\_type\` CHANGE \`name\`
    \`name\` ENUM( 'AOL', 'Away', 'DNS', 'Host', 'Inactive', 'Invalid',
    'Loop', 'Quota', 'Relay', 'Spam', 'Syntax', 'Unknown', 'Mandrill
    Hard', 'Mandrill Soft', 'Mandrill Spam', 'Mandrill Reject' )
    CHARACTER SET utf8 COLLATE utf8\_unicode\_ci NOT NULL COMMENT 'Type
    of bounce';\
     This isn't the greatest. While one can get the list of enums (kind
    of) by using a query against INFORMATION\_SCHEMA, it returns the
    field definition in a form like "enum('first','second')", which
    would have to be parsed and then used in an ALTER statement like the
    one above. Is that what we should be doing, in order to avoid core
    and extensions stepping on each other as enums are changed? If so,
    this approach would need to be done in core upgrades for all enum
    fields as well.\

1.  Agree that you should be able to use both PHP and SQL. Currently,
    you can do the PHP by either tweaking the hook\_civicrm\_install or
    by overloading the onInstall() method. But this probably isn't best
    – so maybe it would be good to include empty
    install()/uninstall()/enable()/disable() functions in the upgrader
    class. That way everything can be seen/managed in the one file
    (which better approximates the coding conventions from Drupal.
2.  I really don't know the best convention. In the examples, I just
    pantomimed Drupal. But the truth is probably that the DB-numbering
    issue ties into other release practices – e.g. Does one support
    several Civi releases – or only the newest? Does one maintain a
    single build/branch for multiple Civi releases -- or separate
    builds/branches for each.
3.  It's a bad idea for extensions to manipulate core schema. This will
    very likely break things in future releases. It's better to either
    create new tables or coordinate schema changes in the core system.
    For this particular example, it seems like there should be a
    discussion with about why new bounce-types are required and how they
    fit with reports/etc -- and then assess the options of either (a)
    adding more options to the enum in core or (b) changing the enum to
    an OptionGroup.

I realize this page was written some time so my apologies for coming to
the party a little late; I used the older version of Civix for
boilerplating some extensions and just recently reconfigured my
development environment with a new install of Civix on an Ubuntu virtual
machine.

My question is about the Database Upgrader part of civix (**civix
[generate:upgrader](http://generateupgrader)**). When we use this
instruction it will stub out the code to run the installer and
uninstaller SQL files but it doesn't actually create the empty SQL files
and the installer/uninstaller hooks are commented out. I think the
previous versions produced the empty SQL files and did not comment out
the hooks. Is that correct?

Further on the subject, is the correct course of action for the
developer to open up CRM/module\_name/Upgrader.php, uncomment the
install and uninstall hooks and then create the myinstall.sql and
myuninstall.sql files as necessary?

Oh, one last question regarding database table naming... What is the
preferred practice for naming conventions? CiviCRM's database schema has
triggers that run on civicrm tables that have in the past thwarted the
use of custom tables prefixed with civicrm.

-   I tried to verify whether previous versions of
    "[generate:upgrader](http://generateupgrader)" automatically created
    the SQL files. Running "git log
    src/CRM/CivixBundle/Command/AddUpgraderCommand.php" doesn't show any
    evidence that they did.
-   The command "[generate:custom-data](http://generatecustom-data)"
    does automatically create a file – but it's an XML file
    (xml/auto\_install.xml).
-   There are two ways to run some SQL as part of upgrader:
    -   Create a file whose name matches "sql/\*\_install.sql". The
        upgrader will automatically find/execute these files during
        initial installation.
    -   As you say, one can open-up CRM/module\_name/Upgrader.php -- and
        then uncomment or add new lines. This is useful if you want to
        control the sequencing (e.g. run some PHP code; then run some
        SQL code; then run some more PHP code), and it's useful for
        upgrades.

-   For CiviHR, the naming convention was "civicrm\_hrfoo" (e.g.
    "civicrm\_hrjob"). It may be notable that we also use
    [https://github.com/civicrm/civihr/blob/589b1f2c4036854b08a6c7b8154e68f534c18b82/hrjob/hrjob.php\#L237](https://github.com/civicrm/civihr/blob/589b1f2c4036854b08a6c7b8154e68f534c18b82/hrjob/hrjob.php#L237)
    . If there are issues with using tables that follow that convention,
    then we should file bugs accordingly. (IIRC, there may be a
    race-condition in terms of installing extensions with custom tables
    and activating detailed logging.)

I'm not seeing generate:custom-data with the current version of civix.
Was it removed or part of an unreleased version of civix?

My bad. The command is "generate:custom-xml". (It was called
"generate:custom-data" in an unreleased draft.)
