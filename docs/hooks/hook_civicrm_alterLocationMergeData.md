# hook_civicrm_alterLocationMergeData

## Summary

This hook allows you to alter the location information that will be moved from
the duplicate contact to the master contact.

## Availability

This hook was first available in CiviCRM 4.7.10.

## Definition

```php
hook_civicrm_alterLocationMergeData(&$blocksDAO, $mainId, $otherId, $migrationInfo)
```

## Parameters

   * array $blocksDAO: Array of location DAO to be saved. These are arrays in 2 keys 'update' & 'delete'.
   * int $mainId: Contact ID of the contact that survives the merge.
   * int $otherId: Contact ID of the contact that will be absorbed and deleted.
   * array $migrationInfo: Calculated migration info.

## Details

The $blocksDAO contains a list of 'location blocks' (eg: emails, phones,
addresses) which will be updated or deleted as part of the merge. This is
formatted like:

```php
[
  email
    delete
      id => object
    update
      id => object
  address
    delete
      id => object
    update
      id => object
]
```
