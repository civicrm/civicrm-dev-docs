# Development requirements

## Languages and Services

* Required
    - Unix-like environment (Linux, OS X, or a virtual machine)
    - [PHP v7.0+](http://php.net/) including the following extensions: `bcmath curl gd gettext imap intl imagick json mbstring openssl pdo_mysql phar posix soap zip`
    - [MySQL v5.7.5+](http://mysql.com/) or [MariaDB 10.0.2+](https://mariadb.org/), including both client and server
    - [NodeJS v8+](https://nodejs.org/)
    - [Git](https://git-scm.com/)
* Recommended (for `civibuild` / `amp`):
    - Apache HTTPD v2.2 or v2.4 including the `mod_rewrite` module and, on SUSE, possibly `mod_access_compat` (This list may not be exhaustive.)

## Command Line

There are many ways to install MySQL, PHP, and other dependencies -- for example, `apt-get` and `yum` can download packages automatically; `php.net` and `mysql.com` provide standalone installers; and MAMP/XAMPP provide bundled installers.

Civi development should work with most packages -- with a priviso: ***the command-line must support standard command names*** (eg `git`, `php`, `node`, `mysql`, `mysqldump`, etc).

Some environments (e.g. most Linux distributions) are configured properly out-of-the-box. Other environments (e.g. MAMP and XAMPP) may require configuring the `PATH`.

<!-- FIXME: There should be a link about diagnosing/fixing paths for third-party binaries. TLDR: `find / -name php -executable` and then update `PATH` via bashrc/bash_profile/whatever -->

## Buildkit

The developer docs reference a large number of developer tools, such as `drush` (the Drupal command line), `civix` (the CiviCRM code-generator), and `karma` (the Javascript tester).

Many of these tools are commonly used by web developers, so you may have already installed a few.  You could install all the tools individually -- but that takes a lot of work.

[civicrm-buildkit](../tools/buildkit.md) provides a script which downloads the full collection.
