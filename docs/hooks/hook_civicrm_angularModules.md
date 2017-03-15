# hook_civicrm_angularModules

## Description

EXPERIMENTAL: This hook generates a list of Angular modules. It allows
one to register additional Angular modules.

## Availability

This hook is available in CiviCRM 4.5+. It may use features only
available in CiviCRM 4.6+.

## Definition

    angularModules(&$angularModules)

## Parameters

-   &$angularModules - an array containing a list of all Angular
    modules.

## Returns

-   null

## Example

    function mymod_civicrm_angularModules(&$angularModules) {
      $angularModules['myAngularModule'] = array(
        'ext' => 'org.example.mymod',
        'js' => array('js/myAngularModule.js'),
      );
      $angularModules['myBigAngularModule'] = array(
        'ext' => 'org.example.mymod',
        'js' => array('js/part1.js', 'js/part2.js'),
        'css' => array('css/myAngularModule.css'),
        'partials' => array('partials/myBigAngularModule'),
      );
    }
