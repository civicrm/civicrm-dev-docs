# Hooks Changelog

*This page lists hooks added, removed, deprecated or modified with each new release of CiviCRM.*

For API changes, see [APIv4 Changelog](../api/v4/changes.md) and [APIv3 Changelog](../api/v3/changes.md).

## CiviCRM 5.x

### 5.25: hook_civicrm_postCommit added

[hook_civicrm_postCommit](hook_civicrm_postCommit.md) is a variant of [hook_civicrm_post](hook_civicrm_post.md) which is deferred until the relevant data is fully committed to the database.

### 5.11: hook_civicrm_pageRun invocation removed from CRM_Core_Page_Inline_Help

`CRM_Core_Page_Inline_Help` is the class that fetches inline documentation from `.hlp` templates to be shown in help baloons. `hook_civicrm_pageRun` normally does not run when fetching help, except in the (very rare) case that a site has been customized with an `.extra.hlp` file, which potentially causes problems because the class `CRM_Core_Page_Inline_Help` does not extend `CRM_Core_Page`. The inconsistent hook invocation [has been removed](https://github.com/civicrm/civicrm-core/commit/87bf0ec4c246b03e3e6c2ab2fb0c14664473c52b).

## CiviCRM 4.7

### 4.7.14: hook_civicrm_pre & hook_civicrm_post supports CustomField

Pre and post hooks now fire when adding, updating or deleting custom fields.

### 4.7.0: New hook_civicrm_selectWhereclause

This hook can be used to impose access limits on most entities fetched via the api by adding conditions to the where clause.

### 4.7.0 hook_civicrm_tabs deprecated

This hook is deprecated in 4.7. Use `hook_civicrm_tabset` instead.

### 4.7.0 hook_civicrm_validate removed

This hook was deprecated since v4.2 in favor of `hook_civicrm_validateForm`.

## CiviCRM 4.5

### 4.5.0: hook_civicrm_enableDisable removed

The deprecated enableDisablehook  was not reliably invoked every time an entity was enabled or disabled.It was removed in 4.5. Use the standard *pre* and *post* hooks instead.

### 4.5.0: hook_civicrm_referenceCounts

The new API call "getrefcount" allows one to ask about references to a given record. Using [hook_civicrm_referenceCounts](hook_civicrm_referenceCounts.md), a third-party developer can modify the reference-count.

## CiviCRM 4.4

### 4.4.0: Add hooks for profile forms

Added hooks for 

- "civicrm/profile/view" [hook_civicrm_viewProfile](hook_civicrm_viewProfile.md),
- "civicrm/profile" [hook_civicrm_searchProfile](hook_civicrm_searchProfile.md),
- "civicrm/profile/edit" or "civicrm/profile/create" [hook_civicrm_buildProfile](hook_civicrm_buildProfile.md),[hook_civicrm_validateProfile](hook_civicrm_validateProfile.md), [hook_civicrm_processProfile](hook_civicrm_processProfile.md)).

See also: [CRM-12865](http://issues.civicrm.org/jira/browse/CRM-12865)

### 4.4.0: hook_civicrm_searchColumns: Change of $values in profile-listings

When displaying a profile-based listing (such as "civicrm/profile?gid=XXX"), the $values represents a row/column matrix by providing an array of rows. Each row contains several cells keyed numerically, but one cell was inconsistently keyed by the string `sort_name` instead of its numeric position. The `sort_name` cell appeared early and affected the numbering of all subsequent cells. For greater consistency, the `sort_name` will now be identified numerically, and other cells will be renumbered accordingly.

### 4.4.0: hook_civicrm_buildUFGroupsForModule: Change to $ufGroups

$ufGroups provides an array of UFGroup records. In previous releases, this array always contained the same fields for each record (`id`, `title`, `is_reserved`, etc). In 4.4, the fields are usually the same, but they may vary depending on the context. See patches to [CRM-13388](http://issues.civicrm.org/jira/browse/CRM-13388)
