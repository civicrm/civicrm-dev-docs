# hook_civicrm_buildAsset

## Summary

This hook fires whenever the system builds a semi-dynamic asset.

## Notes

For more discussion, see [AssetBuilder](../framework/asset-builder.md).

## Definition

    hook_civicrm_buildAsset($asset, $params, &$mimeType, &$content)

## Parameters

 * `$asset` (string): the logical file name of an asset (ex: `hello-world.json`)
 * `$params` (array): an optional set of parameters describing how to build the asset
 * `$mimeType` (string, output): the MIME type of the asset (ex: `application/json`)
 * `$content` (string, output): the full content of the asset

## Returns

 * null

## Example

```php
function mymodule_civicrm_buildAsset($asset, $params, &$mimeType, &$content) {
  if ($asset === 'hello-world.json') {
    $mimeType = 'application/json';
    $content = json_encode(array('hello', 'world'));
  }
}
```