# hook_civicrm_getAssetUrl

## Summary

This hook is called when building a link to a semi-static asset, allowing you to modify the params the asset will be built with.

## Notes

For more discussion, see [AssetBuilder](../framework/asset-builder.md).

## Definition

    hook_civicrm_getAssetUrl(&$asset, &$params)

## Parameters

 * `$asset` (string): the logical file name of an asset (ex: `hello-world.json`)
 * `$params` (array): an optional set of parameters describing how to build the asset

## Returns

 * null

## Example

```php
function mymodule_civicrm_getAssetUrl(&$asset, &$params) {
  if ($asset === 'hello-world.json') {
    $params['planet'] = 'Earth';
  }
}
```
