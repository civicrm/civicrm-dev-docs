# AngularJS: File names

As a developer working with CiviCRM-Angular, you write *Angular modules* --
these modules are composed of various JS/CSS/HTML files which define the
*services*, *directives*, *controllers*, and *routes*.

For sake of predictability, these files are placed in the `ang/` folder, and
they follow a naming convention.

!!! note "How does this work with `civix`?"
    When you generate Angular code via `civix`, the files are
    named according to convention.

    One file, `ang/{mymodule}.ang.php`, provides instructions for the
    file-loader. It lists any files which match the naming
    convention.

!!! note "What if I don't use `civix`? What if my code doesn't follow the naming convention?"
    The file-loader needs some information about the name and location of
    your AngularJS code, but you don't need to follow the convention.  You
    can configure it via hook.  See: [AngularJS: Loading](loader.md).

## Abridged convention

The abridged convention applies to small Angular modules with a narrow
purpose -- such as defining a singular `service` or `directive`.  These
modules only have 2 or 3 files.

   * `ang/{mymodule}.ang.php` - General metadata about the module (per [hook_civicrm_angularModules](../../hooks/hook_civicrm_angularModules.md)).
   * `ang/{mymodule}.js` - All Javascript for the module.
   * `ang/{mymodule}.css` - All CSS for the module (if applicable).
   * `ang/{mymodule}.md` - Developer documentation about the module (if applicable).

## Full convention

The full convention applies to bigger Angular modules which serve a broader
purpose -- such as defining a new screen with a series of related
`directive`s, `controller`s, and `service`s. Each of these elements may
have multiple aspects (JS/HTML/CSS).

__Module Files__

   * `ang/{mymodule}.ang.php` - General metadata about the module (per [hook_civicrm_angularModules](../../hooks/hook_civicrm_angularModules.md)).
   * `ang/{mymodule}.js` - General metadata about the module.
   * `ang/{mymodule}.css` - General CSS that applies throughout the module (if applicable).
   * `ang/{mymodule}.md` - Developer documentation about the module (if applicable).

__Directive Files__

   * `ang/{mymodule}/{FooBar}.js` - The declaration and logic for a directive named `mymoduleFooBar` or `<div mymodule-foo-bar>`.
   * `ang/{mymodule}/{FooBar}.html` - The main/default template for the directive (if applicable).
   * `ang/{mymodule}/{FooBar}/{Extra}.html` - If you have multiple templates used by the same directive (e.g. via `ng-include` or conditional logic), then put them in a subdir.
   * `ang/{mymodule}/{FooBar}.css` - Any CSS specifically intended for `mymoduleFooBar` (if applicable).
   * `ang/{mymodule}/{FooBar}.md` - Developer documentation about the directive (if applicable).

__Controller Files__ (These follow the same convention as directives, but they have the suffix `Ctrl`.)

   * `ang/{mymodule}/{FooBar}Ctrl.js` - The declaration and logic for a controller named `MymoduleFooBarCtrl`.
   * `ang/{mymodule}/{FooBar}Ctrl.html` - The main/default template for the controller (if applicable).
   * `ang/{mymodule}/{FooBar}Ctrl/{Extra}.html` - If you have multiple templates used with the same controller (e.g. via `ng-include` or conditional logic), then put them in a subdir.
   * `ang/{mymodule}/{FooBar}Ctrl.css` - Any CSS specifically intended for `MymoduleFooBarCtrl` (if applicable).
   * `ang/{mymodule}/{FooBar}Ctrl.md` - Developer documentation about the controller (if applicable).

__Service Files__

   * `ang/{mymodule}/{FooBar}.js` - The declaration and logic for a service named `mymoduleFooBar`.
   * `ang/{mymodule}/{FooBar}.md` - Developer documentation about the service (if applicable).

!!! tip "Tip: Use tilde (`~`) to load HTML templates"
    When writing code for Angular, you might use an expression like
    `{templateUrl: 'https://example.org/FooBar.html'}`.  However,
    constructing a full URL that works in every Civi deployment would be
    complex.  Instead, use the tilde prefix.  For example, `{templateUrl: '~/mymodule/FooBar.html'}`.
