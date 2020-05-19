# hook_civicrm_managed

## Summary

This hook allows a module to declare a list of managed entities using
the API.

## Notes

A managed entity will be automatically inserted, updated, deactivated, and deleted
in tandem with enabling, disabling, and uninstalling the module. The
hook is called periodically during cache-clear operations.

For more background, see [API and the Art of
Installation](http://civicrm.org/blogs/totten/api-and-art-installation).

## Definition

```php
hook_civicrm_managed(&$entities)
```

## Parameters

-   array `$entities` - the list of entity declarations; each declaration
    is an array with these following keys:

    -   string `module` - for module-extensions, this is the
        fully-qualifed name (e.g. `com.example.mymodule`); for Drupal
        modules, the name is prefixed by `drupal` (e.g.
        `drupal.mymodule`)

    -   string `name` - a symbolic name which can be used to track this
        entity (*Note: Each module creates its own namespace*)

    -   string `entity` - an entity-type supported by the [CiviCRM
        API](../api/index.md) (*Note: this
        currently must be an entity which supports the 'is_active'
        property*)

    -   array `params` - the entity data as supported by the [CiviCRM
        API](../api/index.md)

    -   string `update` - a policy which describes when to
        update records

        -   `always` (**default**): always update the managed-entity
            record; changes in `$entities` will override any local
            changes (eg by the site-admin)
        -   `never`: never update the managed-entity record; changes
            made locally (eg by the site-admin) will override changes in
            `$entities`

    -   string `cleanup` - a policy which describes whether
        to cleanup the record when it becomes orphaned (i.e. when
        $entities no longer references the record)

        -   `always` (**default**): always delete orphaned records
        -   `never`: never delete orphaned records
        -   `unused`: only delete orphaned records if there are no other
            references to it in the DB. (This is determined by calling
            the API's `getrefcount` action.)

## Returns

-   void - the return value is ignored

## Example

```php
/**
 * Declare a report-template which should be activated whenever this module is enabled
 */
function modulename_civicrm_managed(&$entities) {
  $entities[] = array(
    'module' => 'com.example.modulename',
    'name' => 'myreport',
    'entity' => 'ReportTemplate',
    'params' => array(
      'version' => 3,
      'label' => 'Example Report',
      'description' => 'Longish description of the example report',
      'class_name' => 'CRM_Modulename_Report_Form_Sybunt',
      'report_url' => 'mymodule/mysbunt',
      'component' => 'CiviContribute',
    ),
  );
}
```
