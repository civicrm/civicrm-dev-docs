# hook_civicrm_caseTypes

## Summary

This hook defines available case types.

## Notes

Note that this hook is actually an adapter
for [hook_civicrm_managed](hook_civicrm_managed.md), so any case
type defined inside this hook will be automatically
inserted, updated, deactivated, and deleted in tandem with enabling,
disabling, and uninstalling the module. For more background, see [API
and the Art of
Installation](http://civicrm.org/blogs/totten/api-and-art-installation).

## Definition

    hook_civicrm_caseTypes(&$caseTypes)

## Parameters

-   array **$caseTypes**list of case types; each item is an array with
    keys:
    -   **'module'**: string; for module-extensions, this is the
        fully-qualifed name (e.g. "*com.example.mymodule*"); for Drupal
        modules, the name is prefixed by "drupal" (e.g.
        *"drupal.mymodule*")
    -   **'name'**: string, a symbolic name which can be used to track
        this entity
    -   **'file'**: string, the path to the XML file which defines the
        case-type

## Example

    function civitest_civicrm_caseTypes(&$caseTypes) {
      $caseTypes['MyCase'] = array(
        'module' => 'org.example.mymodule',
        'name' => 'MyCase',
        'file' => __DIR__ . '/MyCase.xml',
      );
    }