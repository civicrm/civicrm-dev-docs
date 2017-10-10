These review standards provide a name and description for common review tasks.

## Usage

When [reviewing a pull-request](/core/pr-review.md), you may consult this list for ideas/inspiration on things to check.  If you find a problem or feel that some QA task remains to
be done, then it can help to post a link to the relevant guideline.  This practice allows newcomers to understand the critique, but it doesn't require you to
write a long, bespoke blurb.

## Common

### Ensure that the PR links to a JIRA issue. {:#r-jira}

For most bug-fixes and improvements, there needs to be a [JIRA issue](/tools/issue-tracking.md#jira). However, small [NFC](/tools/git.md#nfc) PRs and some [WIP](/tools/git.md#wip) PRs may not need that. (***R-JIRA***)

### Examine test results. {:#r-test}

If the [automated tests](/testing/continuous-integration.md) comes back with any failures, look into why. Hopefully, Jenkins provides an itemized list of failures. If not, dig further into the "Console" output for PHP-fatals or build-failures. (***R-TEST***)

### Read the code. {:#r-read}

Is it understandable? Does it follow common conventions? Does it fit in context? If it changes a difficult section of code -- does it tend to make that section better or worse? (***R-READ***)

### Try it out. {:#r-run}

Use the code somehow. You don’t need to attack every imaginable scenario in every PR, but you should do something to try it out. Be proportionate. (***R-RUN***)

### Assess impact on users. {:#r-users}

If a user was comfortable using the old revision, would they upgrade and assimilate naturally and unthinkingly to the new revision? If not, has there been commensurate effort to provide a fair transition-path and communication? (***R-USERS***)

### Assess impact on extensions/integrations. {:#r-ext}

Would the proposal change the behaviors/inputs/outputs of an API, hook, or widely-used function? If an existing extension uses it, would it continue to work the same way? If you're unsure, consider grepping [universe](/tools/universe.md) for inspiration. If there is a foreseeable problem, has there been commensurate effort to communicate change and provide a fair transition path? (***R-EXT***)

### Assess impact on core. {:#r-core}

Would the patch change the contract for a PHP function or JS widget or CSS class? If so, have you verified that there are no stale/dangling references based on the old contract? Look for unexpected side-effects. (***R-CORE***)

### Check for tests or maintainability. {:#r-maint}

Many changes should introduce some kind of automated test or protective measure to ensure maintainability. However, there can be tricky cost/benefit issues, and the author and reviewer must exercise balanced judgment. (***R-MAINT***)

### Check for documentation. {:#r-doc}

Some changes require addition documentation, or adjustments to existing documentation. Consider the impact of this change on users, system administrators, and developers. Do they need additional instructions in order to reap the benefits of this change? If so, [update documentation](/documentation/index.md) as necessary by making a corresponding PR on one of the guides. (***R-DOC***)

## Gotchas

### Packaging {:#rg-pkg}

If the PR adds a new top-level file, new top-level folder, or novel file-type, consider whether "distmaker" will properly convey the file in `*.zip/*.tar.gz` builds. (***RG-PKG***)

### Permissions {:#rg-perm}

If the PR changes the permissions model, are we sure that demo/test builds and existing installations will continue to work the same? (***RG-PERM***)

### Security {:#rg-sec}

If the PR passes data between different tiers (such as SQL/PHP/HTML/CLI), is this data [escaped and validated](/security/index.md) correctly? Or would it be vulnerable to SQL-injections, cross-site scripting, or similar? (***RG-SEC***)

### Settings {:#rg-setting}

If the PR adds or removes a setting, will existing deployments or build-scripts which reference the setting continue to work as expected? (***RG-SETTING***)

### Schema {:#rg-schema}

If the PR changes the DB, ensure new installations and upgraded installations will end up with consistent schema. (Extra: If it's a backport, take extra care to consider all upgrade paths.) (***RG-SCHEMA***)

### Hook signature {:#rg-hook}

Adding a new parameter to an existing hook may be syntactically safe, but is it semantically safe? (***RG-HOOK***)
