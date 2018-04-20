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

* array `$blocksDAO`: Array of location DAO objects. Formatted as follows:

    ```php
    [
      'email' => [
        'delete' => ['id' => object],
        'update' => ['id' => object],
      ],
      'address' => [
        'delete' => ['id' => object],
        'update' => ['id' => object],
      ],
    ]
    ```
    
* int `$mainId`: Contact ID of the contact that survives the merge.
* int `$otherId`: Contact ID of the contact that will be absorbed and deleted.
* array `$migrationInfo`: Calculated migration info.
