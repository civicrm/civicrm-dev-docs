# hook_civicrm_crudLink

## Summary

Generate a default CRUD URL for an entity.

## Availability

This hook is available in CiviCRM 4.5+.

## Definition

    crudLink($spec, $bao, &$link)

## Parameters

-   $spec - An array with keys:

    -   action: int, eg CRM_Core_Action::VIEW or
        CRM_Core_Action::UPDATE
    -   entity_table: string
    -   entity_id: int
-   $bao CRM_Core_DAO

-   $link - An array.



    To define the link, add these keys to $link:

-   title: string

-   path: string

-   query: array

-   url: string (used in lieu of "path"/"query")

    Note: if making "url" CRM_Utils_System::url(), set $htmlize=false



## Returns

-   mixed