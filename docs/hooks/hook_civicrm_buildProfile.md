# hook_civicrm_buildProfile

## Summary

This hook is called while preparing a profile form. This form allows for extension authors to add various scripts onto the profile pages. Note that `hook_civicrm_buildForm` is not fired for profile pages

## Definition

buildProfile($profileName)

## Parameters

- $profileName - the (machine readable) name of the profile.

## Returns

- null

```php
function myext_civicrm_buildProfile($profileName) {
  if ($profileName === 'MyTargetedProfile) {
    CRM_Core_Resources::singleton()->addScriptFile('org.example.myext', 'some/fancy.js', 100);
  }
}
```
