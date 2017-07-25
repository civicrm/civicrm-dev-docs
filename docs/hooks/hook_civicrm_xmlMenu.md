# hook_civicrm_xmlMenu

## Summary

This hook is called when building CiviCRM's menu structure, which is
used to render urls in CiviCRM.

!!! note "Comparison of Related Hooks"
    This is one of three related hooks. The hooks:

    -   [hook_civicrm_navigationMenu](/hooks/hook_civicrm_navigationMenu.md) manipulates the navigation bar at the top of every screen
    -   [hook_civicrm_alterMenu](/hooks/hook_civicrm_alterMenu.md) manipulates the list of HTTP routes (using PHP arrays)
    -   [hook_civicrm_xmlMenu](/hooks/hook_civicrm_xmlMenu.md) manipulates the list of HTTP routes (using XML files)

!!! tip "Applying changes"

    Menu data is cached. After making a change to the menu data, [clear the system cache](/tools/debugging.md#clearing-the-cache).

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

## XML: Common

Several elements are supported in any route:

 * `<path>` (ex:`civicrm/ajax/my-page`): This specifies the URL of the page. On a system like Drupal (which supports "clean URLs"), the full page URL would look like `http://example.org/civicrm/ajax/my-page`.
 * `<page_callback>` (ex: `CRM_Example_Page_AJAX::runMyPage` or `CRM_Example_Page_MyStuff`): This specifies the page-controller, which may be any of the following:
    * Static function (ex: `CRM_Example_Page_AJAX::runMyPage`)
    * A subclass of `CRM_Core_Page` named `CRM_*_Page_*` (ex: `CRM_Example_Page_MyStuff`)
    * A subclass of `CRM_Core_Form` named `CRM_*_Form_*` (ex: `CRM_Example_Form_MyStuff`)
    * A subclass of `CRM_Core_Controller` named `CRM_*_Controller_*` (ex: `CRM_Example_Controller_MyStuff` )
 * `<access_arguments>` (ex: `administer CiviCRM`): A list of permissions required for this page.
    * If you'd like to reference *new* permissions, be sure to declare them with [hook_civicrm_permission](/hooks/hook_civicrm_permission.md).
    * To require *any one* permission from a list, use a semicolon (`;`). (ex: `edit foo;administer bar` requires `edit foo` **or** `administer bar`)
    * To require *multiple* permissions, use a comma (`,`). (ex: `edit foo,administer bar` requires `edit foo` **and** `administer bar`)
    * At time of writing, mixing `,`s and `;`s has not been tested.
 * `<title>` (ex: `Hello world`): Specifies the default value for the HTML `<TITLE>`. (This default be programmatically override on per-request basis.)

!!! caution "Caution: Wildcard sub-paths"
    One path can match all subpaths.  For example, `<path>civicrm/admin</path>` can match `http://example.org/civicrm/admin/f/o/o/b/a/r`.  However, one should avoid designs which rely on this because it's imprecise and it can be difficult to integrate with some frontends.

## XML: Administration

The administration screen (`civicrm/admin`) includes a generated list of fields. This content is determined by some additional elements:

 * `<desc>`
 * `<icon>`
 * `<adminGroup>`
 * `<weight>`

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
    [hook_civicrm_idsException](/hooks/hook_civicrm_idsException.md) supports a blanket exemption for the entire page.
    When possible, it is better to use a narrow exception.
