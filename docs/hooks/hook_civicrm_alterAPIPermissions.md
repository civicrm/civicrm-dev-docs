# hook_civicrm_alterAPIPermissions

## Description

This hook is called when API 3 permissions are checked and can alter the
`$permissions` structure from `CRM/Core/DAO/permissions.php` (as well as
the API `$params` array) based on the `$entity` and `$action` (or
unconditionally).

!!! Note
    If a given entity/action permissions are unset, the default
    "access CiviCRM" permission is enforced.


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

When developing an extension with custom API, this code is placed
directly in the API php file that you have created. In this case the
extension would be named CiviTest. The API function for the GET would be
`function civicrm_api3_civi_test_get();`. The `alterAPIPermissions`
function is prefixed with the full extension name, all lowercase,
followed by `_civicrm_alterAPIPermissions`.
