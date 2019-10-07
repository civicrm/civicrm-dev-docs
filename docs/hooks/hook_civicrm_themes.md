# hook_civicrm_themes

## Summary

This hook is called when building a list of available themes for use within CiviCRM.

## Definition

    hook_civicrm_themes( &$themes )

## Parameters

-  array  $themes - array of theme information 
    - ext: string (required)
      The full name of the extension which defines the theme.
      Ex: "org.civicrm.themes.greenwich".
    - title: string (required)
      Visible title.
    - help: string (optional)
      Description of the theme's appearance.
    - url_callback: mixed (optional)
       A function ($themes, $themeKey, $cssExt, $cssFile) which returns the URL(s) for a CSS resource.
       Returns either an array of URLs or PASSTHRU.
       Ex: \Civi\Core\Themes\Resolvers::simple (default)
       Ex: \Civi\Core\Themes\Resolvers::none
    - prefix: string (optional)
      A prefix within the extension folder to prepend to the file name.
    - search_order: array (optional)
      A list of themes to search.
      Generally, the last theme should be "*fallback*" (Civi\Core\Themes::FALLBACK).
    - excludes: array (optional)
      A list of files (eg "civicrm:css/bootstrap.css" or "$ext:$file") which should never
      be returned (they are excluded from display).     - object being imported (for now Contact

## Returns

-   null

## Availability

-   This hook was first available in CiviCRM 5.16

## Example

A minimal example:

```php
     /*
     * A theme is a set of CSS files which are loaded on CiviCRM pages.
     */
    function civitest_civicrm_themes( &$themes ) {
      $themes['civielection'] = [
        'title' => 'civielection theme',
        'ext' => 'au.org.greens.civielection',
      ];
    }
```

A more detailed example

```php
     /*
     * A theme is a set of CSS files which are loaded on CiviCRM pages.
     */
    function civitest_civicrm_themes( &$themes ) {
      $themes['civielection'] = [
        'title' => 'civielection theme',
        'ext' => 'au.org.greens.civielection',
        'name' => 'civielection',
        'url_callback' => '\\Civi\\Core\\Themes\\Resolvers::simple',
        'search_order' => [
          0 => 'civielection',
          1 => Civi\Core\Themes::FALLBACK,
        ],
        'prefix' => 'election',
        'excludes' => ['bootstrap.css'],
      ];
    }
```
