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

[Markdown]: markdownrules.md
