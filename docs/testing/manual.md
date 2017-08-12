# Manual testing

Due to the varied nature of environments & the implicitly incomplete coverage automated tests have manual testing will never be obsolete. This page contains links to some test plans to be mixed & matched for RC testing


# Dedupe &amp; merge testing

## Tests for the dedupe / merge functionality

### Finding duplicates

-   Choose a rule to dedupe on, do not filter by a group, check that all
    potential duplicates are displayed
-   As above, but enter a 'limit', ensure that the number of results is
    limited to no more than the number specified
-   Choose a rule to dedupe on, filter by a group, check that the list
    of potential duplicates is fewer

### Manually merging records

<span
style="color: rgb(0,0,0);font-size: 10.0pt;font-weight: normal;line-height: 13.0pt;">Click
to 'Merge' two records using the interface.</span>

-   Check that clicking 'Next' takes you to the next record expected
-   Check that clicking 'Previous' takes you to the previous record
-   Check that clicking 'Flip' flips the positions of the 'main' and
    'duplicate' contacts

Check the manual merge UI when choosing information and contact details
to merge across.

-   Check that when changing an email 'type' the value on the right
    changes to display the email address currently associated with that
    record
-   Check that the merge type (eg: 'add' or 'overwrite') changes when
    the box is ticked

Merge the records, and check that records merged correctly.

-   Check that the duplicate was deleted
-   Check that any core information 'ticked' to be migrated was migrated
    across
-   Check that location fields were 'added' or 'overwritten' as
    appropriate, into any new 'types' that were specified
-   Check that attached information (such as mailings, groups, CMS
    user accounts) were migrated successfully if ticked to migrate

### Batch merging records

From the dedupe listing screen.

-   Tick and batch merge a pair of contacts that can be merged
    without conflict. Check they are merged correctly:
    -   No duplicate phone numbers or email addresses created (eg: two
        home@home.com email address in the home type)
-   <span>Tick and batch merge a pair of contacts that would cause a
    merge conflict:</span>
    -   <span>Check that they are not merged, and you are taken to the
        'conflicts' screen</span>
    -   <span>Force merge them, and check that they are merged (no
        duplicate phone numbers or email addresses)</span>
-   'Flip' a pair of contacts and then 'batch merge' them. Check that
    the new 'duplicate' record is still the duplicate and is deleted
    (currently failing
    - <https://issues.civicrm.org/jira/browse/CRM-18575>)

### Merge from the contact search 'task' list

-   Perform a contact search, select two contacts and choose the 'Merge'
    task: Ensure you are taken to the 'Merge' screen for the contacts.

### Merging by code (API)

@todo


