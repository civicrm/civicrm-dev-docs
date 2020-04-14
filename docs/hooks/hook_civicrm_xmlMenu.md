# hook_civicrm_xmlMenu

## Summary

This hook is called when building CiviCRM's menu structure, which is
used to render urls in CiviCRM.

## Notes

!!! note "Comparison of Related Hooks"
    This is one of three related hooks. The hooks:

    -   [hook_civicrm_navigationMenu](hook_civicrm_navigationMenu.md) manipulates the navigation bar at the top of every screen
    -   [hook_civicrm_alterMenu](hook_civicrm_alterMenu.md) manipulates the list of HTTP routes (using PHP arrays)
    -   [hook_civicrm_xmlMenu](hook_civicrm_xmlMenu.md) manipulates the list of HTTP routes (using XML files)

!!! tip "Applying changes"

    Menu data is cached. After making a change to the menu data, [clear the system cache](../tools/debugging.md#clearing-the-cache).

## Definition

    hook_civicrm_xmlMenu( &$files )

## Parameters

-   `$files` the array for files used to build the menu. You can append
    or delete entries from this file. You can also override menu items
    defined by CiviCRM Core.

## Returns

-   `null`

## Example

To define a new route, create an XML file (`my_route.xml`) in your extension or module:

```xml
<?xml version="1.0" encoding="iso-8859-1" ?>
<menu>
  <item>
     <path>civicrm/ajax/my-page</path>
     <page_callback>CRM_Example_Page_AJAX::runMyPage</page_callback>
     <access_arguments>administer CiviCRM</access_arguments>
  </item>
</menu>
```

and register this using `hook_civicrm_xmlMenu`:

```php
function EXAMPLE_civicrm_xmlMenu(&$files) {
    $files[] = dirname(__FILE__) . '/my_route.xml';
}
```

## XML Structure

See the [routing](../framework/routing.md) page for details on the XML schema.

## XML: IDS

[PHPIDS](https://github.com/PHPIDS/PHPIDS) provides an extra layer of
security to mitigate the risk of cross-site scripting vulnerabilities, SQL
injection vulnerabilities, and so on.  In CiviCRM, PHPIDS scans all inputs
for suspicious data (such as complex Javascriptor SQL code) before allowing
the page-controller to execute.

However, in some rare occasions, it is expected that the page-controller
will accept otherwise suspicious data -- for example, a REST endpoint may
accept JSON which superfically resembles complex XSS Javascript code; or an
administrative form may allow admins to customize the HTML of a screen.
When processing these page-requests, PHPIDS may generate false alarms.

In the following example, we provide hints to PHPIDS indicating that the
page `civicrm/my-form` accepts some inputs (`field_1`, `field_2`, `field_3`,
and `field_4`) which may ordinarily look suspicious.

```xml
<?xml version="1.0" encoding="iso-8859-1" ?>
<menu>
  <item>
    <path>civicrm/my-form</path>
    ...
    <ids_arguments>
      <!-- Fields #1 and #2 accept JSON input. These are partially exempt from PHPIDS -- they use less aggressive heuristics. -->
      <json>field_1</json>
      <json>field_2</json>
      <!-- Field #3 accepts HTML input. It is  partially exempt from PHPIDS -- they use less aggressive heuristics. -->
      <html>field_3</html>
      <!-- Field #4 accepts anything; it is not protected by PHPIDS heuristics. -->
      <exception>field_4</exception>
    </ids_arguments>
  </item>
</menu>
```

!!! tip "Tip: Narrow exceptions are better than blanket exceptions"
    The `<ids_arguments>` element allows you to define a narrow exception for a specific field on a specific page.
    [hook_civicrm_idsException](hook_civicrm_idsException.md) supports a blanket exemption for the entire page.
    When possible, it is better to use a narrow exception.
