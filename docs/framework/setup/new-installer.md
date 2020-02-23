# Writing an installer

For a CMS integration (e.g. `civicrm-drupal` or `civicrm-wordpress`) which aims to incorporate an installer, you'll
first need to initialize the runtime and get a reference to the `$setup` object. The general steps are:

* Bootstrap the CMS/host environment.
    * __Tip__: You may not need to do anything here -- this is often implicitly handled by the host environment.
    * __Tip__: Initializing this first ensures that we can *automatically discover* information about the host environment.
* (Optional) Check for `civicrm/.use-civicrm-setup`
    * If you're incrementally replacing the old installer with the new installer, you can check for a signal that allows the system-builder to toggle between the old/new installers. Specifically, in the `civicrm-core` folder, look for a file named `.use-civicrm-setup`.
    * If you're writing an installer for a new build/channel, then you probably don't need to worry about this.
* Load the class-loader.
    * __Ex__: If you use `civicrm` as a distinct sub-project (with its own `vendor` and autoloader), then you may need to load `CRM/Core/ClassLoader.php` and call `register()`.
    * __Ex__: If you use `composer` to manage the full site-build (with CMS+Civi+dependencies), then you may not need to take any steps.
* Initialize the `\Civi\Setup` subsystem.
    * Call `\Civi\Setup::init($modelValues = array(), $pluginCallback = NULL)`.
    * The `$modelValues` provides an opportunity to seed the configuration options, such as DB credentials and file-paths. See the fields defined for the [Model](src/Setup/Model.php).
    * The `$pluginCallback` (`function(array $files) => array $files`) provides an opportunity to add/remove/override plugin files.
    * __Tip__: During initialization, some values may be autodetected. After initialization, you can inspect or revise these with `Civi\Setup::instance()->getModel()`.
* Get a reference to the `$setup` API.
    * Call `$setup = Civi\Setup::instance()`.

For example:

```php
<?php
$civicrmCore = '/path/to/civicrm';
if (file_exists($civicrmCore . DIRECTORY_SEPARATOR . '.use-civicrm-setup')) {
  require_once implode(DIRECTORY_SEPARATOR, [$civicrmCore, 'CRM', 'Core', 'ClassLoader.php']);
  CRM_Core_ClassLoader::singleton()->register();
  \Civi\Setup::assertProtocolCompatibility(1.0);
  \Civi\Setup::init([
    'cms' => 'WordPress',
    'srcPath' => $civicrmCore,
  ]);
  $setup = Civi\Setup::instance();
}
```

Once you have a copy of the `$setup` API, there are a few ways to work with it. For example, you might load
the pre-built installation form:

```php
<?php
// Create and execute the default setup controller.
$ctrl = \Civi\Setup::instance()->createController()->getCtrl();
$ctrl->setUrls(array(
  'ctrl' => 'url://for/the/install/controller',
  'res' => 'url://for/civicrm-setup/res',
  'jquery.js' => 'url://for/jquery.js',
  'font-awesome.css' ='url://for/font-awesome.css',
));
\Civi\Setup\BasicRunner::run($ctrl);
```

The `BasicRunner::run()` function uses PHP's standard, global I/O (e.g. 
`$_POST` for input; `header()` for headers; `echo` for output). 
However, some frameworks have their own I/O conventions.  You can get more
direct control of I/O by calling the controller directly, e.g.:

```php
<?php
list ($httpHeaders, $htmlBody) = $ctrl->run($_SERVER['REQUEST_METHOD'], $_POST);
```

Alternatively, you might build a custom UI or an automated installer. `$setup` provides a number of functions:

* `$setup->getModel()`: Get a copy of the `Model`. You may want to tweak the model's data before performing installation.
* `$setup->checkAuthorized()`: Determine if the current user is authorized to perform an installation.
* `$setup->checkInstalled()`: Determine if CiviCRM is already installed.
* `$setup->checkRequirements()`: Determine if the local system meets the installation requirements.
* `$setup->installFiles()`: Create data files, such as `civicrm.settings.php` and `templates_c`.
* `$setup->installDatabase()`: Create database schema (tables, views, etc). Perform first bootstrap and configure the system.
* `$setup->uninstallDatabase()`: Purge database schema (tables, views, etc).
* `$setup->uninstallFiles()`: Purge data files, such as `civicrm.settings.php`.

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
