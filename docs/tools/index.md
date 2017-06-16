# Development tools

## CiviCRM-specific tools

All of these tools come bundled within [buildkit](/buildkit).

* `civibuild` - Build a complete source tree (with CMS+Civi+addons), provision httpd/sql, etc.
    * *[documentation](/buildkit/civibuild.md)*
    * *[repository](https://github.com/civicrm/civicrm-buildkit)*
* `cv` - command is a utility for interacting with a CiviCRM installation
    * *documentation: run `cv list`*
    * *[repository](https://github.com/civicrm/cv)*
* `civix` - Generate skeletal code for CiviCRM extensions
    * *[documentation](/extensions/civix)*
    * *[repository](https://github.com/totten/civix)*
* `civistrings` - Scan code for translatable strings (*.pot)
    * *documentation: run `civistrings --help`*
    * *[repository](https://github.com/civicrm/civistrings)*
* `cividist` - Generate tarballs from a series of git branches/tags
    * *[documentation](/buildkit/cividist)*
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
* Coder - Configure phpcs for CiviCRM's [coding standards](http://wiki.civicrm.org/confluence/display/CRMDOC/PHP+Code+and+Inline+Documentation)
    * *[documentation & repository](https://github.com/civicrm/coder)*
    * *(Derived from [Drupal's coder project](https://www.drupal.org/project/coder))*


## External tools installed with buildkit

These tools are not specific to CiviCRM, but you'll get them anyway when you install [buildkit](/buildkit)

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

## Other useful tools

### Text editors

If you already have a text editor you love, then stick to that. If you're new and need some recommendations, here are some of the most popular text editors among CiviCRM developers:

* [PhpStorm](https://www.jetbrains.com/phpstorm/) *(See [CiviCRM-specific notes on PhpStorm](/tools/phpstorm))*
* [NetBeans](https://netbeans.org/)
* [Sublime](https://www.sublimetext.com/)
* [Atom](https://atom.io/)
* [vim](http://www.vim.org/)
