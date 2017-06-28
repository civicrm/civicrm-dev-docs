# hook_civicrm_alterSettingsMetaData

## Summary

This hook is called when Settings have been loaded from the xml. 
It is an opportunity for hooks to alter the data.

## Definition

    alterSettingsMetaData(&$settingsMetaData, $domainID, $profile)

## Parameters

-   @param array $settingsMetaData Settings Metadata.
-   @param int $domainID
-   @param mixed $profile
