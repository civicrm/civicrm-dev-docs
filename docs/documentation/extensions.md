# How to document your extension

So you've written an [extension](../extensions/index.md). Awesome! Now you want to add some documentation to help people use it. Great! Maybe you even want some docs for other *developers* working on it too. Very good. This page will help you set up your own documentation *guide* (just like this Developer Guide) for your extension.

!!! summary "Example"
    For a fully-featured working example of extensions documentation, look at CiviVolunteer.

    * Read the published [CiviVolunteer guide](https://docs.civicrm.org/volunteer/en/latest/)
    * Inspect the following source code to see how it's made:
        * [/docs/](https://github.com/civicrm/org.civicrm.volunteer/tree/master/docs) within the project repo (to store all the content in markdown files)
        * [/mkdocs.yml](https://github.com/civicrm/org.civicrm.volunteer/blob/master/mkdocs.yml) within the project repo (to specify guide structure)
        * [volunteer.yml](https://lab.civicrm.org/documentation/docs-books/blob/master/volunteer.yml) within the `docs-books` repo (to specify how the guide is to be published)

## Overview

Here are the basic steps (and each one is explained in more detail later on this page.)

1. You use the git repo for your extension to store its documentation.
* You store the content in [markdown](markdown.md) files within a `docs` directory in your project.
* You use git branches just like you normally would, with that `docs` directory sitting there in every branch.
* You put one file at the root level of your project, `mkdocs.yml` to configure some of the high-level details of your book.
* You use MkDocs locally to preview your guide.
* When you're ready, you make a pull request on our [publishing system's books repository](https://lab.civicrm.org/documentation/docs-books) to add the necessary configuration for your guide, so that it gets published to [docs.civicrm.org](https://docs.civicrm.org).
* You configure GitLab or GitHub to tell our publishing system when to publish updates to your guide.

Follow along in the steps below to get a guide up and running for your extension.

## Set up `mkdocs.yml` {:#config}

Create a new file called `mkdocs.yml` in the root level of your project with the following content:

```yaml
site_name: Your Extension Name
repo_url: https://lab.civicrm.org/extensions/yourproject
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

The README file at the root of your repository is great for keeping some simple documentation that people will see when they visit your repo. But non-technical users can become confused when seeking docs landing on GitLab/GitHub.

When creating a docs guide, you have two options for how to deal with your README file:

### Mirror README to a single-page guide {#:readme-mirrored}

With this option, you basically keep documentation content in the README file, and create an MkDocs guide directly from that file. The content remains on GitHub when people visit your repo, and it also gets published on docs.civicrm.org &mdash; and you only have to edit it in one place. The downside is that you have to fit everything into one page.

1. Make sure you're using [markdown](markdown.md) formatting within the README file, and make sure the file is named `README.md`.
1. Create an MkDocs index file which is a symbolic link to your README file:

    ```
    cd <project-root>
    mkdir docs
    cd docs
    ln -s -T ../README.md index.md
    ```

### Use a multi-page guide and a separate README file {:#readme-separate}

With this option you have a very small README file and a separate MkDocs guide which can have multiple pages.

1. Add a `docs` directory at the root of your project.
2. Within that, add a new file `index.md` to use for your content.
3. Keep your README file, but don't store documentation in it. Just have it explain the bare bones of your project and *link* to your documentation (once it's published on docs.civicrm.org.)


## Add content

Add some [markdown](markdown.md) content in `docs/index.md`.

You can add more pages by creating more markdown files and specifying these files under `pages` in `mkdocs.yml`.

!!! note
    We have [markdown coding standards](markdown.md#standards) and a documentation [style guide](style-guide.md). Adherence to these rules within your extensions docs is recommended but not required.


## Preview your guide

Now you should be able to run `mkdocs serve` from within your project directory to start previewing your content. See [instructions here](index.md#mkdocs) to get MkDocs set up.


## Submit your guide to our publishing system {:#submit}

Once your guide is in good shape it's time to get it up on [docs.civicrm.org](https://docs.civicrm.org).

1. Go to the [repository for docs books](https://lab.civicrm.org/documentation/docs-books/)
1. Click **Fork**, and if necessary choose your user as the destination of the fork. 
1. Click on the `+` button to add a new file to the root directory within your fork.
1. For the file name, use something like `foobar.yml`, where "foobar" is your extension's **short name**. This is the name that will be used in the URL for your docs.
1. Copy paste the following content into the file editor (note that the leading whitespace is important for lines in this file since it communicates structure in yaml):

    ```yaml
    name: Foo Bar
    description: Provides a baz for every contact's bat
    langs:
      en:
        repo: 'https://lab.civicrm.org/extensions/foobar'
    ```

    * There are lots of other settings you can put here if you want to have multiple languages or versions. Look at `user.yml` as an example of a guide (the User Guide) which takes advantage of all the possible settings.
    * But if you want to keep it simple and just have one English edition which points to the `master` branch of your repo, then stick with the above settings.

1. Adjust `name` and `repo` to your own values

    * The `name` you set here will be shown in the list of all guides on [docs.civicrm.org](https://docs.civicrm.org) as well as at the top of every page of your guide. Use whatever **long name** you've chosen for your extension, such as "Foo Bar", or "CiviFoobar". (*Don't* use a fully qualified name like "org.civicrm.foobar" because that wouldn't look so nice to visitors.)

1. For the commit message, write something like "Add new Foobar Guide".
1. Click **Commit changes**.
1. Click **Merge requests** > **New merge request**.
    * Set the source branch to your fork and `master`.
    * Set the target branch to the upstream repository and `master`.
    * Click **Compare branches and continue**.
    * Click **Submit merge request**.

At some point (hopefully soon!) someone will merge your MR and get the necessary config for your guide up on the server. Then it can be published.

## Manually publish your guide {:#manual-publishing}

Once your guide's config is up, you can go to the following URL to ask the publishing system to publish (or re-publish) your guide:

    https://docs.civicrm.org/admin/publish/foobar

(where `foobar` is the short name you used for your guide)

Publishing your guide manually is fine at first, but once you update the guide later, you might wish to avoid this extra step. This is why we have automatic publishing...

## Set up automatic publishing {:#automatic-publishing}

When you set up automatic publishing, GitLab or GitHub will tell the publishing system when content within your repo has changed, and the publishing system will re-publish your guide as necessary.

See the [docs-publisher instructions](https://lab.civicrm.org/documentation/docs-publisher#setting-up-automatic-publishing) for how to set up automatic publishing on GitHub and GitLab.

Now when you make changes to your docs, those changes will be published automatically *and* you'll receive an email notification from the publishing system informing you of the status (including any errors) of the publishing process.

## Make your documentation discoverable {:discoverable}

Hey, you're not done yet! Don't forget to to add links to your new guide in all the places where your users might look. This includes this following:

* The listing for your extension in the civicrm.org [extensions directory](https://civicrm.org/extensions)
* Your README file
* Your extension's `info.xml` file like this:
    ```
    <urls>
        <url desc="Documentation">http://docs.civicrm.org/foobar/en/latest</url>
    </urls>
    ```
* Other places from within your extension's UI, as necessary.
* Anywhere else that you previously had documentation (e.g. CiviCRM wiki, dedicated site, etc.)
