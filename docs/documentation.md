# Writing Documentation

[CiviCRM.org/documentation](https://civicrm.org/documentation) has a nice
high-level list of all active documentation.

## Wiki migration

As of early 2017, developer documentation on the [wiki] is under
active migration to mkdocs. If you are helping to migrate wiki pages, please
read about the [migration process][migration] to ensure that you understand the
workflow including the process of redirecting wiki pages to mkdocs.

[migration]: https://wiki.civicrm.org/confluence/display/CRMDOC/Content+migration+from+wiki+to+Developer+Guide
[wiki]: https://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+Documentation

## Guides in mkdocs

We are using [mkdocs](http://www.mkdocs.org) to produce guides, and currently
have the following two:

-   [User Guide](https://docs.civicrm.org/user/en/stable/)
-   [Developer Guide](https://docs.civicrm.org/dev/en/master/) *(which you are
    reading now!)*

The content for each of these guides is written in [markdown], stored in text
files, and hosted on GitHub.

### How to edit

For minor changes you can simply edit the [markdown] online using GitHub.
However, for a better editing experience we highly recommend installing
`mkdocs` as follows.

1.  Obtain the source files for the guide you want to edit
    1.  Find the repository on GitHub (see bottom left of screen)
    1.  Fork and clone locally.
1.  Install mkdocs on your machine.
    1.  For Ubuntu

            sudo apt-get install python-pip python-wheel
            sudo pip install mkdocs

    1.  For other platforms, follow instructions on
        [mkdocs.org](http://www.mkdocs.org)

1. Launch a local copy of the guide
    1. Run:

            cd civicrm-dev-docs
            mkdocs serve

        -   If you get `[Errno 98] Address already in use` then try using a
            different port with `mkdocs serve -a localhost:8001`

    1. Go to `http://localhost:8000` to view

1.  Edit the [markdown] with an editor of your choice. As you
    save your changes `mkdocs` will automatically reprocess the page and
    refresh your browser.

1.  When you are happy with your edits, use git to commit and push your changes.
    Then submit a  pull request on GitHub.

### Content standards for guides

These content standards apply to all guides written in MkDocs.

-   Keep it simple
    -   Be concise.
    -   When possible, avoid the use of: tables, deeply nested lists, and
        images.
-   Page titles
    -   *In navigation menu:* (as stored in `mkdocs.yml`) should be short enough
        to fit nicely in the menu, but should also long enough to stand on its
        own to a reasonable extent. The titles set here are used in the
        navigation menu *and* the page title that displays in the browser tab.
        The guide will be more usable if the reader sees two tabs titled
        "Using Hooks" and "API Usage" instead of "Usage" and "Usage".
    -   *In page content:* Insert one heading 1 at the top of each page. In the
        rest of the page, use headings of level 2 and greater.
-   Navigation structure
    -   Each page (except "Home") should be contained within one and only one
        folder. The folder serves as a "part" and the page serves as a "chapter"
        which is in keeping with the structure common to most text books and
        manuals. Do not make deeper levels of page hierarchy.
-   Spelling -- use US English spelling (e.g. "Standardize" instead of
    "Standardise")


[Markdown]: markdownrules.md
