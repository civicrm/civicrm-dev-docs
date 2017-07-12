# hook_civicrm_container

## Summary

This hook modifies the CiviCRM container allowing you to add new services, parameters,
extensions, etc.

## Notes

!!! tip
    The container configuration will be compiled/cached. The default cache behavior is aggressive. When you first implement the hook, be sure to flush the cache. Additionally, you should relax caching during development. In `civicrm.settings.php`, set `define('CIVICRM_CONTAINER_CACHE', 'auto')`.

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

    use Symfony\Component\Config\Resource\FileResource;
    use Symfony\Component\DependencyInjection\Definition;

    function mymodule_civicrm_container($container) {
      $container->addResource(new FileResource(__FILE__));
      $container->setDefinition('mysvc', new Definition('My\Class', array()));
    }
