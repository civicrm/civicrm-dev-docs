# hook_civicrm_container

## Summary

This hook modifies the CiviCRM container allowing you to add new services, parameters,
extensions, etc.

## Notes

!!! tip
    The container configuration will be compiled/cached. The default cache behavior is aggressive. When you first implement the hook, be sure to flush the cache. Additionally, you should relax caching during development. In `civicrm.settings.php`, set `define('CIVICRM_CONTAINER_CACHE', 'auto')`.

!!! note
    As of CiviCRM version 5.27 CiviCRM now uses Symfony v3.4 or v4.4. There is an important change which is that in v3.3 Symfony services are now considered by default to be private. To ensure backwards compatibility you just need to add `->setPublic(TRUE)` after your definition in your code. This will make the service public as was the default originally in the Symfony 2.x series. If you don't need the service to be public, you can omit this.

## Availability

This hook is available in CiviCRM 4.7+.

## Definition

    hook_civicrm_container(\Symfony\Component\DependencyInjection\ContainerBuilder $container)

## Parameters

-   $container - An object of type
    \Symfony\Component\DependencyInjection\ContainerBuilder.  See
    [here](http://symfony.com/doc/current/components/dependency_injection/index.html).

## Returns

-   null

## Example

```php
    use Symfony\Component\Config\Resource\FileResource;
    use Symfony\Component\DependencyInjection\Definition;

    function mymodule_civicrm_container($container) {
      $container->addResource(new FileResource(__FILE__));
      $container->setDefinition('mysvc', new Definition('My\Class', array()))->setPublic(TRUE);
    }
```
