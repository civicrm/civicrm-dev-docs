# CiviCRM Bootstrap Process

## Definition

Bootstrapping CiviCRM means loading the entire CiviCRM configuration files, loading in all CiviCRM's classes and include-paths. In essence to prepare code to utilise all of CiviCRM's functionality. This must be done on every server/page request before accessing CiviCRM APIs or classes.

## When is this needed

If you are writing a CiviCRM extension or for CiviCRM Core, this is handled automatically and you do not need to worry about bootstrapping CiviCRM. The only exception is the `extern` folder.

If you are writing code (for example a `Drupal module`, `Joomla Extension` or any other PHP script), you will need to bootstrap CiviCRM before accessing any of CiviCRM's functionality including CiviCRM's API. If you are developing an independent PHP script you should also ensure that the CMS is bootstrapped before bootstrapping CiviCRM. See the documentation of your CMS for details.

## Bootstrap Sequence (v4.7+)

1. civicrm.settings.php - Reads core system settings from the configuration file, civicrm.settings.php
1. ClassLoader - Initalises the PHP Class-Loader this ensures all of CiviCRM's classes are loaded into PHP
1. Boot Services - Instantiate CiviCRM's pre-requisites that must be vailable efore the CiviCRM Container may load
  - Runtime: parase config data from define()s $_SERVER etc
  - Database: (if $dsn) Open a connection to the MySQL database
  - Paths: Register all menu and associated paths
  - User System: Load all relevant drivers for CMS associated with the CiviCRM install
  - Settings: Load all the settings from the Database civicrm_setting table
  - Extensions: (if $dsn) - Load all Extensions enabled
  - Container: (if $dsn) Load container configuration (e.g. hook_civicrm_container)
4. hook_civicrm_config - Notify Extensions that the container has been booted (if $dsn)

## How to Bootstrap CiviCRM

### Independent scripts

If you are writting an independent script you can download the [cv](https://github.com/civicrm/cv) helper command. Call it and evaluate the output e.g.

```php
// Perform bootstrap
eval(`cv php:boot`);

// Call CiviCRM function e.g. API
$contact = civicrm_api3('contact', 'get', array(
  'first_name' => 'Mr',
  'last_name' => 'T',
));
```
The cv helper will scan the current directory to locate the CMS and CiviCRM and bootstrap both. 

### Drupal, Backdrop and WordPress

If you are writing a module in Drupal, Backdrop or WordPress, use the "civicrm_initialize();" function to bootstrap CiviCRM

```php
// Perform bootstrap
civicrm_initialize();

// Call CiviCRM function e.g. API
$contact = civicrm_api3('contact', 'get', array(
  'first_name' => 'Mr',
  'last_name' => 'T',
));
```

### Joomla

```php
// Perform bootstrap
define('CIVICRM_SETTINGS_PATH', JPATH_ADMINISTRATOR . '/components/com_civicrm/civicrm.settings.php');
define('CIVICRM_CORE_PATH', JPATH_ADMINISTRATOR . '/components/com_civicrm/civicrm/');
require_once CIVICRM_SETTINGS_PATH;
require_once CIVICRM_CORE_PATH .'CRM/Core/Config.php';
$config = CRM_Core_Config::singleton();

// Call CiviCRM function e.g. API
$contact = civicrm_api3('contact', 'get', array(
  'first_name' => 'Mr',
  'last_name' => 'T',
));
```

### Bin, Extern and CLI scripts

CiviCRM Core includes a handful of helper scripts which boot with the following pattern. The pattern is not generally recommended for third-party scripts.

```php
// Perform bootstrap
require_once '/path/to/civicrm/civicrm.config.php';
require_once 'CRM/Core/Config.php';
$config = CRM_Core_Config::singleton();
 
// Optional: Bootstrap the CMS
// CRM_Utils_System::loadBootStrap(array(), FALSE);

// Call CiviCRM function e.g. API
$contact = civicrm_api3('contact', 'get', array(
  'first_name' => 'Mr',
  'last_name' => 'T',
));
```
