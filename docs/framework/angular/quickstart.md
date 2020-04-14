# AngularJS: Quick Start

Let's create a new CiviCRM extension with an AngularJS page. It will present
a small "About Me" screen.

## Create a CiviCRM extension

First, we'll need a skeletal CiviCRM extension:

```
$ civix generate:module org.example.aboutme
Initalize module org.example.aboutme
Write org.example.aboutme/info.xml
Write org.example.aboutme/aboutme.php
Write org.example.aboutme/aboutme.civix.php
Write org.example.aboutme/LICENSE.txt
```

## Create an Angular module

Of course, AngularJS also has its own module system -- any Angular routes,
directives, or services need to live within an Angular `module`.  Let's
create one.

```
$ cd org.example.aboutme
$ civix generate:angular-module
Initialize Angular module "aboutme"
Write ang/aboutme.ang.php
Write ang/aboutme.js
Write ang/aboutme.css
```

!!! tip "Tip: Angular module names"
    By default, `civix` assumes that your Angular module name matches your
    extension name.  In this case, both are named `aboutme`.  However, this
    is not required -- the option `--am` can specify a different name.  This
    can be useful if you want to organize your code into multiple modules.

!!! note "Note: `ang/` folder"
    By convention, AngularJS source code is stored in the `ang/` folder, and
    each item is named after its module.  The convention is discussed in
    more detail in [AngularJS: File Names](files.md)

The first file, `ang/aboutme.ang.php`, provides metadata for the PHP-based
file-loader, e.g.

```php
return array(
  'requires' => array('ngRoute', 'crmUi', 'crmUtil'),
  'js' => array('ang/aboutme.js', 'ang/aboutme/*.js', 'ang/aboutme/*/*.js'),
  'css' => array('ang/aboutme.css'),
  'partials' => array('ang/aboutme'),
  'settings' => array(),
);
```

The second file, `ang/aboutme.js`, provides metadata for the JS-based
Angular runtime, e.g.

```js
  angular.module('aboutme', [
    'ngRoute', 'crmUi', 'crmUtil'
  ]);
```

!!! tip "Tip: angular.module() and CRM.angRequires()"
    The list of dependencies is declared once in PHP and once in JS.  To
    remove this duplication, call `CRM.angRequires(...)`, as in:

    ```js
    angular.module('aboutme', CRM.angRequires('aboutme'));
    ```


## Add an Angular-based page

Now let's add a new Angular-based page.  This page will require a `route`
with a `controller` and an HTML template.  The command
`civix generate:angular-page` will create each of these:

```
$ civix generate:angular-page EditCtrl about/me
Initialize Angular page "AboutmeEditCtrl" (civicrm/a/#/about/me)
Write ang/aboutme/EditCtrl.js
Write ang/aboutme/EditCtrl.html
```

If you inspect the code, you'll find a basic AngularJS app which uses
`$routeProvider`, `angular.module(...).controller(...)`, and so on.

The generated code will display a small "About Me" screen with the current
user's first-name and last-name.

!!! tip "Tip: Flush caches _or_ enable debug mode"
    By default, CiviCRM aggregates AngularJS files and caches them.  You can
    flush this cache manually (`cv flush`).  However, it may be easier to
    disable some of the aggregation/caching features by [enabling debug
    mode](../../tools/debugging.md).

## Open the page

Finally, we'd like to take a look at this page in the web-browser.

By default, CiviCRM combines all Angular modules into one page, `civicrm/a`.
The URL of this page depends on your system configuration.  Here are a few
examples:

```
# Example: Lookup the URL on Drupal 7
$ cv url 'civicrm/a/#/about/me'
"http://dmaster.l/civicrm/a/#/about/me"

# Example: Lookup the URL on WordPress
$ cv url 'civicrm/a/#/about/me'
"http://wpmaster.l/wp-admin/admin.php?page=CiviCRM&q=civicrm/a/#/about/me"
```

!!! tip "Tip: Open the browser from the command-line"
    If you're developing locally on a Linux/OSX workstation, pass the
    option `--open` to automatically open the page in a web browser.
