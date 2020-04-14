# Manual testing

Due to the varied nature of environments & the implicitly incomplete coverage automated tests have manual testing will never be obsolete. This page contains links to some test plans to be mixed & matched for RC testing


## Dedupe and merge testing {:#dedupe}

### Finding duplicates

1.  Choose a rule to dedupe on, do not filter by a group, check that all
    potential duplicates are displayed
1.  As above, but enter a 'limit', ensure that the number of results is
    limited to no more than the number specified
1.  Choose a rule to dedupe on, filter by a group, check that the list
    of potential duplicates is fewer

### Manually merging records

Click to **Merge** two records using the interface.

1.  Check that clicking **Next** takes you to the next record expected
1.  Check that clicking **Previous** takes you to the previous record
1.  Check that clicking **Flip** flips the positions of the *main* and
    *duplicate* contacts

Check the manual merge UI when choosing information and contact details
to merge across.

1.  Check that when changing an email 'type' the value on the right
    changes to display the email address currently associated with that
    record
1.  Check that the merge type (eg: 'add' or 'overwrite') changes when
    the box is ticked

Merge the records, and check that records merged correctly.

1.  Check that the duplicate was deleted
1.  Check that any core information 'ticked' to be migrated was migrated
    across
1.  Check that location fields were 'added' or 'overwritten' as
    appropriate, into any new 'types' that were specified
1.  Check that attached information (such as mailings, groups, CMS
    user accounts) were migrated successfully if ticked to migrate

### Batch merging records

From the dedupe listing screen.

1.  Tick and batch merge a pair of contacts that can be merged
    without conflict. Check they are merged correctly:
    -   No duplicate phone numbers or email addresses created (eg: two
        home@home.com email address in the home type)
1.  Tick and batch merge a pair of contacts that would cause a
    merge conflict:
    -   Check that they are not merged, and you are taken to the
        'conflicts' screen
    -   Force merge them, and check that they are merged (no
        duplicate phone numbers or email addresses)
1.  'Flip' a pair of contacts and then 'batch merge' them. Check that
    the new 'duplicate' record is still the duplicate and is deleted

### Merge from the contact search 'task' list

-   Perform a contact search, select two contacts and choose the 'Merge'
    task: Ensure you are taken to the 'Merge' screen for the contacts.

### Merging by code (API)

*Not yet documented*


## Smart group testing {:#smart-group}

#### General use cases

-   Run advanced search by name=Adams. Create a smart group. Check
    group membership.
-   Run advanced search by name=Bachman. Create a smart group. Check
    group membership.
-   Edit a contact, changing last name to Adams. Check group membership.
    Observe new member.
-   Edit a contact, changing last name away from Adams. Check
    group membership. Observe removed member.
-   Add a contact with last name Bachman.  Check group membership.
    Observe  new member.
-   Delete a contact with last name Bachman.  Check group membership.
    Observe missing member.
-   Update definition of group Bachman to filter on
    contact_type=Individual.  Check group membership.  Observe removed
    member (household "Bachman family").

#### Confounding Factors

Smart groups are cached. The caching behavior is influenced by a couple
options:

-   `smartGroupCacheTimeout`: Time-to-live (e.g. "retain the cached list of members for 5 minutes")
-   `smart_group_cache_refresh_mode`: Refresh-mode
    -   "opportunistic": (e.g. "flush stale caches whenever data changes")
    -   "deterministic": (e.g. "flush stale caches using a cron job")

You may experience slightly different behaviors depending what was
cached before, how you time the steps, and which options are chosen.

Think of it this way; each general use-case includes the step "Check
group membership". As part of that step, you might initially see a stale
list of members. To force it to display an actual list, you might wait 5
minutes (to pass the TTL) **OR** hack `civicrm_group.cache_date` to
set an earlier expiration.

