# Writing Extensions

Todo:

- Use APIv3 and hooks. Package code in Civi extensions or CMS extensions (w/trade-offs).
- Create a CiviCRM extension developer's guide to best practices (Nicolas?)

## Introduction

**CiviCRM Extensions** are packaged pieces of functionality that extend CiviCRM's out-of-the-box functionality, independent of CMS plaform.

This section covers how to write extensions.

## Extension Names

All extension names follow the same convention as Java package names – they look like reversed domain names. (e.g.  `com.example.myextension`). For module-extensions, the last word in the module name will be the module's *short-name*. The short-name *must* be unique. It is possible to pick a different short-name, but that requires extra work.

## Pre-Requisites

-   Have basic knowledge of PHP, Unix, and object-oriented programming.
-   Install ***civix v14.01*** or newer. For instructions, see [Civix Documentation](/extensions/civix.md/). This page assumes that "civix" is installed and registered in the PATH.
-   Configure an extensions directory. For instructions, see [Extensions](/extensions/index.md). This page assumes the directory is `/var/www/extensions`, but you should adapt as appropriate. Your extensions directory must be under the CMS root directory so that civix can find and bootstrap the CMS. Otherwise, it will fail with an error like "Sorry, could not locate bootstrap.inc" on most operations.
-   The user account you use to develop the module must have permission to read all CMS files, including configuration files, and write to the extensions directory. For example, Debian's drupal7 package saves database configuration to `/etc/drupal/7/sites/default/dbconfig.php`, which is only readable by the www-data user. You will need to make this file readable by your development user account for civix to work.

### 0. Decide
Writing an extension is a great way to implement a new feature – but it may be unnecessary if someone else has already implemented that feature. If you're not sure, you can do a couple things:
- Search the [Extensions Directory](http://civicrm.org/extensions) for an existing extension.
- Post about your planned extension in the [Extensions Channel](https://chat.civicrm.org/civicrm/channels/extensions).

Extensions provide a native, portable way to extend CiviCRM, but there are other ways to extend CiviCRM – such as implementing Drupal modules or Joomla plugins. If you're considering another way, look at the [Add-on Formats](/extensions/packaging.md)
to help decide.

### 1. Install civix
Some tasks in the process of writing an extension require boilerplate code. To reduce the amount of work required to find, understand, and adapt the boilerplate code, one should install the CiviCRM extension builder, civix. Civix is a command-line tool which generates code for some common development tasks.

>> See [https://github.com/totten/civix/](https://github.com/totten/civix/)

>> For more information on the boilerplate civix generates for you, in particular the extension manifest file (info.xml), see the [Extension Reference](/extensions/index.md).

### 2. Develop
To get started with development, one should usually follow the steps in "[Create a Module Extension.](/extensions/civix.md#generate-module)". A module extension is the most flexible type of extension – it can define any mix of new reports, custom search screens, payment processors, and web pages; it can listen for hooks, override page-templates, and more. The coding
conventions closely resemble those of CiviCRM Core and of CiviCRM-Drupal modules. Module extensions are fully supported in CiviCRM 4.2+.

### 3. Publish
The CiviCRM ecosystem is built on the belief that non-profit organizations can serve themselves best by collaborating in development of their data-management applications. As staff, volunteers, and consultants for non-profit organizations, we can share our new enhancements and extensions -- and build a richer whole for the entire ecosystem.

Extension authors may make their extensions available to the larger CiviCRM community by publishing them in the [Extensions Directory](https://civicrm.org/extensions).

>> See: [Publish](/extensions/publish.md)

Extensions which undergo a [formal review](/extensions/lifecycle.md#formal-review) may be distributed in-app. Approved extensions can be [installed directly](https://docs.civicrm.org/user/en/master/introduction/extensions/#installing-extensions) into CiviCRM via the user interface, lowering the barrier to entry for many users.

>> See: [Automated Distribution](/extensions/publish.md#automated-distribution)

