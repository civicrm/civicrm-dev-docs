# Development tools

## Tools included with buildkit {:#with-buildkit}

When you install [buildkit](buildkit.md) you'll get all these tools.

*This list of tools is also maintained [in the buildkit readme file](https://github.com/civicrm/civicrm-buildkit/blob/master/README.md).*

### CiviCRM-specific tools {:#civicrm-specific}

* `civibuild` - Build a complete source tree (with CMS+Civi+addons), provision httpd/sql, etc.
    * *[documentation](civibuild.md)*
    * *[repository](https://github.com/civicrm/civicrm-buildkit)*
* `cv` - command is a utility for interacting with a CiviCRM installation
    * *documentation: run `cv list`*
    * *[repository](https://github.com/civicrm/cv)*
* `civix` - Generate skeletal code for CiviCRM extensions
    * *[documentation](../extensions/civix.md)*
    * *[repository](https://github.com/totten/civix)*
* `civistrings` - Scan code for translatable strings (*.pot)
    * *documentation: run `civistrings --help`*
    * *[repository](https://github.com/civicrm/civistrings)*
* `cividist` - Generate tarballs from a series of git branches/tags
    * *[documentation](cividist.md)*
    * *repository: [within civicrm-buildkit](https://github.com/civicrm/civicrm-buildkit/blob/master/bin/cividist)*
* `gitify` - Convert a CiviCRM installation to a git repo
    * *documentation: run `gitify` with no arguments*
    * *repository: [within civicrm-buildkit](https://github.com/civicrm/civicrm-buildkit/blob/master/bin/gitify)*
* `civilint` - Check the syntax of uncommitted files using `phpcs`, `jshint`, etc.
    * *documentation: run `civilint` with no arguments*
    * *repository: [within civicrm-buildkit](https://github.com/civicrm/civicrm-buildkit/blob/master/bin/civilint)*
* `civihydra` - Create a series test sites for several CMSs (extends `civibuild`)
    * *documentation: run `civihydra` with no arguments*
    * *repository: [within civicrm-buildkit](https://github.com/civicrm/civicrm-buildkit/blob/master/bin/civihydra)*
* `civicrm-upgrade-test` - Scripts and data files for testing upgrades
    * *[documentation& repository](https://github.com/civicrm/civicrm-upgrade-test)*
* `civi-test-run` - Run one or more test suites
    * *[documentation](civi-test-run.md)*
    * *repository: [within civicrm-buildkit](https://github.com/civicrm/civicrm-buildkit/blob/master/bin/civi-test-run)*
* Coder - Configure phpcs for CiviCRM's [coding standards](../standards/php.md)
    * *[documentation & repository](https://github.com/civicrm/coder)*
    * *(Derived from [Drupal's coder project](https://www.drupal.org/project/coder))*


### External tools installed with buildkit {:#external}

These tools are not specific to CiviCRM, so you may already have some of them installed on your system. If you install [buildkit](buildkit.md) you'll get all these tools at once, in addition to the CiviCRM-specific tools listed above.

* Dependency management
    * [composer](http://getcomposer.org/) - Manage dependencies for PHP code.
    * [bower](http://bower.io/) - Manage dependencies for Javascript code.
* Source code management
    * [git-scan](https://github.com/totten/git-scan/) - Manage a large number of git repositories.
    * [hub](http://hub.github.com/) - Send commands to github.com.
* Source code quality
    * [jshint](http://jshint.com/) - Check the syntax of Javascript files.
    * [phpcs](https://github.com/squizlabs/PHP_CodeSniffer) - Check the syntax of PHP files.
 * Site management
    * [amp](https://github.com/totten/amp) - Abstracted interface for local httpd/sql service (Apache/nginx/MySQL).
    * [drush](http://drush.ws/) - Administer a Drupal site.
    * [joomla](https://github.com/joomlatools/joomla-console) (joomla-console) - Administer a Joomla site.
    * [wp](http://wp-cli.org/) (wp-cli) - Administer a WordPress site.
* Testing
    * [karma](http://karma-runner.github.io) (w/[jasmine](http://jasmine.github.io/)) - Unit testing for Javascript.
    * [paratest](https://github.com/brianium/paratest) - Parallelized version of PHPUnit.
    * [phpunit](http://phpunit.de/) - Unit testing for PHP (with Selenium and DB add-ons).

## Other useful tools {:#other}

### Miscellaneous {:#misc}

* [git](https://git-scm.com/) - version control system
* [psysh](http://psysh.org/) - a reply-echo-print-loop for PHP (like `php -a`, but better)
* [MySQL Workbench](https://www.mysql.com/products/workbench/) - A graphical interface to your local (or remote) MySQL server
* [MkDocs](http://www.mkdocs.org) - for [editing documentation](../documentation/index.md)

### Text editors

If you already have a text editor you love, then stick to that. If you're new and need some recommendations, here are some of the most popular text editors among CiviCRM developers:

* [PhpStorm](https://www.jetbrains.com/phpstorm/) *(See [CiviCRM-specific notes on PhpStorm](phpstorm.md))*
* [NetBeans](https://netbeans.org/)
* [Sublime](https://www.sublimetext.com/)
* [Atom](https://atom.io/)
* [vim](http://www.vim.org/)

