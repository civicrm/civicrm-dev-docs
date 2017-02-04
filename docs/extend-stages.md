# Extension Life cycle

The CiviCRM ecosystem is built on the belief that non-profit
organizations can serve themselves best by collaborating in development of their
data-management applications.  As staff, volunteers, and consultants for
non-profit organizations, we can share our new enhancements and extensions --
and build a richer whole for the entire ecosystem.

Of course, this sharing arrangement means that many members of the community
have a split role, e.g.

-   Sometimes, we are consumers. We want to quickly browse the available
    extensions, pick the ones which look best, and install them. We
    expect these to just work -- both now and going forward (with future
    upgrades).
-   Sometimes, we are developers.  We enjoy building great functionality, and we
    want to invite people to use our products, but we need to juggle the
    publishing tasks (like testing and maintenance releases) with the goals
    and resources provided by our bosses and clients.

The purpose of this document is to describe the process of publishing
extensions through the CiviCRM ecosystem.

## Definitions

### Project Maturity

Should we expect this to work for most users? Should we expect to work in 6
months?

Experimental
:   An experimental project offers zero support, stability, or maintenance.
    It may be useful for discussion, finding collaborators, or proving a
    concept.

Incubation
:   An incubation project offers some degree of support,
    stability, or maintenance. It's probably in use at multiple
    organizations. However, the levels are not guaranteed; some gaps and
    road bumps should be expected. A project may be "Incubation" for days
    or months or years.

Stable
:   A stable project has undertaken significant efforts to
    ensure that it works and continues working in the future. It has a
    strong quality-signal.

Deprecated
:   The project is no longer being maintained. It may work
    today; but it's liable to break tomorrow (unless someone steps up to
    manage it).

### Stewardship

Who manages a project? Who decides whether the project is experimental? Or
maintained? Or unmaintained?

Contributed
:   This project is managed by an individual or company in the ecosystem. All
    design, support, and maintenance are at discretion of the original author.

Official
:   The project is monitored as a community resource.
    Generally, the original author retains editorial control, but the
    project receives more strenuous reviews and follows stricter standards
    with feedback from others in the community.

Seeking Maintainer
:   This project does not have a person or organization responsible for it.
    If you think the project is useful, feel free to take responsibility for it.

### Support Model

How do you submit questions and requests about issues?

Free
:   Submit questions and requests to an open bug-tracker.

Negotiated
:   Issues may be reported to open bug-tracker. If the
    author agrees it is critical or data-loss, they may address it.
    Otherwise, you need to negotiate a contract.

Pre-Paid
:   The author will not engage in any support discussions unless you have
    pre-paid for support.

### Quality Signals

How do we know if an extension is any good?

Self-Assessment
:   An author makes a claim about the stability of his
    work. (This is a low-tech, low-touch process.)

Informal Discussion
:   One or more experts give gut reactions. (This
    is a low-tech, high-touch process.)

Formal Review
:   One or more experts assesses the quality,
    maintainability, best-practices, etc. using formal criteria. (This is a
    low-tech, high-touch process.)

Social Metrics
:   Data-points (such as #installations or average
    5-star rating) is collected from many people. (This is a high-tech,
    low-touch process.)

Technical Metrics
:   Technical details (such as test-coverage,
    test-results, style-checks, or cyclomatic complexity) are checked by a
    bot. (This is a high-tech, low-touch process.)

## Workflow

The database on `civicrm.org` publishes information about available extensions,
including maturity and stewardship. This is significant because it affects
authors (who publish the extension) and users (who download the extension) and
determines access to communal resources on `civicrm.org`. The particulars are
determined the maturity and stewardship of the project -- with a few basic
rules of thumb:

-   The author always registers his extension on `civicrm.org` by creating an
    `extension` node.
-   *Official* extensions are subject to more scrutiny than *Contributed*
    extensions.
-   *Experimental*, *Incubation*, and *Deprecated* extensions have simple, open
    processes -- such as *Self-Assessment* or *Informal Discussion*.
-   *Stable* extensions require some kind of *Formal Review*.

Based on these rules, we can fill out a full table of the workflow:


