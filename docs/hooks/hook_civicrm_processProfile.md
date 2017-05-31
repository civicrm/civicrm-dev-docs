# hook_civicrm_processProfile

## Description

This hook is called processing a valid profile form submission (e.g. for "civicrm/profile/create" or "civicrm/profile/edit").

## Definition

    processProfile($profileName)

## Parameters

-   $profileName - the (machine readable) name of the profile.

!!! Tip
    In SQL, this corresponds to the "name" column of table "civicrm_uf_group"

## Returns

-   null