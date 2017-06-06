# AngularJS: File names

As a developer working with CiviCRM-Angular, you write *Angular modules* --
these modules are composed of various JS/CSS/HTML files which define the
*services*, *directives*, *controllers*, and *HTML partials*.

For sake of predictability, these files follow a naming convention.  All
major Angular files are placed in the `ang/` folder.

!!! note "How does this work with `civix`?"
    When you generate Angular code via `civix`, the files are
    named according to convention.

    One of the files, `ang/{mymodule}.ang.php` provides instructions for the
    file-loader.

!!! note "What if my code doesn't follow the naming convention? What if I don't use `civix`?"
    The file-loader needs some information about the name and location of
    your AngularJS code, but you don't need to follow the convention.  You
    can configure it via hook.  See: [AngularJS: Loading](/framework/angular/loader.md).

## Abridged convention

Some Angular modules have a very narrow purpose -- such as defining a
singular `service` or `directive`. These modules only have 2 or 3 files.

   * `ang/{mymodule}.ang.php` - General metadata about the module (per [hook_civicrm_angularModules](/hooks/hook_civicrm_angularModules.md)).
   * `ang/{mymodule}.js` - All Javascript for the module
   * `ang/{mymodule}.css` - All CSS for the module (if applicable)

## Full convention

Some Angular modules have broader purpose -- such as defining a new screen
with a series of related `directive`s, `controller`s, and `service`s.  Each
of these elements may have multiple aspects (JS/HTML/CSS).

__Module Files__

   * `ang/{mymodule}.ang.php` - General metadata about the module (per [hook_civicrm_angularModules](/hooks/hook_civicrm_angularModules.md)).
   * `ang/{mymodule}.js` - General metadata about the module.
   * `ang/{mymodule}.css` - General CSS that applies throughout the module.

__Directive Files__

   * `ang/{mymodule}/{FooBar}.js` - The declaration and logic for a directive named `mymoduleFooBar` or `<div mymodule-foo-bar>`.
   * `ang/{mymodule}/{FooBar}.html` - The main/default template for the directive (if applicable).
   * `ang/{mymodule}/{FooBar}/{Extra}.html` - If you have multiple templates used by the same directive (e.g. via `ng-include` or conditional logic), then put them in a subdir.
   * `ang/{mymodule}/{FooBar}.css` - Any CSS specifically intended for `mymoduleFooBar` (if applicable).

__Controller Files__ (These follow the same convention as directives, but they have the suffix `Ctrl`.)

   * `ang/{mymodule}/{FooBar}Ctrl.js` - The declaration and logic for a controller named `MymoduleFooBarCtrl`.
   * `ang/{mymodule}/{FooBar}Ctrl.html` - The main/default template for the controller (if applicable).
   * `ang/{mymodule}/{FooBar}Ctrl/{Extra}.html` - If you have multiple templates used with the same controller (e.g. via `ng-include` or conditional logic), then put them in a subdir.
   * `ang/{mymodule}/{FooBar}Ctrl.css` - Any CSS specifically intended for `MymoduleFooBarCtrl` (if applicable).

__Service Files__

   * `ang/{mymodule}/{FooBar}.js` - The declaration and logic for a service named `mymoduleFooBar`.

!!! tip "Tip: Use tilde (`~`) to load HTML templates"
    When writing code for Angular, you might use an expression like
    `{templateUrl: 'https://example.org/FooBar.html'}`.  However,
    constructing a full URL that works in every Civi deployment would be
    complex.  Instead, use the tilde prefix.  For example, `{templateUrl: '~/mymodule/FooBar.html'}`.
		
<!--
 Proposed Amendment
 Put documentation and examples for each directive or controller in a `*.md` file, adjacent to the `*.js` file. The format is handy for reading/writing/code-snippets, and it won't bloat the final `*.js` output.
-->
