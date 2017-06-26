# hook_civicrm_dashboard

## Summary

This hook is called when rendering the dashboard page and can be
used to add content to the dashboard page.

## Definition

    hook_civicrm_dashboard( $contactID, &$contentPlacement = self::DASHBOARD_BELOW )

## Parameters

-   $contactID the contactID for whom the dashboard is being generated
-   $contentPlacement (output parameter) where should the hook content
    be displayed relative to the activity list. One of
    CRM_Utils_Hook::DASHBOARD_BELOW,
    CRM_Utils_Hook::DASHBOARD_ABOVE,
    CRM_Utils_Hook::DASHBOARD_REPLACE. Default is to add content
    BELOW the standard dashboard Activities listing. DASHBOARD_REPLACE
    replaces the standard Activities listing with the provided content.

## Returns

-   the HTML to include in the dashboard

## Example

    function civitest_civicrm_dashboard( $contactID, &$contentPlacement ) {
        // REPLACE Activity Listing with custom content
        $contentPlacement = 3;
        return array( 'Custom Content' => "Here is some custom content: $contactID",
                      'Custom Table' => "
    <table>
    <tr><th>Contact Name</th><th>Date</th></tr>
    <tr><td>Foo</td><td>Bar</td></tr>
    <tr><td>Goo</td><td>Tar</td></tr>
    </table>
    ",
                      );
    }

Also check [Civitest Sample
Module](http://svn.civicrm.org/civicrm/branches/v2.2/drupal/civitest.module.sample)