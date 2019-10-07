# Database Transaction Reference

## Background

In online transaction processing, proper error-handling requires that
one demarcate the beginning and ending of each database transaction. If
you're unfamiliar with database transactions, then you may want to read
some background material like:

-   <http://en.wikipedia.org/wiki/Database_transaction>
-   <http://dev.mysql.com/doc/refman/5.1/en/commit.html>

Generally, managing transactions is about:

* Marking the start and end of the transaction
* Handling errors and rolling back the transaction

## Examples

### Wrapping code in a transaction

#### Exception-safe helper

(recommended for v4.6+)

```php
function myBusinessOperation() {
  CRM_Core_Transaction::create()->run(function($tx) {
    CRM_Core_DAO::executeQuery( /* ... do some stuff ... */ );
    if (/* ... received an error ... */) {
      throw new MyBusinessException();
    }
  });
}
```

In this example, one never explicitly issues a `BEGIN`, `ROLLBACK`, or
`COMMIT`. When we instantiate the transaction with `create()`, it will issue
`BEGIN` automatically. If you throw an exception (or if an exception
otherwise bubbles up), then `run()` will issue a `ROLLBACK`; otherwise, it
will issue a `COMMIT`.

You can also trigger a rollback without throwing an exception using
`$tx->rollback()`, e.g.

```php
function myBusinessOperation() {
  $result = NULL;
  CRM_Core_Transaction::create()->run(function($tx) use (&$result) {
    CRM_Core_DAO::executeQuery( /* ... do some stuff ... */ );
    if (/* ... received an error ... */) {
      $tx->rollback();
      $result = FALSE;
    } else {
      $result = TRUE;
    }
  });
  return $result;
}
```

#### Procedural style

General rules:

-   Mark the beginning of a transaction with 
    `$tx = new CRM_Core_Transaction()`.
-   Mark the transaction as failed (`$tx->rollback()`) when an error
    is detected
-   Continue reporting and handling errors (by returning error-codes,
    throwing exceptions, etc)

```php
/**
 * @return bool TRUE on success; FALSE on failure
 */
function myBusinessOperation() {
  $tx = new CRM_Core_Transaction();
  CRM_Core_DAO::executeQuery( /* ... do some stuff ... */ );
  if (/* ... received an error ... */) {
    $tx->rollback();
    return FALSE;
  } else {
    return TRUE;
  }
}

function myBusinessOperation() {
  $tx = new CRM_Core_Transaction();
  try {
    /* ... do some stuff ... */
    if (/* ... received an error ... */) {
      throw new MyBusinessException();
    }
  } catch (Exception $e) {
    $tx->rollback();
    throw $e; // re-throw the exception
  }
}
```

