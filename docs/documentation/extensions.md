# How to document your extension

So you've written an [extension](/extensions/basics). Awesome! Now you want to add some documentation to help people use it. Great! Maybe you even want some docs for other *developers* working on it too. Very good. This page will help you set up your own documentation *book* (just like this Developer Guide) for your extension.

<!-- (commented out until we can get the CiviVolunteer book up)
!!! summary "Example"
    For a fully-featured working example of extensions documentation, look at CiviVolunteer.

    * Read the published [CiviVolunteer book](https://docs.civicrm.org/volunteer/en/latest/)
    * Inspect the following source code to see how it's made:
        * [`/docs/`](https://github.com/civicrm/org.civicrm.volunteer/tree/master/docs) within the project repo (to store all the content in markdown files)
        * [`/mkdocs.yml`](https://github.com/civicrm/org.civicrm.volunteer/blob/master/mkdocs.yml) within the project repo (to specify book structure)
        * [`/books/volunteer.yml`](https://github.com/civicrm/civicrm-docs/blob/master/books/volunteer.yml) within the `civicrm-docs` repo (to specify how the book is to be published)
-->

## Overview

Basically how this works is:

1. You use the git repo for your extension to store its documentation.
* You store the content in [markdown](/markdownrules) files within a `docs` directory in your project.
* You use git branches just like you normally would, with that `docs` directory sitting there in every branch.
* You put one file at the root level of your project, `mkdocs.yml` to configure some of the high-level details of your book.
* You use MkDocs locally to preview your book.
* When you're ready, you make a pull request on our [publishing system](https://github.com/civicrm/civicrm-docs) to add the necessary configuration for your book, so that it gets published to [docs.civicrm.org](https://docs.civicrm.org).
* You configure GitHub to tell our publishing system when to publish updates to your book.

Follow along in the steps below to get a book up and running for your extension.

## Set up `mkdocs.yml` {:#config}

Create a new file called `mkdocs.yml` in the root level of your project with the following content:

```yaml
site_name: Your Extension Name
repo_url: https://github.com/yourusername/org.civicrm.yourproject
theme: material

pages:
- Home: index.md

markdown_extensions:
  - attr_list
  - admonition
  - def_list
  - codehilite
  - toc(permalink=true)
  - pymdownx.superfences
  - pymdownx.inlinehilite
  - pymdownx.tilde
  - pymdownx.betterem
  - pymdownx.mark
```

Replace values on the first two lines with your own. Leave everything else as-is (for now).


## Decide what to do with your README file {:#readme}

The README file at the root of your repository is great for keeping some simple documentation that people will see when they visit your repo. But non-technical users can become confused when seeking docs landing on GitHub.

When creating a docs book, you have two options for how to deal with your README file:

### Mirror README to a single-page book {#:readme-mirrored}

With this option, you basically keep documentation content in the README file, and create an MkDocs book directly from that file. The content remains on GitHub when people visit your repo, and it also gets published on docs.civicrm.org &mdash; and you only have to edit it in one place. The downside is that you have to fit everything into one page.

1. Make sure you're using [markdown](/markdownrules) formatting within the README file, and make sure the file is named `README.md`.
1. Create an MkDocs index file which is a symbolic link to your README file:

    ```
    cd <project-root>
    mkdir docs
    cd docs
    ln -s -T ../README.md index.md
    ```

### Use a multi-page book and a separate README file {:#readme-separate}

With this option you have a very small README file and a separate MkDocs book which can have multiple pages.

1. Add a `docs` directory at the root of your project.
1. Within that, add a new file `index.md` to use for your content.
1. Keep your README file, but don't store documentation in it. Just have it explain the bare bones of your project and *link* to your documentation (once it's published on docs.civicrm.org.)


## Add content

Add some [markdown](/markdownrules) content in `docs/index.md`.

Now you should be able to run `mkdocs serve` from within your project directory to start previewing your content. See [instructions here](/documentation/#editing-locally-with-mkdocs) to get MkDocs set up.

You can add more pages by creating more markdown files and specifying these files under `pages` in `mkdocs.yml`.

!!! note
    We have [markdown code standards](/markdownrules/#standards) and a documentation [style guide](/best-practices/documentation-style-guide/). Adherence to these rules within your extensions docs is recommended but not required.


## Submit your book to our publishing system {:#submit}

Once your book is in good shape it's time to get it up on [docs.civicrm.org](https://docs.civicrm.org).

1. Go to the [books configuration within our publishing system](https://github.com/civicrm/civicrm-docs/tree/master/books)
1. Click **Create new file** to begin adding a config file for *your* book.
1. For the file name, use something like `foobar.yml`, where "foobar" is your extension's **short name**. This is the name that will be used in the URL for your docs.
1. Copy paste the following content into the file editor:

    ```
    name: Foo Bar
	description: Provides a baz for every contact's bat
    languages:
      en:
        repo: 'https://github.com/username/org.civicrm.foobar'
    ```

    * There are lots of other settings you can put here if you want to have multiple languages or versions. Look at `user.yml` as an example of a book (the User Guide) which takes advantage of all the possible settings.
    * But if you want to keep it simple and just have one English edition which points to the `master` branch of your repo, then stick with the above settings.

1. Adjust `name` and `repo` to your own values

    * The `name` you set here will be shown in the list of all books on [docs.civicrm.org](https://docs.civicrm.org) as well as at the top of every page of your book. Use whatever **long name** you've chosen for your extension, such as "Foo Bar", or "CiviFoobar". (*Don't* use a fully qualified name like "org.civicrm.foobar" because that wouldn't look so nice to visitors.)

1. Click **Propose new file**.
1. On the next screen, click **Create pull request**. (You're not done until you create a pull request!)

At some point (hopefully soon!) someone will merge your PR and get the necessary config for your book up on the server. Then it can be published.

## Manually publish your book {:#manual-publishing}

Once your book's config is up, you can go to the following URL to ask the publishing system to publish (or re-publish) your book:

    https://docs.civicrm.org/admin/publish/foobar

(where `foobar` is the short name you used for your book)

Publishing your book manually is fine at first, but once you update the book later, you might wish to avoid this extra step. This is why we have automatic publishing...

## Set up automatic publishing {:#automatic-publishing}

*(Currently only possible if your repo is on GitHub.)*

When you set up automatic publishing, GitHub will tell the publishing system when content within your repo has changed, and the publishing system will re-publish your book as necessary.

1. Go to your repo on GitHub.
1. Go to the **Settings** tab at the far right.
1. Go to **Webhooks > Add webhook**.
1. Set the **Payload URL** to `https://docs.civicrm.org/admin/listen`
1. Set the **Content type** to `application/json`
1. Set **Which events would you like to trigger this webhook?** to 'Let me select individual events' and select 'Pull request' and 'Push' (since these are the only events that should trigger an update)

Now when you make changes to your docs, those changes will be published automatically *and* you'll receive an email notification from the publishing system informing you of the status (including any errors) of the publishing process.

## Make your documentation descoverable {:descoverable}

Hey, you're not done yet! Don't forget to to add links to your new book in all the places where your users might look. This includes this following:

* The listing for your extension in the civicrm.org [extensions directory](https://civicrm.org/extensions)
* Your README file
* Your extension's `info.xml` file like this:
    ```
    <urls>
        <url desc="Documentation">http://docs.civicrm.org/foobar/en/latest</url>
    </urls>
    ```
* Other places from within your extension's UI, as necessary
* Anywhere else that you previously had documentation (e.g. CiviCRM wiki, dedicated site, etc.)
