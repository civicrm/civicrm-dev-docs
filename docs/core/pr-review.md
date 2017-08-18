# How to review a core pull request

When someone [opens a pull request](/tools/git.md#pr) (aka "PR") on CiviCRM Core, it must be reviewed before we can merge it. Reviewing core PRs is a useful (and often much-needed) way of contributing to CiviCRM. You do not need any special access or merge rights. What you do need, is...

* [GitHub Account](https://github.com)
* A [CiviCRM Development Environment](https://github.com/civicrm/civicrm-buildkit/blob/master/doc/civibuild.md) (this might be optional, but good to have). One benefit is the ability to check out the PR in your environment.
* Comfort reading code and patches

Follow the remaining the steps below to review a pull request.

## Pick a PR

Look through the list of [open PRs](https://github.com/civicrm/civicrm-core/pulls) and choose one which meets all of the following criteria:

* The automated tests should be completed and passed, as indicated by a green check-mark. When automated tests *fail*, a red X display instead and GitHub sends the PR submitter a notification of the failure. Don't review PRs that have failed tests. You can add `status:success` to your search to show only PRs which have passed. (Here is a [direct link](https://github.com/civicrm/civicrm-core/pulls?utf8=%E2%9C%93&q=is%3Aopen%20is%3Apr%20status%3Asuccess)).

* There should not be any git merge conflicts. GitHub displays this at the bottom of the page by saying either *"This branch has no conflicts with the base branch"* or *"This branch has conflicts that must be resolved"*.

* The title should not contain `WIP` which indicates "Work In Progress".

* The comments should not contain any unresolved disagreements or unanswered questions.

If you're a beginning developer looking for easy PRs to review, you might have good luck by looking at ones with a low number of comments.


## Claim the PR

Add a comment to the PR like "Reviewing this now" to let others know that you intend to submit a review.


## Read about the issue

Every PR *should* have an issue ID for [JIRA](https://issues.civicrm.org) linked from the PR's page on GitHub. Read the original issue and understand how to reproduce the problem and what the solution looks like as well.


## Read the code changes

On the PR, click over to “Files Changed” and understand what the code is doing.

* Ensure the code is readable, and therefore maintainable by the next developer that has to work with it
* Ensure it follows best practices. *(TODO: what best practice?)* (Note: basic code format standards are checked in the automated testing process.)
* Consider whether any additional automated tests might be needed for this change. *(TODO: how should I know?)*


## Reproduce the problem

Confirm which branch the PR was created against. This is probably either `master` or the LTS. Setup an instance locally from that branch (e.g. with [buildkit](https://github.com/civicrm/civicrm-buildkit)), or test on the [public demo site if possible](https://civicrm.org/demo). Repeat the steps to reproduce described in the Jira Issue.

Confirm that the issue was a problem and a problem “worth solving”, generally worthy of being in core.


## Reproduce the fix

Confirm that the PR works as advertised by observing the result in the build.

You can either test locally or on the test server.

### Using the test server to review

Our test server automatically creates a dedicated CiviCRM installation for every PR so that (in most cases) it's easy to review the PR without needing to set up a local installation with the fix applied. To access the test build follow these steps:

1. In the PR, find the section at the bottom of the page which says "All checks have passed"
1. Go to: "Show all checks" > "Details" > "Console Output" > "Full Log"
1. Search in page for `CMS_URL`
1. The first result should bring you to a URL which points to an installation for the build of this PR.
1. Click on the URL to go to the built site and log in with username = `pradmin` and password = `pradmin1234`

### Reviewing locally

For more complicated PRs it is sometimes helpful or necessary to manually test them within a local development installation.

#### If the PR does not contain any database upgrades

*(This is the most common situation)*

Begin with a local development installation of CiviCRM master and apply the fix in the PR to your site.

An easy way to do this is:

1. Install [Hub](https://hub.github.com/)
1. `cd` to your `civicrm` root directory
1. Run `git checkout https://github.com/civicrm/civicrm-core/pull/1234` where `1234` is the PR number you're reviewing

#### If the PR contains database upgrades

*(This situation is less common)*

1. Install a buildkit site for the latest publicly available release of CiviCRM (*not* `master`). Pass the `--civi-ver` option to civibuild for this.
1. Update the `civicrm` directory files so that the codebase has the changes in the PR (perhaps by using [Hub](https://hub.github.com/) as described above).
1. From the `civicrm` directory, run `./bin/setup.sh -Dg` to update the generated-code
1. Run `drush civicrm-upgrade-db` to perform database upgrades


## Form an opinion about the fix

* The change should make sense for *all users*.
* The change should not take users by surprise.
* Significant changes should add functionality in a generalized way that is configurable.


## Write a review as a comment

Summarize your actions and findings, and recommend specific next steps (e.g. merging or otherwise). In your comment, tag [one of the active contributors](https://github.com/civicrm/civicrm-core/graphs/contributors) (e.g. `@eileenmcnaughton`) so they will see that the PR is ready for further action.



