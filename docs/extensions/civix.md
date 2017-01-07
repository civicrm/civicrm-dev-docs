# Civix

The [`civix`](https://github.com/totten/civix/) command-line tool is the
community-endorsed method for building your CiviCRM extensions.

Follow the installation instructions in the
[GitHub repository](https://github.com/totten/civix/).

In order to verify that all your configuration is correct ping CiviCRM from
within your extensions directory with:

      civix civicrm:ping

This command should reply with "Ping successful".

It is also useful to examine the output of the `civix help` command to read
about what `civix` can do for you.

Help is available on individual commands, e.g.:

```bash
civix help civicrm:ping
```

## Generating a skeletal extension

To generate a skeletal extension module, we will use `civix generate:module`
and pass in the name for our extension. See [here](./basics/#extension-names)
for details of naming conventions.

### Generate skeleton
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
[standard extension structure](./files).

The command attempts to autodetect authorship information (your name and
email address) by reading your
[`git`](https://git-scm.com/book/en/v2/Getting-Started-First-Time-Git-Setup)
configuration. If this fails or is
otherwise incorrect, then you may pass explicit values with `--author`
and `--email`.

### Update "info.xml"

The default ***info.xml*** file contains some examples and placeholders
which you need to fix. You can edit most of these fields intuitively.
If you need detailed specifications, see
[Extension
Reference](http://wiki.civicrm.org/confluence/display/CRMDOC/Extension+Reference).

### Enable the extension

Now that you have created your extension, you can activate by navigating
to:

**Administer** » **System Settings** » **Manage Extensions**

or

**Administer » Customize Data and Screens » Manage Extensions.**

For more detailed instructions, see
[Extensions](http://wiki.civicrm.org/confluence/display/CRMDOC/Extensions).

## Add features

There are many different features that you can add to a module-extension
at your discretion. A few possibilities:

### Add a basic web page

CiviCRM uses a typical web-MVC architecture. To implement a basic web
page, one must create a PHP controller class, create a Smarty template
file, and create a routing rule. You can create the appropriate files by
calling "civix generate:page"

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

***`check!`***[](fixme!)

After adding or modifying a route in the XML file, you must reset
CiviCRMs "menu cache". This can be done in a web browser by visiting
"/civicrm/menu/rebuild?reset=1" or by running
`drush                           cc civicrm` if using Drupal & Drush.

***`check!`***[](fixme!)

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

***`check!`***[](fixme!)

After adding or modifying a route in the XML file, you must reset
CiviCRMs "menu cache". This can be done in a web browser by visiting
"/civicrm/menu/rebuild?reset=1"

***`forbidden!`***[](fixme!)

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

***`information!`***[](fixme!)

The "upgrader" class is a wrapper for
[hook\_civicrm\_upgrade](/confluence/display/CRMDOC43/Hook+Reference)
which aims to be easy-to-use for developers with Drupal experience. If
you need to organize the upgrade logic differently, then consider
providing your own implementation of hook\_civicrm\_upgrade.

***`forbidden!`***[](fixme!)

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

***`information!`***[](fixme!)

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

***`check!`***[](fixme!)

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

***`check!`***[](fixme!)

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

***`warning!`***[](fixme!)

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

***`check!`***[](fixme!)

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

