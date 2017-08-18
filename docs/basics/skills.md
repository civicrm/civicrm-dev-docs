# Useful skills

## Learn how to *use* CiviCRM

Before diving into CiviCRM development, it's worth mentioning that a solid
understanding of CiviCRM from the *user's* perspective can really help. You
pick up the standard user interface patterns that you can re-use in your work,
and you might even realise that the UI already provides the functionality you
wish to develop. To that end, please see the
[User Guide](https://docs.civicrm.org/user/en/stable/).


## Learn these developer skills

Below we've outlined the major technologies that CiviCRM is built with.
You don't need to be a pro at everything listed here to get started, but it's
useful to understand at least of couple of them well, and have a basic
understanding of how they fit together to create CiviCRM.

Technologies which are contained within the CiviCRM project
(e.g. civix, buildkit) are covered in detail within this guide, but
other technologies (such as PHP and Git, which are not CiviCRM-specific) are
outside its scope. As such, this guide assumes that readers
arrive with a baseline understanding of these developer skills. This page lists
these prerequisite skills, along with pointers to usher the reader towards
appropriate resources for closing skills gaps, wherever necessary.
Items listed towards the top of this list are, generally-speaking, more
important skills for CiviCRM development, but the specific skills needed to
accomplishing a particular development goal, certainly vary.

-   **PHP** - the main programming language in which CiviCRM is written
    -   [Language reference](http://php.net/manual/en/langref.php)
-   **Git, GitHub, and GitLab** - Git is a version control system for tracking changes to source code. GitHub and GitLab are web-based tools which host git repositories and issue-tracking systems.
    -   See [our recommended external resources](/tools/git.md#resources)
-   **Command line / bash** - in general, "the command line" refers to using a
    text-only interface to run programs such as `civix`, `git`, and many more.
    Bash is the most common "shell" program used to execute these commands on
    Unix computers (i.e. Linux and OS X).
    -   [Unix command line tutorial](http://www.ee.surrey.ac.uk/Teaching/Unix/)
    -   *Microsoft Windows has a command line shell which functions very
        differently from bash. While developing CiviCRM on Windows is possible,
        it will be a slightly more uphill battle, for this reason and others.*
-   **Javascript** - another programing language used in CiviCRM, especially
    for any logic that happens within the web browser. *(Note that "javascript"
    and "java" are entirely different technologies and should not be confused.)*
    -   [Javascript tutorial](http://www.w3schools.com/js/default.asp)
-   **jQuery** - a javascript library that makes manipulating elements on a web
    page easy
    -   [jQuery documentation](http://api.jquery.com/)
-   **AngularJS** - a front-end javascript/html library providing a client-side framework
    for creating one-page MVC (model-view-controller) and MVVM (model-view-viewmodel) apps
    -   [angularJS documentation](https://docs.angularjs.org/)
-   **HTML** - the markup used so transmit page content to a web browser.
    -   [HTML tutorial](http://www.w3schools.com/html/default.asp)
-   **Smarty** - a "template engine" which allows developers to write an HTML
    file for one web page, while easily including content dynamically generated
    from PHP
    -   [Smarty documentation](http://www.smarty.net/docs/en/)
-   **CSS** - a programming language used to specify consistent visual style to
    be applied to many different elements within a web page. Different web
    browsers interpret the CSS code in slightly different ways.
    -   [CSS tutorial](http://www.w3schools.com/css/default.asp)
    -   [Can I use](http://caniuse.com/) - good for seeing which web browsers
        have implemented certain CSS features
    -   [Comparison of layout engines](https://en.wikipedia.org/wiki/Comparison_of_layout_engines_\(Cascading_Style_Sheets\))
        another helpful comparison of the differences between web browsers
-   **Drupal / Wordpress / Joomla!** - CiviCRM must be installed within one of
    these content management systems, and learning more about the underlying
    CMS will aid CiviCRM development. Drupal is favored by most CiviCRM
    developers and CiviCRM actually borrows many development practices from
    the project, so learning Drupal is a good place to start if you are unsure.
    -   [Drupal documentation](https://www.drupal.org/docs/)
    -   [Wordpress documentation](https://codex.wordpress.org/Main_Page)
    -   [Joomla documentation](https://docs.joomla.org/)
-   **SQL / MySQL** - "SQL" is a standardized language used by many different
    kinds of databases to manipulate data in the database. "MySQL" is one kind
    of database which CiviCRM uses to store all its data. The query syntax
    that MySQL uses conforms [almost](http://troels.arvin.dk/db/rdbms/)
    entirely to the SQL standard, so learning SQL is basically synonymous to
    learning MySQL.
    -   [SQL tutorial](http://www.w3schools.com/sql/default.asp)
    -   [MySQL statement syntax](http://dev.mysql.com/doc/refman/en/sql-syntax.html)
    -   [MySQL Workbench](http://www.mysql.com/products/workbench/) -
        an intuitively designed GUI tool for inspecting and interacting with a
        MySQL database (great for learning more about the CiviCRM data model).


