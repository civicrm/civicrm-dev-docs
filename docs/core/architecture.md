# The codebase

This chapter provides a general overview of the codebase organisation.

In order to explore the directories inside the CiviCRM repository it is
generally quickest to to make a local clone of the CiviCRM from GitHub.

!!! tip
    The CiviCRM codebase is object oriented. If you aren't familiar with object
    oriented programming, spend a little time reading some beginner tutorials on
    the net.

## Namespaces
Classes in CiviCRM must be placed in one of two folders:

***`CRM`*** (e.g.: `CRM_Core_Invoke`)
classes use PEAR-style class-naming conventions that were common up until
PHP 5.2. Class names include underscores and MUST NOT use the PHP
"[namespace](http://php.net/namespace)"
directive. Use `CRM` style when creating classes that plug into existing `CRM`
subsystems such
as payment processors (CRM_Core_Payment) and reports (CRM_Report).

***`Civi`*** (e.g.: `\Civi\API\Kernel`)
"Civi" classes use PHP 5.3 namespaces. They MUST use the
"[namespace](http://php.net/namespace)" directive.
Namespaces are designated with "\".

!!! note
    At time of writing (May 2014, before Civi 4.5), some classes may not load
    properly if they are in `Civi` – for example, the payment system will only load
    payment-processor classes in `CRM_Core_Payment`. If you encounter problems like
    this, please submit an issue or patch.

!!! tip
    The `Civi` namespace uses composer's PSR-0 autoloader. This autoloader does not
    support custom PHP overrides.
    Use `Civi` when creating new object-oriented subsystems (like `\Civi\API`).

## Business logic
Most of the business logic of CiviCRM, is found in the CRM directory (`CIVICRM_ROOT/CRM`).
This logic is the part of CiviCRM that
defines what it does and how it behaves
(e.g. that allows people to register on events)
In this directory, you will find directories for core CiviCRM functions like
contacts, groups, profiles, deduping, importing, and the CiviCRM components
like CiviCampaign, CiviMail, Pledges, etc.

Each of these directories is slightly different depending on what they do but
there are some common subdirectories: BAO, DAO, Form and Page.

### DAO
DAO stands for data access object.  Code in this directory exposes the contents
of the database.  The DAOs are automatically generated for each release based
on the data schema.  DAO objects tend to be instantiated in BAO classes.

The DAO has a property for each field (using the actual field name, not the
unique name).  They also have standard CRUD (create retrieve update delete) type
functions, etc. <!--fixme why the etc? what else?? -->

The DAO files live in the relevant DAO folder - e.g. CiviCRM/Pledge/DAO and
there is one file for each of the xml files we looked at earlier. These files
are built on the fly when CiviCRM is packaged from the XML files which is why
you won't see them if you checkout CiviCRM from SVN.

The DAO object generated has:
* A property for each field using the actual field name, not the unique name
* A links function which retrieves the links to other tables (off the foreign keys)
* An import function and an export function for ?
* A fields function which returns an array of fields for that object keyed by the field's unique name. Looking again at the field 'pledge.amount' we see
* A couple of functions to define the labels for enumerated fields

```php
  'pledge_amount' => array(
    'name' => 'amount',
    'type' => CRM_Utils_Type::T_MONEY,
    'title' => ts('Total Pledged') ,
    'required' => true,
    'import' => true,
    'where' => 'civicrm_pledge.amount',
    'headerPattern' => '',
    'dataPattern' => '',
    'export' => true,
    'bao' => 'CRM_Pledge_BAO_Pledge',
    'table_name' => 'civicrm_pledge',
    'entity' => 'Pledge',
  ),
```

Note that the key is the unique name but the name field is the field's name and the 'where' field shows the mysql description of it. We also see the data type and whether it is available for search or export.

Note that most fields should probably be exportable unless there is a security reason or they are weird & confusing as the search builder is also driven by this setting.

Fields whose option values can be calculated will also have a 'pseudoconstant' section.

