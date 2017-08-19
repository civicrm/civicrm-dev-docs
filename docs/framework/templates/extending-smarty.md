# Extending Smarty

The Smarty templating language that is used to output the HTML for
CiviCRM's templates is fairly powerful in its own right, but sometimes
it can't do everything you want.  Smarty can be extended using "plugins"
-- you can find some examples and documentation online at [Smarty's
website](http://www.smarty.net){.external-link} or by searching the web.

To install Smarty plugins without editing CiviCRM core (which is
difficult to maintain), you will have to implement the
hook_civicrm_config hook.  (Activating hooks in Drupal and Joomla is
covered
[here](http://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+hook+specification#CiviCRMhookspecification-Proceduresforimplementinghooks%28forDrupal%29)).

Once you've created your module or hook file, you can retrieve the
Smarty object and register your custom plugin path:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    function yourmodule_civicrm_config(&$config) {
        $smarty = CRM_Core_Smarty::singleton();
        array_push($smarty->plugins_dir, __DIR__ . '/relative/path/to/custom/plugin/directory');
    }

</div>

</div>

Then in that custom plugin directory, you can place whatever Smarty
plugins you need.

You can also use this trick to change other Smarty behavior, such as
whether it can evaluate PHP placed directly in templates. For instance:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    function yourmodule_civicrm_config(&$config) {
        $smarty = CRM_Core_Smarty::singleton();
        array_push($smarty->security_settings['MODIFIER_FUNCS'], 'explode'); // allow the explode() php function to be used as a "modifier" in Smarty templates
    }

</div>

</div>

However, be very careful with these settings – if you don't know what
you're doing, you can open security holes or create a mess of code
that's difficult to maintain.
