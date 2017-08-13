# Using GitHub for CiviCRM development


-   [Git is a "source-code management system" or "version control
    system"](http://learn.github.com/p/intro.html) – an
    alternative to Subversion. It was designed for a large open-source
    project (Linux) and has seen broad adoption among other
    FOSS projects.
-   GitHub is a company which provides hosted, web-based tools for
    enhancing Git development. For open projects like CiviCRM, its
    services are free.
-   Git and GitHub have several advantages:
    -   The tools are already popular among FOSS projects and
        web developers.
    -   The tools are free ("as in beer") and mostly free ("as
        in speech").
    -   They support off-line development.
    -   They support lightweight branching, merging, and code-review.
    -   They support open teams – anyone can jump-in, make changes, and
        share changes.
-   To develop patches or enhancements to CiviCRM, you should base
    any changes on the latest source-code from Github.
-   For more introductions to Git and Github, see
    <https://help.github.com/>.

## Repositories

CiviCRM is divided into a few repositories. This allows developers to
work with different components, allows different teams to manage each
component, and will allow all the pieces to be remixed using different
tools (shell scripts, Drush-make, or composer). The repositories are:



## How-To: Checkout the latest source code from Github

Because CiviCRM is split across multiple repositories, the easiest way
to get the latest code is to run a tool which handles all of the
repositories. Two alternative tools are included with
[civicrm-buildkit](https://github.com/civicrm/civicrm-buildkit):

-   *gitify*: Gitify takes an existing CiviCRM installation and replaces
    various folders with the appropriate git repositories. This is
    generally the easiest way to get the code for the first time because
    it works with your current httpd/mysql configuration.
-   *civibuild*: Civibuild downloads, installs, and configures a full
    installation with a CMS (Drupal/WordPress) and Civi. For the first
    build, this is more difficult because it requires deeper integration
    with your httpd/mysql servers. However, it provides a reproducible
    configuration (which matches the test infrastructure) and makes it
    easier to maintain multiple installations (v4.4 and v4.5 and v4.6;
    WP and Drupal; etc) (For more, see http://buildkit.civicrm.org/ or
    https://github.com/civicrm/civicrm-buildkit/blob/master/doc/civibuild.md .)

For now, we'll assume this is your first project and proceed with
*gitify*. You will need to:

-   Note the existing CiviCRM directory
    (eg "/var/www/drupal/sites/all/modules/civicrm").
-   Download builkdit.
-   Run the "gitify" command.

You may need to adapt the command arguments, but a typical case would
be:


    $ cd /var/www/drupal/sites/all/modules/civicrm
    $ git clone https://github.com/civicrm/civicrm-buildkit.git ~/buildkit
    $ cd ~/buildkit/bin
    $ export PATH=$PWD:$PATH
    $ cd /var/www/drupal/sites/all/modules/civicrm
    $ gitify Drupal --hooks


Depending on the type of development you do, you may need to checkout
more or less code. For example, if you work with CiviCRM in multiple
environments (Drupal 7, Joomla, WordPress) and multiple languages
(English, French, Chinese, etc), then you can instruct gitify to fetch a
lot of source-code; if you only use one environment and one language,
then you might instruct it to fetch less source-code. For more
instructions, run "gitify" (with no extra parameters).



"git clone" vs "gitify"

If you're already familiar with git, then you've used "git clone" before. You're free to use "git clone" instead of "gitify". "gitify" is basically a wrapper that:

* Calls "git clone" for each of the CiviCRM repositories
* Optionally registers git-hooks in each of the CiviCRM repositories
* Calls "GenCode.php" to generate PHP stubs and data files

Skim the bottom of "gitify" to see the details.

Note: the dependencies repository name on Github does not have the same directory name as CiviCRM structure e.g. civicrm-packages -> packages when you clone a repository from the github the would be:

git clone git://github.com/civicrm/civicrm-packages.git packages



## How-To: Create your first change ("pull-request")

When you create your first change for CiviCRM, there will be several
steps of setup and configuration. As part of the process, you will
create:

-   A **"fork"** which is a public space where you can store copies of
    the code – a space where you can freely edit and publish at your own
    discretion
-   A **"branch"** which lists your specific code changes
-   A **"pull request (PR)"** which requests that your changes be merged
    into the official CiviCRM code. (A coordinator from civicrm.org will
    review the PR before it becomes official.)

The process:

1.  Checkout the latest source code from Github (see above)
2.  [Signup for a user account on
    Github](https://github.com/signup/free)
3.  [Set up
    git](https://help.github.com/articles/set-up-git)
4.  Create your own "fork" of the CiviCRM code.
    1.  In the web browser, navigate to
        <https://github.com/civicrm/civicrm-core/>
    2.  In the top-right corner, click the fork icon and complete
        the form.
    3.  You will be taken to a screen that lists files in your "fork".
    4.  Note the top of the screen includes an "HTTP" address like
        "*https://github.com/myuser/civicrm-core.git*"
        or an "SSH" address like:
        "*git@github.com:myuser/civicrm-core.git*" You will need this in
        a moment.

5.  Link the local code to your "fork"
    1.  

            ## Navigate to the directory; adjust path to match your local system
            cd /var/www/drupal/sites/all/modules/civicrm

            ## Link code your fork; adjust the address to match your fork
            git remote set-url origin git@github.com:myuser/civicrm-core.git
            git pull

            ## Link code to the official "upstream" source
            git remote add upstream git://github.com/civicrm/civicrm-core.git
            git fetch upstream


6.  Create a branch with your changes
    1.  

            ## Navigate to the directory; adjust the path to match your local system
            cd /var/www/drupal/sites/all/modules/civicrm

            ## Make a branch.
            ##
            ## When you make a branch, you should explicitly declare a starting point.
            ## The parameter "upstream/master" indicates that you'll base your changes
            ## on the latest, bleeding-edge code from CiviCRM. If you wanted to base
            ## your changes on a specific release, then you might say "upstream/4.3.1".
            ##
            ## The "-b cool-footer" indicates that your code goes into a new branch.
            ## The name ("cool-footer") should reflect what you're working on.
            git checkout upstream/master -b cool-footer

            ## Modify some code
            vi templates/CRM/common/footer.tpl

            ## Save the changes
            git commit templates/CRM/common/footer.tpl -m 'CRM-12603 - subtracted 4 degrees C to make footer cooler'
            git push origin cool-footer

        

    2.  In order to make it possible for people to understand your
        commit in the future, please follow the recommendations on [Git
        Commit Messages for
        CiviCRM](/confluence/display/CRMDOC/Git+Commit+Messages+for+CiviCRM).

7.  Create a pull request
    1.  In the web browser, navigate to the web page for your fork (e.g.
        <https://github.com/myuser/civicrm-core> )
    2.  Click "Pull Request"
    3.  There will be two branches specified – the (first) left should
        be "civicrm" (ie where the code is going to). The second (right)
        should be your branch.

    4.  Add an explanation and submit
    5.  (See also:
        <https://help.github.com/articles/creating-a-pull-request> )

8.  Create an issue on JIRA referencing your pull request. Then comment
    on the pull request with the link to the created issue.

## How-To: Submit a Patch to a Version Branch

So now you are doing well at making changes to civicrm-core, and you've
created a bug fix that should be applied to the current stable branch
rather than the next major release. In May, 2013 this means you want it
to come out in the next 4.3.x release, rather than the 4.4.0 release
that is months in the future. Here is how you go about creating the
patch and the related pull request.

1.  Create an issue on JIRA
    (<http://issues.civicrm.org/jira/secure/CreateIssue!default.jspa>)
    with an Issue Type of Patch, and note its number.
2.  Go to <https://github.com/civicrm/civicrm-core>, select the version
    branch you want to create a patch against such as 4.3 or 4.4, then
    click Fork to create a fork of that branch in your github
    account:![](/confluence/download/attachments/86213305/2013-05-14_18-05-34.png?version=1&modificationDate=1372586127000&api=v2)


1.  On your local machine,



    ## go to the directory setup previously using steps 1 - 5 of How-To: Create your first change ("pull-request")
    $ cd /var/www/drupal/sites/all/modules/civicrm

    ## this time checkout the 4.3 branch, and name your new branch for the issue you created in 1. above
    $ git checkout upstream/4.3 -b CRM-12603

    ## make your changes to one or more files
    $ vi some_civi_core_file.php

    ## make sure everything looks good
    $ git diff
    ## add and commit the change to your local branch
    $ git add some_civi_core_file.php
    $ git commit -m 'CRM-12603 - grant pre-hook invoked with wrong arg'

    ## push your local issue branch to your github repository
    $ git push origin CRM-12603



    1.  Note that the commit message should follow standards outlined
        in [Git Commit Messages for CiviCRM](/confluence/display/CRMDOC/Git+Commit+Messages+for+CiviCRM).


1.  In your browser, go to your fork of civicrm-core
    at <https://github.com/myuser/civicrm-core>, click Pull Request, on
    the left change the base branch from master to the version branch,
    on the right change to your local issue fix branch, then underneath
    make sure the Commits and Files Changed is what you expect, and
    finally click Send pull
    request.![](/confluence/download/attachments/86213305/2013-05-14_17-12-10.png?version=1&modificationDate=1372586127000&api=v2)

2.  Note the pull request number. Go back to JIRA and add a comment
    including the pull request number.

## How-To: Manage files

*git add* is used
to add files to a repository.  For example if you want to add an
additional .js file to the code. 

*git commit * creates a point in time, with a message about it, that can
be pushed to a repository.

*git rm* is used to remove files from a repository. For example if two
files are combined together you would remove one from the repository.

*git status* shows the current status of a remote repository (ie the
files you have changed or added).  It will also show you what will be
included in your next commit.

Here is a list of [Major Git Commands](http://www.siteground.com/tutorials/git/commands.htm) and
a list of  [Useful Git Commands](http://davidwalsh.name/git-commands).


## How-To: Manage day-to-day branching and merging

The previous "How To" covers one's *first* change -- but as you make
more changes, then you'll need a few more techniques.  For example,
suppose you work on two new features -- one feature provides a fancy
event registration form, and the second feature adds clairvoyance to the
automated dedupe system.  You begin work on the first feature:



$ git fetch upstream
$ git checkout upstream/master -b fancy-registration
$ vi templates/CRM/Event/Form/FancyRegistration.tpl
$ git commit templates/CRM/Event/Form/FancyRegistration.tpl
$ vi templates/CRM/Event/Form/FancyRegistration.tpl
$ vi CRM/Event/Form/FancyRegistration.php
$ vi templates/CRM/Event/Form/FancyRegistration.tpl


The fancy registration isn't done yet, but you get a memo from the boss:
the clairvoyant dedupe is now top priority!  You need to get started on
it ASAP!  First, tie up any loose ends -- make sure your work has been
saved.




    me@localhost:~/civicrm$ git status
    # On branch fancy-registration
    # Changes not staged for commit:
    #   (use "git add <file>..." to update what will be committed)
    #   (use "git checkout -- <file>..." to discard changes in working directory)
    #
    #       modified:   CRM/Event/Form/FancyRegistration.php
    #       modified:   templatesCRM/Event/Form/FancyRegistration.tpl
    #
    me@localhost:~/civicrm$ git commit templates CRM



And then begin work on the new feature. Create a new branch using the
same "git checkout ... -b ..." command.



$ git fetch upstream
$ git checkout upstream/master -b clairvoyant-dedupe
$ vi CRM/Dedupe/BAO/QueryBuilder/Clairvoyant.php
$ git add CRM/Dedupe/BAO/QueryBuilder/Clairvoyant.php
$ git commit .



If you're clairvoyant yourself, then the clairvoyant deduper will be
easy to write.  After a few minutes, you can resume work on the fancy
registration form.


$ git checkout fancy-registration
$ vi templates/CRM/Event/Form/FancyRegistration.tpl
$ git commit templates/CRM/Event/Form/FancyRegistration.tpl


Of course, some of us aren't good at writing clairvoyant code. It may
take a couple days.  If you switch back to feature #1 after a notable
delay, then you might encounter a problem: the official "upstream" code
has moved on!  To be safe, you should pull in the latest changes from
upstream and reset your MySQL database.


$ git checkout fancy-registration
$ git pull --rebase upstream master
$ cd bin
$ ./setup.sh
$ cd ..
$ vi templates/CRM/Event/Form/FancyRegistration.tpl
$ git commit templates/CRM/Event/Form/FancyRegistration.tpl



Note: There's a connection with the commands from the earlier step,
"Begin work on new feature #1".


Begin work on new feature #1

$ git fetch upstream
$ git checkout upstream/master -b fancy-registration

Switch back to feature #1
    
$ git checkout fancy-registration
$ git pull --rebase upstream master



In the first case, we create the new branch "fancy-registration"; in the
second case, we update the existing branch "fancy-registration". In both
cases, "fancy-registration" builds on top of the latest
"upstream/master".


Push Changes

In all the steps above, changes are saved on your local computer but aren't published for other developers to see. When you're ready to share the changes for a particular branch, you should "push" them. Simply run "git push origin <branch-name>". This will send a copy of all your branch to Github. Feel free to push as frequently or rarely as you want.

There is only one hard constraint: publish your changes (with "git push") before requesting review (with a "pull-request").



## Tip: Getting out of trouble

To understand what branches you have locally:

$ git branch


To prune branches on your local system that are tracking remote branches
that no longer exist:

$ git -p



To delete a branch on your local system:

$ git branch -d my_local_branch


If you changed a file and you have not git add'ed or committed it, but
want to discard your changes:


$ git checkout path/to/file



If you altered a file or files and you have git add'ed them or you just
want to blow away all un-committed changes in your repository:

$ git reset --hard HEAD


If you committed to your local repository, but you have not pushed, and
you want to change your previous commit (or commit message):


fix the file you want fixed

$ git add path/to/file/you/fixed
$ git commit --amend


If you git pull and git tells you have a conflict with your local
changes:

Edit conflicted files, resolving the conflict

$ git add path/to/conflicted/file/or/files
$ git commit




## CiviCRM-Git Workflow for Git Experts

Recommended practices:

-   Create a new branch for each feature
-   Base new feature-branches on "upstream/master" or "upstream/4.3.x"
    (as appropriate)
-   Publish changes to your personal fork on Github
-   Send a pull-request through Github

See [Pull-Request Process](/confluence/display/CRM/Pull-Request+Process)
for more details on how your PR will be reviewed (or how to review PRs
by others).

Note: CiviCRM previously used Subversion. The migration was
"history-less" (i.e. the source of 4.3.beta1 was imported to git without
any history).

## Links

These links look interesting, but no one has thought-through/presented
on how helpful/unhelpful they would be in our community:

-   <http://joeyh.name/blog/entry/introducing_mr/> (Comment: I like that
    it shows statuses across repos; don't like the per-user config file)
-   <http://defunkt.io/hub/> (Comment: I like that this automatically
    manages the "git remote"; not a fan of "alias git=hub")
-   <https://github.com/jsmits/github-cli>
-   <https://github.com/peter-murach/github_cli>
-   <http://code.google.com/p/git-repo/>
