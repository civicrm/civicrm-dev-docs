## Overview

The [__Symfony EventDispatcher__](http://symfony.com/components/EventDispatcher) is a
common event system used by many PHP applications and frameworks.  For
example, Symfony SE, Drupal 8, Magento, Laravel, CiviCRM, and many others
support `EventDispatcher`.

In CiviCRM v4.7.19+, you can use Symfony `EventDispatcher` with hooks.

```php
Civi::dispatcher()->addListener('hook_civicrm_EXAMPLE', $callback, $priority);
```

Using `EventDispatcher` is useful if you need more advanced features, such as:

 * Setting the priority of an event-listener
 * Registering an object-oriented event-listener
 * Registering a dynamic, on-the-fly event-listener
 * Registering multiple listeners for the same event
 * Registering for internal/unpublished events

For a general introduction or background on `EventDispatcher`, consult the [Symfony documentation](http://symfony.com/doc/2.7/components/event_dispatcher.html).

## Example: `Civi::dispatcher()`

In this case, we have a CiviCRM extension or Drupal module named `example`.
During the system initialization, we lookup the `EventDispatcher`, call
`addListener()`, and listen for `hook_civicrm_alterContent`.

```
function example_civicrm_config(&$config) {
  if (!isset(Civi::$statics[__FUNCTION__])) { return; }
  Civi::$statics[__FUNCTION__] = 1;

  Civi::dispatcher()->addListener('hook_civicrm_alterContent', function($event) {
    $event->content = 'hello ' . $event->content;
  });
}
```

> Note: In some environments, `hook_civicrm_config` runs multiple times. The flag
> `Civi::$statics[__FUNCTION__]` prevents duplicate listeners.

## Example: `Container::findDefinition()`

In this case, we have a CiviCRM extension or Drupal module named `example`.
We lookup the defintion of the `dispatcher` service and amend it.

```php
function example_civicrm_container($container) {
  $container->findDefinition('dispatcher')
    ->addMethodCall('addListener', array('hook_civicrm_alterContent', '_example_say_hello'));
}

function _example_say_hello($event) {
  $event->content = 'hello ' . $event->content;
}
```

> __Note__: The "definition" will be written to a cache file.  Consequently,
> the callback function must be a string (function-name) or an array
> (class-name, function-name).  Using an anonymous `function(){}` block
> could cause problems with the cache file.

<!--
  TODO: an example using a container-service and tag.  See "Registering Event Listeners
  in the Service Container" from http://symfony.com/doc/2.7/components/event_dispatcher.html
-->

## Event Names

The Symfony `EventDispatcher` was originally introduced in CiviCRM v4.5.0 for
handling *private/internal* events.  It was expanded in v4.7.19 to handle
*public/external* events.

Both use the same dispatcher and PHP functions.  However, they follow different
naming conventions.  Compare:

```php
// Listen to a public-facing hook. Note the prefix, "hook_civicrm_*".
Civi::dispatcher()->addListener('hook_civicrm_alterContent', $callback, $priority);

// Listen to an internal event. Note the prefix, "civi.*".
Civi::dispatcher()->addListener('civi.api.resolve', $callback, $priority);
```

## History

 * _CiviCRM v4.5.0_: Introduced Symfony EventDispatcher for internal use (within `civicrm-core`). For example,
   APIv3 dispatches the events `civi.api.resolve` and `civi.api.authorize` while executing an API call.
 * _CiviCRM v4.7.0_: Introduced `hook_civicrm_container`.
 * _CiviCRM v4.7.0_: Integrated the Symfony `Container` and `EventDispatcher`.
 * _CiviCRM v4.7.19_: Integrated `hook_civicrm_*` with the Symfony `EventDispatcher`.
 * _CiviCRM v4.7.19_: Added the `Civi::dispatcher()` function.

## Limitations

 * _Boot-critical hooks_: `hook_civicrm_config`, `hook_civicrm_container`, and `hook_civicrm_entityTypes`
   are fired during the bootstrap process -- before the Symfony subsystems are fully online. Consequently,
   you may not be able to listen for these hooks.
 * _Opaque CMS listeners_: Most hooks are dispatched through `EventDispatcher` as well as the traditional
   hook systems for Drupal modules, Joomla plugins, WordPress plugins, and/or CiviCRM extensions.
   This is accomplished by daisy-chaining: first, the event goes `EventDispatcher`; there, a
   listener called `delegateToUF()` passes the event down to the other systems. If you inspect
   `EventDispatcher`, there will be one step (`\Civi\Core\CiviEventDispatcher::delegateToUF()`)
   which represents _all_ CMS-based listeners.

