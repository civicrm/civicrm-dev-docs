# hook_civicrm_batchItems

## Summary

This hook is called when a CSV batch export file is about to be
generated.

## Notes

Notice that this hook will be called in per batch bases, e.g.
if 3 batches are going to be exported in the same CSV then this hook
will be called three times regarding each batch.

## Definition

```php
hook_civicrm_batchItems(&$results, &$items)
```

## Parameters

-   `$results` - the query result for the current batch that is being processed
-   `$items` - the entries of financial items that will actually become the records on the CSV (still per batch based)

## Hints

-   This hook can be used together with `hook_civicrm_batchQuery` to add/modify the information in CSV batch exports
-   You can loop through the two parameters to modify per financial item. This can even be used to filter financial items.