| Maturity	| Stewardship	| Primary Quality Signal	| How does an author get his extension designated as X?	| How does a user download an extension with X designation? |
| ------------- | ------------- | ----------------------------- | ----------------------------------------------------- | --------------------------------------------------------- |
| Experimental	| Contributed	| Self-Assessment	| In `civicrm.org`, the author creates an "extension" node and flags it as "Experimental".	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Experimental	| Official	| Informal Discussion	| As above. *Additionally* The author announces to a high-visibility medium (such as blog or mailing-list). If discussion is persuasive, a senior member of core team flags the project as official.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Incubation	| Contributed	| Self-Assessment	| In `civicrm.org`, the author creates an "extension" node and flags it as "Incubation".	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Incubation	| Official	| Informal Discussion	| As above. *Additionally* The author announces to a high-visibility medium (such as blog or mailing-list). If discussion is persuasive, a senior member of core team flags the project as official.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Stable	| Contributed	| Formal Review (light)	| In JIRA, the author requests a formal peer review.  Once the reviewer is satisfied, they mark the node in `civicrm.org` as Stable.	| In app, go to "Add New" and choose the extension.
| Stable	| Official	| Formal Review (heavy)	| As above. *Additionally* FormalReview criteria are more detailed. Announce to a high-visibility medium. At least one reviewer must be a senior member of the core team.	| In app, go to "Add New" and choose the extension.
| Deprecated	| Contributed	| Self-Assessment	| In `civicrm.org`, the author marks the "extension" node as deprecated and announce to a high-visibility medium.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.
| Deprecated	| Official	| Informal Discussion	| The author announces intent to deprecate in a high-visibility medium. If discussion is persuasive and no alternative maintainer comes forward, a senior member of core team flags the project as official.	| Locate the extension on the website. View a block which says, "Install Instructions", which includes drush/wp-cli commands.


## Formal Review

To designate an extension as *Stable*, someone must conduct a *Formal Review*
and assess several criteria. As a rule of thumb, *Contributed* extensions are
subject to a gentler review (fewer criteria), and *Official* extensions are
subject to more stringent review (more criteria).

|   	|       | Contributed	| Official |
|------ | ----- | ----------------------------- | --------------------- |
| | | Review by at least one peer/contributor	| Review by at least one senior member of core team |
| Admin	| License code under AGPLv3+, GPLv2+, LGPLv2+, MIT/X11, or BSD-2c	| Required	| Required |
| Admin	| Publish code on github.com 	| Required	| Required |
| Admin	| Put the extension name under the "org.civicrm.*" namespace	| Not assessed	| Suggested (Not Required) |
| Admin	| Bus factor >= 2	| Not assessed	| Suggested (Not Required) |
| Admin	| Grant project admin access to infra team	| Not assessed	| Suggested (Not Required) |
| Admin	| Release schedule is aligned with core.	| Not assessed	| Suggested (Not Required)
| Coding	| All code complies with civicrm-core style guidelines.	| Not assessed	| Required
| Coding	| Automated tests execute within 3 minutes (or less).	| Not assessed	| Suggested (Not Required)
| Coding	| All dependencies are at similar stage. (Ex: A stable project should not depend on an experimental project.)	| Not assessed	| Required
| Coding	| Strings are wrapped in ts()	| Suggested (Not Required)	| Required
| Coding	| The project does not *override* PHP, TPL, JS, or SQL from civicrm-core.	| Required	| Required
| Coding	| The project does not *conflict* with other official projects.	| Suggested (Not Required)	| Suggested (Not Required)
| Distribution	| The project is packaged as a CiviCRM Extension, Drupal Module, Backdrop Module, Joomla Extension, or WordPress plugin.	| Required	| Required
| Distribution	| Have a stable version (1.0+; not alpha or beta)	| Required	| Required
| Distribution	| Provide a demo site	| Suggested (Not Required)	| Suggested (Not Required)
| QA	| Works in all CMS's (for CiviCRM Extension)	| Suggested (Not Required)	| Suggested (Not Required)
| QA	| Include an automated test suite	| Suggested (Not Required)	| Required
| QA	| Periodically re-validate with newer versions of CiviCRM. Publish updates for compatibility.	| Not assessed	| Required
| QA	| Subject all patches to peer review	| Not assessed	| Suggested (Not Required)
| QA	| Subject all patches to automated tests	| Not assessed	| Required
| Support	| Publish documentation	| Suggested (Not Required)	| Required
| Support	| Track issues in an open, public issue management system	| Suggested (Not Required)	| Required

## Benefits

Based on a project's maturity and stewardship, it may be eligible to use
resources from `civicrm.org`.

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
| Support	| The project may have its own space or component on "issues.civicrm.org" (JIRA)	| "Official" projects (regardless of stability)
