# The codebase

Having a general overview of the codebase and high level understanding of how
it is organised, where to find specific types and bits of code, etc. is really
useful when you are getting started with developing CiviCRM. This chapter looks
at a few key parts of the codebase to give you an idea of how everything fits
together.

Download CiviCRM from sourceforge.net unzip it and have a look around the
codebase.  The rest of this chapter explores the directories inside the CiviCRM
zip file.

## Object oriented
The CiviCRM codebase is object oriented. If you aren't familiar with object
oriented programming, spend a little time reading some beginner tutorials on
the net.

## Namespaces
Classes in CiviCRM must be placed in one of two folders:

Folder
Example Class
Description
When to Use
CRM	CRM_Core_Invoke
"CRM" classes use PEAR-style class-naming conventions that were common up until
PHP 5.2. Class names include underscores and MUST NOT use the PHP
"[namespace](http://wiki.civicrm.org/confluence/php.net/namespace)"
directive.
Use "CRM" when creating classes that plug into existing "CRM" subsystems – such
as payment processors (CRM_Core_Payment) and reports (CRM_Report).
Civi	\Civi\API\Kernel
"Civi" classes use PHP 5.3 namespaces. They MUST use the "namespace" directive.
Namespaces are designated with "\".


At time of writing (May 2014, before Civi 4.5), some classes may not load
properly if they are in "Civi" – for example, the payment system will only load
payment-processor classes in "CRM_Core_Payment". If you encounter problems like
this, please submit an issue and/or patch.

The "Civi" namespace uses composer's PSR-0 autoloader. This autoloader does not
support custom PHP overrides.
Use "Civi" when creating new object-oriented subsystems (like \Civi\API).


## Business logic
Most of the business logic of CiviCRM, that is to say the part of CiviCRM that
defines what it does and how it behaves (e.g. that allows people to register on
    events) is found in the CRM directory ('CIVICRM_ROOT/CRM').  Looking in
this directory, you'll find directories for core CiviCRM functionality like
contacts, groups, profiles, deduping, importing, and the CiviCRM components
like CiviCampaign, CiviMail, Pledges, etc.

Each of these directories is slightly different depending on what they do but
there are some subdirectories that you'll find in most of them, for example
BAO, DAO, Form and Page.  So what can you expect to find in these directories?

### DAO
DAO stands for data access object.  Code in this directory exposes the contents
of the database.  The DAOs are automatically generated for each release based
on the data schema.  DAO objects tend to be instantiated in BAO classes.

The DAO has a property for each field (using the actual field name, not the
    unique name).  They also have standard create retrieve update delete type
functions, etc.

### BAO
BAO stands for business access object.  BAOs map to DAOs and extend them with
the business logic of CiviCRM.  A lot of the meat of CiviCRM is found in the
BAOs, for example they have the code that creates follow up activities when an
activity is created, or create activities and populating custom fields when a
pledge is created.

### Form
In general each page in CiviCRM which contains a form maps to a file in one of
the form directories.  Form files contain a class that extends CRM_Core_Form.
This class has different methods that are used before the form is displayed to
do things like check permissions, retrieve information (preProcess), display
the form (buildForm), validate the form (formRule) and carry out tasks once the
form is submitted (postProcess).  Forms can take information from the BAO to be
displayed to users and call the BAO on submission. In general, each form has an
associated template (see below) which is used to create the html of the form.
Perhaps the best way to get to grips with the Forms is by experience and
experimentation.

### Page
If a CiviCRM screen isn't a Form, it is probably a page.  Pages files contain a
class that extend CRM_Core_Page.  Similar to the form class, Pages have methods
that are called before the page is displayed to control access, set the title,
     etc. (preProcess), and when the page is displayed (run). Pages tend to
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
in templates/CRM/Member/Form/MembershipRenewal.tpl

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
The database structure is defined in a series of XML files.  These files are
not packaged in the releases but are available in the Github repository. They
are located in Civicrm/xml/Schema. All the folders within this directory also
have folders in the main CRM folder which contain a DAO folder and generally a
BAO folder too.

Looking in CiviCRM/xml/Schema/Pledge we see 4 files:

files.xml
Pledge.xml
PledgePayment.xml
PledgeBlock.xml
files.xml is just a list of the other files. Each of the others represents a
table in the Database. The XML files describe the database and are used to
build both the DAO files and the new database sql generation script.

The XML describes fields, foreign keys and indexes, an example of a field definition is:

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

## When should I edit core CiviCRM?
The above should have given you a reasonable idea of what the CiviCRM codebase
looks like.  Remember that most of the time, editing the core codebase directly
is not the recommended way for developers to customise and extend CiviCRM.
CiviCRM has two version releases per year so direct edits to the core codebase
will create upgrade issues for you.

There are other recommended ways for the majority of scenarios - for example
extensions, the APIs and hooks. Be sure that any edits that you make to the
core codebase are really necessary.

To help you decide, here are a couple of principles:

- Bug fixes should always be applied to core. Information on how to submit your
  bug fix will be added soon.
- Some (but not all) enhancements to existing features may be best applied to
  core, especially if they would be useful to others.
- New features should generally be packed as Extensions.
- If you aren't familiar with CiviCRM, by far the best way to get a sense if
  you should be editing core is by talking with the CiviCRM developer community
  on IRC, the forums, etc.
