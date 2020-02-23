# Writing a plugin

A plugin is a PHP file with these characteristics:

* The file's name ends in `*.civi-setup.php`. (Plugins in `civicrm-setup/plugins/*.civi-setup.php` are autodetected.)
* The file has a guard at the top (`defined('CIVI_SETUP')`). If this constant is missing, then bail out. In case the file becomes accessible on the web, this prevents direct execution.
* The file's logic locates the event-dispatcher and registers listeners, e.g. `\Civi\Setup::disptacher()->addListener($eventName, $callback)`.

For example, here is a basic plugin that logs a message during database installation:

```php
<?php
if (!defined('CIVI_SETUP')) {
  exit("Installation plugins must only be loaded by the installer.\n");
}

\Civi\Setup::dispatcher()
  ->addListener('civi.setup.installDatabase', function (\Civi\Setup\Event\InstallDatabaseEvent $event) {
    \Civi\Setup::log()->info("I like to run the plugin during installation.");
  });
```

Observe that the primary way for a plugin to interact with the system is to register for events (using Symfony's
`EventDispatcher`). Most methods in the `Civi\Setup` API have a corresponding event name and event class:

* `\Civi\Setup::init()` => `civi.setup.init` => `Civi\Setup\Event\InitEvent`
* `\Civi\Setup::checkAuthorized()` => `civi.setup.checkAuthorized` => `Civi\Setup\Event\CheckAuthorizedEvent`
* `\Civi\Setup::checkInstalled()` => `civi.setup.checkInstalled` => `Civi\Setup\Event\CheckInstalledEvent`
* `\Civi\Setup::checkRequirements()` => `civi.setup.checkRequirements` => `Civi\Setup\Event\CheckRequirementsEvent`
* `\Civi\Setup::installFiles()` => `civi.setup.installFiles` => `Civi\Setup\Event\InstallFilesEvent`
* `\Civi\Setup::installDatabase()` => `civi.setup.installDatabase` => `Civi\Setup\Event\InstallDatabaseEvent`
* `\Civi\Setup::uninstallFiles()` => `civi.setup.uninstallFiles` => `Civi\Setup\Event\UninstallFilesEvent`
* `\Civi\Setup::uninstallDatabase()` => `civi.setup.uninstallDatabase` => `Civi\Setup\Event\UninstallDatabaseEvent`

For events related to the built-in web UI, the names are slightly different:

* `\Civi\Setup::createController()` => `civi.setupui.construct` => `Civi\Setup\UI\Event\UIConstructEvent`
* `\Civi\Setup\UI\SetupController::run()` => `civi.setupui.run` => `Civi\Setup\UI\Event\UIRunEvent`
* `\Civi\Setup\UI\SetupController::boot()` => `civi.setupui.boot` => `Civi\Setup\UI\Event\UIBootEvent`

All events provide access to the setup data-model.

> __Ex__: To get the path to the `civicrm.settings.php` file, read `$event->getModel()->settingsPath`.

Some events provide additional methods for relaying additional information. For example:

* For `civi.setup.checkRequirements`, use `$event->addError(...)` to record an error that prevents installation.  Similarly, use
  `addWarning(...)` and `addInfo(...)` to report less critical issues.
* For `civi.setup.checkAuthorized`, use `$event->setAuthorized(bool $authorized)` to indicate whether authorization is permitted,
  and use `$event->isAuthorized()` to see if authorization has been permitted.
* For `civi.setupui.run`, the list of HTTP inputs is available as `$event->getFields()`.

## What's in a file name?

The `plugins/` folder is *loosely* organized based on how the plugin fits into
the system.  Let's take a few example files (at time of writing):

```
plugins/checkRequirements/CheckBaseUrl.civi-setup.php
plugins/checkRequirements/CheckDbWellFormed.civi-setup.php
plugins/common/LogEvents.civi-setup.php
plugins/installDatabase/InstallExtensions.civi-setup.php
plugins/installDatabase/InstallSchema.civi-setup.php
plugins/installDatabase/InstallSettings.civi-setup.php
```

Notice a pattern?

* The files under `plugins/checkRequirements` are all plugins which listen to the `civi.setup.checkRequirements` event.
* The files under `plugins/installDatabase` are all plugins which listen to the `civi.setup.installDatabase` event.

Most plugins only handle one event, so it's a convenient way to organize them.  However, this is just a
*convention*.  It's entirely legitimate for a plugin to listen to multiple events. For example:

* `common/LogEvents.civi-setup.php` listens to many events.
* `blocks/*` define visible blocks for the built-in web UI. Many of these listen to 2-3 events.

Tips:

* If you write a new plugin, should it handle one event or multiple? Do whatever is best (on the whole) for improving the concept/coupling/cohesion. This *usually* means writing a small/narrow plugin, but it doesn't necessarily.
* Browsing the folders provides a high-level skim. Try to respect this in framing new plugins, but don't assume that it's perfect. For detailed inspection or debugging, use `cv core:install --debug-event`.
