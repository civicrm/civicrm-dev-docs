# hook_civicrm_post_case_merge

## Summary

This hook is called after a case merge happens.

## Notes

A case merge is when two cases are merged or when a case is reassigned to another client.

Added in CIviCRM 4.5

## Definition

    civicrm_post_case_merge($mainContactId, $mainCaseId, $otherContactId, $otherCaseId, $changeClient)

## Parameters

-   $mainContactId - Contact ID of the new case (if set already)
-   $mainCaseId - Case ID of the new case (if set already)
-   $otherContactId - Contact ID of the original case
-   $otherCaseId - Case ID of the original case
-   $changeClient - boolean if this function is called to change
    clients

## Return

-   Returns null

## Example

In this example we want to move the linked documents of a case to the
new case.

    function documents_civicrm_post_case_merge($mainContactId, $mainCaseId = NULL, $otherContactId = NULL, $otherCaseId = NULL, $changeClient = FALSE) {
      $repo = CRM_Documents_Entity_DocumentRepository::singleton();
      if (!empty($mainCaseId) && !empty($otherCaseId)) {
        $docs = $repo->getDocumentsByCaseId($otherCaseId);
        $case = civicrm_api('Case', 'getsingle', array('id' => $otherCaseId, 'version' => 3));
        foreach($docs as $doc) {
          $doc->addCaseId($mainCaseId);
          if ($changeClient) {
            $doc->removeCaseId($otherCaseId); //remove the old case
          }
          foreach($case['client_id'] as $cid) {
            $doc->addContactId($cid);
          }
          $repo->persist($doc);
        }
      }
    }



## See also

[hook_civicrm_pre_case_merge](hook_civicrm_pre_case_merge.md)