If your main interest is browsing the list of members through the UI,
then (in my experience) it's not necessary to explicitly run the cron
job. When you look at a group in the UI, it checks the staleness and
updates the cache (if it's expired).

However, if your main interest is testing the nuances of refresh-modes,
then you should *not* check the list of members through the UI. Instead:

-   Repeatedly use SQL to inspect the content of `civicrm_group` and `civicrm_group_contact_cache`
-   For opportunistic mode, trigger a flush by editing some unrelated contact.
-   For deterministic cron mode, trigger a flush by calling `Job.group_cache_flush` API.

!!! bug "Wishlist"

    For testing/inspection purposes, it would help to update the list screen ("Contacts in Group"  aka "civicrm/group/search"):

    -   Display the timestamp for when the cache was generated
    -   Provide a button to force the cache to regenerate


## Tarball installation testing {:#tarball}

If you are testing a release-candidate or specifically working on the
installation subsystem, then you may need to try some of the manual
testing processes below.

### Picking tarballs

-   For official tarballs, browse [civicrm.org/download](http://civicrm.org/download)
-   For unofficial nightly tarballs, browse
    [download.civicrm.org/latest](https://download.civicrm.org/latest)
-   For a pending pull-request, you must build custom tarballs

To make custom tarballs from a pull request, you need to run
`distmaker` which requires a special file structure and some config
files, but `civibuild` can automated that. For example:

1. Checkout the code for the pull request. (In this example, it uses civicrm-core.git#8177.)

    ```bash
    $ civibuild create dist --url http://dist.localhost --patch https://github.com/civicrm/civicrm-core/pull/8177
    ```
    
1. Run distmaker

    ```bash
    $ cd $HOME/buildkit/build/dist/src/distmaker
    $ ./distmaker.sh all
    ```
    
1. Observe the output

    ```bash
    $ ls $HOME/buildkit/build/dist/out/tar
    ```

### Option 1. Fully manual installation and upgrade

You can, of course, follow the normal instructions for installation and
upgrades.

If you're not big into scripting/CLI, this is the way to go. But it can
be time-consuming.

### Option 2. Upgrade a staging site with "drush cvup"

If you have a Drupal staging site with CiviCRM already installed, you can use `drush` to load a tarball.

Formula: 

```bash
drush cvup --tarfile=<path to tarfile> --backupdir=<path to backup dir>
```

1.  Navigate to your site

    ```bash
    $ cd /var/www/drupal
    ```
    
1.  Clear caches

    ```bash
    $ drush cc all
    ```

1.  Download and extract an official CiviCRM tarball

    ```bash
    $ wget http://download.civicrm.org/civicrm-4.7.7-drupal.tar.gz -O /tmp/civicrm-4.7.7-drupal.tar.gz
    $ drush cvup --tarfile=/tmp/civicrm-4.7.7-drupal.tar.gz --backupdir=/tmp/myupgrade
    ```

1.  OR... Extract a custom CiviCRM tarball

    ```bash
    $ drush cvup --tarfile=$HOME/buildkit/build/dist/out/tar/civicrm-4.7.7-drupal.tar.gz --backupdir=/tmp/myupgrade
    ```
    
1.  Clear caches

    ```bash
    $ rm -rf sites/default/files/civicrm/templates_c/
    $ sdrush cc all
    ```

Also see this [StackExchange question](http://civicrm.stackexchange.com/questions/4829/is-it-easy-to-upgrade-civicrm-using-drush).

### Option 3. Create an empty site with "civibuild"

If you don't have a staging site, you can use [civibuild](../tools/civibuild.md)
to create empty sites for Drupal, WordPress, and Backdrop and preload
the tarball. This will cue the system so that it's ready for you to go
the installation screen.


1. Create an empty Drupal test site. Download and extract a CiviCRM tarball. Display login details.

    ```bash
    $ civibuild create dempty --type drupal-empty --url http://dempty.localhost --dl sites/all/modules=http://download.civicrm.org/civicrm-4.7.7-drupal.tar.gz
    ```

1. Cleanup a test site

    ```bash
    $ civibuild destroy dempty
    ```
    
See [civibuild](../tools/civibuild.md) for more options.
    
!!! note
    At time of writing, this supports Drupal 7, WordPress, and Backdrop.

### Option 4. Create a batch of sites with "civihydra" (experimental)

This is a lot like Option 3, but it loops through all the tarballs and runs `civibuild` for each of them.

Formula:

```bash
civihydra create <civicrm-tar-files>
```

1. Create D7, WordPress, and Backdrop sites using official tarballs. Display login details.

    ```bash
    civihydra create http://download.civicrm.org/civicrm-4.7.7-{drupal.tar.gz,wordpress.zip,backdrop-unstable.tar.gz}
    ```
    
1. OR... create D7, WordPress, and Backdrop sites using your own custom tarballs. Display login details.

    ```bash
    civihydra create $HOME/buildkit/build/dist/out/tar/*
    ```
    
1. Cleanup all the test sites

    ```bash
    civihydra destroy
    ```

!!! note
    At time of writing, this supports Drupal 7, WordPress, and Backdrop.

### See also: Headless installation testing for civibuild and git

If you use [civibuild](../tools/civibuild.md), then you most likely setup the local source tree using a conventional
configuration like `drupal-demo` or `wp-demo`. This downloads the
pristine code from git and performs an automated installation and enables a convenient development loop:

1. Write a patch
1. Run `civibuild reinstall <name>`.
1. Check the outcome
1. (Repeat as necessary)
1. Commit and open a pull request

!!! note
    In addition to re-running the installation, you can also test the DB upgrades by running "`civibuild upgrade-test <name>`". For more information about the available commands, see [civibuild](../tools/civibuild.md).

This is convenient for most development but has a downside:  Most admins
use the web-based graphical installer included with the tarballs (with a
filtered version of the source tree). Consequently, the automated
installation is not quite as representative of how a typical admin
works.

