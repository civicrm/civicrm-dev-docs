# hook_civicrm_summary

## Description

This hook is called when contact summary is rendered and you can add on
top, below or replace summary with your own html content.

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
        $contentPlacement = 3;
        $content = "
    <table>
    <tr><th>Hook Data</th></tr>
    <tr><td>Data 1</td></tr>
    <tr><td>Data 2</td></tr>
    </table>
    ";

    }