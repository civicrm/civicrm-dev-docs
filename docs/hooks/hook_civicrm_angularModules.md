# hook_civicrm_angularModules

## Summary

This hook generates a list of AngularJS modules and allows you to register additional AngularJS modules. It is currently **experimental**.

## Availability

This hook is available in CiviCRM 4.5+. It may use features only
available in CiviCRM 4.6+.

## Definition

    angularModules(&$angularModules)

## Parameters

 * `&$angularModules` - an array containing a list of all Angular modules. Each item is keyed by the Angular module name.

Each item `angularModules`  may include these properties:

 * `ext` (`string`): The name of the CiviCRM extension which has the source-code.
 * `js` (`array`): List of Javascript files. May use the wildcard (`*`). Relative to the extension.
 * `css` (`array`): List of CSS files. May use the wildcard (`*`). Relative to the extension.
 * `partials` (`array`): List of HTML folders. Relative to the extension.
 * `settings` (`array`): Runtime data to export from PHP to JS. This is mapped to the JS global (Ex: `array("foo"=>"bar")`, which would  be available as `CRM.myModule.foo`.
 * `requires` (`array`): List of AngularJS modules required by this module. Default: `array()`. (`v4.7.21+`)
 * `basePages` (`array`): Uncondtionally load this module onto the given Angular pages. (`v4.7.21+`)
   * If omitted, the default is `array('civicrm/a')`. This provides backward compatibility with behavior since `v4.6+`.
   * For a utility that should only be loaded on-demand, use `array()`.
   * For a utility that should be loaded in all pages use, `array('*')`.

## Returns

 * `null`

## Example

    function mymod_civicrm_angularModules(&$angularModules) {
      $angularModules['myAngularModule'] = array(
        'ext' => 'org.example.mymod',
        'js' => array('js/myAngularModule.js'),
      );
      $angularModules['myBigAngularModule'] = array(
        'ext' => 'org.example.mymod',
        'requires' => array('ngRoute', 'crmUi'),
        'basePages' => array('civicrm/a'),
        'js' => array('js/part1.js', 'js/part2.js'),
        'css' => array('css/myAngularModule.css'),
        'partials' => array('partials/myBigAngularModule'),
        'settings' => array(
          'foo' => 'bar',
        ),
      );
    }
