# Reviewing a PR 

You, too, can be a CiviCRM PR reviewer!
You do not need special access or merge rights on the CiviCRM gitHub organization.
What you do need, is…

* [GitHub Account](https://github.com)
* A [CiviCRM Development Environment](https://github.com/civicrm/civicrm-buildkit/blob/master/doc/civibuild.md) (this might be optional, but good to have). One benefit is the ability to check out the PR in your environment.
* Comfort reading code and patches

## Pick a PR

Look through the list of [open PRs that have sucessfully passed the automated test suite](https://github.com/civicrm/civicrm-core/pulls?utf8=✓&q=is%3Aopen%20is%3Apr%20status%3Asuccess). The easiest PRs to review will probably be the ones that have no comments.

You should see if there have already been comments on the PR and if the PR looks ready to be reviewed? Have suggested changes already been made?

Be aware of flags such as “WIP” - work in progress.

Look for the green check-mark or the red-X. If the automated build tests have failed, then the PR is not ready for review. The PR submitter should have received a notification of this.

Merge-conflicts also indicate the PR is not ready for review.

**Claim the PR by commenting on it that you are reviewing it.**

## Read about the Issue
Every PR *should* have an issue ID for [https://issues.civicrm.org](https://issues.civicrm.org) and that JIRA issue should be linked from the PR's page on GitHub.
Read the original issue and understand how to reproduce the problem and what the solution looks like as well.

## Read the code changes
On the PR, click over to “Files Changed” and understand what the code is doing.

## Does this change make sense for EVERYONE?

* Is this really a bug? Or is it just outside of the submitter’s use-case?
* Will this change take users by surprise?
* Does it alter defaults?
* Does it add functionality in a generalized way that is configurable?


## Quality
* Is the code readable, and therefore maintainable by the next developer that has to work with it?
* Does it follow best practices? *(whose best practice?)* (Note: basic code format standards are checked in the automated testing process.)
* Are there tests needed for this change? *(how should I know?)*

If you think this is an appropriate change to core, then you can proceed to validate the PR.


## Reproduce the problem
Confirm which branch the PR was created against.
This is probably either Master or the LTS.
Setup an instance from that branch. Buildkit (civibuild) is probably how you want to do this.
Repeat the steps to reproduce described in the Jira Issue.


...

rough notes follow
Patches Welcome.


Navigate to the build

Confirm that the issue was a problem and a problem “worth solving”
… generally worthy of being in core… applies to all users

Review the Jira Issue.

Confirm that the PR works as advertized by observing the result in the build.

Confirm that the issue was reproducible in a build of master.


Review the code …
	makes sense
	red flags



“@” mention a core team members and report the findings of the review and recommend action…

