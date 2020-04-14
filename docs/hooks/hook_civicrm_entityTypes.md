# hook_civicrm_entityTypes

## Summary

This hook is used to declare a new type of entity, for example a booking extension might want to declare a *Resource* entity.

## Notes

[See this tutorial](../extensions/civix.md#generate-entity) for a more complete description of creating new types of entities.

## Definition

```php
hook_civicrm_entityTypes(&$entityTypes)
```

## Parameters

* `$entityTypes` is a two-dimensional associative array. Each element in the array has:

    * A **key** which is the DAO name of the entity as a string (e.g. `'CRM_Report_DAO_Instance'`), although this has not always been enforced.

    * A **value** which is an associative with the following elements:

        * `'name'`: *string, required* - a unique short name (e.g. `"ReportInstance"`)

        * `'class'`: *string, required* - a PHP DAO class (e.g.`"CRM_Report_DAO_Instance"`)

        * `'table'`: *string, required* - a SQL table name (e.g. `"civicrm_report_instance"`)

        * `'fields_callback'`: *array, optional* - a list of callback functions which can modify the DAO field metadata. `function($class, &$fields)` Added circa 4.7.11+

        * `'items_callback'`: *array, optional* - a list of callback functions which can modify the DAO foreign-key metadata. `function($class, &$links)` Added circa 4.7.11+


## Returns

* null

## Examples

### Add new entities

This example is taken from CiviVolunteer [here](https://github.com/civicrm/org.civicrm.volunteer/blob/eafc2b0c3966a492a3080ac70abe06cbd960a00e/volunteer.php#L333).

```php
/**
 * Implements hook_civicrm_apiWrappers().
 */
function volunteer_civicrm_entityTypes(&$entityTypes) {
  $entityTypes[] = array(
    'name'  => 'VolunteerNeed',
    'class' => 'CRM_Volunteer_DAO_Need',
    'table' => 'civicrm_volunteer_need',
  );
  $entityTypes[] = array(
    'name'  => 'VolunteerProject',
    'class' => 'CRM_Volunteer_DAO_Project',
    'table' => 'civicrm_volunteer_project',
  );
  $entityTypes[] = array(
    'name'  => 'VolunteerProjectContact',
    'class' => 'CRM_Volunteer_DAO_ProjectContact',
    'table' => 'civicrm_volunteer_project_contact',
  );
}
```

### Alter metadata for existing entities

This functionality was added in (approximately) v4.7.11.

```php
/**
 * Implements hook_civicrm_apiWrappers().
 */
function apilogging_civicrm_entityTypes(&$entityTypes) {
  $entityTypes['CRM_Contact_DAO_Contact']['fields_callback'][]
    = function ($class, &$fields) {
      unset($fields['created_date']['export']);
    };
}
```
