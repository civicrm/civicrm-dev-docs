# hook_civicrm_summary

## Summary

This hook is called when the contact summary is rendered, allowing you to modify the summary with your own content.

## Definition

    hook_civicrm_summary( $contactID, &$content, &$contentPlacement = CRM_Utils_Hook::SUMMARY_BELOW )

## Parameters

-   $contactID the contactID for whom the contact summary is being
    generated
-   $contentPlacement (output parameter) where should the hook content
    be displayed relative to the exiting content. One of
    CRM_Utils_Hook::SUMMARY_BELOW, CRM_Utils_Hook::SUMMARY_ABOVE,
    CRM_Utils_Hook::SUMMARY_REPLACE. Default is to add content BELOW
    default contact summary content.

## Example

    function civitest_civicrm_summary( $contactID, &$content, &$contentPlacement ) {
        // REPLACE default Contact Summary with your customized content
        $contentPlacement = CRM_Utils_Hook::SUMMARY_REPLACE;
        $content = "
    <table>
    <tr><th>Hook Data</th></tr>
    <tr><td>Data 1</td></tr>
    <tr><td>Data 2</td></tr>
    </table>
    ";

    }
