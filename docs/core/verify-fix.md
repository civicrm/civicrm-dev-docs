# Verify a bug fix

!!! note "See also"
    This page is about verifying a fix after it has been merged into CiviCRM core. Also see instructions on [reviewing a core pull request](pr-review.md) to learn how to review fixes *before* they are merged.

Suppose you (or some like-minded spirit) [report a bug](../tools/issue-tracking.md#guidelines). With a bit of luck, someone from the community (perhaps a core developer) reproduces the bug, writes a fix, and announces gleefully: "It's fixed! It took four hours, but I did it!" Hooray! Now what? How do you get the fix running on your system? How do you verify that the fix fixed exactly your problem?
 
## Step 1. Check the "Fix Version" in JIRA

The JIRA issue includes a field called *Fix Version*. This declares the expected release which will include the fix.

If the fix is low-risk (small) or critical (dealing with data-corruption or security), the *Fix Version* will usually be the next point-release. Otherwise, the *Fix Version* version will usually be the next major-release.

However, every patch is evaluated on a case-by-case basis, so it's important to read the *Fix Version*.

!!! summary "Example"

    Suppose the current stable release is v4.6.2.
    
    A low-risk or critical fix will usually target the next point-release – i.e. v4.6.3.
    
    A high-risk or less-critical fix will usually target the next major release – i.e. v4.7 or v5.0.

For an example, see [CRM-16501](https://issues.civicrm.org/jira/browse/CRM-16501). Note the *Fix Version* is 4.7.

## Step 2. Check the proposal status in Github

When a developer prepares a fix for an issue, he submits a proposal ("PR" or "pull-request") via *github.com*. The proposal is evaluated using both [continuous integration](../testing/continuous-integration.md) and peer review. The proposal will have one of three statuses:

-   Open (green): The proposal has not been accepted yet. It's waiting for peer review.
-   Merged (purple): The proposal has been accepted.
-   Closed (red): The proposal has been rejected or abandoned. (This may happen for a variety of reasons - it could be a problem in the proposal itself, or perhaps it took too long to get peer review, or perhaps the author came up with a different/better proposal.)

Returning to the example of [CRM-16501](https://issues.civicrm.org/jira/browse/CRM-16501), the developer (Tim Mallezie) included a link to *github.com:* [#5829](https://github.com/civicrm/civicrm-core/pull/5829). Inspecting that page, you can see that the status is Merged, and another developer (Kurund Jalmi) approved the proposal.

## Step 3. Try the fixed code

To be sure that the patch actually works, you'll need try it out. There are a few different ways to try it out – the choice will depend on your skillset, time/motivation, and the version/status of the fix.

These options are generally sorted by difficulty, with the easiest option first.

### Option A. Use the CiviCRM sandbox

-   **Summary**:
    -   The [CiviCRM sandboxes](https://civicrm.org/sandboxes) are public web-sites which are automatically rebuilt once every day. The easiest way to test a fix is to try it on the sandbox.
-   **Required Skills**:
    -   No particular skills are required (beyond normal Civi user skills).
-   **Required Time**:
    -   Minimal. Generally 5-15min. Possibly up to an hour if you need to reproduce some special configuration options on the sandbox.
-   **Timeframe**:
    -   You can usually test a fix on the sandbox within 24hr ***after the proposal has been approved*** (merged).
    -   If the fix was recently approved (ie a few hours ago), then be patient and come back tomorrow.
    -   If you feel really anxious, you can compare the timestamp for when it was merged against [the build history of the sandboxes](https://test.civicrm.org/view/Sites/job/demo.civicrm.org/).
-   **Caveats**:
    -   This only works ***after*** the proposal has been accepted (merged).
    -   The automatic rebuild only works with Drupal and WordPress sandboxes. At time of writing, the Joomla sandboxes
        cannot be rebuilt automatically.
    -   The sandboxes use fake, generic data with a standardized configuration. The data and configuration on your
        server may be different. 
    -   Outgoing email sending is disabled on all sandboxes to prevent accidental spamming etc.  So it might not be possible to test the email related issues.

### Option B. Install the next release

-   **Summary**:
    -   Wait for the next release. When it's available, upgrade your server (as usual).
-   **Required Skills**:
    -   CiviCRM system administration
-   **Required Time**:
    -   Moderate. Generally 20-60 min.
    -   If you need a major upgrade, have many customizations, or don't have much experience with setting up CiviCRM test systems, then it may take several hours.
-   **Timeframe**:
    -   (For a point release) Point releases may be issued on [the first or third Wednesday of each month](https://civicrm.org/blogs/totten/release-policy-and-new-release-candidates). However, this is discretionary, and it may not happen if there
        are a small number of fixes. If this matters, ask.
    -   (For  a major release) Major releases are generally issued every 6 months (+/- 3 months). Consult the [CiviCRM Roadmap](https://civicrm.org/roadmap).
-   **Caveats**:
    -   This is the slowest process. If the patch doesn't work, then you'll need to wait for the next release and try again (minimum: 2 weeks. maximum: 9 months).

### Option C. Install the nightly tarball on a test server

-   **Summary**:
    -   Set up a test server. Duplicate your CMS+CiviCRM configuration on the test server.
    -   Download and install the [latest nightly tarball](https://civicrm.org/blogs/totten/pre-release-policy-and-nightly-builds) from <http://dist.civicrm.org/by-date/latest/>
-   **Required Skills**:
    -   CiviCRM system administration
    -   Testing / staging / production management
-   **Required Time**:
    -   Moderate. Generally 20-60 min.
    -   If you need a major upgrade, have many customizations, or don't have much experience with setting up CiviCRM test systems, then it may take several hours.
-   **Timeframe**:
    -   You can usually test a nightly tarball within 24hr ***after the proposal has been approved*** (merged).
    -   If the fix was recently approved (ie a few hours ago), then be patient and come back tomorrow.
    -   If you feel really anxious, you can compare the timestamp for when it was merged against the timestamp on *dist* server.
-   **Caveats**:
    -   This only works ***after*** the proposal has been accepted (merged).
    -   Do not install nightly tarballs on production servers. For more discussion of why, see the original blog post, [Pre-Release Policy and Nightly Builds](https://civicrm.org/blogs/totten/pre-release-policy-and-nightly-builds).

!!! tip

    When browsing <http://dist.civicrm.org/by-date/latest/>, you may find that the Fix Version does not appear as a folder – because it has not been released yet. Choose the closest major version.
    
    Example: If the Fix Version is "4.6.3", and if there is no folder for "4.6.3", then look in the "4.6" folder.
    
    Example: If the Fix Version is "4.7", and if there is no folder for "4.7.0" or "4.7", then look in the "master" folder.


### Option D. Download the patch file from Github

-   ****Summary**:**
    -   Setup a test server.
    -   View the PR on Github.
    -   [Download and apply the patch on your test server.](http://stackoverflow.com/questions/7827002/how-to-apply-a-git-patch-when-given-a-pull-number)
-   **Required Skills**:
    -   CiviCRM system administration
    -   Basic web development
-   **Required Time**:
    -   Moderate. Generally 20-60 min.
-   **Timeframe**:
    -   You can download patches as soon as they are **proposed**. This means you can try new patches **before** they've been reviewed or approved.
-   **Caveats**:
    -   Patches do not always apply cleanly. For example, if there is a big gap in the versions (e.g., your test system is 4.6.0 and the *Fix Version* is 4.6.5), or if the patch is large, then there's an increased risk that minutiae will prevent the patch from loading on your system.
    -   Patches may have hidden dependencies. For example, patch #456 may only work correctly if patch #123 is also loaded. This risk can only be assessed on a case-by-case basis.

### Option E. Setup a developer system and checkout the patch

-   ****Summary**:**
    -   Active core contributors setup a development system optimized for evaluating new patches.
    -   Install <https://buildkit.civicrm.org/> (aka <https://github.com/civicrm/civicrm-buildkit>) on a developer
        workstation 
    -   Create a test site with [civibuild](https://github.com/civicrm/civicrm-buildkit/blob/master/doc/civibuild.md)
        using the closest matching git branch. (For example, if the *Fix Version* is "4.6.3", then the closest branch is "4.6".)
        -   Example: *civibuild create d46 --url <http://d46.localhost>*
    -   (If the proposal has not been approved) Checkout the proposal
        -   Example: *cd build/d46/sites/all/modules/civicrm; hub
            checkout
            <https://github.com/civicrm/civicrm-core/pull/5829>*
-   **Required Skills**:
    -   CiviCRM system administration
    -   Git-based source-code management
    -   Unix/Linux (CLI) system administration
-   **Required Time**:
    -   (Initial setup) Generally, 30min - 4 hours. (Depending on environment and experience level.)
    -   (Subsequent tests) Generally, 10-30 min.
-   **Timeframe**:
    -   You can download patches as soon as they are **proposed**. This means you can try new patches **before** they've been reviewed or approved.
-   **Caveats**:
    -   Not supported on Windows. Use a Linux VM.
    -   If you use a MySQL/Apache bundle (such as MAMP or XAMPP), you may need to do extra configuration to enable scripting of the CLI environment.
    -   If you get stuck, reach out on [Mattermost](https://chat.civicrm.org)