The first example explicitly marks the beginning of the transaction in
our function and (if there's an error) marks the transaction for
`ROLLBACK`. It **also** reports the error ("return `FALSE`") so that anyone
who calls `myBusinessOperation()` can perform their own cleanup.

Note that we never explicitly issue a `BEGIN`, `ROLLBACK` or `COMMIT`. When
instantiating `CRM_Core_Transaction`, it will issue a `BEGIN`
automatically. When the function terminates (or, more specifically, when
`$tx` destructs), it will issue a `COMMIT` or `ROLLBACK`.

### Combining transactions

When writing business-logic, each business operation should generally be
executed within a transaction – for example, when a constituent fills in
a profile form, the business operation of "Create a new contact from
profile form" should be executed inside a transaction. Similarly, when a
constituent registers for an event, the business operation of "Register
for event" should be executed inside a transaction.

In practice, we often perform multiple operations at the same time – for
example, a new constituent may fill in a profile **and** register for an
event at the same time. When executed individually, each operation
should be its own transaction. When executed together, the two
operations should be in the same transaction. Civi accomplishes this by
requiring each operation to **declare** its own transaction; if
operations are combined or overlap, then the transactions will be
combined automatically. The following example illustrates the
programming style:


```php
/**
 * Create a contact using a profile form
 *
 * @return NULL|int contact ID, or NULL on error
 */
function createContactFromProfile($contactData) {
  $tx = new CRM_Core_Transaction();
  ...
  if (...error...) {
    $tx->rollback();
    return NULL;
  } else {
    return $contactID;
  }
}

/**
 * Register a contact for an event
 * @return int|NULL participant registration ID, or NULL on error
 */
function registerForEvent($eventID, $contactID) {
  $tx = new CRM_Core_Transaction();
  ...
  if (...error...) {
    $tx->rollback();
    return NULL;
  } else {
    return $participantID;
  }
}

/**
 * Create a new contact and register for an event
 *
 * @return NULL|int participant ID, or NULL on error
 */
function registerNewContactForEvent($eventID, $contactData) {
  $tx = new CRM_Core_Transaction();

  $contactID = createContactFromProfile($contactData);
  if ($contactID === NULL) {
    return NULL;
  }

  $participantID = registerForEvent($eventID, $contactID)
  if ($participantID === NULL) {
    return NULL;
  }

  return $participantID;
}
```

This has a few important properties:

-   If an error arises while creating the contact, the entire
    transaction will be rolled back – leaving no contact records, no
    participant records, etc – and an error will be returned.
-   If an error arises while creating the registration, the entire
    transaction will be rolled back – leaving no contact records, no
    participant records, etc – and an error will be returned.

### Nesting transactions

(v4.6+)

In some cases, it may be appropriate to use a **nested** transaction or
[SAVEPOINT](http://dev.mysql.com/doc/refman/5.1/en/savepoint.html)s.
With nested transactions, it is possible to rollback individual steps
(such as the contact-creation or the registration) while committing the
overall work. This is appropriate in cases where some errors are
recoverable, expected, or otherwise tolerated. For example, suppose you
have a bulk importer and want this policy: "We will tolerate errors as
long as they affect fewer than 5 records." If there are fewer than 5 errors, then all
the valid records should be allowed (and bad records should be skipped);
if there are 5 or more records, then the entire batch should be skipped.

One can create a nested transaction the same way as before – but one
must pass the argument `$nested == TRUE`, e.g. 
`new CRM_Core_Transaction(TRUE)` or `CRM_Core_Transaction::create(TRUE)`.


```php
/**
 * Attempt to import a batch of contacts.
 *
 * @param array $contacts list of contact records to import
 * @param int $maxErrors max number of contacts allowed to hit an error
 * @param array $erroneousContacts a list of contacts that were skipped due to errors
 * @return bool TRUE if the batch was committed
 */
function importBatchOfContacts($contacts, $maxErrors, &$erroneousContacts) {
  $txMain = new CRM_Core_Transaction(TRUE);
  $erroneousContacts = array();
  foreach ($contacts as $contact) {
    try {
      $txNested = new CRM_Core_Transaction(TRUE); // NOTE: the "TRUE" makes for a nested transaction
      if (!createContactFromProfile($contact)) {
        $erroneousContacts[] = $contact;
      }
    } catch (Exception $e) {
      $erroneousContacts[] = $contact;
      $txNested->rollback();
    }
    $txNested = NULL; // finish the nested transaction
  }
  if (count($erroneousContacts) < $maxErrors) {
    // batch was "good enough"; errors have been outputted to $erroneousContacts
    return TRUE;
  } else {
    // too many errors; give up on the entire batch
    $txMain->rollback();
    $erroneousContacts = $contacts;
    return FALSE;
  }
}
```

!!! warning
    Marking a transaction for rollback is different from sending the `ROLLBACK` command to the SQL server – the two may not happen at the same time.The transaction is marked for rollback when an error is first detected, but the `ROLLBACK` command is sent when all outstanding copies of `CRM_Core_Transaction` finish-up.

    For example, suppose the sequence of events include:

    -   Someone calls `registerNewContactForEvent`
        -   `registerNewContactForEvent` creates $tx (the first copy of `CRM_Core_Transaction`)
        -   `registerNewContactForEvent` calls `registerForEvent`
            -   `registerForEvent` creates `$tx` (the second copy of `CRM_Core_Transaction`)
            -   `registerForEvent` encounters an error and **marks the transaction for rollback** (but the SQL `ROLLBACK` is **not** executed yet)
            -   `registerForEvent` terminates – and therefore $tx is destroyed (but the SQL `ROLLBACK` is **not** executed yet)
        -   `registerNewContactForEvent` terminates – and therefore $tx is destroyed, and **the SQL `ROLLBACK` is executed**


### Abnormal termination

In some exceptional circumstances, program execution terminates
abnormally – which prevents the normal transaction logic from managing
the rollback or commit properly. For example, when Civi encounters a
fatal error, it calls PHP's exit() to abort processing. Of course, if
there's a fatal error, then any pending transactions shouldn't be
committed. CRM_Core_Error addresses this by calling
CRM_Core_Transaction immediately before exit:

```php
static function abend($code) {
  // do a hard rollback of any pending transactions
  // if we've come here, its because of some unexpected PEAR errors
  CRM_Core_Transaction::forceRollbackIfEnabled();
  CRM_Utils_System::civiExit($code);
}
```

## Special Topics

### APIv3

Some APIs are transactional. What does that mean?

* If an API is transactional, then an error in the API will cause a rollback.
* If an API is NOT transactional, then an error in the API will NOT cause a rollback.

At time of writing, the API [TransactionSubscriber](https://github.com/civicrm/civicrm-core/blob/master/Civi/API/Subscriber/TransactionSubscriber.php) 
determines whether a specific API-call is transactional. It follows these rules:

* If the API caller specifically passes `is_transactional` (`TRUE` or `FALSE`), then that takes precedence.
* If the API action is `create`, `delete`, or `submit`, then the API is transactional.
* If the API action is anything else (`get`, `getsingle`, etc), then the API is NOT transactional.

### `TRUNCATE` and `ALTER` force immediate commit

In MySQL, changes to the schema will cause pending transactions to
commit immediately (regardless of what Civi would normally do for
transactions) – so installation of new extensions, modification of
custom-data, and cache-resets would be likely to interfere with
transaction management.

### Batching and imports

If you are serially processing a large number of 'rows', despite the
additional overhead it is normally more appropriate to put a transaction
around the logically linked operations for each row and NOT to put a
transaction around the loop through all the rows. Exceptions include
cases where a whole batch must succeed or fail together; this is often
something better done offline.
