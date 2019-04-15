# hook_civicrm_contact_get_displayname

## Summary

This hook is called to retrieve the display name of a contact, allowing you to return a custom display name.

## Notes

Probably you won't need this hook but in some case it might be useful.
For example you want to show who is a manager of an organisation but you
don't want to store this in the database.

## Definition

    civicrm_contact_get_displayname(&$display_name, $contactId, $objContact)

## Parameters

-   &$display_name - the current display name, you can change the
    display name by changing the contents of this parameter
-   $contactId - Contact ID
-   $objContact - The contact BAO

## Returns

-   null

## Example

Below an example of showing the contact ID after the display name

    function myextension_civicrm_contact_get_displayname(&$display_name, $contactId, $objContact) {
        $display_name = $display_name . ' - '.$contactId;
    }
