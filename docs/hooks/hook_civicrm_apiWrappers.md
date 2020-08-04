# hook_civicrm_apiWrappers

!!! warning "Legacy Hook"
    This hook implements a legacy *API Wrapper* interface. It may be deprecated in a future version of CiviCRM.

## Summary

This hook allows you to add, override, or remove methods to be called before and after API calls &mdash; and to modify either the parameters or the result of the call.

## Notes

Introduced in CiviCRM 4.4.0.

!!! caution ""
    Use caution when overriding or removing methods.

## Definition

    hook_civicrm_apiWrappers(&$wrappers, $apiRequest)

## Parameters

- `API_Wrapper[]` $wrappers - an array of objects which implement the [`API_Wrapper`](#wrapper-class) interface.
- `array|Civi\Api4\Generic\AbstractAction` `$apiRequest` - contains keys 'entity', 'action', and params; see [APIv3 Usage](../api/v3/usage.md).

## Returns

- Void

## Wrapper class

The `API_Wrapper` interface specifies two methods:

 * `fromApiInput($apiRequest)`  - Allows for modifcation of API parameters before the request is executed.
   Should return $apiRequest (possibly modified).

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
    if ($apiRequest['version] == '3') {
      $wrappers[] = new CRM_Myextension_API3Wrappers_Contact();
    }
    elseif ($apiRequest['version] == '4') {
      $wrappers[] = new CRM_Myextension_API4Wrappers_Contact();
    }
  }
}
```

!!! note
    We need to handle requests that might be from API version 3 or 4. In this example we have identified different classes to handle the different versions. You could handle the difference in one class if you prefer.


We then create files for our two classes:

- `path/to/myextension/CRM/Myextension/API3Wrappers/Contact.php`
- `path/to/myextension/CRM/Myextension/API4Wrappers/Contact.php`

### API3 version

```php
class CRM_Myextension_API3Wrappers_Contact implements API_Wrapper {

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

### API4 version

```php
class CRM_Myextension_API4Wrappers_Contact implements API_Wrapper {

  /**
   * Conditionally changes contact_type parameter for the API request.
   */
  public function fromApiInput(Civi\Api4\Generic\AbstractAction $apiRequest) {
    $params = $apiRequest->getParams();
    if ('Invalid' === $params['contact_type'] ?? NULL) {
      $apiRequest->addValue('contact_type', 'Individual');
    }
    return $apiRequest;
  }

  /**
   * Munges the result before returning it to the caller.
   */
  public function toApiOutput(Civi\Api4\Generic\AbstractAction $apiRequest, $result) {
    if (isset($result['id'], $result['values'][$result['id']]['display_name'])) {
      $result['values'][$result['id']]['display_name_munged'] = 'MUNGE! ' . $result['values'][$result['id']]['display_name'];
      unset($result['values'][$result['id']]['display_name']);
    }
    return $result;
  }
}
```

!!! note
    `$api4Request->addValue()` is appropriate because we are creating a record. You may need different calls to adjust other API requests.

## Migrating away from this hook

This hook is deprecated in favour of using more flexible Symfony event listeners to achieve what you want.

This hook provides an onion-like middleware pattern where each wrapper added is the first to alter the input and the last to alter the output. If you need this style of wrapper, see [EventPrepareTest.php](https://lab.civicrm.org/dev/core/-/blob/master/tests/phpunit/Civi/API/Event/PrepareEventTest.php) which implements this functionality by replacing the API provider object with a special wrapper class that delegates calling the API to your callback code.

However, often you don't need this onion-like before and after - often you only used `toApiOutput` or `fromApiInput` but not both. In which case you can instead just add a listener to the `Civi\API\Events::PREPARE` or `Civi\API\Events::RESPOND` as needed to do your work.

For help understanding Symfony events see [Hooks in Symfony](../usage/symfony.md).

To implement this form of hook in an Symfony the following example maybe useful:

1. Define your hook listener.

```php

/**
 * Implements hook_civicrm_config().
 * Most of the magic is in the CRM_Example_APIWrapper class
 */
function example_civicrm_config(&$config) {
  // Bind our wrapper for API Events
  Civi::dispatcher()->addListener(\Civi\API\Events::PREPARE, ['CRM_Example_APIWrapper', 'PREPARE'], -100);
  Civi::dispatcher()->addListener(\Civi\API\Events::RESPOND, ['CRM_Example_APIWrapper', 'RESPOND'], -100);
}
```

1. Implement function that actually manages the event processing e.g.

```php
/**
 * Implements an API Wrapper to signal the membership creation preHook that
 * we're currently inside of a payment transaction.
 */
class CRM_Example_APIWrapper {

  /**
   * Callback to wrap completetransaction API calls.
   */
  public static function PREPARE ($event) {
    $request = $event->getApiRequestSig(); //getApiRequestSig() returns '<api version>.<entity>.<action>', all lower case

    switch($request) {
      // Wrap completetransaction in the v3 API.
      // Doesn't exist yet in the v4 API.
      case '3.contribution.completetransaction':
       $event->wrapAPI(['CRM_Example_APIWrapper', 'completeTransaction']);
       break;

    }
  }

  public static function RESPOND($event) {
    $request = $event->getApiRequestSig();

    switch($request) {
      case '3.membership.create':
        $apiRequest = $event->getApiRequest();
        $result = $event->getResponse();
        $this->membershipRespond($event->getApiKernel(), $apiRequest['params'], $result, $apiRequest['action'], $apiRequest['entity'], $apiRequest['version']);
        $event->setResponse($result);
        break;

    }
  }

  public function completeTransaction($apiRequest, $callsame) {
    // Do some work for the contribution.completetransaction api call
  }

  /**
   * Call any nested api calls.
   *
   * TODO: We don't really need this to be a separate function.
   * @param \Civi\API\Kernel $apiKernel
   * @param $params
   * @param $result
   * @param $action
   * @param $entity
   * @param $version
   * @throws \Exception
   */
  public function membershipRespond($apiKernel, &$params, &$result, $action, $entity, $version) {
    $result['test_field'] = 1;
    // Do some other work as applicable
  }

}
```
