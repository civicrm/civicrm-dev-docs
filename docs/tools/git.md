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
    * Here are some of the most important repositories hosted on GitHub
        * [civicrm-core](https://github.com/civicrm/civicrm-core/) - Core application which can be embedded in different systems (Drupal, Joomla, etc).
        * [civicrm-drupal](https://github.com/civicrm/civicrm-drupal/) - Drupal integration modules, with branches for each CiviCRM release & Drupal major version (e.g. 7.x-4.6, 7.x-4.7, 6.x-4.4, 6.x-4.6).
        * [civicrm-joomla](https://github.com/civicrm/civicrm-joomla/) - Joomla integration modules.
        * [civicrm-wordpress](https://github.com/civicrm/civicrm-wordpress/) - WordPress integration modules.
        * [civicrm-backdrop](https://github.com/civicrm/civicrm-backdrop/) - Backdrop integration module.
        * [civicrm-packages](https://github.com/civicrm/civicrm-packages/) - External dependencies required by CiviCRM.
        * [civicrm-l10n](https://github.com/civicrm/civicrm-l10n/) - Localization data.
        * *...and [many others](https://github.com/civicrm/) too!*

* GitLab - **[lab.civicrm.org/explore/projects](http://lab.civicrm.org/explore/projects)**
    * CiviCRM also has some repositories hosted on this self-hosted installation of GitLab


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
1. Make your changes. (Take care to follow the guidelines in [contributing to core](/core/contributing.md).)
1. **Commit** your changes. *[Learn more...](#commiting)*
1. **Push** your changes *to your fork*.
1. **Open a pull request**. *[Learn more...](#pr)*
1. Wait for someone else to [review your pull request](/core/pr-review.md).
1. If you need to make more changes later, commit them on the same branch and push your new commits to your fork. The new commits the will automatically appear in the pull request.
1. If other people commit changes to the upstream repository which create *merge conflicts* in your pull request, then **rebase** your branch. *[Learn more...](#rebasing)*
1. Once your changes are merged, delete your local branch

See also: [reviewing someone else's pull request](/core/pr-review.md)
 

## Pull requests {:#pr}

!!! note "terminology"
    The terms "pull request", "merge request", "PR", and "MR" all effectively synonymous. GitHub uses "pull request", and GitLab uses "merge request".

### Creating a pull request {:#pr-submit}

1.  In the web browser, navigate to the web page for your fork (e.g. `https://github.com/myuser/civicrm-core` )
1.  Click **Pull Request**
1.  There will be two branches specified – the (first) left should be "civicrm" (i.e. where the code is going to). The second (right) should be your branch. 
1.  Add a [good subject](#pr-subject) and explanation, and submit.

### Writing a pull request subject {:#pr-subject}

Pull request titles don't need to be identical to issue titles, and in particular, you may want to focus more positively on the changes in code than on the broader feature changes. Here are some guidelines for writing a good subject line:

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

### Pull request scope {:#pr-scope}

A good pull request addresses a clearly-defined problem. There should be a detailed description logged in the [issue tracker](http://issues.civicrm.org/). Excellent PRs also increase test coverage. If you are tempted to do additional tweaks or code cleanup outside the scope of that issue, you could make a separate commit and include them in the PR if they are minor & non-controversial, or create a seperate PR if they are more complex.

There is no size limit for PRs as long as they are focused on completely solving a discreet problem. As a practical matter, though, bigger PRs may take longer to review and merge. When possible, split "epic" issues into bite-sized chunks as long as each seperate PR is functionally complete and does not cause merge conflicts with your other PRs. In the latter case, add commits to an existing PR.

### Reviewing a pull request

See [How to review a core pull request](/core/pr-review.md)

### Who merges pull requests {:#pr-merge}

A person may be granted the privilege/responsibility of reviewing and merging pull requests who:

* Is an active contributor to the CiviCRM project.
* Responds to communications in a timely fashion.
* Is familiar with current CiviCRM coding standards and best practices.
* Is a careful proofreader and tester, and who gives thorough constructive feedback.


## Git tasks

### Cloning {:#cloning}

When you want to set up a local copy of a git repo hosted on GitHub or GitLab, you *clone* it. Here are two ways: 

* Using the SSH protocol
    
    ```bash
    $ git clone git@github.com:civicrm/civicrm-core.git
    ```

* Using the HTTP protocol

    ```bash
    $ git clone https://github.com/civicrm/civicrm-core.git
    ```

Using SSH is a little bit better because you won't need to enter your password all the time, but it does require some [extra steps](https://help.github.com/articles/generating-a-new-ssh-key-and-adding-it-to-the-ssh-agent/).

### Managing multiple git remotes {:#remotes}

Your local git repo is typically set up to track at least one *remote* git repo for operations like `fetch`, `pull`, and `push`. But it can be helpful to set up multiple remotes when contributing to repos which you don't own.

Common terminology: 

* **Upstream repository** - a repo hosted on GitHub or GitLab which *you don't own* but would like to contribute to
* **Fork repository** - a repo hosted on GitHub or GitLab which *you own* and have created by "forking" an upstream repo
* **Local repository** - the repo that lives on your local computer after [cloning](#cloning)

Show the remotes which your local repo is tracking:

```bash
$ git remote -v                                                                          
upstream  https://github.com/civicrm/civicrm-core.git (fetch)
upstream  https://github.com/civicrm/civicrm-core.git (push)
myusername      git@github.com:myusername/civicrm-core.git (fetch)
myusername      git@github.com:myusername/civicrm-core.git (push)
```

The first column shown in the output is the *name* of the remote. You can rename your remotes however you want. Assuming your GitHub user name is `myusername`, the above output looks pretty good because we have two remotes: one named `upstream` (an *upstream repo*), and another named `myusername` (a *fork repo*). When you first [clone](#cloning) a repository, git will set up a remote called `origin` which refers to the repo you initially cloned. In the above example we don't see `origin`, so that remote has been removed or renamed.

Read about [how to use `git remote`](https://git-scm.com/docs/git-remote) to properly set up your remotes.

!!! tip
    If you use [hub](https://hub.github.com/), the command `hub clone` can help with this



### Branching {:#branching}

Git uses branches to separate independent sets of changes. When creating a new branch, here are some things to keep in mind: 
 
* [Choose an appropriate base branch](#base-branch)
* You'll need to keep your local branch until its changes are merged. Sometimes this can take several months. After it's merged, you can delete it.
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

When creating a new branch, you should explicitly declare a starting point.

Most of the time, your base branch should be `master`. However, CiviCRM core keeps two main branches under active development: `master` (for the latest version), *and* another branch for the current LTS release (as listed on [civicrm.org/download](https://civicrm.org/download)). For example, if you have a client running the LTS version (e.g. `4.6`) then any changes you make to `master` will not affect this client until they do a major upgrade. In this case you may wish to "backport" a change to the LTS version and choose the `4.6` branch as your base branch.

### Committing {:#committing}

As much as possible, separate your changes into distinct commits that each make sense on their own. Using smaller commits will make your changes easier for others to review.

#### Writing a commit message {:#commit-messages}

When making commits, remember that this isn't just a small personal project: your audience is hundreds of other developers &mdash; now and ten years from now &mdash; as well as end users trying to understand features and bugs. By leaving a commit history that makes sense &mdash; both in content and in commit messages &mdash; you will make the code more legible for everyone.

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

### Rebasing {:#rebasing}

Sometimes when you [make a pull request](#pr) someone else merges a change into the upstream repository that *conflicts* with your change. The best way to resolve this conflict is to rebase. It's a good idea to [read about rebasing](https://git-scm.com/docs/git-rebase) so you understand the theory. Here's the practice:

!!! note
    In this example we have two [remotes](#remotes) set up:

    * `upstream` which tracks the upstream repo
    * `myusername` which tracks the fork repo
    
    Also we are working on changes in a branch called `my-branch`

1. Update your local `master` branch

    ```bash
    $ git checkout master
    $ git pull upstream master
    ```

1. Checkout the branch that has conflicts you'd like to resolve

    ```bash
    $ git checkout my-branch
    $ git rebase master
    ```
    
1. See which files need attention

    ```bash
    $ git status
    ```

1. Make changes to the files which resolve the merge conflicts

1. "Add" the files to tell git you have resolved the conflicts

    ```bash
    $ git add CRM/Utils/String.php
    ```

1. Continue the rebase

    ```bash
    $ git rebase --continue
    ```

1. Force-push your changes back up to your fork

    ```bash
    $ git push -f myusername my-branch
    ```

1. Now, if you go to back to the page for your pull request it should no longer show merge conflicts
