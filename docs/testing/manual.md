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


# Smart group testing

### General use cases

-   Run advanced search by name=Adams. Create a smart group. Check
    group membership.
-   Run advanced search by name=Bachman. Create a smart group. Check
    group membership.
-   Edit a contact, changing last name to Adams. Check group membership.
    Observe new member.
-   Edit a contact, changing last name away from Adams. Check
    group memberhsip. Observe removed member.
-   Add a contact with last name Bachman.  Check group membership.
    Observe  new member.
-   Delete a contact with last name Bachman.  Check group membership.
    Observe missing member.
-   Update definition of group Bachman to filter on
    contact_type=Individual.  Check group membership.  Observe removed
    member (household "Bachman family").

### Confounding Factors

Smart groups are cached. The caching behavior is influenced by a couple
options:

-   **"smartGroupCacheTimeout**": Time-to-live (e.g. "retain the cached
    list of members for 5 minutes")
-   **"smart_group_cache_refresh_mode**": Refresh-mode\
    -   "opportunistic": (e.g. "flush stale caches whenever
        data changes")
    -   "deterministic": (e.g. "flush stale caches using a cron job")

You may experience slightly different behaviors depending what was
cached before, how you time the steps, and which options are chosen.

Think of it this way; each general use-case includes the step "Check
group membership". As part of that step, you might initially see a stale
list of members. To force it to display an actual list, you might wait 5
minutes (to pass the TTL) **OR** hack *civicrm_group.cache_date* to
set an earlier expiration.

If your main interest is browsing the list of members through the UI,
then (in my experience) it's not necessary to explicitly run the cron
job. When you look at a group in the UI, it checks the staleness and
updates the cache (if it's expired).

However, if your main interest is testing the nuances of refresh-modes,
then you should *not* check the list of members through the UI. Instead:

-   Repeatedly use SQL to inspect the content of "civicrm_group" and
    "civicrm_group_contact_cache"
-   For opportunistic mode, trigger a flush by editing some
    unrelated contact.
-   For deterministic cron mode, trigger a flush by calling
    "Job.group_cache_flush" API.

WISHLIST: For testing/inspection purposes, it would help to update the
list screen ("Contacts in Group"  aka "civicrm/group/search"):

-   Display the timestamp for when the cache was generated
-   Provide a button to force the cache to regenerate


