# Managing plugins

Plugins in `civicrm-setup/plugins/*/*.civi-setup.php` are automatically
detected and loaded.  The simplest way to manage plugins is adding and
removing files from this folder.

However, you may find it useful to manage plugins programmatically.  For
example, the `civicrm-drupal` integration or the `civicrm-wordpress`
integration might refine the installation process by:

* Adding a new plugin
* Removing a default plugin

To programmatically manage plugins, take note of the
`\Civi\Setup::init(...)` function.  It accepts an argument,
`$pluginCallback`, which can edit the plugin list. For example:

```php
<?php
function myPluginCallback($files) {
  $files['ExtraWordPressInstallPlugin'] = '/path/to/ExtraWordPressInstallPlugin.php';
  ksort($files);
  return $files;
}

\Civi\Setup::init(..., 'myPluginCallback');
```
