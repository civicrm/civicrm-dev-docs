# Extending Smarty

The Smarty templating language that is used to output the HTML for
CiviCRM's templates is fairly powerful in its own right, but sometimes
it can't do everything you want.  Smarty can be extended using "plugins"
-- you can find some examples and documentation online at [Smarty's website](http://www.smarty.net) or by searching the web.

To install Smarty plugins without editing CiviCRM core (which is
difficult to maintain), you will have to implement 
[hook_civicrm_config](../../hooks/hook_civicrm_config.md).

Once you've created your module or hook file, you can retrieve the
Smarty object and register your custom plugin path:

```php
function yourmodule_civicrm_config(&$config) {
  $smarty = CRM_Core_Smarty::singleton();
  array_push($smarty->plugins_dir, __DIR__ . '/relative/path/to/custom/plugin/directory');
}
```

Then in that custom plugin directory, you can place whatever Smarty
plugins you need.

You can also use this trick to change other Smarty behavior, such as
whether it can evaluate PHP placed directly in templates. For instance:

```php
function yourmodule_civicrm_config(&$config) {
  $smarty = CRM_Core_Smarty::singleton();
  
  // allow the explode() php function to be used as a "modifier" in Smarty templates
  array_push($smarty->security_settings['MODIFIER_FUNCS'], 'explode'); 
}
```

However, be very careful with these settings – if you don't know what
you're doing, you can open security holes or create a mess of code
that's difficult to maintain.