### BAO
BAO stands for business access object
([example](https://github.com/civicrm/civicrm-core/blob/master/CRM/Event/BAO/Event.php)).
BAOs map to DAOs and extend them with
the business logic of CiviCRM.  A lot of the meat of CiviCRM is found in the
BAOs, for example they have the code that creates follow up activities when an
activity is created, or create activities and populating custom fields when a
pledge is created.

!!! note
    BAOs typically have both `add()` and `create()` methods. The `add()` method
    just creates the database record with the DAO `save()` method whereas 'create()' calls
    subsidiary functions such as creating activities & populating custom fields.
    The APIs call 'Create' which then calls 'Add' as well as other associated
    functions.




### Form
In general each form page in CiviCRM maps to a file in one of
the form directories.  Form files contain a class that extends CRM_Core_Form.
This class has different methods that the core calls before display to
check permissions, retrieve information (`preProcess`), display
the form (`buildForm`), validate the form (`formRule`) and carry out tasks once the
form is submitted (`postProcess`).  Forms can diplay information from the BAO
to users and then call the BAO on submission. Generaly each form has an
associated template (see below) which defines the form's html.

!!! tip
    Perhaps the best way to get to grips with the Forms is by experience and
    experimentation.

### Page
If a CiviCRM screen is not a Form, it is probably a page.  Pages files contain a
class that extend CRM_Core_Page.  Similar to the form class, Pages have methods
that are called before the page is displayed to control access, set the title,
etc. (`preProcess`), and when the page is displayed (`run`). Pages tend to
take information from the BAO to be displayed to users. In general, each
page has an associated template (see below) which is used to create the
html of the page.

### xml
This directory contains a menu directory which maps urls to CRM form or page
classes and controls access to these URLs using permissions.

## Templates
The templates directory contains all the HTML for pages and forms.  Directly
inside the templates directory is a CRM directory.  In general, all templates
map to a form or page class.  CiviCRM chooses a template for the form or page
based on the class name.

For example, the class CRM_Member_Form_MembershipRenewal looks for a template
in `templates/CRM/Member/Form/MembershipRenewal.tpl`.

Templates are written in smarty, a common PHP template engine.  Variables can
be passed to smarty using the assign() method which is available to all Form
and Page classes.

Customising templates is discussed in more detail in 'Techniques'

## The API
The application programming interface (API) is stored in the api root
directory.  Best practice for using the API is discussed in more detail in
'Techniques'

## bin scripts
The bin directory contains a variety of scripts that can be run to carry out
specific functions.  Some of these scripts are run on a regular basis, for
example the CiviMail 'send' and 'process' scripts.  Others are run on a one of
or occasional basis, e.g. update geo-coding.

## SQL
The SQL directory is automatically generated as part of a release.  It contains
useful files like the SQL to create the database and insert demo data. Most
developers won't need to edit files in this directory.

## l10n
This directory contains lots of automatically generated localisation files.
You should not need to edit this directory directly.  You should instead use
CiviCRM's online translation tool transifex.

## packages
CiviCRM makes use of a lot of 3rd party packages for things like the database,
        form, javascript and pdf libraries, wysiwyg editors and so on.  You
        shouldn't need to edit these packages directory.

## Database structure

The database structure is defined in a series of XML files
[for example](https://github.com/civicrm/civicrm-core/blob/master/xml/schema/SMS/History.xml).
These files are
not packaged in the releases but are available in the Github repository. They
are located in Civicrm/xml/Schema. All the folders within this directory also
have folders in the main CRM folder which contain a DAO folder and generally a
BAO folder too.

!!! Info
    The XML files describe the database and are used to
    build both the DAO files and the database generation SQL by the
    [`GenCode` script](https://github.com/civicrm/civicrm-core/blob/master/xml/GenCode.php) and
    [`CRM_Core_CodeGen_Main` class](https://github.com/civicrm/civicrm-core/blob/master/CRM/Core/CodeGen/Main.php).


Looking in `CiviCRM/xml/Schema/Pledge` we see 4 files:

- files.xml
- Pledge.xml
- PledgePayment.xml
- PledgeBlock.xml

The files.xml is just a list of the other files. Each of the others represents a
table in the Database, defining both table-leve and field-level metadata.

The XML describes fields, foreign keys and indexes, an example of a field definition is:

```
  <field>
    <name>amount</name>
    <uniqueName>pledge_amount</uniqueName>
    <title>Total Pledged</title>
    <type>decimal</type>
    <required>true</required>
    <import>true</import>
    <comment>Total pledged amount.</comment>
    <add>2.1</add>
  </field>
```

<!-- fixme:
The field 'amount' in the table 'pledge' has a unique name to distinquish it from fields in other tables (e.g. contribute) with the same field. This isn't consistent in all tables and I am trying to find out the reason / rules for this. Also, I presume 'import' means the field is exposed for .... imports?.-->

We can see that the above 'pledge_amount' field was added in CiviCRM v2.1.

Occassionally tables are dropped from the core schema so will not have associated DAOs
(for example the
[SMS history](https://github.com/totten/civicrm-core/blob/master/xml/schema/SMS/History.xml)
table was dropped in version 2.0, as indicated by a `<drop>2.0</drop>` field.
