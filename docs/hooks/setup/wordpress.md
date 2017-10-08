For a detailed overview of the updated relationship between WordPress and 
CiviCRM, see the blog post [Working with CiviCRM 4.6 in WordPress][civi-wp-blog]
on the CiviCRM website.

In WordPress, hooks can be implemented in a variety of ways.
You can write a plugin or include them in your theme's 'functions.php' 
file - where you place them depends largely on whether they are theme-dependent
or theme-independent. The general rule for targeting the hook is to 
remove the 'hook_' prefix when you create the filter or action.

The following code block shows the simplest form of a hook implementation in 
WordPress, in this case the 'hook_civicrm_pre' hook:

```
// Implements hook_civicrm_pre
add_filter( 'civicrm_pre', 'my_plugin_pre_callback', 10, 4 );
function my_plugin_pre_callback( $op, $objectName, $objectId, &$objectRef ) {
  // your code here
}
```
                                                      
As long as the plugin is active (or - if the code is in 'functions.php' - as 
long as your theme is active), this function will be called every time CiviCRM 
is about to save data to the database.

In summary, as of CiviCRM 4.6 there is (almost) full compatibility with the 
WordPress actions and filters system.

## Including Your Hooks

Any PHP file that will be included by WordPress can be used to contain your 
hook implementations. Use an in-house plugin, your site's 'functions.php' 
file, or place a file named 'civicrmHooks.php' in your CiviCRM custom php 
path as specified in

> Administer -> System Settings -> Directories -> Custom PHP Path Directory

## Targeting Hooks

As of CiviCRM 4.6, the general rule for targeting the hook is to remove the
'hook_' prefix when you create the filter or action. So, if your plugin or 
theme wants to receive callbacks from 'hook_civicrm_pre', the filter 
should be written as

```
add_filter('civicrm_pre', 'my_callback_function', 10, 4 ) 
```
or if your callback method is declared in a class, the filter should be written 
as 

```
add_filter( 'civicrm_pre', array( $this, 'my_callback_method', 10, 4 )
```

For more details (as well as the exceptions to this rule) see the 
[blog post][civi-wp-blog] on CiviCRM in WordPress.

## Legacy Hooks

Prior to CiviCRM 4.6, hooks had to use the prefix "wordpress_" as
the replacement for the "hook_" part of the hook name. So to implement 
'hook_civicrm_pre' you had to write: 
```
    function wordpress_civicrm_pre($op, objectName, $objectId, &$objectRef)
```

This method still works, so if you have legacy modifications, they will not 
break.

## Inspecting hooks

The documentation about hooks can be somewhat abstract, and it sometimes  
helps to see interactively how the hooks run.

If you use WordPress, you can inspect hooks by installing the following plugin:

- [Query Monitor](https://wordpress.org/plugins/query-monitor/)
    
  
[civi-wp-blog]: https://civicrm.org/blog/haystack/working-with-civicrm-46-in-wordpress
