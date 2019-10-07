# hook_civicrm_emailProcessorContact

## Summary

This hook is called by the Email Processor when deciding which
contact to create an activitity for recording an inbound email.

## Notes

You can use this hook to choose a different
contact or decide whether it should create contacts.

## Definition

    hook_civicrm_emailProcessorContact($email, $contactID, &$result)

## Parameters

-   @param string $email - the email address
-   @param int $contactID - the contactID that matches this email address, IF it exists
-   @param array  $result (reference) has two fields:
    - contactID - the new (or same) contactID
    - action - 3 possible values:
      - `CRM_Utils_Mail_Incoming::EMAILPROCESSOR_CREATE_INDIVIDUAL` - create a new contact record
      - `CRM_Utils_Mail_Incoming::EMAILPROCESSOR_OVERRIDE` - use the new contactID
      - `CRM_Utils_Mail_Incoming::EMAILPROCESSOR_IGNORE` - skip this email address

## Returns

-   null

## Availability

This hook was first available in CiviCRM 4.1.0

## Example

```php
function civitest_civicrm_emailProcessorContact($email, $contactID, &$result) {
  require_once 'CRM/Utils/Mail/Incoming.php';

  // first split the email into name and domain
  // really simple, definitely wrong implementation
  list($mailName, $mailDomain) = CRM_Utils_System::explode('@', $email, 2);

  // we are doing all our checks based on mailDomain, so if empty
  // return and let EmailProcessor do its own thing
  if (empty($mailDomain)) {
    return;
  }

  define('FILE_TO_ORG_ALWAYS_TAG', 'MyTag1');
  $orgID = _civitest_find_org_with_tag(FILE_TO_ORG_ALWAYS_TAG, $mailDomain);
  if ($orgID) {
    $result = array(
      'contactID' => $orgID,
      'action' => CRM_Utils_Mail_Incoming::EMAILPROCESSOR_OVERRIDE,
    );
    return;
  }

  // if we already have a match, we will
  // return and let EmailProcessor do its own thing
  if ($contactID) {
    return;
  }

  // Orgs with this tag will have same-domain emails filed on them only if it
  // passes through the ALWAYS tag check without finding a match, AND it does
  // not match an individual.
  define('FILE_TO_ORG_INDIVIDUAL_UNMATCHED_TAG', 'MyTag2');
  $orgID = _civitest_find_org_with_tag(FILE_TO_ORG_INDIVIDUAL_UNMATCHED_TAG, $mailDomain);
  if ($orgID) {
    $result = array(
      'contactID' => $orgID,
      'action' => CRM_Utils_Mail_Incoming::EMAILPROCESSOR_OVERRIDE);
    return;
  }

  $result = array('action' => CRM_Utils_Mail_Incoming::EMAILPROCESSOR_CREATE_INDIVIDUAL);
}
```
