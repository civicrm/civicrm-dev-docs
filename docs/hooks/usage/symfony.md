## Overview

The [__Symfony EventDispatcher__](http://symfony.com/components/EventDispatcher) is an
event library used by several PHP applications and frameworks.  For example,
Symfony SE, Drupal 8, Magento, Laravel, CiviCRM, and many others support
`EventDispatcher`.  It provides a common mechanism for dispatching and listening
to events.

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

```php
function example_civicrm_config(&$config) {
  if (isset(Civi::$statics[__FUNCTION__])) { return; }
  Civi::$statics[__FUNCTION__] = 1;

  Civi::dispatcher()->addListener('hook_civicrm_alterContent', '_example_say_hello');
}

function _example_say_hello($event) {
  $event->content = 'hello ' . $event->content;
}
```

!!! tip "Using the `$event` object"
    Hook parameters are passed as an object, `$event`.
    For example, [`hook_civicrm_alterContent`](../hook_civicrm_alterContent.md)
    has the parameters `(&$content, $context, $tplName, &$object)`.
    You can access the data as `$event->content`, `$event->context`, `$event->tplName`, and `$event->object`.

!!! tip "Using `hook_civicrm_config`"
    In some environments, `hook_civicrm_config` runs multiple times. The flag
    `Civi::$statics[__FUNCTION__]` prevents duplicate listeners.

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

<!--
  TODO: an example using a container-service and tag.  See "Registering Event Listeners
  in the Service Container" from http://symfony.com/doc/2.7/components/event_dispatcher.html
-->

## Events

CiviCRM broadcasts many different events through the `EventDispatcher`. These
events fall into two categories:

 * __External Events/Hooks__ (v4.7.19+): These have a prefix `hook_civicrm_*`. They extend
   the class [`GenericHookEvent`](https://github.com/civicrm/civicrm-core/blob/master/Civi/Core/Event/GenericHookEvent.php)
   (which, in turn, extends  [`Event`](http://api.symfony.com/2.7/Symfony/Component/EventDispatcher/Event.html)).
   Hooks are simulcast across `EventDispatcher` as well as CMS-specific event systems.
 * __Internal Events__ (v4.5.0+): These have a prefix `civi.*`. They extend
   the class  [`Event`](http://api.symfony.com/2.7/Symfony/Component/EventDispatcher/Event.html).
   They are *only* broadcast via `EventDispatcher` (**not** CMS-specific event systems).

You can recognize these events by their naming convention. Compare:

```php
// Listen to a hook. Note the prefix, "hook_civicrm_*".
Civi::dispatcher()->addListener('hook_civicrm_alterContent', $callback, $priority);

// Listen to an internal event. Note the prefix, "civi.*".
Civi::dispatcher()->addListener('civi.api.resolve', $callback, $priority);
```

## Methods

The `EventDispatcher` has several different methods for registering a
listener.  Our examples have focused on the simplest one, `addListener()`,
but the Symfony documentation describes other methods (`addSubscriber()`,
`addListenerService()`, and `addSubscriberService()`).  See also:

 * [Symfony EventDispatcher](http://symfony.com/doc/2.7/components/event_dispatcher.html)
 * [Symfony ContainerAwareEventDispatcher](http://symfony.com/doc/2.7/components/event_dispatcher/container_aware_dispatcher.html)

!!! tip "Using `addListener()`"
    When calling `addListener()`, you _can_ pass any [PHP callable](http://php.net/manual/en/language.types.callable.php).
    However, _in practice_, the safest bet is to pass a string (function-name) or array
    (class-name, function-name). Other formats may not work with the
    [container-cache](http://symfony.com/doc/2.7/components/dependency_injection/compilation.html).

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
   This is accomplished by _daisy-chaining_: first, the event is dispatched with `EventDispatcher`; then, the
   listener `CiviEventDispatcher::delegateToUF()` passes the event down to the other systems.
   If you inspect `EventDispatcher`, there will be one listener (`delegateToUF()`)
   which represents _all_ CMS-based listeners.
