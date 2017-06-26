# hook_civicrm_alterAPIPermissions

## Summary

This hook is called when API 3 permissions are checked.

## Notes

This hook can alter the `$permissions` structure from `CRM/Core/DAO/permissions.php` (as well as the API `$params` array) based on the `$entity` and `$action` (or unconditionally).

!!! Note
    If a given entity/action permissions are unset, the default
    "administer CiviCRM" permission is enforced.


## Definition

```php
hook_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions)
```

## Parameters

-   string `$entity` - the API entity (like contact)
-   string `$action` - the API action (like get)
-   array `&$params` - the API parameters
-   array `&$permisisons` - the associative permissions array (probably to
    be altered by this hook)
    -   Note: the entity in `$permissions` array use the camel case
        syntax (e.g. `$permissions['option_group']['get'] = ...` and not
        `$permissions['OptionGroup']['get'] = ...`)

## Returns

-   null

## Availability

-   This hook was first available in CiviCRM 3.4.1

## Example

The `alterAPIPermissions` function is prefixed with the full extension name, all lowercase,
followed by `_civicrm_alterAPIPermissions`. For an extension "CiviTest" the hook
would be placed in the `civitest.php` file and might look like:

```php
function civitest_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions)
{
  // skip permission checks for contact/create calls
  // (but keep the ones for email, address, etc./create calls)
  // note: unsetting the below would require the default ‘access CiviCRM’ permission
  $permissions['contact']['create'] = array();

  // enforce ‘view all contacts’ check for contact/get, but do not test ‘access CiviCRM’
  $permissions['contact']['get'] = array('view all contacts');

  // add a new permission requirement for your own custom API call
  // (if all you want to enforce is ‘access CiviCRM’ you can skip the below altogether)
  $permissions['foo']['get'] = array('access CiviCRM', 'get all foos');

  // allow everyone to get info for a given event; also – another way to skip permissions
  if ($entity == 'event' and $action == 'get' and $params['title'] == 'CiviCon 2038') {
      $params['check_permissions'] = false;
  }
}
```

The API function for the "get" action for the new custom API entity called "Foo" would be
`function civicrm_api3_foo_get();`.




