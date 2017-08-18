# Issue-tracking systems

As of 2017 CiviCRM uses three different system for tracking and managing issues (aka tickets). This page offers a brief summary of the systems and helps developers understand when and how to use them.


## Systems

### Jira {:#jira}

**[issues.civicrm.org](https://issues.civicrm.org/jira)**

Used as an issue-tracking system for: 

* **[CiviCRM core](https://issues.civicrm.org/jira/browse/CRM)**
* [CiviVolunteer](https://issues.civicrm.org/jira/browse/VOL)
* [CiviHR](https://issues.civicrm.org/jira/browse/HR)
* *...and a small number of [other projects](https://issues.civicrm.org/jira/secure/BrowseProjects.jspa?selectedCategory=all&selectedProjectType=all)*

### GitHub {:#github}

**[github.com/civicrm](https://github.com/civicrm)**

Used as an issue-tracking system for:

* [buildkit](https://github.com/civicrm/civicrm-buildkit/issues)
* [cv](https://github.com/civicrm/cv/issues)
* [civix](https://github.com/totten/civix)
* *...and many [other projects](https://github.com/civicrm)*

!!! note
    Some projects (e.g. CiviCRM core) have their repository hosted on GitHub but do *not* use the GitHub issue-tracking functionality. For these projects you will notice there is no "Issues" tab. 

 
### GitLab {:#gitlab}

**[lab.civicrm.org](http://lab.civicrm.org)**

Used as an issue-tracking system for:

* [civicrm.org website issues](https://lab.civicrm.org/marketing-team/civicrm-website)
* [infrastructure issues](https://lab.civicrm.org/infrastructure/ops/issues)
* *...and some [other projects](https://lab.civicrm.org/explore/projects)*

In 2017, CiviCRM began to use a private GitLab installation for *some* projects.


## Guidelines for creating issues {:#guidelines}

### When to create an issue {:#when-to-create}

All changes to CiviCRM *Core*, however small, must receive a Jira issue. Among other things, this helps us assemble the [release notes](https://github.com/civicrm/civicrm-core/tree/master/release-notes).

For other (non-core) ancillary projects, it's okay [submit a pull request](/tools/git.md#pr) *without* first creating an issue.

### Check the latest version {:#check-version}

There's no sense in planning any changes to CiviCRM's core code without looking at the most recent release.  Any changes you make will be based upon it, and it may include a fix or attempted resolution that may change your thinking about the issue.

It's best to start with upgrading your own site rather than just trying to use one with demo data.  That way, you can be sure to know how the system behaves with your real-life data.  

If upgrading your site doesn't resolve it, try a plain installation of CiviCRM, such as one generated with Buildkit.  This will ensure that your site-specific data isn't the problem, and having a plain vanilla site will be important for trying out your changes later.

### Talk over the issue {:#talk}

To get your ideas together for later steps, it's best to start with a conversation.  This doesn't need to be technical, but it should be with someone familiar with using CiviCRM.  A coworker or consultant might be a place to start, or you could talk it over on [Mattermost](https://chat.civicrm.org/) or [Stack Exchange](http://civicrm.stackexchange.com/).

In your conversation, think about some of the following questions:

-   How severe is the impact on organizations using CiviCRM?

-   Has this feature's behavior changed recently?  Is a bug a regression, or has it always been this way?  Is this a new feature that doesn't handle all situations properly?

-   Who might like things the way they are?  Are there ways to resolve the issue that meet their needs as well as yours?

-   Will your change be self-explanatory, or will other users need an explanation?

If you are able to coherently explain the problem and resolution&mdash;and reasonably confident that fix will be good for everyone&mdash;it's time to register the issue with CiviCRM.

### Research existing issues {:#research}

It's now time to get your issue into [Jira](https://issues.civicrm.org/).  To start, search for existing issues that may be the same as or related to yours.  Jira's search will order by relevance, but you are searching over a decade of issues, so you may get overwhelmed with old items.  Consider filtering Created Date to two years ago or newer.

If an issue directly describes your situation, your job will be different: read it over, and edit or comment as necessary.  If the issue is marked as closed and completed, you should create a new issue indicating a regression, and you should link to the original issue you found.

If issues you find are related but not quite the same, you should still record them so that you can mention them in the issue you create.

### Describe the issue {:#describe}

Now's the time to create your issue.  Give it a title that describes your issue concisely, and explain the issue in the details.  In writing your issue, remember that your audience includes a variety of people:

-   Other users encountering the same problem now
-   Maintainers deciding whether to include your code
-   Developers considering future changes
-   The release notes editor compiling the notes
-   Users browsing what's new in an upcoming version

Readers will come from different perspectives and contexts, so thorough explanations and coherent summaries are valuable.  A well-written issue will be taken more seriously, increasing the likelihood that your changes are accepted and that others engage in your issue.

#### Naming your issue {:#naming}

*Vague issue titles are boring and unhelpful.*  They don't inspire people to use or upgrade CiviCRM, and they make it difficult for implementors and developers to know what's different.  Don't say "improve" unless the improvement is so scattered and subtle that you can't say anything else.  Instead, make the specific improvements explicit.

Bug titles are slightly different, but they still should never be vague.  *A good bug title simply says the bad thing that's happening.*   Great examples include the following:

- "Batch merge redirects users to snippet URL"
- "Contribution page: missing translation"
- "Cannot create smart group from 'Find participants'"  

The best leave no question as to what was going wrong or what has changed: something undesirable was happening, and once this issue is resolved, it won't happen anymore.

#### Issue scope {:#scope}

*It's important to keep your issue snappy and closeable.*  A Jira issue that stays open long after commits have been merged into core is confusing to users and demoralizing for contributors.  The way to prevent this is to make issues distinct and coherent so they're clearly done or not done.

Better yet, describe the issue distinctly and coherently yourself.  If you find an existing issue that was reported vaguely, there's no reason not to revise the description.  If the original issue involves several things, don't be shy about closing it and opening new ones--just document what you've done.

A rule of thumb is that if an issue has more than 2 or 3 pull requests in GitHub (described below), something is wrong.  It may be a series of false starts, and that's okay, but if it's a bunch of pull requests against the same repository, you probably should have opened new issues to describe the separate features or bugs&mdash;or to document a regression or feature gap.

See also: [pull request scope](/tools/git.md#pr-scope)

#### Categorization {:#categorization}

Categorization is useful for finding issues in Jira, and it also determines how issues appear in the release notes.

When setting the issue **Type**, "Bug" results in it being listed among Bugs Resolved in the release notes.  Otherwise, issues appear in Features.  

The **Component/s** field determines where the issue goes in the notes, but it will only go one place.  There's no value in saying something is "Accounting Integration", "CiviContribute", "CiviEvent", and "WordPress Integration": the editor will pick the most relevant one for the notes.

The **Priority** field can get contentious, but use your best sense as to the impact that your issue will have.  Think of it as the product of the breadth (the size of the user base that may notice) and depth (how much those users are affected) of the issue.

**Affects Version/s** doesn't need to be each and every version that the problem affects, but it is helpful to indicate the extent of it.  Include the latest version you tested it on, and include the earliest version in the stable and long-term support series you know it to affect.

You might wonder what the **Funding Source** means.  If you plan on writing code yourself, mark it as "Contributed Code".  Otherwise, mark it as "Needs Funding".
