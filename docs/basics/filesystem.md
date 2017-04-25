# File system

CiviCRM installs within a content-management system (CMS), and each CMS has a
different file structure. Never-the-less, the general concepts are the same:
one directory contains the CiviCRM **codebase**, another directory
contains **local data files**, and a third contains the CiviCRM **settings file**.

## Codebase

The codebase consists of the common CiviCRM code (found in the
[civicrm-core](https://github.com/civicrm/civicrm-core/) and
[civicrm-packages](https://github.com/civicrm/civicrm-packages/) repositories)
along with the code to integrate with the CMS.  Obviously, Drupal and Backdrop
modules, Joomla components, and WordPress plugins have different structures, so
the [civicrm-drupal](https://github.com/civicrm/civicrm-drupal/),
[civicrm-backdrop](https://github.com/civicrm/civicrm-backdrop/),
[civicrm-joomla](https://github.com/civicrm/civicrm-joomla/), and
[civicrm-wordpress](https://github.com/civicrm/civicrm-wordpress/) repositories
contain the code connecting CiviCRM to the CMS along with some CMS-specific
features.

### Drupal and Backdrop

The CiviCRM module is typically found in the `sites/all/modules/civicrm`
directory.  As with any Drupal module, it is possible to put CiviCRM in
several alternative folders, such as:

 * `sites/example.com/modules/civicrm`
 * `sites/default/modules/civicrm`
 * `sites/all/modules/civicrm` (most common)
 * `modules/civicrm`

Within the `civicrm` folder, there will be a
[`drupal/`]((https://github.com/civicrm/civicrm-drupal/) or
[`backdrop/`](https://github.com/civicrm/civicrm-backdrop/) subfolder which
contains the `civicrm.module` along with the role sync modules, blocks, and
drush and views integration.

### Joomla

CiviCRM's codebase exists in *two* places within Joomla:

 -  A front-end component at `components/com_civicrm`
 -  A back-end component at `administrator/components/com_civicrm`

The back-end component contains the common CiviCRM code, in
`administrator/components/com_civicrm/civicrm`.  The
[civicrm-joomla](https://github.com/civicrm/civicrm-joomla/) repository contains
a `site` directory for the front-end files and an `admin` directory for the
back-end files.

### WordPress

The CiviCRM plugin is found in `wp-content/plugins/civicrm`.  This corresponds
to the [civicrm-wordpress](https://github.com/civicrm/civicrm-wordpress/)
repository, containing the plugin file as well as WP-CLI integration.  The
common CiviCRM codebase is found at `wp-content/plugins/civicrm/civicrm`.

## Local data files

CiviCRM also needs a files directory for storing a variety of site-specific
files, including uploaded files, logs, and the template cache.  This directory
is located away from the codebase in a location that is unlikely to be
overwritten during upgrades.

### Drupal and Backdrop

CiviCRM stores its files in a folder named `civicrm` within the site-specific
files directory.  This is commonly `sites/default/files/civicrm`, though it
could be `files/civicrm`, `sites/example.org/files/civicrm`, or another
folder determined by the system administrator.

### Joomla

The CiviCRM local files are within the `media/civicrm` directory.

### WordPress

Newly-installed CiviCRM sites on WordPress have their local files at
`wp-content/uploads/civicrm`.  Many older sites use the previous default:
`wp-content/plugins/files/civicrm`.

## Settings file

CiviCRM's database connection, base URL, site key, CMS, and file paths are defined in `civicrm.settings.php`.

### Drupal and Backdrop

The `civicrm.settings.php` file will be a sibling of Drupal's `settings.php`,
commonly at `sites/default/civicrm.settings.php`, or
`sites/example.org/civicrm.settings.php` in multi-site.

In Backdrop, the `civicrm.settings.php` is often located in the site root.

### Joomla

There are two instances of `civicrm.settings.php` in Joomla, within each of the
components:

 -  front-end at `components/com_civicrm/civicrm.settings.php`
 -  back-end at `administrator/components/com_civicrm/civicrm.settings.php`

The files are *nearly* identical.  The one difference is that the front-end file
has the site's normal base URL, while the back-end file has `/administrator/` on
the end, pointing to the back-end of the site.

### WordPress

Newly-installed CiviCRM sites on WordPress have the settings file at
`wp-content/uploads/civicrm/civicrm.settings.php`.  Many older sites, however,
put the settings file within the CiviCRM plugin folder at
`wp-content/plugins/civicrm/civicrm.settings.php`.  This latter location can be
dangerous when upgrading: it is important in this case to keep the `civicrm`
folder until the upgrade is complete and the site is verified to be working
properly.
