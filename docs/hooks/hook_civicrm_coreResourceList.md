# hook_civicrm_coreResourceList

## Summary

This hook is called when the list of core js/css resources is about to
be processed, giving you the opportunity to modify
the list prior to the resources being added, or add your own.

## Notes

Added in v4.6.6.

See [Resource Reference](../framework/resources.md)
for more information.

## Definition

```php
hook_civicrm_coreResourceList(&$list, $region)
```

## Parameters

* `$list` - an array of items about to be added to the page. Items in the
list may be:

    * A string ending in `.js`
    * A string ending in `.css`
    * An array of settings (will be added to the javascript CRM object).

* `$region` - target region of the page - normally this is `"html-header"`

## Example


```php
function myextension_civicrm_coreResourceList(&$list, $region) {
  // Prevent navigation.css from loading
  $cssWeDontWant = array_search('css/navigation.css', $list);
  unset($list[$cssWeDontWant]);

  // Add some css of our own to the page.
  // Note that if the file we want to add is outside civicrm directory
  // (e.g. in an extension) we can't just append it to the list.
  // But we can add it directly, which is what happens to items in this list anyway.
  Civi::resources()->addStyleFile('org.example.myextension', 'css/my_style.css', 0, $region);

  // Add a setting - in this example we override the CKEditor config file location
  $myCKEConfFile = Civi::resources()->getUrl('org.example.myextension', 'js/my-ckeditor-config.js');
  $list[] = ['config' => ['CKEditorCustomConfig' => ['default' => $myCKEConfFile]]];
}
```
