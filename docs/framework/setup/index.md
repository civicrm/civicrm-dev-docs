!!! note "Status of `install` and `setup` subsystems"

    The CiviCRM `setup` subsystem is a refactored/rewritten version of the older `install` subsystem.
    There is a Gitlab issue for [tracking the migration from `install` to `setup`](https://lab.civicrm.org/dev/core/issues/1615).

The CiviCRM `setup` subsystem facilitates installation and configuration.  It aims to support multiple installers, such
as a generic CLI (`cv core:install` and `cv core:uninstall`), a generic web-based installer, and specialized/scripted installers
for different environments and use-cases.

Key features:

* The subsystem can be used by other projects -- such as `cv`, `civicrm-drupal`, `civicrm-wordpress` -- to provide an installation process.
* It is a *leap*. It can coexist with the old installer.
    * _Example_: The `civicrm-wordpress` integration is phasing-in support for the new installer. By default, it uses the old installer. If you create a file `civicrm/.use-civicrm-setup`, then it will use the new installer.
* It has minimal external dependencies. (The codebase for CiviCRM and its dependencies must be available -- but nothing else is needed.)

General design:

* Installers call a high-level API ([Civi\Setup](https://github.com/civicrm/civicrm-core/tree/master/setup/src/Setup.php)) which supports all major installation tasks/activities -- such as:
    * Check system requirements (`$setup->checkRequirements()`)
    * Check installation status (`$setup->checkInstalled()`)
    * Install data files (`$setup->installFiles()`)
    * Install database (`$setup->installDatabase()`)
* A *data-model* ([Civi\Setup\Model](https://github.com/civicrm/civicrm-core/tree/master/setup/src/Setup/Model.php)) lists all the standard configuration parameters. This data-model is available when executing each task. For example, it includes:
    * The path to CiviCRM's code (`$model->srcPath`)
    * The system language (`$model->lang`)
    * The DB credentials (`$model->db`)
* Each major task corresponds to an [*event*](https://github.com/civicrm/civicrm-core/tree/master/setup/src/Setup/Event) -- such as:
    * `civi.setup.checkRequirements`
    * `civi.setup.checkInstalled`
    * `civi.setup.installFiles`
    * `civi.setup.installDatabase`
* *Plugins* (`plugins/*/*.civi-setup.php`) work with the model and the events. For example:
    * The plugin `init/WordPress.civi-setup.php` runs during initialization (`civi.setup.init`). It reads the WordPress config (e.g.`get_locale()` and `DB_HOST`) then updates the model (`$model->lang` and `$model->db`).
    * The plugin `installDatabase/SetLanguage.civi-setup.php` runs when installing the database (`civi.setup.installDatabase`). It reads the `$model->lang` and updates various Civi settings.

