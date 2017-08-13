# How to contribute

This chapter assumes that you have identified a bug or improvement for CiviCRM where the best resolution is to [write and contribute code to core](/core/hacking.md).

!!! note

    This chapter will refer to a number of resources that are explained in greater depth in the [Developer Community](/basics/community.md) chapter.


## Create an issue

Creating a good issue is an important first step and often involves research, discussion, and thoughtful description.

[Following these comprehensive steps](/tools/issue-tracking.md#guidelines) to create your issue.

## Document your change

Your changes might require documentation updates. Read about [when to document](/documentation/index.md#when) and [how to document](/documentation/index.md#contributing) and follow steps as necessary.

## Write tests

Having a plain-language description of how things should work in hand, it's time to operationalize the description and build automated tests for the feature.  CiviCRM comes with a variety of [testing tools](/testing/setup.md) that help ensure that changes don't break existing functionality.

Since CiviCRM [doesn't release code with failing tests](/tools/jenkins.md), your bug or improvement must not be covered in the existing tests.  Maybe there are incomplete tests, maybe the tests aren't valid measures of the functionality, or maybe your feature lacks test coverage.  Either way, you will need to write them to make sure your work doesn't get undermined by future changes.

Use your documentation to identify tests that can be run, and then write them.  If you are adding functionality, you may not have the code that the test will call, but you can write your tests as if all the pages and functions exist, defining them later.

## Make changes

It's finally time to write the code.  The preparatory steps can seem tedious, but they're essential for working effectively in a worldwide team on software used by thousands of diverse organizations.  Hopefully these steps have made your coding task clearer and helped you be aware of the variety of priorities and use cases.

The key in making changes is legibility: helping others see what you've changed and why.

### Coding style

One element of legibility is literal: make your changes according to the [CiviCRM coding standards](/standards/index.md).  This doesn't just make the code more readable on its own; standards make the diff more legible too.

Each pull request is [automatically tested](/tools/jenkins.md) by PHP_CodeSniffer according to [the standards](https://github.com/civicrm/coder), and you should save time and test your code yourself.

### Making commits

Follow these steps to [make high-quality commits](/tools/git.md#committing).

Once you've completed the work, revisit your documentation and tests to see if you've missed anything.

## Open a pull request

Read about [creating a pull request](/tools/git.md#pr) which includes information on writing a good subject line and minding the scope of your PR.

Once you submit your pull request, CiviCRM's [Jenkins server](/tools/jenkins.md) will build a copy of CiviCRM and run tests against it, beginning with `PHP_CodeSniffer`.  If tests fail, you will be able to follow a link to view details.

Other developers may comment on your code, raising questions or concerns or marking the changes as approved.  This is fine, but it is important not to hide important discussion.  If substantive discussion occurs in a pull request, note it in Jira.  If a pull request is closed in favor of another, explain that in Jira and mention the old pull request in the new one.

The goal is that the next person working on this feature area shouldn't have to do a lot of archeology to figure out the motivations, concerns, and impact of your changes.

## Maintain your local changes

While your pull request is reviewed, and even after it is merged, you will need to maintain your code on the site that needed the changes.  There are two main techniques for this.

First, you can keep your `civicrm` directory under version control, including your changes there.  If you need to upgrade while your changes are still in review, [rebase](/tools/git.md#rebase) your changes on top of the new version.

Alternatively, you can use a custom PHP or template override directory.  While this is generally discouraged for long-term customizations of your site (extensions are better), it can be an efficient way to track short-term overrides.  Just declare the path to the custom PHP and template folders in the Administer - System Settings - Directories page and copy your changed file(s) there, placing them under the same directory structure as within the `civicrm-core` repository.  Note the issue number and pull request in a comment at the top of each file, and remember to check the directory each time you upgrade.  Once your change is merged, just delete the override.

## Review some other code

CiviCRM works through the generosity of its users and the organizations that employ them.  Now that you have a pull request open (and some experience working with the CiviCRM codebase), why not take some time to [review another pull request](/core/pr-review.md)?  

Pick one from the list of open pull requests, review the corresponding Jira ticket, merge the changes into your development copy of CiviCRM, and see how it works.  Share your thoughts on the pull request.  You'll notice that the time you spend reviewing others' code and interacting with the rest of the community will serve you well: you'll be a better CiviCRM developer, and you'll have a better product.
