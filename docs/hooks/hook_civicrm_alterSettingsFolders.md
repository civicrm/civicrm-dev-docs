# hook_civicrm_alterSettingsFolders

## Summary

This hook allows modules and extensions to scan for settings in
additional folders.

## Notes

The [Settings](../framework/setting.md) subsystem
provides metadata about many of CiviCRM's internal settings by scanning
for files matching "settings/*.setting.php" (e.g.
[settings/Core.setting.php](https://github.com/civicrm/civicrm-core/blob/4.3/settings/Core.setting.php)).

## Availability

This hook was introduced in CiviCRM v4.3.0 and v4.2.10.

## Definition

    hook_civicrm_alterSettingsFolders(&$metaDataFolders)

## Parameters

-   @param array $metaDataFolders list of directory paths
