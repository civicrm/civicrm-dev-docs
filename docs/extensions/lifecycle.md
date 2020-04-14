# Extension Life cycle

!!! tip "Abandoned Extension Process"
    Are you looking for information on the process handling abandoned extensions for CiviCRM? See [Abandoned Extensions](#abandoned-extensions).

This document describes the process of publishing extensions within the CiviCRM ecosystem and reporting abandoned extensions.

## Background

The CiviCRM ecosystem is built on the belief that non-profit organizations can serve themselves best by collaborating in development of their data-management applications.  As staff, volunteers, and consultants for non-profit organizations, we can share our new enhancements and extensions &mdash; and build a richer whole for the entire ecosystem.

Of course, this collaboration means that we're all engaged in some give-and-take. We alternate between two roles:

-   **Consumers**: Sometimes we're the receivers. We want to quickly browse the available extensions, pick the ones which look best, and install them. We expect the extensions to work &mdash; both now and going forward (with future upgrades).
-   **Developers**: Sometimes we're the providers. We enjoy building great functionality, and want to invite people to use our products, but need to juggle the publishing tasks (like testing and maintenance releases) with the goals and resources provided by our bosses and clients.

With the extension life-cycle described here, we seek to build an ecosystem that balances the needs of both consumers and developers.

## Definitions

### Project Maturity

Should we expect this to work for most users? Should we expect it to work in 6 months?

Experimental
:   An experimental project offers zero support, stability, or maintenance. It may be useful for discussion, finding collaborators, or proving a concept.

Incubation
:   An incubation project offers some degree of support, stability, or maintenance. It's probably in use at multiple     organizations. However, the levels are not guaranteed; some gaps and road bumps should be expected. A project may be "Incubation" for days or months or years.

Stable
:   A stable project has undertaken significant efforts to ensure that it works and continues working in the future. It has a     strong quality-signal.

Deprecated
:   The project is no longer being maintained. It may work today; but it's liable to break tomorrow (unless someone steps up to     manage it).

### Stewardship

Who manages a project? Who decides whether the project is experimental? Or maintained? Or unmaintained?

Contributed
:   This project is managed by an individual or company in the ecosystem. All design, support, and maintenance are at discretion of the original author.

Official
:   The project is monitored as a community resource. Generally, the original author retains editorial control, but the     project receives more strenuous reviews and follows stricter standards with feedback from others in the community.

Seeking Maintainer
:   This project does not have a person or organization responsible for it. If you think the project is useful, feel free to take responsibility for it.

### Support Model

How do you submit questions and requests about issues?

Free
:   Submit questions and requests to an open bug-tracker.

Negotiated
:   Issues may be reported to open bug-tracker. If the author agrees it is critical or data-loss, they may address it. Otherwise, you need to negotiate a contract.

Pre-Paid
:   The author will not engage in any support discussions unless you have pre-paid for support.

### Quality Signals

How do we know if an extension is any good?

Self-Assessment
:   An author makes a claim about the stability of their work. (This is a low-tech, low-touch process.)

Informal Discussion
:   One or more experts give gut reactions. (This is a low-tech, high-touch process.)

Formal Review
:   One or more experts assesses the quality, maintainability, best-practices, etc. using formal criteria. (This is a low-tech, high-touch process.)

Social Metrics
:   Data-points (such as #installations or average 5-star rating) is collected from many people. (This is a high-tech, low-touch process.)

Technical Metrics
:   Technical details (such as test-coverage, test-results, style-checks, or cyclomatic complexity) are checked by a bot. (This is a high-tech, low-touch process.) 

## Workflow

The database on `civicrm.org` publishes information about available extensions, including maturity and stewardship. This is significant because it affects authors (who publish the extension) and users (who download the extension) and determines access to communal resources on `civicrm.org`. The particulars are determined the maturity and stewardship of the project -- with a few basic rules of thumb:

-   The author always registers their extension on `civicrm.org` by creating an `extension` node.
-   *Official* extensions are subject to more scrutiny than *Contributed* extensions.
-   *Experimental*, *Incubation*, and *Deprecated* extensions have simple, open processes -- such as *Self-Assessment* or *Informal Discussion*.
-   *Stable* extensions require some kind of *Formal Review*.

Based on these rules, we can fill out a full table of the workflow:


| Maturity	| Stewardship	| Primary Quality Signal	| How does an author get their extension designated as X?	| How does a user download an extension with X designation? |
| ------------- | ------------- | ----------------------------- | ----------------------------------------------------- | --------------------------------------------------------- |
| Experimental	| Contributed	| Self-Assessment	| In `civicrm.org`, the author creates an "extension" node and flags it as "Experimental".	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Experimental	| Official	| Informal Discussion	| As above. *Additionally* The author announces to a high-visibility medium (such as blog or mailing-list). If discussion is persuasive, a senior member of core team flags the project as official.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Incubation	| Contributed	| Self-Assessment	| In `civicrm.org`, the author creates an "extension" node and flags it as "Incubation".	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Incubation	| Official	| Informal Discussion	| As above. *Additionally* The author announces to a high-visibility medium (such as blog or mailing-list). If discussion is persuasive, a senior member of core team flags the project as official.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Stable	| Contributed	| Formal Review (light)	| On `lab.civicrm.org`, the author requests a formal peer review (at   https://lab.civicrm.org/extensions/extension-review-requests/issues ) .  Once the reviewer is satisfied, they mark the node in `civicrm.org` as Stable.	| In app, go to "Add New" and choose the extension.
| Stable	| Official	| Formal Review (heavy)	| As above. *Additionally* FormalReview criteria are more detailed. Announce to a high-visibility medium. At least one reviewer must be a senior member of the core team.	| In app, go to "Add New" and choose the extension.
| Deprecated	| Contributed	| Self-Assessment	| In `civicrm.org`, the author marks the "extension" node as deprecated and announce to a high-visibility medium.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Deprecated	| Official	| Informal Discussion	| The author announces intent to deprecate in a high-visibility medium. If discussion is persuasive and no alternative maintainer comes forward, a senior member of core team flags the project as official.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.

## Formal Review Process {:#formal-review}

Extensions must pass a *Formal Review* to become designated as *Stable* and made available for automated distribution through CiviCRM's in-app Extension management screen.

The review process assess several criteria, and as a rule of thumb, *Contributed* extensions are subject to a gentler review (fewer criteria), and *Official* extensions are subject to more stringent review (more criteria).

### Who can review?

* Contributed extensions must be reviewed by at least one peer/contributor.
* Official extensions must be reviewed by at least one senior member of core team.

### Becoming an extensions reviewer

To become an extensions reviewer, please take the following steps:

1. Set up accounts on these sites *(if you need help, reach out through the [community resources](../basics/community.md))*
    1.  [civicrm.org](https://civicrm.org/user/)
    1.  [lab.civicrm.org](https://lab.civicrm.org/) - log in using your civicrm.org account
1.  Visit the [CiviCRM Extensions Directory project](https://lab.civicrm.org/extensions/extensions-directory) and click the "Request Access" link. You'll be notified when the necessary administrative steps have been completed.

### Selecting an Extension for Review

1. Choose one of these [unassigned extension review requests](https://lab.civicrm.org/extensions/extension-review-requests/issues?scope=all&utf8=%E2%9C%93&state=opened&assignee_id=None).

    Consider the following criteria while choosing:

    -   Readiness: The issue should contain a link to an extension node on civicrm.org. If no such link is provided, please request one in a comment on the issue, and move on to another issue.
    -   Age of request: All else being equal, older review requests should be reviewed first.
    -   Affinity/interest: Reviewers may wish to select an extension which relates to functionality in which they have an interest or with which they're especially familiar. On the other hand, there's no requirement to have any special knowledge of the extension's functionality if one is prepared to invest a little extra effort in the review.
    -   Neutrality: Reviewers should not have been involved in the development of the extension. Employment or contracting  relationships can introduce conflicts of interest. Reviews should be conducted by a neutral third party.

1.  To claim a review, assign the relevant "Extension Review Request" issue to yourself so that others know you're beginning the review. When you are ready to begin the review, update the issue status to "In Progress".

!!! tip
    You can also browse [*all* extension review requests](https://lab.civicrm.org/extensions/extension-review-requests/issues?scope=all&utf8=%E2%9C%93), including assigned ones.

### Conducting a Review

Reviewers should follow these steps to conduct an extension review for automated distribution:

1. You will be reviewing the extension on at least one [supported CMS](https://docs.civicrm.org/user/en/latest/website-integration/choosing-your-cms/). (You don't need to test that it works on every CMS.) To that end, ensure you have such a site available, on which you can be free to experiment with untested extensions like the one you're reviewing.

    !!! tip
        Use [Buildkit](https://github.com/civicrm/civicrm-buildkit) to create the CMS environment on-demand.
        
    !!! tip
        By definition, the extension you're reviewing is unreviewed. You probably do not want to install it on a live site.

1. Download and install the most recent release of the extension.

    !!! attention "Important"
        If you clone the git repository of the extension, be sure to check out the *tag* for the most recent release. (Don't assume that the master branch is ready for review.)

1.  Observe that the extensions meets relevant [criteria](#review-criteria) listed below.

    1.  All criteria marked as "Required" must be met.
    1.  At least some of the criteria marked as "Suggested" must be met.

1.  Try to make the extension misbehave in any potential edge cases that occur to you. Note any significant failures.

1.  Create a document to show the details of your review. It can be a google doc or `.odt` file or something similar.

    * Copy/paste the [criteria](#review-criteria) table into your review document.

    * Add an additional column to the table for your comments.

    * Summarize all your tests and findings, positive or negative.

    * Attach or link to your review document in the "Extension Review Request" issue that you assigned to yourself.

    * Here is an [example review document](https://docs.google.com/spreadsheets/d/1-dJmHBYjZDPMB3F69axUH6OCjWgvbv0zzKkUjDL1YRM/edit#gid=0) &mdash; *but don't copy-paste from this example document (use the criteria table below for the most up-to-date criteria).*


1. Use all of the information gained in the review to decide whether to approve the extension.

### Criteria for passing a review {:#review-criteria}

| Category	| Criterion | Required for<br>*contributed*<br>extensions? | Required for<br>*official*<br>extensions? |
|------ | ----- | :-----------------------------: | :---------------------: |
| Admin	| Code is licensed under AGPLv3+, GPLv2+, LGPLv2+, MIT/X11, or BSD-2c	| **Required**	| **Required** |
| Admin	| Code is published on github.com or lab.civicrm.org 	| **Required**	| **Required** |
| Admin	| Extension name uses "org.civicrm.*" namespace	| No	| *Suggested* |
| Admin	| Bus factor >= 2	| No	| *Suggested* |
| Admin	| Access to project is granted to infra team	| No	| *Suggested* |
| Admin	| Release schedule is aligned with core	| No	| *Suggested*
| Coding	| All code complies with civicrm-core style guidelines	| No	| **Required**
| Coding	| Automated tests execute within 3 minutes (or less)	| No	| *Suggested*
| Coding	| All dependencies are at similar stage (Ex: A stable project should not depend on an experimental project)	| No	| **Required**
| Coding	| All strings are wrapped in ts()	| *Suggested*	| **Required**
| Coding	| The project does not *override* `PHP` or `TPL` files from civicrm-core	| **Required**	| **Required**
| Coding	| The project does not modify the `SQL` schema of a standard civicrm-core table	| **Required**	| **Required**
| Coding	| The project does not *conflict* with other official projects	| *Suggested*	| *Suggested*
| Distribution	| The project is packaged as a CiviCRM Extension, Drupal Module, Backdrop Module, Joomla Extension, or WordPress plugin	| **Required**	| **Required**
| Distribution	| The project has a stable version (1.0+; not alpha or beta)	| **Required**	| **Required**
| Distribution	| A demo site is provided	| *Suggested*	| *Suggested*
| QA	| The project declares, on the in-app extension management screen, the nature of any changes it makes to existing data or functionality.	| **Required**	| **Required**
| QA	| The project functions in all CMS's (for CiviCRM Extension)	| *Suggested*	| *Suggested*
| QA	| An automated test suite is included	| *Suggested*	| **Required**
| QA	| Project is periodically re-validated with newer versions of CiviCRM and compatibility updates are published	| No	| **Required**
| QA	| All patches are subjected to peer review	| No	| *Suggested*
| QA	| All patches are subjected automated tests	| No	| **Required**
| Support	| Documentation is published	| *Suggested*	| **Required**
| Support	| Issues are tracked in an open, public issue management system	| *Suggested*	| **Required**

### Acting on review results

#### If the extension needs work

If a review indicates that the extension needs further improvement before it can be approved, the reviewer should take these steps:

1.  Edit the extension's node on civicrm.org to set the field "Reviewed and ready for automated distribution?" to "Needs work: This Extension Release has been reviewed and needs work from the developer before the review can continue".
1.  Add a comment to the issue to notify the issue reporter that the extension needs work; specifically mention the issues that prevent approval as well as other items which the developer may want to improve at their discretion.

Continue monitoring the issue for updates from the developer, and respond in a timely way to answer questions or to conduct a follow-up review after changes have been made.

#### If the extension is approved

If a review indicates that the extension should be approved, the reviewer should take these steps:

1.  Edit the extension's node on civicrm.org to set the field "Reviewed and ready for automated distribution?" to "Yes: This Extension Release has been reviewed and is ready for automated distribution."
1.  Add a comment to the issue to notify the issue reporter that the extension has been approved for automated distribution.  Also mention any items which the developer may want to improve, even though they did not prevent the extension from being approved.
1.  Close the issue.
1.  Optionally: Mention the extension approval on Twitter or in the extensions channel at chat.civicrm.org.
1.  Congratulate yourself on your contribution to CiviCRM. Thank you!


## Benefits

Based on a project's maturity and stewardship, it may be eligible to use resources from `civicrm.org`.

| Action Type | Benefit/Resource/Privilege	| Eligibility |
| ----------- | ---------------------------- | ----------- |
| Admin	| The project code may be stored in github.com/civicrm/civicrm-core.git.	| "Official" projects (regardless of stability) |
| Admin	| The project code may be stored in github.com/civicrm/{$project}	| "Official" projects (regardless of stability)
| Communication	| Direct discussions through `chat.civicrm.org` 	| All projects
| Communication	| Direct discussions through `lists.civicrm.org`	| All projects
| Communication	| Direct discussions through `wiki.civicrm.org` 	| All projects
| Distribution	| Discovery on the in-app screen (ie. automated distribution)	| All projects ("Stable" or "Incubation") [where technically applicable]
| Distribution	| Project may be bundled into the standard CiviCRM tarballs.	| "Official" projects ("Stable" or "Incubation")
| Distribution	| The project is listed in `http://civicrm.org/extensions`	| All projects
| Distribution	| Test and demo sites on civicrm.org include the extension.	| "Official" projects ("Stable" or "Incubation")
| Marketing	| The project is included in official marketing literature about CiviCRM	| "Stable", "Official" projects
| QA	| The `civicrm.org` build-bot runs extension tests for PRs (own repo)	| "Official" projects (regardless of stability)
| QA	| The `civicrm.org` build-bot runs extension tests for PRs (civicrm-core repo)	| "Official" projects ("Stable" or "Incubation")
| Support	| The project may have its own space or component on "lab.civicrm.org"	| "Official" projects (regardless of stability)

## Obsolete Extensions

Sometimes an extension's functionality becomes part of CiviCRM Core, or is otherwise redundant, and the extension *should no longer be used*.

At that point it can be added to the [Extension Compatibility List](https://github.com/civicrm/civicrm-core/blob/master/extension-compatibility.json)
with one or more of the following flags:

- **`"obsolete"`** - extension will not be installable, and will be labeled "Obsolete" in the in-app *Manage Extensions* page. Any dependencies to an obsolete extension will be ignored.
- **`"disable"`** - extension will be automatically disabled by the CiviCRM upgrader.
- **`"uninstall"`** - extension will be automatically uninstalled by the CiviCRM upgrader.
- **`"force-uninstall"`** - extension code will be prevented from loading at all (necessary if the extension's presence would cause a fatal error such as a redeclared class).

For example, APIv4 was moved from [an extension](https://github.com/civicrm/org.civicrm.api4) into core in 5.19.
The extension was marked `"obsolete": 5.19` and `"force-uninstall": true` to prevent php fatal errors due to the same classes now being in core.
Any extensions declaring `org.civicrm.api4` as a dependency in their `info.xml` would continue to work without it, as the dependency would be ignored as of 5.19. 

## Abandoned Extensions {:#abandoned-extensions}

A CiviCRM Extension may be abandoned when the project author is no longer releasing updates and/or is not responding to support requests within 14 days or has expressly stated that they are no longer actively maintaining the project.

The CiviCRM community has two options for managing an abandoned extension.

### Transfer Ownership to a New Maintainer

When an extension author has stopped being responsive:

* and there are important bugs in the extension
* and someone else has offered to take over maintenance.

#### Procedure

1. Open an issue on the issue tracker of the project. [See example request](https://github.com/futurefirst/Email-Amender/pull/1#issuecomment-530755565)
2. Wait 14 days for a response.
3. If no response, or if a response clearly indicates the author has no intention to maintain the project going forward:
   1. Change the node author for the extension from civicrm.org
   2. Change the Git URL for the extension
   3. Add the new author as a co-maintainer on civicrm.org
   4. If the extension is on Gitlab, add the new maintainer on the project.

### Request Removal of the Extension

* When an extension is superseded by another extension, or
* When an extension has critical bugs and is not being updated by the maintainer.

#### Procedure

1. Open an issue on the issue tracker of the project. [See example request](https://github.com/ChrisChinchilla/CiviCRM-eWay-recurring-payment-processor/issues/59)
2. Wait 14 days for a response.
3. If no response (or if the author confirms it is abandoned), create a ticket in the [Extensions Review Request issues queue](https://lab.civicrm.org/extensions/extension-review-requests/issues) asking for the extension to be either a) de-listed for in-app installation, or b) unpublished from civicrm.org