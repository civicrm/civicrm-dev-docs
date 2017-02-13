# How to contribute

This chapter assumes that you have identified a bug or improvement for CiviCRM where the best resolution is to [write and contribute code to core](/core/hacking.md).

!!! note

    This chapter will refer to a number of resources that are explained in greater depth in the [Developer Community](/basics/community.md) chapter.

-   Check the latest version
-   Talk over the issue
-   Research existing issues
-   Describe the issue
-   Document the feature
-   Write tests
-   Make changes
-   Open a pull request
-   Maintain your local changes
-   Review some other code

## Check the latest version

There's no sense in planning any changes to CiviCRM's core code without looking at the most recent release.  Any changes you make will be based upon it, and it may include a fix or attempted resolution that may change your thinking about the issue.

It's best to start with upgrading your own site rather than just trying to use one with demo data.  That way, you can be sure to know how the system behaves with your real-life data.  

If upgrading your site doesn't resolve it, try a plain installation of CiviCRM, such as one generated with Buildkit.  This will ensure that your site-specific data isn't the problem, and having a plain vanilla site will be important for trying out your changes later.

## Talk over the issue

To get your ideas together for later steps, it's best to start with a conversation.  This doesn't need to be technical, but it should be with someone familiar with using CiviCRM.  A coworker or consultant might be a place to start, or you could talk it over on [Mattermost](https://chat.civicrm.org/) or [Stack Exchange](http://civicrm.stackexchange.com/).

In your conversation, think about some of the following questions:

-   How severe is the impact on organizations using CiviCRM?

-   Has this feature's behavior changed recently?  Is a bug a regression, or has it always been this way?  Is this a new feature that doesn't handle all situations properly?

-   Who might like things the way they are?  Are there ways to resolve the issue that meet their needs as well as yours?

-   Will your change be self-explanatory, or will other users need an explanation?

If you are able to coherently explain the problem and resolution&mdash;and reasonably confident that fix will be good for everyone&mdash;it's time to register the issue with CiviCRM.

## Research existing issues

It's now time to get your issue into [Jira](https://issues.civicrm.org/).  To start, search for existing issues that may be the same as or related to yours.  Jira's search will order by relevance, but you are searching over a decade of issues, so you may get overwhelmed with old items.  Consider filtering Created Date to two years ago or newer.

If an issue directly describes your situtation, your job will be different: read it over, and edit or comment as necessary.  If the issue is marked as closed and completed, you should create a new issue indicating a regression, and you should link to the original issue you found.

If issues you find are related but not quite the same, you should still record them so that you can mention them in the issue you create.

## Describe the issue

Now's the time to create your issue.  Give it a title that describes your issue concisely, and explain the issue in the details.  In writing your issue, remember that your audience includes a variety of people:

-   Other users encountering the same problem now
-   Maintainers deciding whether to include your code
-   Developers considering future changes
-   The release notes editor compiling the notes
-   Users browsing what's new in an upcoming version

Readers will come from different perspectives and contexts, so thorough explanations and coherent summaries are valuable.  A well-written issue will be taken more seriously, increasing the likelihood that your changes are accepted and that others engage in your issue.

### Naming your issue

*Vague issue titles are boring and unhelpful.*  They don't inspire people to use or upgrade CiviCRM, and they make it difficult for implementors and developers to know what's different.  Don't say "improve" unless the improvement is so scattered and subtle that you can't say anything else.  Instead, make the specific improvements explicit.

Bug titles are slightly different, but they still should never be vague.  *A good bug title simply says the bad thing that's happening.*   Great examples include the following:

- "Batch merge redirects users to snippet URL"
- "Contribution page: missing translation"
- "Cannot create smart group from 'Find participants'"  

The best leave no question as to what was going wrong or what has changed: something undesirable was happening, and once this issue is resolved, it won't happen anymore.

### Issue scope

*It's important to keep your issue snappy and closeable.*  A Jira issue that stays open long after commits have been merged into core is confusing to users and demoralizing for contributors.  The way to prevent this is to make issues distinct and coherent so they're clearly done or not done.

Better yet, describe the issue distinctly and coherently yourself.  If you find an existing issue that was reported vaguely, there's no reason not to revise the description.  If the original issue involves several things, don't be shy about closing it and opening new ones--just document what you've done.

A rule of thumb is that if an issue has more than 2 or 3 pull requests in GitHub (described below), something is wrong.  It may be a series of false starts, and that's okay, but if it's a bunch of pull requests against the same repository, you probably should have opened new issues to describe the separate features or bugs&mdash;or to document a regression or feature gap.

### Categorization

Categorization is useful for finding issues in Jira, and it also determines how issues appear in the release notes.

When setting the issue **Type**, "Bug" results in it being listed among Bugs Resolved in the release notes.  Otherwise, issues appear in Features.  

The **Component/s** field determines where the issue goes in the notes, but it will only go one place.  There's no value in saying something is "Accounting Integration", "CiviContribute", "CiviEvent", and "WordPress Integration": the editor will pick the most relevant one for the notes.

The **Priority** field can get contentious, but use your best sense as to the impact that your issue will have.  Think of it as the product of the breadth (the size of the user base that may notice) and depth (how much those users are affected) of the issue.

**Affects Version/s** doesn't need to be each and every version that the problem affects, but it is helpful to indicate the extent of it.  Include the latest version you tested it on, and include the earliest version in the stable and long-term support series you know it to affect.

You might wonder what the **Funding Source** means.  If you plan on writing code yourself, mark it as "Contributed Code".  Otherwise, mark it as "Needs Funding".

## Document the feature

Now that you have an issue created, you can start work.  The best place to begin is to document what you want to happen.  By writing the documentation first, you have a way to measure whether the feature works.

If you're addressing a bug, document the feature that the bug affects.  You may find documentation in the [User and Administrator Guide](https://docs.civicrm.org/user/en/stable/), or you may have to start from scratch.  Either way, save what you do and contribute it back to the guide once you're finished.

## Write tests

Having a plain-language description of how things should work in hand, it's time to operationalize the description and build automated tests for the feature.  CiviCRM comes with a variety of [testing tools](https://wiki.civicrm.org/confluence/display/CRMDOC/Testing) that help ensure that changes don't break existing functionality.

Since CiviCRM doesn't release code with failing tests, your bug or improvement must not be covered in the existing tests.  Maybe there are incomplete tests, maybe the tests aren't valid measures of the functionality, or maybe your feature lacks test coverage.  Either way, you will need to write them to make sure your work doesn't get undermined by future changes.

Use your documentation to identify tests that can be run, and then write them.  If you are adding functionality, you may not have the code that the test will call, but you can write your tests as if all the pages and functions exist, defining them later.

## Make changes

It's finally time to write the code.  The preparatory steps can seem tedious, but they're essential for working effectively in a worldwide team on software used by thousands of diverse organizations.  Hopefully these steps have made your coding task clearer and helped you be aware of the variety of priorities and use cases.

The key in making changes is legibility: helping others see what you've changed and why.

### Coding style

One element of legibility is literal: make your changes according to the [CiviCRM coding standards](https://wiki.civicrm.org/confluence/display/CRMDOC/PHP+Code+and+Inline+Documentation), which are just a relaxed version of [Drupal's standards](https://www.drupal.org/docs/develop/standards).  This doesn't just make the code more readable on its own; standards make the diff more legible too.

Each pull request is automatically tested by PHP_CodeSniffer according to [the standards](https://github.com/civicrm/coder), and you should save time and test your code yourself.

### Making commits

In making commits, remember that this isn't just a small personal project: your audience is hundreds of other developers&mdash;now and ten years from now&mdash;as well as end users trying to understand features and bugs.  By leaving a commit history that makes sense&mdash;both in content and in [commit messages](https://wiki.civicrm.org/confluence/display/CRMDOC/Git+Commit+Messages+for+CiviCRM)&mdash;you will make the code more legible for everyone.

Once you've completed the work, revisit your documentation and tests to see if you've missed anything.

## Open a pull request

Open a pull request on GitHub to merge your development branch with the `master` branch.  Check that the title makes sense and that the description indicates what's going on.  Pull request titles don't need to be identical to issue titles, and in particular, you may want to focus more positively on the changes in code than on the broader feature changes.

Once you submit your pull request, CiviCRM's Jenkins server will build a copy of CiviCRM and run tests against it, beginning with PHP_CodeSniffer.  If tests fail, you will be able to follow a link to view details.

Other developers may comment on your code, raising questions or concerns or marking the changes as approved.  This is fine, but it is important not to hide important discussion.  If substantive discussion occurs in a pull request, note it in Jira.  If a pull request is closed in favor of another, explain that in Jira and mention the old pull request in the new one.

The goal is that the next person working on this feature area shouldn't have to do a lot of archeology to figure out the motivations, concerns, and impact of your changes.

## Maintain your local changes

While your pull request is reviewed, and even after it is merged, you will need to maintain your code on the site that needed the changes.  There are two main techniques for this.

First, you can keep your `civicrm` directory under version control, including your changes there.  If you need to upgrade while your changes are still in review, rebase your changes on top of the new version.

Alternatively, you can use a custom PHP or template override directory.  While this is generally discouraged for long-term customizations of your site (extensions are better), it can be an efficient way to track short-term overrides.  Just declare the path to the custom PHP and template folders in the Administer - System Settings - Directories page and copy your changed file(s) there, placing them under the same directory structure as within the `civicrm-core` repository.  Note the issue number and pull request in a comment at the top of each file, and remember to check the directory each time you upgrade.  Once your change is merged, just delete the override.

## Review some other code

CiviCRM works through the generosity of its users and the organizations that employ them.  Now that you have a pull request open (and some experience working with the CiviCRM codebase), why not take some time to review another pull request?  

Pick one from the list of open pull requests, review the corresponding Jira ticket, merge the changes into your development copy of CiviCRM, and see how it works.  Share your thoughts on the pull request.  You'll notice that the time you spend reviewing others' code and interacting with the rest of the community will serve you well: you'll be a better CiviCRM developer, and you'll have a better product.
