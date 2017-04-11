# Writing Documentation

To *read* documentation, go to [docs.civicrm.org](https://docs.civicrm.org) for the most high-level list of all active documentation.

This page describes the documentation systems within CiviCRM and how to contribute.

!!! note "Note: the wiki is not covered here"
    The [wiki] has historically been CiviCRM's documentation system but is currently being phased out. As of early 2017, documentation is still somewhat split between the wiki the the guide books described below, but we are working to eventually consolidate *all* documentation into guide books. A [migration process][migration] is currently underway for this Developer Guide, and a process will [likely](https://github.com/civicrm/civicrm-docs/issues/17) follow for a dedicated Administrator Guide, as well as [extension guides](https://github.com/civicrm/civicrm-docs/issues/14).

    The rest of **this page describes guide books only** and does *not* cover documentation processes that involve the wiki.

[migration]: https://wiki.civicrm.org/confluence/display/CRMDOC/Content+migration+from+wiki+to+Developer+Guide
[wiki]: https://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+Documentation

## Guide books in MkDocs

We are using [MkDocs](http://www.mkdocs.org) to produce books. The content for each of these books is written in [markdown](/markdownrules.md), stored in text files, and hosted in a repository on GitHub. Then, the books are automatically published to [docs.civicrm.org](https://docs.civicrm.org) using our custom [publishing system](https://github.com/civicrm/civicrm-docs).

### Languages

A book can have multiple languages, and we use separate repositories for different languages.

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
- [Markdown code standards](/markdownrules.md#standards) - recommendations for markdown syntax to use
- [Style guide](/best-practices/documentation-style-guide.md) - to maintain consistent language and formatting
- [Documentation chat room](https://chat.civicrm.org/civicrm/channels/documentation) - live discussion, fast (most of the time) answers to your questions
- [Documentation mailing list](https://lists.civicrm.org/lists/info/civicrm-docs) - low traffic, mostly used for informational updates regarding documentation projects


### Submitting issues

The simplest way to help out is to *describe* a change that you think *should* be made by writing a new issue in the issue queue for the book you are reading. Then someone will see your issue and act on it, hopefully fast. Each book has its own issue queue. First find the GitHub repository for the book (listed in the above table), then when viewing on GitHub, click on "Issues". You will need an account on GitHub to submit a new issue, but creating one is quick and free.

### Editing through GitHub

Suggest specific changes by making the changes within the text editor on GitHub. (You will first need an account on GitHub.)

1. Find the page in the book you wish to edit.
1. Click on the pencil icon at the top right.
1. Make changes within the editor on GitHub.
1. Click "Propose file change" at the bottom.
1. **Important**: Click "Create pull request" and confirm. (You're not done until you create a pull request.)

After you follow the steps above, someone else will review your changes and hopefully accept them, at which point you'll be notified via email.

### Editing locally with MkDocs

The most advanced way to work on a book is to use git to download all the markdown files to your computer, edit them locally, preview the changes with [MkDocs](http://mkdocs.org/), then use git to push those changes to your personal fork, and finally make a "pull request" on the main repository. This approach makes editing very fast and easy, but does require a bit of setup, and some knowledge of how git works.

1. Install [pip](https://pypi.python.org/pypi/pip) (python package manager)

    - OS X: `brew install python`
    - Debian/Ubuntu: `sudo apt-get install python-pip python-wheel`

1.  Install MkDocs, plus the [Material theme](http://squidfunk.github.io/mkdocs-material/) and the [Pygments syntax highlighter](http://pygments.org/).

    ```bash
    sudo pip install mkdocs mkdocs-material pygments pymdown-extensions
    ```

1.  Obtain the source files for the book you want to edit
    1.  Find the repository on GitHub &mdash; *Most books will have a link to this repo at the top right of every page (with a GitHub icon)*
    1.  Fork the repository on GitHub.
    1.  Clone *your fork* of the repository to your computer

        ```bash
        git clone https://github.com/YourGitHubUserName/civicrm-dev-docs.git
        cd civicrm-dev-docs
        ```

1. Launch a local copy of the book
    1. Run:

        ```bash
        mkdocs serve
        ```

        -   If you get `[Errno 98] Address already in use` then try using a
            different port with `mkdocs serve -a localhost:8001`

    1. View through your browser at `http://localhost:8000`.

1.  Edit the [markdown](/markdownrules.md) with an editor of your choice. As you
    save your changes `mkdocs` will automatically reprocess the page and
    refresh your browser.

1.  When you are happy with your edits, use git to commit and push your changes up to your fork.    Then submit a  pull request on GitHub.


## Content attribution guidelines {:#attribution}

All CiviCRM documentation content is licensed [CC BY-SA 3.0](https://creativecommons.org/licenses/by-sa/3.0/). This means that if you want to copy content out of our docs and use it elsewhere, you're welcome to do so as long as your give attribution to the author. 

### How to obtain author information for content within our books {:#attributing-exports}

This is relevant when you want to copy content *out of* our documentation books.

1. Find the GitHub repository for the book that contains the content you'd like to use. (There will usually be a link to this repository at the top right of ever page.)
2. Navigate to the corresponding markdown file within GitHub (it will match the URL path of the published content).
3. Click on "Blame" to see detailed information about content authors, line, by line.

### How to displaying attributing for content migrated *into* our books {:#attributing-imports}

The [CiviCRM wiki](https://wiki.civicrm.org/confluence/dashboard.action) and [Stack Exchange](http://civicrm.stackexchange.com/) also use the CC BY-SA 3.0 license, which is convenient because content is regularly migrated into our MkDocs books from these sources. But to comply with the license, we must attribute the original content authors

When migrating content into our docs books which requires attribution, display this attribution at the bottom of the page as follows:

```
## Credits

Some content from this page was migrated from other sources
and contributed by the following authors: 

* Mickey Mouse
* Lisa Simpson
* Big Bird
```

Commit messages should also reference the URL of the original content.

