# hook_civicrm_findDuplicates

## Summary

This hook is called when contacts are added/updated via profiles, event registration pages, contribution pages etc.
When a form is submitted CiviCRM checks if a contact already exists using one of the built-in deduplication rules and returns a contact ID if a match is found.

This hook allows you to override the contact matching rules to implement more complex rules.

## Notes

The dedupe mechanism is triggered in four places:

1.  when a CMS user account is created and connected to an existing or
    new CiviCRM contact;
2.  during contact imports, where the incoming file records are compared
    with the existing database;
3.  when a contact record is created through the interface; and
4.  when a find duplicate contacts rule is run (comparing a group of
    contacts or the entire database with itself).

Using the hook parameters, you can isolate how and when your rule
modifications are used.

Note that this hook depends upon the existence of a dedupe rule created
in the interface in order for it to be called. It works by allowing
access to the queries constructed by an interface-create rule.You can
modify or completely replace the query or queries that would be run at
the point the hook is called, as illustrated below.

You cannot define rule groups with this hook.

## Definition

    hook_civicrm_findDuplicates($dedupeParams, &$dedupeResults, $contextParams)

## Parameters

-   @param array $dedupeParams

      Array of params for finding duplicates:
      ```
      [
        '{parameters returned by CRM_Dedupe_Finder::formatParams},
        'check_permission' => TRUE/FALSE,
        'contact_type' => $contactType,
        'rule' = $rule,
        'rule_group_id' => $ruleGroupID,
        'excludedContactIDs' => $excludedContactIDs
      ]
      ```
-   @param array $dedupeResults

      Array of results: 
      ```
      [
        'handled' => TRUE/FALSE,
        'ids' => array of IDs of duplicate contacts
      ]
      ```
-   @param array $contextParams

      The context if relevant, eg. `['event_id' => X]`


## Returns

-   null

## Availability

-   Available since 5.12

## Example

An organisation wants to allow duplicate contacts to be created if they did NOT already exist in list of contacts from a specific smart group. 
But they wanted to use the existing contact record if the contact DID already exist in that smart group.

This example uses a Group ID specified via a custom field for an event (`duplicate_if_in_groups`) and returns all contact IDs that match within that group based on the deduplication rule configured for the event.


```
/**
 * Implements hook_civicrm_findDuplicates().
 *
 * When submitting an online event registration page we check for duplicate contacts based on specific groups
 *  as specified in the event custom field 'duplicate_if_in_groups'
 */
function example_civicrm_findDuplicates($dedupeParams, &$dedupeResults, $context) {
  // Do we have an event?
  if (empty($context['event_id'])) {
    return;
  }
  try {
    $eventParams = [
      'id' => $context['event_id'],
      'return' => CRM_Example_Utils::getField('duplicate_if_in_groups'),
    ];
    // Get the group that this event allows duplicate contacts for
    $duplicateGroupId = civicrm_api3('Event', 'getsingle', $eventParams);
    $duplicateGroupId = CRM_Utils_Array::value(CRM_Example_Utils::getField('duplicate_if_in_groups'), $duplicateGroupId);
    // As we are submitting from anonymous event registration form we don't want to check permissions to find matching contacts.
    $dedupeParams['check_permission'] = FALSE;
    // Run the "standard" dedupe routine. This will return one or more contact IDs based on the unsupervised dedupe rule
    $dedupeResults['ids'] = CRM_Dedupe_Finder::dupesByParams($dedupeParams, $dedupeParams['contact_type'], $dedupeParams['rule'], $dedupeParams['excluded_contact_ids'], $dedupeParams['rule_group_id']);
    if (!empty($dedupeResults['ids'])) {
      $duplicateContactIds = [];
      foreach ($dedupeResults['ids'] as $duplicateContactId) {
        // We've got a duplicate contact ID.  If that ID is in the specified group we return the duplicate ID,
        // Otherwise we return an empty array (no duplicates) and allow the contact to be created again.
        $contactGroups = civicrm_api3('Contact', 'getsingle', [
          'id' => $duplicateContactId,
          'return' => ['group'],
        ]);
        // Loop through each of the groups linked to the contact ID to see if any match our group
        if (!empty($contactGroups['groups'])) {
          $groups = explode(',', $contactGroups['groups']);
          foreach ($groups as $groupId) {
            if ($groupId == $duplicateGroupId) {
              $duplicateContactIds[] = $duplicateContactId;
              break;
            }
          }
        }
      }
      // If we found duplicates this array will contain those IDs, otherwise it will be an empty array.
      $dedupeResults['ids'] = $duplicateContactIds;
    }
    $dedupeResults['handled'] = TRUE;
    return;
  }
  catch (Exception $e) {
    Civi::log()->debug('example_civicrm_findDuplicates: ' . $e->getMessage());
    return;
  }
}
```