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

### Codebase: Drupal and Backdrop

The CiviCRM module is typically found in the `sites/all/modules/civicrm`
directory.  As with any Drupal module, it is possible to put CiviCRM in
several alternative folders, such as:

* `sites/example.com/modules/civicrm`
* `sites/default/modules/civicrm`
* `sites/all/modules/civicrm` (most common)
* `modules/civicrm`

Within the `civicrm` folder, there will be a
[`drupal/`](https://github.com/civicrm/civicrm-drupal/) or
[`backdrop/`](https://github.com/civicrm/civicrm-backdrop/) subfolder which
contains the `civicrm.module` along with the role sync modules, blocks, and
drush and views integration.

### Codebase: Joomla

CiviCRM's codebase exists in *two* places within Joomla:

* A front-end component at `components/com_civicrm`
* A back-end component at `administrator/components/com_civicrm`

The back-end component contains the common CiviCRM code, in
`administrator/components/com_civicrm/civicrm`.  The
[civicrm-joomla](https://github.com/civicrm/civicrm-joomla/) repository contains
a `site` directory for the front-end files and an `admin` directory for the
back-end files.

### Codebase: WordPress

The CiviCRM plugin is found in `wp-content/plugins/civicrm`.  This corresponds
to the [civicrm-wordpress](https://github.com/civicrm/civicrm-wordpress/)
repository, containing the plugin file as well as WP-CLI integration.  The
common CiviCRM codebase is found at `wp-content/plugins/civicrm/civicrm`.

### Tip: Programatically using [civicrm.root]

If you are writing an extension or integration that needs to reference the
codebase of an existing installation, use a command to lookup the correct
value.  In `bash`, call the [`cv`](https://github.com/civicrm/cv)
command-line tool:

``` sh
cv path -d '[civicrm.root]'
cv path -d '[civicrm.root]/README.md'
cv url -d '[civicrm.root]'
cv url -d '[civicrm.root]/README.md'
```

Or in PHP, use `Civi::paths()`:

``` php
echo Civi::paths()->getPath("[civicrm.root]/.");
echo Civi::paths()->getPath("[civicrm.root]/README.md");
echo Civi::paths()->getUrl("[civicrm.root]/.");
echo Civi::paths()->getUrl("[civicrm.root]/README.md");
```

## Codebase: Dependencies

The CiviCRM codebase includes some third-party libraries. These are pulled
into three folders:

* `vendor`__ is collection of PHP libraries which are automatically downloaded
  by [`composer`](http://getcomposer.org/) based on the `composer.json` configuration.
* `bower_components`__ is a collection of JS/CSS libraries which are automatically
  downloaded by [`bower`](https://bower.io/) based on the `bower.json` configuration.
* `packages`__ is a manually curated collection of library files.
  It's maintained in [`civicrm-packages.git`](https://github.com/civicrm/civicrm-packages/).

!!! note "When adding a new dependency to core, use `composer.json` or `bower.json`."

### Tip: Programmatically using Civi::paths()

Traditionally, one would use the root folder of `civicrm-core` to determine the path
or URL of a library, as in:

``` php
global $civicrm_root;
echo "$civircm_root/packages/IDS/default_filter.xml";
```

This arrangement works, but it assumes that all three folders are immediate
descendents of the `civicrm-core` folder -- which may not hold in the
future.

In CiviCRM v4.7.21+, one should use `Civi::paths()` to lookup the path or
URL for a library:

``` php
echo Civi::paths()->getPath('[civicrm.vendor]/dompdf/dompdf/README.md');
echo Civi::paths()->getUrl('[civicrm.vendor]/dompdf/dompdf/README.md');

echo Civi::paths()->getPath('[civicrm.bower]/jquery/dist/jquery.min.js');
echo Civi::paths()->getUrl('[civicrm.bower]/jquery/dist/jquery.min.js');

echo Civi::paths()->getPath('[civicrm.packages]/IDS/default_filter.xml');
echo Civi::paths()->getUrl('[civicrm.packages]/IDS/default_filter.xml');
```

Additionally, to register JS/CSS resources in `Civi::resources()`, you can use
a shorthand with the path variables, as in:

``` php
echo Civi::resources()->addScriptFile('civicrm.bower', 'jquery/dist/jquery.min.js');
```

## Local Data Files

CiviCRM also needs directories for storing volatile data files, such as
logs, caches, and uploads. These directories are located outside
the main codebase, in a location that can be safely preserved during
upgrades.

CiviCRM provides two main file storage helpers:

* `[civicrm.files]` - Intended to store files which can safely live within the files directory of your CMS, within your webroot.
* `[civicrm.private]` - Intended to store files which could be stored outside of your webroot for enhanced security.

The actual path is chosen to align with the conventions of each CMS and typically [civicrm.private] will refer to the same directory as [civicrm.files] while providing the ability to manage paths in a more granular fashion should the need arise.

### Local Data Files: Drupal and Backdrop

CiviCRM stores its files in a folder named `civicrm` within the Drupal
`files` directory.  This is commonly `sites/default/files/civicrm`, though
it could be `files/civicrm`, `sites/example.org/files/civicrm`, or another
folder determined by the system administrator.

### Local Data Files: Joomla

The CiviCRM local files are within the `media/civicrm` directory.

### Local Data Files: WordPress

Newly-installed CiviCRM sites on WordPress have their local files at
`wp-content/uploads/civicrm`.  Many older sites use the previous default:
`wp-content/plugins/files/civicrm`.

### Tip: Sub-directories

The `[civicrm.files]` and `[civicrm.private]` are CiviCRM's base directories for files storage, each will contain several sub-directories.
These sub-directories will include:

| Code Name             | Typical Path                                                   | Recommended Access Level                        | Comments                                                                                                                                   |
| --------------------- | -------------------------------------------------------------- | ----------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------ |
| `configAndLogDir`     | `[civicrm.log]` defaults to `[civicrm.private]/ConfigAndLog`   | Prohibit all web access                         | Stores log files. Writes and reads should be infrequent, unless there are errors/warnings.                                                 |
| `customFileUploadDir` | `[civicrm.files]/custom`                                       | Prohibit all web access                         | Stores sensitive uploads (such as custom-field attachments). Writes and reads vary widely (depending on use-case/config).                  |
| `extensionsDir`       | `[civicrm.files]/ext`                                          | Allow file reads but prohibit directory listing | Stores downloaded extensions. Writes should be infrequent. Reads are very frequent. In hardened systems, this may be readonly or disabled. |
| `imageUploadDir`      | `[civicrm.files]/persist/contribute`                           | Allow file reads but prohibit directory listing | Stores uploaded or autogenerated media (images/css/etc). Writes and reads vary widely (depending on use-case/config).                      |
| `l10nDir`             | `[civicrm.l10n]` defaults to `[civicrm.private]/l10n`          | Prohibit all web access                         | Stores CiviCRM's localisation files. Writes should be infrequent (generally on Update or Extension Install). Reads are very frequent.      |
| `templateCompileDir`  | `[civicrm.compile]` defaults to `[civicrm.private]/templates_c`| Prohibit all web access                         | Stores autogenerated PHP files. Writes should be infrequent. Reads are very frequent.                                                      |
| `uploadDir`           | `[civicrm.files]/upload`                                       | Prohibit all web access                         | Temporary files. Writes and reads vary widely (depending on use-case/config).                                                              |

!!! tip "Advanced filesystem and web server configurations"
    Each folder has different requirements (with respect to permissions,
    read-write frequency, etc).  Most Apache-based web servers respect the `.htaccess`
    and `index.html` files, and these are configured automatically.
    However, some web-servers don't support these, so CiviCRM's
    status-check shows warnings if it detects unexpected configuration.

### Configuration options for paths

For the time being,`CIVICRM_TEMPLATE_COMPILEDIR` can be set in `civicrm.settings.php` and file paths will work as before. Alternatively, one may remove `CIVICRM_TEMPLATE_COMPILEDIR` and instead, set all paths using `$civicrm_paths`. With the following examples, the reasons for this setup should be more intuitive.

#### Example 1: Minimalist: all public and private data go to the same folder, sites/default/files/civicrm

``` php
$civicrm_paths['civicrm.files']['path'] = '/srv/example.com/htdocs/sites/default/files/civicrm';
$civicrm_paths['civicrm.private']['path'] = '/srv/example.com/htdocs/sites/default/files/civicrm';
```

The constant `CIVICRM_TEMPLATE_COMPILEDIR` is never consulted. Updating `civicrm.files` changes only public data folders. Updating `civicrm.private` changes only private data folders (e.g. `templateCompileDir` and `ConfigAndLogDir`).

#### Example 2: The public and private data are stored in separate places, akin to Drupal's public and private folders

``` php
$civicrm_paths['civicrm.files']['path'] = '/srv/example.com/htdocs/sites/default/files/civicrm');
$civicrm_paths['civicrm.private']['path'] = '/srv/example.com/private/civicrm');
```

#### Example 3: Try to follow Linux FHS and override most default paths

``` php
$civicrm_paths['civicrm.files']['path'] = '/var/www/sites/default/files/civicrm');
$civicrm_paths['civicrm.private']['path'] = '/var/lib/civicrm');
$civicrm_paths['civicrm.log']['path'] = '/var/log/civicrm');
$civicrm_paths['civicrm.l10n']['path'] = '/var/lib/civicrm/l10n');
$civicrm_paths['civicrm.compile']['path'] = '/var/run/civicrm-compile');
$civicrm_paths['civicrm.root']['path'] = '/usr/share/php/civicrm-core');
$civicrm_paths['civicrm.root']['url'] = 'https://example.com/civicrm-core'); // with httpd path alias
$civicrm_paths['cms.root']['path'] = '/usr/share/php/drupal-core');
$civicrm_paths['cms.root']['url'] = 'https://example.com/'); // with httpd path alias
```

### Tip: Programmatically using [civicrm.files]

If you are writing an extension or integration that needs to reference the
data files of an existing installation, use a command to lookup the correct
value.  In `bash`, call the [`cv`](https://github.com/civicrm/cv)
command-line tool:

``` sh
cv path -d'[civicrm.files]'
cv path -d'[civicrm.files]/upload'
cv url -d'[civicrm.files]'
cv url -d'[civicrm.files]/upload'
```

Or in PHP, use `Civi::paths()`:

```php
echo Civi::paths()->getPath("[civicrm.files]/.");
echo Civi::paths()->getPath("[civicrm.files]/upload");
echo Civi::paths()->getUrl("[civicrm.files]/.");
echo Civi::paths()->getUrl("[civicrm.files]/upload");
```

Additionally, some items -- such as the log folder or cache folder -- are
configurable. The most correct way to find these is to read a config
variable. In `bash`:

``` sh
cv path -c configAndLogDir
cv path -c templateCompileDir
cv path -c templateCompileDir/en_US
```

Or in PHP:

``` php
echo CRM_Core_Config::singleton()->configAndLogDir;
echo CRM_Core_Config::singleton()->templateCompileDir;
echo CRM_Core_Config::singleton()->templateCompileDir . '/en_US';
```

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

* front-end at `components/com_civicrm/civicrm.settings.php`
* back-end at `administrator/components/com_civicrm/civicrm.settings.php`

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

### Tip: Programmatically using CIVICRM_SETTINGS_PATH

If you are writing an extension or integration that needs to reference the
settings of an existing installation, use the constant
`CIVICRM_SETTINGS_PATH` to locate `civicrm.settings.php`. In `bash`:

``` sh
cv ev 'echo CIVICRM_SETTINGS_PATH;'
```

Or in PHP:

``` php
echo CIVICRM_SETTINGS_PATH;
```
