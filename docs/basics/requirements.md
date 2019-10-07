# Development requirements

## Languages and Services

-   Unix-like environment (Linux, OS X, or a virtual machine)
-   [PHP v5.3+](http://php.net/)
-   [MySQL v5.1+](http://mysql.com/)
-   [NodeJS](https://nodejs.org/)
-   [Git](https://git-scm.com/)
-   Recommended: Apache HTTPD v2.2+
-   Recommended: Ruby/Rake

## Command Line

There are many ways to install MySQL, PHP, and other dependencies -- for example, `apt-get` and `yum` can download packages automatically; `php.net` and `mysql.com` provide standalone installers; and MAMP/XAMPP provide bundled installers.

Civi development should work with most packages -- but there's one proviso: ***the command-line must support standard commands***  (`php`, `mysql`, `node`, `git`, `bash`, etc).

Some packages are configured properly out-of-the-box. (Linux distributions do a pretty good job of this.) Other packages require extra configuration steps.

In subsequent steps, the download script will attempt to identify misconfigurations and display an appropriate message.

## Buildkit

The developer docs reference a large number of developer tools, such as `drush` (the Drupal command line), `civix` (the CiviCRM code-generator), and `karma` (the Javascript tester).

Many of these tools are commonly used by web developers, so you may have already installed a few.  You could install all the tools individually -- but that takes a lot of work.

[civicrm-buildkit](/tools/buildkit.md) provides a script which downloads the full collection.
