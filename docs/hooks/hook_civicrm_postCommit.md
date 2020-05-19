# hook_civicrm_postCommit

## Summary

This hook is called after the DB *commits* changes (for certain objects).

## Notes

<!-- TODO: The notes at the top of hook_civicrm_{pre,post,postCommit} are
all kind of high-level, applicable to all hooks. Figure a better place to put this. -->

`hook_civicrm_post` and `hook_civicrm_postCommit` are nearly identical. They differ in timing and transaction management.

* `hook_civicrm_post`: Runs immediately after the change is *sent* to the DB. If there's a SQL transaction, then it runs within the transaction.
* `hook_civicrm_postCommit`: Runs after the change is committed to the DB. It always runs outside of any SQL transactions.

For example, suppose a user submits an "Event Registration" form.  Recording the registration may require writing records in
multiple tables (`civicrm_contact`, `civicrm_participant`, and `civicrm_log`).  These writes are performed atomically - grouped
into a database transaction. Loosely, the operation sends these SQL statements:

```sql
BEGIN;
INSERT INTO civicrm_contact (...) VALUES (...);
INSERT INTO civicrm_log (...) VALUES (...);
INSERT INTO civicrm_participant (...) VALUES (...);
INSERT INTO civicrm_log (...) VALUES (...);
COMMIT;
```

Now let's compare the hooks:

* `hook_civicrm_post` will fire immediately after each `INSERT` (before the `COMMIT`).
* `hook_civicrm_postCommit` will fire after the `COMMIT`.
* If an extension listens to `hook_civicrm_post` and throws an exception, then it can interrupt the transaction: the user gets
  an error screen, the operation is rolled back, and the user needs to retry later.
* If an extension listens to `hook_civicrm_postCommit` and throws an exception, it does not affect the main operation -
  because that has already committed. The user may get an error screen, but there's no need to resubmit.

The choice between hooks comes down to *how critical* and *how reliable* the work is (from a busines point of view). For example:

* If you're using a hook to play a chime on the speaker in the office whenever someone registers, then it's probably not critical (*because
  you'd want the registration even if the chime is unplayable*), and it's also rather unreliable (*because a coworker might get frustrated by all
  the chimes and unplug the whole speaker apparatus*). Use `postCommit`.
* If you're working in a regulated domain and using a hook to create a detailed audit trail in another table (`civicrm_log_plus_plus`),
  then it is critical (*because regulators will complain if the records are missing*) and fairly reliable (*because it's in the same
  database as all the other records*).

!!! note "What if the use-case does not involve an explicit transaction?"

    If there is no active SQL transaction, then the DB is in auto-commit mode. The timing of `post` and `postCommit` is essentially the same.

!!! note "What if I have a critical task that is also unreliable?"

    Consider using a queue pattern. For example:

    * During `hook_civicrm_post`, add the task to the SQL-backed queue (`civicrm_queue`).
    * The task will be queued if (and only if) the main operation commits.
    * In the queue worker, look out for errors and arrange for retries.



## Definition

```php
hook_civicrm_postCommit($op, $objectName, $objectId, &$objectRef)
```

## Parameters

-   `$op` - the operation being performed with CiviCRM object (e.g. `create` or `edit`)
-   `$objectName` - the type of object being updated (e.g. `Activity` or `Contact)
-   `$objectId` - the unique identifier for the object. `tagID` in case of `EntityTag`
-   `$objectRef` - the reference to the object if available. For case of `EntityTag` it is an array of (`entityTable`, `entityIDs`)

For full details about these parameters, see [hook_civicrm_post](hook_civicrm_post.md) for full details.

## Returns

-   None
