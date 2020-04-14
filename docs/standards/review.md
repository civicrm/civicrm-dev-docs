These review standards provide a name and description for common review tasks.

## Usage

When [reviewing a pull-request](../core/pr-review.md), you may consult this list for ideas/inspiration on things to check.  If you find a problem or feel that some QA task remains to
be done, then it can help to post a link to the relevant guideline.  This practice allows newcomers to understand the critique, but it doesn't require you to
write a long, bespoke blurb.

!!! tip "Standard codes"
    Each standard has a code name (e.g. `r-explain`). These make it easier to reference the standards when chatting with others about PR review.

## Templates

You may conduct a structured review, checking each standard in turn. Doing this  will be easier if you copy a template and paste it into your Github comment.

* When conducting your first or second structured review, copy [template-del-1.0.md](https://raw.githubusercontent.com/civicrm/civicrm-dev-docs/master/docs/standards/review/template-del-1.0.md) or [template-mc-1.0.md](https://raw.githubusercontent.com/civicrm/civicrm-dev-docs/master/docs/standards/review/template-mc-1.0.md). It provides several examples.
* Once you're familiar with the criteria, copy [template-word-1.0.md](https://raw.githubusercontent.com/civicrm/civicrm-dev-docs/master/docs/standards/review/template-word-1.0.md). It's a bit shorter and quicker.

## General standards

### Explanation {:#r-explain}

_Standard code: `r-explain`_

Ensure the PR has an adequate explanation. 

If you were a site-builder reading the PR-log/release-notes and drilled into this PR, would you understand the description? If you were debugging a problem and traced the change back to this PR, would you understand why the change was made?

It is strongly encouraged that PR's include URLs/hyperlinks for any explanatory material (when available) -- such as a [Gitlab issue](http://lab.civicrm.org/), [JIRA issue](../tools/issue-tracking.md#jira), [StackExchange question](https://civicrm.stackexchange.com/), related PR, or [Mattermost chat](https://chat.civicrm.org). However, hyperlinks are not a substitute for a description. The PR should still have a description.

PR descriptions should generally follow the [pull-request template](https://github.com/civicrm/civicrm-core/blob/master/.github/PULL_REQUEST_TEMPLATE.md), although this could be waived if another structure is more expressive.

__Exception__: 

* [WIP](../tools/git.md#wip) PRs do not need a detailed explanation until they're ready for earnest review.
* Genuine [NFC](../tools/git.md#nfc) PRs do not need a detailed explanation. 

### User impact {:#r-user}

_Standard code: `r-user`_

If a user was comfortable using the old revision, would they upgrade and assimilate naturally and unthinkingly to the new revision? If not, has there been commensurate effort to provide a fair transition-path and communication?

### Documentation {:#r-doc}

_Standard code: `r-doc`_

Some changes require adding or updating documentation. Consider the impact of this change on users, system administrators, and developers. Do they need additional instructions in order to reap the benefits of this change? If so, [update documentation](../documentation/index.md) as necessary by making a corresponding PR on one of the guides.

### Run it {:#r-run}

_Standard code: `r-run`_

Use the code somehow. You don’t need to attack every imaginable scenario in every PR, but you should do something to try it out. Be proportionate.

## Developer standards

### Technical impact {:#r-tech}

_Standard code: `r-tech`_

* Would the patch materially change the contract (signature/pre-condition/post-condition) for APIv3, a hook, a PHP function, a PHP class, a JS widget, or a CSS class?
* Would you consider the changed element to be an officially supported contract? A de-facto important contract? An obscure internal detail?
* How might the change affect other parts of `civicrm-core`? extensions? third-party integrations?
* If it's hard to answer, look for inspiration:
    * Grep `civicrm-core` or [universe](../tools/universe.md) to find out where the API/hook/function/etc is called. Consider how these might be affected.
    * Look at the [Gotchas](#gotchas) for a list of issues that have been mistakenly overlooked in past reviews.
* If there is a foreseeable problem:
    * Is there a simple alternative?
    * Has there been commensurate effort to communicate change and provide a fair transition path?

### Code quality {:#r-code}

_Standard code: `r-code`_

Is it understandable? Does it follow common conventions? Does it fit in context? If it changes a difficult section of code -- does it tend to make that section better or worse?

### Maintainability {:#r-maint}

_Standard code: `r-maint`_

Many changes should introduce some kind of automated test or protective measure to ensure maintainability. However, there can be tricky cost/benefit issues, and the author and reviewer must exercise balanced judgment.

### Test results {:#r-test}

_Standard code: `r-test`_

If the [automated tests](../testing/continuous-integration.md) come back with any failures, look into why. Hopefully, Jenkins provides an itemized list of failures. If not, dig further into the "Console" output for PHP-fatals or build-failures.

## Gotchas

### Packaging {:#rg-pkg}

_Standard code: `rg-pkg`_

If the PR adds a new top-level file, new top-level folder, or novel file-type, consider whether "distmaker" will properly convey the file in `*.zip/*.tar.gz` builds.

If the PR *removes* a dangerous file, then common package handling may not be enough to remove the file. (This is particularly for Joomla users, but also true for with
manual file management on other platforms.) Consider updating `CRM_Utils_Check_Component_Security::checkFilesAreNotPresent`.

### Permissions {:#rg-perm}

_Standard code: `rg-perm`_

If the PR changes the permissions model (by adding, removing, or repurposing a permission), are we sure that demo/test builds and existing installations will continue to work as expected?

### Security {:#rg-sec}

_Standard code: `rg-sec`_

If the PR passes data between different tiers (such as SQL/PHP/HTML/CLI), is this data [escaped and validated](../security/index.md) correctly? Or would it be vulnerable to SQL-injections, cross-site scripting, or similar?

### Settings {:#rg-setting}

_Standard code: `rg-setting`_

If the PR adds or removes a setting, will existing deployments or build-scripts which reference the setting continue to work as expected?

### Upgrades {:#rg-upgrades}

_Standard code: `rg-upgrades`_

Some PRs change the database by modifying schema or inserting new data. Ensure new installations and upgraded installations will end up with consistent schema. (Extra: If it's a backport, take extra care to consider all upgrade paths.)

If the upgrade adds a column or index, use a helper function to insert it. (This makes the upgrade more robust against alternative upgrade paths, such as backports/cherry-picks.)

If the upgrade needs to populate or recompute data on a large table (such as `civicrm_contact`, `civicrm_activity`, or `civicrm_mailing_event_queue`), the upgrade screen could timeout. Consider applying the updates in batches.

If the upgrade inserts new strings, should they be generated in a multilingual-friendly way?

!!! seealso "See also: [Upgrade Reference](../framework/upgrade.md)"

### Hook signature {:#rg-hook}

_Standard code: `rg-hook`_

Adding a new parameter to an existing hook may be syntactically safe, but is it semantically safe?
