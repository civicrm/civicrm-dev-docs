# Writing Documentation

To *read* documentation, go to [CiviCRM.org/documentation](https://civicrm.org/documentation) for the most high-level list of all active documentation.

This page describes the documentation systems within CiviCRM and how to contribute.

!!! note "Note: the wiki is not covered here"
    The [wiki] has historically been CiviCRM's documentation system but is currently being phased out. As of early 2017, documentation is still somewhat split between the wiki the the guide books described below, but we are working to eventually consolidate *all* documentation into guide books. A [migration process][migration] is currently underway for this Developer Guide, and a process will [likely](https://github.com/civicrm/civicrm-docs/issues/17) follow for a dedicated Administrator Guide, as well as [extension guides](https://github.com/civicrm/civicrm-docs/issues/14).

    The rest of **this page describes guide books only** and does *not* cover documentation processes that involve the wiki.

[migration]: https://wiki.civicrm.org/confluence/display/CRMDOC/Content+migration+from+wiki+to+Developer+Guide
[wiki]: https://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+Documentation

## Guide books in MkDocs

We are using [MkDocs](http://www.mkdocs.org) to produce guides, and have the following:

| Book | English | French
| ---- | ------- | ------ |
| User Guide | **[latest][u-en-l]**,<br>[stable][u-en-s],<br>[4.7][u-en-47], [4.6][u-en-46]<br><br>*[repository][u-r-en]* | **[latest][u-fr-l]**, [stable][u-fr-s]<br><br>*[repository][u-r-fr]* |
| Administrator Guide *([planned](https://github.com/civicrm/civicrm-docs/issues/17))* | | |
| Developer Guide | **[latest][d-l]**<br><br>*[repository][d-r]* | |
| Extension Guides *(planned)* | | |


[u-en-s]: https://docs.civicrm.org/user/en/stable/
[u-en-l]: https://docs.civicrm.org/dev/en/latest/
[u-en-47]: https://docs.civicrm.org/user/en/4.7/
[u-en-46]: https://docs.civicrm.org/user/en/4.6/
[u-fr-l]: https://docs.civicrm.org/user/fr/latest/
[u-fr-s]: https://docs.civicrm.org/user/fr/stable/
[d-l]: https://docs.civicrm.org/dev/en/latest/

[u-r-en]: https://github.com/civicrm/civicrm-docs
[u-r-fr]: https://github.com/civicrm-french/civicrm-user-guide
[d-r]: https://github.com/civicrm/civicrm-dev-docs

The content for each of these guides is written in [markdown](/markdownrules.md), stored in text files, and hosted in a repository on GitHub. Then, the books are automatically published to **docs.civicrm.org** using our custom [documentation infrastructure](https://github.com/civicrm/civicrm-docs).

### Languages

As shown above, a book can have multiple languages, and we use separate repositories for different languages.

### Versions

In an effort to maintain documentation anchored to specific versions of CiviCRM, some books store separate versions of the documentation in different *branches* within the repository.

<!-- TODO: clarify "latest" vs "stable" vs "master" -->

If you're improving current documentation, please edit the `master` branch, which will be periodically merged into other branches as needed.

In rarer cases, if you have an edit that pertains to a specific version, (e.g. documentation about a feature in an *older* version of CiviCRM, which does not require documentation in the latest version), then please edit the branch corresponding to that version.


## Contributing to documentation

We welcome contributions, small and large, to documentation!

### Resources:

Before diving into editing, you may find helpful information within the following resources:

- [Markdown syntax](/markdownrules.md) - necessary (but simple) syntax to format content
- Markdown coding standards - *[coming soon](https://github.com/civicrm/civicrm-dev-docs/issues/43)*
- [Style guide](/best-practices/documentation-style-guide.md) - to maintain consistent language and formatting
- [Documentation chat room](https://chat.civicrm.org/civicrm/channels/documentation) - live discussion, fast (most of the time) answers to your questions
- [Documentation mailing list](https://lists.civicrm.org/lists/info/civicrm-docs) - low traffic, mostly used for informational updates regarding documentation projects


### Submitting issues

The simplest way to help out is to *describe* a change that you think *should* be made by writing a new issue in the issue queue for the guide book you are reading. Then someone will see your issue and act on it, hopefully fast. Each guide book has its own issue queue. First find the GitHub repository for the book (listed in the above table), then when viewing on GitHub, click on "Issues". You will need an account on GitHub to submit a new issue, but creating one is quick and free.

### Editing through GitHub

A slightly more helpful way to plug in is to suggest specific changes by making the changes within the text editor on GitHub. To do this, you would go to the repository, find the markdown file for the page you'd like to change (look at the page's URL in your browser to help figure out where the file is within the repository), then click on the pencil icon at the top right of the page on GitHub. When you make a change, you are essentially forking (i.e. copying) the entire guide into your personal account on GitHub, changing that fork and then requesting to merge your fork into the main guide.

### Editing locally with MkDocs

The most advanced way to work on a guide book is to use git to download all the markdown files to your computer, edit them locally, preview the changes with [MkDocs](http://mkdocs.org/), then use git to push those changes to your personal fork, and finally make a "pull request" on the main repository. This approach makes editing very fast and easy, but does require a bit of setup, and some knowledge of how git works.

1. Install [pip](https://pypi.python.org/pypi/pip) (python package manager)

    - OS X: `brew install python`
    - Debian/Ubuntu: `sudo apt-get install python-pip python-wheel`

1.  Install MkDocs.

        sudo pip install mkdocs

1.  Obtain the source files for the guide you want to edit
    1.  Find the repository on GitHub *(see "repository" links above, or the "GitHub" link on the bottom left of screen of the documentation you are reading)*
    1.  Fork the repository on GitHub.
    1.  Clone *your fork* of the repository to your computer

            git clone https://github.com/YourGitHubUserName/civicrm-dev-docs.git
            cd civicrm-dev-docs

1. Launch a local copy of the guide
    1. Run:

            mkdocs serve

        -   If you get `[Errno 98] Address already in use` then try using a
            different port with `mkdocs serve -a localhost:8001`

    1. View through your browser at `http://localhost:8000`.

1.  Edit the [markdown](/markdownrules.md) with an editor of your choice. As you
    save your changes `mkdocs` will automatically reprocess the page and
    refresh your browser.

1.  When you are happy with your edits, use git to commit and push your changes up to your fork.    Then submit a  pull request on GitHub.


## Documenting your extension

TODO

