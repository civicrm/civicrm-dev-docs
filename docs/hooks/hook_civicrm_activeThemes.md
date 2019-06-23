# hook_civicrm_activeTheme

## Summary

The activeTheme hook determines which theme is active.

## Definition

    hook_civicrm_activeTheme( &$theme, $context)

## Parameters

   - parameter string $theme
      The identifier for the theme. Alterable.
      Ex: 'greenwich'.
   - parameter array $context
      Information about the current page-request. Includes some mix of:
      - page: the relative path of the current Civi page (Ex: 'civicrm/dashboard').
      - themes: an instance of the Civi\Core\Themes service.

## Returns

-   null

## Availability

-   This hook was first available in CiviCRM 5.16

## Example

```php
     /*
     * Set which theme is active. 
     */

    function civitest_civicrm_activeTheme( &$theme, $context ) {
      $theme = 'civielection';
    }
```
