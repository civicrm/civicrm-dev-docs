# Getting started

Whether your goal is to improve the `setup` subsystem, write a new
installer, or write a new plugin, it will help to start out by running the
command-line installer. This can help you inspect and experiment.

## Create a CMS+Civi build

You'll need a copy of WordPress, Drupal, or another Civi-supported CMS.
Create this by whatever you means you prefer, e.g.

 * `drush dl ... drush site-install ...`
 * `wp core download ... wp core install ...`
 * `civibuild create ...`

The build should include a copy of the CiviCRM source-code in a standard
location (e.g. `sites/all/modules/civicrm` or `wp-content/plugins/civicrm`).
If necessary, download the tarball/zipball or clone the git repos.

!!! tip "Using the uninstaller"

    If you used `civibuild` to create a full site with Civi+CMS, then it does
    everything needed... and little more. We want a build where the
    configuration files and database tables don't exist yet.
    Use `cv core:uninstall` to rollback to this stage.

For the rest of this tutorial, you'll want to be in the root folder of the
CMS build.

```
$ cd /path/to/web-root
```

## Inspect the model

The *model* defines any local installation options.  These values are mostly
discovered automatically. To inspect them, use the `--debug-model` option, as in:

```
$ cv core:install --debug-model
Found code for civicrm-core in /var/www/wp-content/plugins/civicrm/civicrm
Found code for civicrm-setup in /var/www/wp-content/plugins/civicrm/civicrm/setup
{
    "srcPath": "/var/www/wp-content/plugins/civicrm/civicrm",
    "setupPath": "/var/www/wp-content/plugins/civicrm/civicrm/setup",
    "settingsPath": "/var/www/wp-content/uploads/civicrm/civicrm.settings.php",
    "cms": "WordPress",
    "cmsBaseUrl": "http://hydra-wp.l",
    "db": {
        "server": "127.0.0.1:3307",
        "username": "hydrawpcms_p7urq",
        "password": "t0ps3cr3t4r3a1z",
        "database": "hydrawpcms_p7urq"
    },
...
```

Some options can be customized on the command line . For example, passing `--db=<url>` will change the `db` field:

```
$ cv core:install --debug-model --db=mysql://otheruser:secret@mysql.example.org/civicrm
...
    "db": {
        "server": "mysql.example.org",
        "username": "otheruser",
        "password": "secret",
        "database": "civicrm"
    },
...
```

To browse a more complete list of options, run `cv core:install --help`.

!!! tip "Improvising with `-m`"

    Several common options, such as `--db`, are documented in the help for `cv core:install`.
    If you need to update the model with a less visible option, then use `--model` (`-m`)
    For example, this will load sample data:

    ```
    $ cv core:install -m loadGenerated=1
    ```

## Inspect the plugins and events

Most changes are accomplished by adding or patching plugins, and each plugin subscribes to events.

You can inspect the list of plugins and events with the `--debug-event` option. For example:

```
$ cv core:install --debug-event
...
[Event] civi.setup.installFiles
+-------+------------------------------------------------------------------------------------------------+
| Order | Callable                                                                                       |
+-------+------------------------------------------------------------------------------------------------+
| #1    | closure(/var/www/.../setup/plugins/common.d/LogEvents.civi-setup.php@30)                       |
| #2    | closure(/var/www/.../setup/plugins/installFiles.d/GenerateSiteKey.civi-setup.php@13)           |
| #3    | closure(/var/www/.../setup/plugins/installFiles.d/CreateTemplateCompilePath.civi-setup.php@33) |
| #4    | closure(/var/www/.../setup/plugins/installFiles.d/InstallSettingsFile.civi-setup.php@43)       |
| #5    | closure(/var/www/.../setup/plugins/common.d/LogEvents.civi-setup.php@38)                       |
+-------+------------------------------------------------------------------------------------------------+
...
```

## Inspect the system requirements

CiviCRM has a number of system requirements that should be met before installation. You can review all of them:

```
$ cv core:check-req
Found code for civicrm-core in /var/www/wp-content/plugins/civicrm/civicrm
Found code for civicrm-setup in /var/www/wp-content/plugins/civicrm/civicrm/setup
+----------+----------+--------------------------------------+-----------------------------------------------------+
| severity | section  | name                                 | message                                             |
+----------+----------+--------------------------------------+-----------------------------------------------------+
| info     | database | CiviCRM InnoDB support               | MySQL supports InnoDB                               |
| info     | database | CiviCRM MySQL AutoIncrementIncrement | MySQL server auto_increment_increment is 1          |
| info     | database | CiviCRM MySQL Lock Tables            | Can successfully lock and unlock tables             |
| info     | database | CiviCRM MySQL Temp Tables            | MySQL server supports temporary tables              |
...
```

Notice that the `severity` indicates the importance of the message. The severities are:

* `info`: The requirement is met. The message is just informational. Installation may proceed.
* `warning`: The requirement is partially met. Installation may proceed, but there is some limitation or risk.
* `error`: The requirement is not met. Installation cannot proceed.

> __Tip__: If you want to focus on warnings and errors, use `-we` as in `cv core:check-req -we`

## Run the installer

If all the auto-detection works and all the requirements are met, then installation is straight-forward:

```
$ cv core:install
Found code for civicrm-core in /var/www/wp-content/plugins/civicrm/civicrm
Found code for civicrm-setup in /var/www/wp-content/plugins/civicrm/civicrm/setup
Creating file /var/www/wp-content/uploads/civicrm/civicrm.settings.php
Creating civicrm_* database tables in hydrawpcms_p7urq
```

However, you could encounter an error.  For example, the model requires a valid `cmsBaseUrl`, but this
is not auto-detected correctly in Drupal 7. The installer will raise an error because the requirement
hasn't been met.

```
$ cv core:install
Found code for civicrm-core in /var/www/sites/all/modules/civicrm
Found code for civicrm-setup in /var/www/sites/all/modules/civicrm/setup
(cmsBaseUrl) The "cmsBaseUrl" (http://localhost/home/myuser/src/cv/bin/home/myuser/src/cv/bin/) is unavailable or malformed. Consider setting it explicitly.

  [Exception]
  Requirements check failed.
```

You can resolve this by passing `--cms-base-url`, as in:

```
$ cv core:install --cms-base-url=http://mysite.localhost
```

## Dev loop

When writing an installer, patch, or plugin using the `setup` subsystem, you may want
to alternately update the code, re-run the installation, and inspect what happens.
Try to distill the install/uninstall/reinstall process into a single CLI call that can be quickly repeated.
It would likely include:

* Using `cv core:install -f` to force-install. This will remove any old settings-files or database-tables.
* Using `cv core:install -vvv` to enable very-verbose output. This will log more details about the execution.
* Using `drush` or `wp-cli` to enable or disable the `civicrm` module.

For example, on WordPress, this command will uninstall and reinstall:

```
wp plugin deactivate civicrm ; cv core:install -f -vvv ; wp plugin activate civicrm
```

Similarly, in Drupal 7:

```
drush -y dis civicrm ; cv core:install -f -vvv --cms-base-url=http://example.com/ ; drush -y en civicrm
```

## Test coverage

This library provides little test-coverage on its own.  Instead, the main
test coverage is provided in the `cv` project (`phpunit4 --group installer`).
For more details on running that test, consult the documentation in `cv`'s git repo.
