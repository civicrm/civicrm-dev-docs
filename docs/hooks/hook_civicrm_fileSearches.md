# hook_civicrm_fileSearches

## Summary

This hook allows you to add a reference to a file search service (e.g. Solr).

## Availability

This hook is available in CiviCRM 4.5+.

## Definition

    function fileSearches(&$fileSearches)

## Parameters

-   &$fileSearches - an array whose elements are all of type
    CRM_Core_FileSearchInterface.

## Returns

-   mixed

## Example

    function apachesolr_civiAttachments_civicrm_fileSearches(&$fileSearches) {
       require_once __DIR__ . '/DrupalSolrCiviAttachmentSearch.php';
       $fileSearches[] = new DrupalSolrCiviAttachmentSearch();
     }