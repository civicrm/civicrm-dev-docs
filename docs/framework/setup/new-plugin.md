# Basics

A `setup` plugin is a PHP file with these characteristics:

* The file's name ends in `*.civi-setup.php`.
* The file has a guard at the top (`if (!defined('CIVI_SETUP'))...`).
* The file defines events-listeners (`\Civi\Setup::disptacher()->addListener($eventName, $callback)`).

Plugins are conventionally autoloaded from `civicrm/setup/plugins/**.civi-setup.php` (although some installers may [load custom plugins](plugins.md)).

For example, let's create a plugin that logs a message during database installation:

```php
<?php
// FILE: civicrm/setup/plugins/installDatabase/MySpecialLogger.civi-setup.php
use Civi\Setup\Event\InstallDatabaseEvent;

if (!defined('CIVI_SETUP')) {
  exit("Installation plugins must only be loaded by the installer.\n");
}

\Civi\Setup::dispatcher()->addListener(
  'civi.setup.installDatabase',
  function (InstallDatabaseEvent $event) {
    \Civi\Setup::log()->info("I like to run the plugin during installation.");
  }
);
```

All events provide access to the setup data-model. For example, this will
provide the path to `civicrm.settings.php`:

```php
$event->getModel()->settingsPath
```

Observe that the primary way for a plugin to interact with the system is to register for events (using 
[Symfony's EventDispatcher](https://symfony.com/doc/3.4/components/event_dispatcher.html)). The events
determine when the plugin runs and what data it can access.

## Events: General installation

Most methods in the `Civi\Setup` API have a corresponding event name (`civi.setup.{myEvent}`) and event class (`Civi\Setup\Event\MyEvent`).
Most plugins will register for one of these events:

| \Civi\Setup Method | Event Name | Event Class |
| -- | -- | -- |
| `init()` | `civi.setup.init` | `InitEvent` |
| `checkAuthorized()` | `civi.setup.checkAuthorized` | `CheckAuthorizedEvent` |
| `checkInstalled()` | `civi.setup.checkInstalled` | `CheckInstalledEvent` |
| `checkRequirements()` | `civi.setup.checkRequirements` | `CheckRequirementsEvent` |
| `installFiles()` | `civi.setup.installFiles` | `InstallFilesEvent` |
| `installDatabase()` | `civi.setup.installDatabase` | `InstallDatabaseEvent` |
| `uninstallFiles` | `civi.setup.uninstallFiles` | `UninstallFilesEvent` |
| `uninstallDatabase` | `civi.setup.uninstallDatabase` | `UninstallDatabaseEvent` |

Some events provide additional methods and properties. For example:

* For `civi.setup.checkRequirements`, use `$event->addError(...)` to record an error that prevents installation.  Similarly, use
  `addWarning(...)` and `addInfo(...)` to report less critical issues.
* For `civi.setup.checkAuthorized`, use `$event->setAuthorized(bool $authorized)` to indicate whether authorization is permitted,
  and use `$event->isAuthorized()` to see if authorization has been permitted.

## Events: Web installation

For methods related to the built-in web UI, the event-names and event-classes live in a
different namespace (`civi.setupui.{myEvent}` and `Civi\Setup\UI\Event`, respectively).

| Method | Event Name | Event Class |
| -- | -- | -- |
| `\Civi\Setup::createController()` | `civi.setupui.construct` | `Civi\Setup\UI\Event\UIConstructEvent` |
| `\Civi\Setup\UI\SetupController::run()` | `civi.setupui.run` | `Civi\Setup\UI\Event\UIRunEvent` |
| `\Civi\Setup\UI\SetupController::boot()` | `civi.setupui.boot` | `Civi\Setup\UI\Event\UIBootEvent` |

Some events provide additional methods and properties. For example:

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

!!! faq "Should a plugin only handle one event?"

    If you write a new plugin, should it handle one event or multiple? Do whatever is best (on the whole) for improving the concept/coupling/cohesion.
    This *usually* means writing a small/narrow plugin, but it doesn't necessarily.

!!! tip "Browsing for high-level overview"

    Browse the plugin folders for a high-level skim of the plugins. Try to respect this in framing new plugins, but don't assume that it's perfect. For detailed inspection or debugging, use `cv core:install --debug-event`.
