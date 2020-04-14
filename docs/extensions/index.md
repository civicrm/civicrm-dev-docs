# Writing Extensions

Todo:

- Use APIv3 and hooks. Package code in Civi extensions or CMS extensions (w/trade-offs).
- Create a CiviCRM extension developer's guide to best practices (Nicolas?)

## Introduction

**CiviCRM Extensions** are packaged pieces of functionality that extend CiviCRM's out-of-the-box functionality, independent of CMS plaform.

As of CiviCRM version 5.24.0 extensions are also used internally within CiviCRM to organise chunks of functionality that implement a specific feature. These extensions are part of the main CiviCRM repo and sit inside the ext folder. It is anticipated that over time more chunks of functionality will be moved into these core extensions, as part of our goal to make the code more readable and maintainable. Core extensions cannot be seen in the extensions UI and the structure is invisible to the end user.

There is not currently a clear guideline as to when a feature should be re-organised into a core extension but one useful guideline is whether it can be fully implemented using the extension framework. In some cases we expect to move code over to an extension over a period of time, as we disentangle the functionality from deeper in the main codebase. While it IS possible to disable a core extension through the API it is not currently a supported configuration and site builders should understand this is at their own risk.

This section covers how to write extensions.

## Extension Names

Pick a unique single word for your extension's name. Note that this name is used throughout the extension's code, e.g. function names, so it should only include characters that can be safely used in that way.

``` info
    Historically, extension names used to follow the same convention as Java package names – they look like reversed domain names. (e.g.  `com.example.myextension`), but this is no longer the recommendation.
```

## Pre-Requisites

-   Have basic knowledge of PHP, Unix, and object-oriented programming.
-   Install ***civix v14.01*** or newer. For instructions, see [Civix Documentation](civix.md). This page assumes that "civix" is installed and registered in the PATH.
-   Configure an extensions directory. For instructions, see [Extensions](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/#installing-a-new-extension). This page assumes the directory is `/var/www/extensions`, but you should adapt as appropriate. Your extensions directory must be under the CMS root directory so that civix can find and bootstrap the CMS. Otherwise, it will fail with an error like "Sorry, could not locate bootstrap.inc" on most operations.
-   The user account you use to develop the module must have permission to read all CMS files, including configuration files, and write to the extensions directory. For example, Debian's drupal7 package saves database configuration to `/etc/drupal/7/sites/default/dbconfig.php`, which is only readable by the www-data user. You will need to make this file readable by your development user account for civix to work.

### 0. Decide
Writing an extension is a great way to implement a new feature – but it may be unnecessary if someone else has already implemented that feature. If you're not sure, you can do a couple things:
- Search the [Extensions Directory](http://civicrm.org/extensions) for an existing extension.
- Post about your planned extension in the [Extensions Channel](https://chat.civicrm.org/civicrm/channels/extensions).

Extensions provide a native, portable way to extend CiviCRM, but there are other ways to extend CiviCRM – such as implementing Drupal modules or Joomla plugins. If you're considering another way, look at the [Add-on Formats](packaging.md)
to help decide.

### 1. Install civix
Some tasks in the process of writing an extension require boilerplate code. To reduce the amount of work required to find, understand, and adapt the boilerplate code, one should install the CiviCRM extension builder, civix. Civix is a command-line tool which generates code for some common development tasks.

> See [https://github.com/totten/civix/](https://github.com/totten/civix/)

> For more information on the boilerplate civix generates for you, in particular the extension manifest file (info.xml), [read on](info-xml.md).

### 2. Develop
To get started with development, one should usually follow the steps in "[Create a Module Extension.](civix.md#generate-module)". A module extension is the most flexible type of extension – it can define any mix of new reports, custom search screens, payment processors, and web pages; it can listen for hooks, override page-templates, and more. The coding
conventions closely resemble those of CiviCRM Core and of CiviCRM-Drupal modules. Module extensions are fully supported in CiviCRM 4.2+.

### 3. Publish
The CiviCRM ecosystem is built on the belief that non-profit organizations can serve themselves best by collaborating in development of their data-management applications. As staff, volunteers, and consultants for non-profit organizations, we can share our new enhancements and extensions -- and build a richer whole for the entire ecosystem.

Extension authors may make their extensions available to the larger CiviCRM community by publishing them in the [Extensions Directory](https://civicrm.org/extensions).

> See: [Publish](publish.md)

Extensions which undergo a [formal review](lifecycle.md#formal-review) may be distributed in-app. Approved extensions can be [installed directly](https://docs.civicrm.org/user/en/master/introduction/extensions/#installing-extensions) into CiviCRM via the user interface, lowering the barrier to entry for many users.

> See: [Automated Distribution](publish.md#automated-distribution)

## Interacting with core

There are a number of ways in which extensions can interact with core. These are supported to greater or lesser degrees.

Fully supported methods are:

* [API](../api/index.md)
* [Hooks](../hooks/index.md)
* [`Civi::cache`](../framework/cache.md)
* `Civi::$statics`
* [`Civi::settings`](../framework/setting.md)
* Internal Symfony listeners (e.g. `civi.api.resolve`, `civi.api.prepare`)

See also this [blog post](https://civicrm.org/blog/totten/the-static-is-dead-long-live-the-static) describing the `Civi::` facade in more detail.
