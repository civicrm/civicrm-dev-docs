# Writing Extensions

Todo:

- Use APIv3 and hooks. Package code in Civi extensions or CMS extensions (w/trade-offs).
- Create a CiviCRM extension developer's guide to best practices (Nicolas?)

## Introduction

**CiviCRM Extensions** are packaged pieces of functionality that extend
CiviCRM's out-of-the-box functionality, independent of CMS plaform.

This section covers how to write extensions. See the [extension life cycle
page](/extend-stages) for background on the publishing and review process for
[published extensions](https://civicrm.org/extensions).

## Pre-Requisites

-   Have basic knowledge of PHP, Unix, and object-oriented programming
-   Install ***civix v14.01*** or newer. For instructions, see
    [https://github.com/totten/civix/](https://github.com/totten/civix/)
    . This wiki page assumes that "civix" is installed and registered in
    the PATH.
-   Configure an extensions directory. For instructions, see
    [Extensions](http://wiki.civicrm.org/confluence/display/CRMDOC/Extensions).
    This wiki page assumes the directory is "/var/www/extensions", but
    you should adapt as appropriate.
     Your extensions directory must be under the CMS root directory so
    that civix can find and bootstrap the CMS. Otherwise, it will fail
    with an error like "Sorry, could not locate bootstrap.inc" on most
    operations.
-   The user account you use to develop the module must have permission
    to read all CMS files, including configuration files, and write to
    the extensions directory.
     For example, Debian's drupal7 package saves database configuration
    to /etc/drupal/7/sites/default/dbconfig.php, which is only readable
    by the www-data user. You will need to make this file readable by
    your development user account for civix to work.

## General overview

[]( fixme paragraph this section into above notes )

If you haven't already, you need to configure a local directory to store
extensions.
For instructions, see
[Extensions](https://wiki.civicrm.org/confluence/display/CRMDOC/Extensions)
You may also want to review the
[Extension Reference](https://wiki.civicrm.org/confluence/display/CRMDOC/Extension+Reference) page for technical details.

### 0. Decide
Writing an extension is a great way to implement a new feature – but it may be
unnecessary if someone else has already implemented that feature. If you're not
sure, you can do a couple things:
- Search the [Extensions Directory](http://civicrm.org/extensions) for an existing extension.
- Post about your planned extension in the [Extensions Channel](https://chat.civicrm.org/civicrm/channels/extensions).

Extensions provide a native, portable way to extend CiviCRM, but there are
other ways to extend CiviCRM – such as implementing Drupal modules or Joomla
plugins. If you're considering another way, look at the
[Add-on Formats](https://wiki.civicrm.org/confluence/display/CRMDOC/Add-on+Formats)
to help decide.

### 1. Install civix
Some tasks in the process of writing an extension require boiler-plate code. To
reduce the amount of work required to find, understand, and adapt the
boiler-plate code, one should install the CiviCRM extension builder, civix.
Civix is a command-line tool which generates code for some common development
tasks.

>> See [https://github.com/totten/civix/](https://github.com/totten/civix/)

### 2. Develop
To get started with development, one should usually follow the steps in
"[Create a Module
Extension.](https://wiki.civicrm.org/confluence/display/CRMDOC/Create+a+Module+Extension)"
A module extension is the most flexible type of extension – it can define any
mix of new reports, custom search screens, payment processors, and web pages;
it can listen for hooks, override page-templates, and more. The coding
conventions closely resemble those of CiviCRM Core and of CiviCRM-Drupal
modules. Module extensions are fully supported in CiviCRM 4.2+.

### 3. Publish
The CiviCRM Extensions Directory provides a way to publicize your extension –
and it even provides easy, in-app distribution.

See: [Publish](http://wiki.civicrm.org/confluence/display/CRMDOC/Publish+an+Extension),
