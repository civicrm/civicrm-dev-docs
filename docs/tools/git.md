# Git, GitHub, and GitLab

CiviCRM uses git, GitHub, and GitLab to manage changes to the code. A solid understanding of these tools (especially git) is important for developing CiviCRM. This page provides some information about *how CiviCRM uses these tools* &mdash; but, due to the wealth of resources already available elsewhere online, this page does *not* attempt to teach you everything you need know about how to use these tools for CiviCRM development.

!!! tip
    If you are new to git, a great way to get started using it within the CiviCRM community is to [contribute to documentation](/documentation/index.md). The editing workflow involves git in the same was that core coding does &mdash; but the stakes are much lower!

## External resources {:#resources}

* Git
    * [Official documentation](https://git-scm.com/documentation)
    * [15 minute interactive tutorial](https://try.github.io/levels/1/challenges/1)
    * [Another site with more interactive tutorials](http://learngitbranching.js.org/)
* GitHub
    * [Official help site](https://help.github.com/)
    * [20 minute GitHub tutorial video](https://www.youtube.com/watch?v=0fKg7e37bQE)
    * [hub](https://hub.github.com/) - a useful commandline tool for GitHub which can speed up your workflow
* GitLab
    * [Official documentation](https://docs.gitlab.com/ce/README.html)

   
## Repositories

* GitHub - **[github.com/civicrm](https://github.com/civicrm/)**
    * As of 2017, most of CiviCRM's repositories are hosted on GitHub
    * Most of the repositories hosted on GitHub are owned by the "CiviCRM" organization. 
    * The [civicrm-core](https://github.com/civicrm/civicrm-core) repo is the most important one, but there are many other repositories for other aspects of the project.

* GitLab - **[lab.civicrm.org/explore/projects](http://lab.civicrm.org/explore/projects)**
    * CiviCRM also has some repositories hosted on its own private installation of GitLab


## Git workflow overview {:#contributing}

Whether you are contributing to civicrm-core or an ancillary project (using GitHub or GitLab) the process generally goes somewhat like this: 

1. (If working on core) [open an issue on Jira](/tools/issue-tracking.md#jira) to describe the change you'd like to make.
1. Find the page on GitHub or GitLab for the project to which you would like to contribute. We will call this repository the **upstream repository**.
1. **Clone** the upstream repository to your local machine. (If you are working on core, you should use [civibuild](/tools/civibuild.md) for this step.)
1. On the web page for the upstream repository, **fork** the upstream repository to your personal user account.
1. Within your local repository **add your fork** as a second git *remote*. *[Learn more...](#remotes)*
1. **Choose the correct base branch** in the upstream repository as the starting point for your changes. (Usually this will be `master`.) *[Learn more...](#base-branch)*
1. (If it's been some time since you've cloned) **pull or fetch** the latest changes from the *upstream repository* into the appropriate branch of your local repository. *(You might also need to  [upgrade your civibuild site](/tools/civibuild.md#upgrade-site).)*
1. Create (and checkout) a **new branch** for your changes, based on the correct branch (chosen above) in the upstream repository. *[Learn more...](#branching)*
1. Make your changes.
1. **Commit** your changes. *[Learn more...](#commiting)*
1. **Push** your changes *to your fork*.
1. **Open a pull request**. *[Learn more...](#pr)*
1. Wait for someone else to [review your pull request](/core/pr-review.md).
1. If you need to make more changes later, commit them on the same branch and push your new commits to your fork. The new commits the will automatically appear in the pull request.
1. If other people commit changes to the upstream repository which create *merge conflicts* in your pull request, then **rebase** your branch. *[Learn more...](#rebasing)*
1. Once your changes are merged, delete your local branch
 

## Specific steps


### Cloning {:#cloning}

__TODO__


### Managing multiple git remotes {:#remotes}

__TODO__


### Branching {:#branching}

Git uses branches to separate independent sets of changes. When creating a new branch, here are some things to keep in mind: 
 
* [Choose an appropriate base branch](#base-branch)
* You'll need to keep your local branch until its changes are merged. Sometimes this take several months. After it's merged, you can delete it.
* Give your branch a good name
    * The name of your branch is up to you.
    * It should be unique among your other outstanding branches.
    * It should only contain letters, numbers, dashes, and underscores.
    * If you have a Jira issue, you can use its number (e.g `CRM-1234`) as the name of the branch.

Create a new branch and switch your local repository to it:

```bash
$ git checkout upstream/master -b CRM-1234
```

* `upstream` is your local name for the [git remote](#remotes) which represents the upstream repository (e.g. `https://github.com/civicrm/civicrm-core`) to which you are contributing changes. Depending on how you have set up your local repo, this remote might have a different name, like `origin` or `civicrm`. 
* `master` is the name of the branch in the upstream repository on which you would like to base your changes
* `CRM-1234` is the name of your new branch

### Choosing a base branch {:#base-branch}

When create a new branch, you should explicitly declare a starting point.

__TODO__

### Committing {:#committing}

As much as possible, separate your changes into distinct commits that each make sense on their own. Using smaller commits will make your changes easier for others to review.

#### Writing a commit message {:#commit-messages}

Follow these guidelines to write a good commit messages:

* The first line should be a meaningful **subject**, which should:
    * be prefixed with a Jira issue number (if the commit is to CiviCRM core)
    * mention a "subsystem" after the issue number
    * be 72 characters or less, in total
    * be in "Sentence case"
    * use the imperative mood
    * not end in a period
    * examples: 
        * `CRM-20600 - Civi\Angular - Generate modules via AssetBuilder`
        * `CRM-19417 - distmaker - Change report to JSON`
* (optionally but recommended) After the subject, include a short **body**, which should:
    * have a blank line above it (below the subject)
    * be wrapped at 72 characters
    * explain *what*, *why*, and *how*


### Pull requests {:#pr}

!!! note "terminology"
    The terms "pull request", "merge request", "PR", and "MR" all effectively synonymous. GitHub uses "pull request", and GitLab uses "merge request".

#### Creating a pull request {:#pr-submit}

1.  In the web browser, navigate to the web page for your fork (e.g. `https://github.com/myuser/civicrm-core` )
1.  Click **Pull Request**
1.  There will be two branches specified – the (first) left should be "civicrm" (i.e. where the code is going to). The second (right) should be your branch. 
1.  Add a [good subject](#pr-subject) and explanation, and submit.

#### Writing a pull request subject {:#pr-subject}

Guidelines for writing a good subject line:

When filing a pull-request, use a descriptive subject. These are good examples:

 * `CRM-12345 - Fix Paypal IPNs when moon is at half-crescent (waxing)`
 * `(WIP) CRM-67890 - Refactor SMS callback endpoint`
 * `(NFC) CRM_Utils_PDF - Improve docblocks`

A few elements to include:

 * **CRM-_XXXXX_** - This is a reference to the [Jira issue tracker](/tools/issue-tracking.md#jira). A bot will setup crosslinks between JIRA and GitHub.
 * **Description** - Provide a brief description of what the pull-request does.
 * **(WIP)** - "Work in Progress" - If you are still developing a set of
   changes, it may be useful to submit a pull-request and flag it as
   `(WIP)`. This allows you to have discussion with other developers and
   check test results. Once the change is ready, update the subject line
   to remove `(WIP)`.
 * **(NFC)** - "Non-Functional Change" - Most patches are designed to
   change functionality (e.g. fix an error message or add a new button).
   However, some changes are non-functional -- e.g. they cleanup the
   code-style, improve the comments, or improve the test-suite.

#### Pull-Request Scope {:#pr-scope}

A good pull request addresses a clearly-defined problem. There should be a detailed description logged in the [issue tracker](http://issues.civicrm.org/). Excellent PRs also increase test coverage. If you are tempted to do additional tweaks or code cleanup outside the scope of that issue, you could make a separate commit and include them in the PR if they are minor & non-controversial, or create a seperate PR if they are more complex.

There is no size limit for PRs as long as they are focused on completely solving a discreet problem. As a practical matter, though, bigger PRs may take longer to review and merge. When possible, split "epic" issues into bite-sized chunks as long as each seperate PR is functionally complete and does not cause merge conflicts with your other PRs. In the latter case, add commits to an existing PR.

### Rebasing {:#rebasing}

__TODO__

