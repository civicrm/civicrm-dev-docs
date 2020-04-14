# Writing an installer

## Bootstrap

For a CMS integration (e.g. `civicrm-drupal` or `civicrm-wordpress`) which aims to incorporate an installer, you'll
first need to initialize the runtime and get a reference to the `$setup` object. The general steps are:

1. Bootstrap the CMS/host environment.
    * __Tip__: You may not need to do anything -- this is often implicitly handled by the host environment.
    * __Tip__: Initializing this first ensures that we can *automatically discover* information about the host environment.
2. Load the Civi class-loader(s).
    * __Ex__: If you use `civicrm` as a distinct sub-project (with its own `vendor` and autoloader), then you may need to load `CRM/Core/ClassLoader.php` and call `register()`.
    * __Ex__: If you use `composer` to manage the full site-build (with CMS+Civi+dependencies), then you may not need to take any steps.
3. Initialize the `\Civi\Setup` subsystem.
    * Call `\Civi\Setup::init($modelValues = [], $pluginCallback = NULL)`.
    * The `$modelValues` provide an opportunity to seed the configuration. This is usually just the `cms` and `srcPath`.
    * During initialization, additional `$modelValues` will be autodetected. After initialization, you can inspect or override these with `\Civi\Setup::instance()->getModel()`.
    * The `$pluginCallback` provides an opportunity to [add/remove/override plugins](plugins.md).
4. Get a reference to the `$setup` API.
    * Call `$setup = Civi\Setup::instance()`.
5. (Optional) Customize the model
    * __Ex__: During initialization, the auto-detection may use the CMS DB credentials for the Civi DB. If you'd prefer to use different credentials, then update `$setup->getModel()->db`.
    * __Tip__: For details, see the documentation in [Civi\Setup\Model](https://github.com/civicrm/civicrm-core/tree/master/setup/src/Setup/Model.php).

For example:

```php
<?php
$civicrmCore = '/path/to/civicrm';
require_once implode(DIRECTORY_SEPARATOR, [$civicrmCore, 'CRM', 'Core', 'ClassLoader.php']);
CRM_Core_ClassLoader::singleton()->register();
\Civi\Setup::assertProtocolCompatibility(1.0);
\Civi\Setup::init([
  'cms' => 'WordPress',
  'srcPath' => $civicrmCore,
]);
$setup = Civi\Setup::instance();
```

Once you have a copy of the `$setup` API, there are a few ways to work with it. For example, you might load
the pre-built web-based installer or perform a headless install.

## Web Installer API

The web installer provides a small, HTML-based GUI for performing Civi installation. It can be
embedded in other HTML GUIs. Instantiate and execute the controller:

```php
<?php
function myframework_page_ctrl() {
  // Create and execute the default setup controller.
  $ctrl = \Civi\Setup::instance()->createController()->getCtrl();
  $ctrl->setUrls(array(
    'ctrl' => 'url://for/the/install/controller',
    'res' => 'url://for/civicrm/setup/res',
    'jquery.js' => 'url://for/jquery.js',
    'font-awesome.css' ='url://for/font-awesome.css',
  ));
  \Civi\Setup\BasicRunner::run($ctrl);
}
```

The `BasicRunner::run()` executes the controller.  It uses PHP's standard, global I/O (e.g.  `$_POST` for input;
`header()` for headers; `echo` for output).

Some frameworks have their own I/O conventions which don't use PHP's globals.  You can integrate with these frameworks
by omitting the `BasicRunner`; instead, call the controller:

```php
<?php
list ($httpHeaders, $htmlBody) = $ctrl->run($_SERVER['REQUEST_METHOD'], $_POST);
```

Observe that the `run()` function needs some HTTP inputs (i.e.  the HTTP method plus any available post data) and
returns HTTP outputs (i.e.  headers and the HTML body). You will need to determine equivalents in your framework.

## General Installer API

Alternatively, you might build a custom UI or an automated installer. `$setup` provides a number of functions for this purpose:

* `getModel()`: Get a copy of the `Model`. You may want to tweak the model's data before performing installation.
* `checkAuthorized()`: Determine if the current user is authorized to perform an installation.
* `checkInstalled()`: Determine if CiviCRM is already installed.
* `checkRequirements()`: Determine if the local system meets the installation requirements.
* `installFiles()`: Create data files, such as `civicrm.settings.php` and `templates_c`.
* `installDatabase()`: Create database schema (tables, views, etc). Perform first bootstrap and configure the system.
* `uninstallDatabase()`: Purge database schema (tables, views, etc).
* `uninstallFiles()`: Purge data files, such as `civicrm.settings.php`.

The typical install algorithm would be:

```php
if (!$setup->checkAuthorized()->isAuthorized()) {
  exit("Sorry, you are not authorized to perform installation.");
}

$reqs = $setup->checkRequirements();
if ($reqs->getErrors()) {
  print_r($reqs->getErrors());
  exit("Cannot install. Please address the system requirements." );
}

$installed = $setup->checkInstalled();
if ($installed->isSettingInstalled() || $installed->isDatabaseInstalled()) {
  exit("Cannot install. CiviCRM has already been installed.");
}

$setup->installFiles();
$setup->installDatabase();
```
