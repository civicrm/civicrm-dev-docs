# hook_civicrm_apiWrappers

## Summary

This hook allows you to add, override, or remove methods to be called before and after API calls &mdash; and to modify either the parameters or the result of the call.

## Notes

Introduced in CiviCRM 4.4.0.

!!! caution ""
    Use caution when overriding or removing methods.

## Definition

    hook_civicrm_apiWrappers(&$wrappers, $apiRequest)

## Parameters

- API_Wrapper[] $wrappers - an array of objects which implement the [`API_Wrapper`](#wrapper-class) interface.
- array $apiRequest - contains keys 'entity', 'action', and params; see [API Usage](/api/usage.md).

## Returns

- Void

## Wrapper class

The `API_Wrapper` interface specifies two methods:

 * `fromApiInput($apiRequest)`  - Allows for modifcation of API parameters before the request is executed.
   Should return a (possibly modified) $apiRequest array.

 * `toApiOutput($apiRequest, $result)` - Allows for modification of the result before it is returned.
   Should return a (possibly modified) $result array.

These methods will be called for every API call unless the `hook_civicrm_apiWrappers()` implementation
conditionally registers the object. One way to optimize this is to check for the API Entity in
`hook_civicrm_apiWrappers()` and to check for the API action in the wrapper methods.

## Example

In the file where hooks are implemented, e.g., `path/to/myextension/myextension.php`:

```php
/**
  * Implements hook_civicrm_apiWrappers().
  */
function myextension_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  // The APIWrapper is conditionally registered so that it runs only when appropriate
  if ($apiRequest['entity'] == 'Contact' && $apiRequest['action'] == 'create') {
    $wrappers[] = new CRM_Myextension_APIWrappers_Contact();
  }
}
```

Since we named the wrapper class `CRM_Myextension_APIWrappers_Contact`, the following code is placed in
`path/to/myextension/CRM/Myextension/APIWrappers/Contact.php` to take advantage of CiviCRM's PHP autoloader:

```php
    class CRM_Myextension_APIWrappers_Contact implements API_Wrapper {

      /**
       * Conditionally changes contact_type parameter for the API request.
       */
      public function fromApiInput($apiRequest) {
        if ('Invalid' == CRM_Utils_Array::value('contact_type', $apiRequest['params'])) {
          $apiRequest['params']['contact_type'] = 'Individual';
        }
        return $apiRequest;
      }

      /**
       * Munges the result before returning it to the caller.
       */
      public function toApiOutput($apiRequest, $result) {
        if (isset($result['id'], $result['values'][$result['id']]['display_name'])) {
          $result['values'][$result['id']]['display_name_munged'] = 'MUNGE! ' . $result['values'][$result['id']]['display_name'];
          unset($result['values'][$result['id']]['display_name']);
        }
        return $result;
      }
    }
```
