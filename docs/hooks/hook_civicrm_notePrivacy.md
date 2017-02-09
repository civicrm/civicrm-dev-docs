# hook_civicrm_notePrivacy

## Description

This hook provides a way to override the default privacy behavior for
notes.

If a user has the "View All Notes" permission, this hook is bypassed.

See also [this blog
post](https://civicrm.org/blogs/allenshaw/adding-privacy-and-comments-civicrm-notes).

## Availability

This hook is available in CiviCRM 3.3+.

## Definition

    notePrivacy(&$noteValues)

## Parameters

-   &$noteValues - an array.  The values from an object of type
    CRM_Core_DAO_Note, converted to an array.

## Returns

-   null

## Example

    function civitest_civicrm_notePrivacy (&$note) {
        /* CiviCRM will check for existence of $note['notePrivacy_hidden'].
         * If this value is not set, CiviCRM will show or display the note
         * based on the default, which is to display private notes only to
         * the note author.
         * If this value is set, CiviCRM will hide the note if the value is
         * TRUE, and display the note if the value is FALSE.
         */
        if ($note['is_private']) {
            if ($my_business_rules_say_so) {
                $note['notePrivacy_hidden'] = TRUE;
            } else {
                $note['notePrivacy_hidden'] = FALSE;
        }
    }