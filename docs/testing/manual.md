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


# Tarball installation testing

If you are testing a release-candidate or specifically working on the
installation subsystem, then you may need to try some of the manual
testing processes below.

## Picking tarballs



-   For official tarballs, browse <http://civicrm.org/download>
-   For unofficial nightly tarballs, browse
    <https://download.civicrm.org/latest>[](http://dist.civicrm.org/by-date/latest/){.external-link}
-   For a pending pull-request, you must build custom tarballs

To make custom tarballs from a pull request, you need to run
*distmaker*. Distmaker requires a special file structure and some config
files, but *civibuild* can automated that. For example:

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Building tarballs for a pull-request**

</div>

<div class="codeContent panelContent">

    ## Checkout the code for the pull request. (In this example, it uses civicrm-core.git#8177.)
    civibuild create dist --url http://dist.localhost --patch https://github.com/civicrm/civicrm-core/pull/8177

    ## Run distmaker
    cd $HOME/buildkit/build/dist/src/distmaker
    ./distmaker.sh all

    ## Observe the output
    ls $HOME/buildkit/build/dist/out/tar

</div>

</div>



## Option 1. Fully manual installation and upgrade

You can, of course, follow the normal instructions for [Installation and
Upgrades](/confluence/display/CRMDOC/Installation+and+Upgrades).

If you're not big into scripting/CLI, this is the way to go. But it can
be time-consuming.

## Option 2. Upgrade a staging site with "drush cvup"

If you have a Drupal staging site with CiviCRM already installed, you
can use *drush* to load a tarball.

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Use civibuild with a tarball**

</div>

<div class="codeContent panelContent">

    ## Formula: drush cvup --tarfile=<path to tarfile> --backupdir=<path to backup dir>

    ## Navigate to your site
    cd /var/www/drupal

    ## Clear caches
    drush cc all

    ## Download and extract an official CiviCRM tarball
    wget http://download.civicrm.org/civicrm-4.7.7-drupal.tar.gz -O /tmp/civicrm-4.7.7-drupal.tar.gz
    drush cvup --tarfile=/tmp/civicrm-4.7.7-drupal.tar.gz --backupdir=/tmp/myupgrade

    ## OR... Extract a custom CiviCRM tarball
    drush cvup --tarfile=$HOME/buildkit/build/dist/out/tar/civicrm-4.7.7-drupal.tar.gz --backupdir=/tmp/myupgrade

    ## Clear caches
    rm -rf sites/default/files/civicrm/templates_c/
    sdrush cc all

</div>

</div>

See also:
<http://civicrm.stackexchange.com/questions/4829/is-it-easy-to-upgrade-civicrm-using-drush>

## Option 3. Create an empty site with "civibuild"

If you don't have a staging site, you can
use* *[buildkit](https://github.com/civicrm/civicrm-buildkit/){.external-link}'s
[civibuild](https://github.com/civicrm/civicrm-buildkit/blob/master/doc/civibuild.md){.external-link}
to create empty sites for Drupal, WordPress, and Backdrop and preload
the tarball. This will cue the system so that it's ready for you to go
the installation screen.

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Use civibuild with a tarball**

</div>

<div class="codeContent panelContent">

    ## Formula:
    ## civibuild create <name> --type <cms>-empty --url <site-url> --dl <path>=<tarball>

    ## Create an empty Drupal test site. Download and extract a CiviCRM tarball. Display login details.
    civibuild create dempty --type drupal-empty --url http://dempty.localhost --dl sites/all/modules=http://download.civicrm.org/civicrm-4.7.7-drupal.tar.gz

    ## OR... create an empty Backdrop test site. Extract a custom CiviCRM tarball. Display login details.
    civibuild create bempty --type backdrop-empty --url http://bempty.localhost --dl modules=$HOME/buildkit/build/dist/out/tar/civicrm-4.7.7-backdrop-unstable.tar.gz

    ## Cleanup a test site
    civibuild destroy dempty

</div>

</div>

(Note: At time of writing, this supports Drupal 7, WordPress, and
Backdrop.)

## Option 4. Create a batch of sites with "civihydra" (experimental)

This is a lot like Option 3, but it loops through all the tarballs and
runs *civibuild* for each of them.

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Use civibuild with a tarball**

</div>

<div class="codeContent panelContent">

    ## Formula: civihydra create <civicrm-tar-files>

    ## Create D7, WordPress, and Backdrop sites using official tarballs. Display login details.
    civihydra create http://download.civicrm.org/civicrm-4.7.7-{drupal.tar.gz,wordpress.zip,backdrop-unstable.tar.gz}

    ## OR... create D7, WordPress, and Backdrop sites using your own custom tarballs. Display login details.
    civihydra create $HOME/buildkit/build/dist/out/tar/*

    ## Cleanup all the test sites
    civihydra destroy

</div>

</div>

(Note: At time of writing, this supports Drupal 7, WordPress, and
Backdrop.)

## See also: Headless installation testing for civibuild and git

If you use
[buildkit](https://github.com/civicrm/civicrm-buildkit/){.external-link}'s
[civibuild](https://github.com/civicrm/civicrm-buildkit/blob/master/doc/civibuild.md){.external-link},
then you most likely setup the local source tree using a conventional
configuration like *drupal-demo* or *wp-demo*. This downloads the
pristine code from git and performs an automated installation and
enables a convenient development loop:

-   Write a patch
-   Run `"civibuild reinstall <name>`".
-   Check the outcome
-   (Repeat as necessary)
-   Commit and open a pull request

(Note: In addition to re-running the installation, you can also test the
DB upgrades by running "`civibuild upgrade-test <name>`". For more
information about the available commands, see
[civibuild.doc](https://github.com/civicrm/civicrm-buildkit/blob/master/doc/civibuild.md){.external-link}
and
[daily-coding.doc](https://github.com/civicrm/civicrm-buildkit/blob/master/doc/daily-coding.md#upgrade-tests){.external-link}.)

This is convenient for most development but has a downside:  Most admins
use the web-based graphical installer included with the tarballs (with a
filtered version of the source tree). Consequently, the automated
installation is not quite as representative of how a typical admin
works.

