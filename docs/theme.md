
# Introduction

CiviCRM supports CSS-based theming. You may define a theme which overrides
or supplements the main CSS files, `civicrm.css` and `bootstrap.css`.

# Architecture

Most installations of CiviCRM are embedded within a CMS (such as Drupal,
Joomla, or WordPress).  Customizing the look-and-feel therefore includes two
general areas of work:

 * __CMS Theming__: Use the features and conventions of the CMS to
    manage the general site-wide appearance.
 * __Civi Theming__: Use the features and conventions of Civi to tune
    the appearance of Civi's screens.

As the CiviCRM application continues to evolve, the intention is to align
these two areas by adopting [Bootstrap CSS](http://getbootstrap.com/)
throughout CiviCRM.  A site-builder (or theme-developer) will select (or
write) a Bootstrap-based theme for their CMS -- and this will define the
look-and-feel within Civi's screens.

However, we cannot achieve that vision in one dramatic leap.  Rather, theming
in Civi will be in transition for the foreseeable future, so we must discuss
some more nuanced concepts.  The key concepts in Civi theming are:

 * __Visual Look-and-Feel__ (Theme): This is the visual appearance that an end-user
   will recognize. For example:
    * The "__Greenwich__" theme provides a look-and-feel with beveled grey buttons.
    * The "__Shoreditch__" theme provides a look-and-feel with flat blue buttons.
 * __HTML Coding Convention__: This is the programmatic convention. It defines a contract
   between an application-developer (who produces HTML data) and a theme-developer
   (who produces CSS data). Each coding convention should have a __style-guide__ which
   demonstrates the convention. For example:
    * The "__civicrm.css__" coding convention encodes a button using the class `crm-button` as in `<input type="submit" class="crm-button">`.
    * The "__bootstrap.css__" coding convention encodes a button using the class `btn` as in `<button class="btn btn-default">`.
 * __Package__: This is the electronic deliverable that an administrator installs
   when he wishes to customize the visual look-and-feel.  CiviCRM supports
   many package types, such as "CiviCRM extensions", "Drupal modules", and
   "WordPress plugins".

A theme-developer who wishes to define a new __look-and-feel__ (_theme_) should create
a __package__ with two CSS files:

 * `civicrm.css` defines the look-and-feel for any screens based on the __crm-*__ coding convention.
 * `bootstrap.css` defines the look-and-feel for any screens based on the __Bootstrap__ coding convention.

The remainder of this document will explore the specifics of the coding
conventions and packaging mechanics.

# Quickstart

_TODO: Discuss copying an example theme. Maybe add a civix helper._

# Coding: civicrm.css

_TODO: Discuss history of civicrm.css. Provide instructions on viewing the style-guide. Discuss
maintenance challenges._

# Coding: bootstrap.css

_TODO: Discuss history of bootstrap.css. Provide instructions on viewing the style-guide._

_TODO: Application developers may need to `addStyleFile('civicrm, 'css/bootstrap.css')`._

# Packaging: CiviCRM Extension

To define a new theme, create an extension, e.g.

```bash
civix generate:module org.civicrm.theme.newyork
```

and implement `hook_civicrm_themes` , e.g.

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  $themes['newyork'] = array(
    'ext' => 'org.civicrm.theme.newyork',
    'title' => 'New York',
  );
}
```

Now activate the theme, e.g.

```bash
cv api extension.install key=org.civicrm.theme.newyork
cv api setting.create theme_frontend=newyork theme_backend=newyork
```

Whenever a CiviCRM screen adds a CSS file via `addStyleFile()`, it will
perform a search for the file -- first, looking in the active theme; then,
looking for a fallback in `civicrm-core`.  A typical directory tree would
look like this:

```
org.civicrm.theme.newyork/info.xml
org.civicrm.theme.newyork/newyork.php
org.civicrm.theme.newyork/css/civicrm.css
org.civicrm.theme.newyork/css/bootstrap.css
```

# Packaging: Drupal/Joomla/WordPress

In principle, you may package a theme using anything that supports
[CiviCRM hooks](hook.md). Simply implement `hook_civicrm_themes` as discussed above
(and adjust the notation as required by your packaging format).

At time of writing, this technique has not actually been used yet, but a few tips may
help:

 * __Define the extension key__:  The previous example defines a
   fully-qualified exension-key (`ext`) with value
   `org.civicrm.theme.newyork`.  For other packages, the naming convention
   is different.  For example, a Drupal module named `foobar` would have the
   extension-key `drupal.foobar`.  (These prefixes are not frequently used;
   we may encounter bugs when using different prefixes.  Patchwelcome.)
 * __Exclude `bootstrap.css`__: If the the CMS theme already loads a copy of
   `bootstrap.css` through the CMS, then it may be redundant to load a copy of `bootstrap.css`
   through Civi's theming layer. See the section below, "Advanced: excludes".
 * __Define a callback__:  When loading a CSS file such as `civicrm.css`, the default loader
   tries to read it from the extension. However, if your package has a different file structure
   (or if there's a bug in locating your package's folder), you can define a custom
   callback function. See the secion below, "Advanced: url_callback".

# Packaging: Legacy

Prior to CiviCRM v4.7.10(?), Civi did not support `hook_civicrm_themes`. Instead, it
provided a handful of settings:

 * `customCSSURL`: Loads an extra CSS file on every CiviCRM screen.
 * `disable_core_css`: Disables the standard call to `addStyleFile('civicrm', 'css/civicrm.css')`.

However, these settings have some notable limitations which spurred the development of
`hook_civicrm_themes`:

 * They don't provide a full packaging format. When defining a full, cogent look-and-feel,
   it's useful to bundle related files (e.g. `civicrm.css`, `bootstrap.css`, images, icons).
   This makes it easier to share/redistribute/collaborate on the new look-and-feel.
 * They apply to all CiviCRM screens equally. If you want to provide a different look-and-feel
   on different screens (e.g. frontend vs backend), there's no way to selectively adjust
   these options.

For new theming projects, it's better to package the customization using an
extension (or Drupal module, WordPress plugin, ad nauseum). However, for existing
projects, the settings will continue to work.

# Advanced: <code>excludes</code>

CiviCRM theming supports fallbacks: if you don't define `civicrm.css` in
your theme, then it will fallback to using a version that is bundled in
`civicrm-core`.  But what if you want to *exclude* the file completely?  For
example, if you have provided styling rules through a CMS theme, then loading
`civicrm.css` could be redundant.  Use the `excludes` option to disable a file:

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  $themes['newyork'] = array(
    'ext' => 'org.civicrm.theme.newyork',
    'title' => 'New York',
    'excludes' => array('civicrm:css/bootstrap.css'),
  );
}
```

# Advanced: <code>prefix</code>

If you have several variations on a theme, you may wish to define all of
them in one extension.  For example, the `newyork` extension might define
themes for `astoria` and `wallstreet`.  You can load each variant from a
subfolder:

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  $themes['astoria'] = array(
    'ext' => 'org.civicrm.theme.newyork',
    'title' => 'Astoria',
    'prefix' => 'astoria/',
  );
  $themes['wallstreet'] = array(
    'ext' => 'org.civicrm.theme.newyork',
    'title' => 'Wall Street',
    'prefix' => 'wallstreet/',
  );
}
```

The corresponding file structure would be:

```
org.civicrm.theme.newyork/info.xml
org.civicrm.theme.newyork/newyork.php
org.civicrm.theme.newyork/astoria/css/civicrm.css
org.civicrm.theme.newyork/astoria/css/bootstrap.css
org.civicrm.theme.newyork/wallstreet/css/civicrm.css
org.civicrm.theme.newyork/wallstreet/css/bootstrap.css
```

# Advanced: <code>search_order</code>

Sometimes you may want to share files among themes; for example, the
`astoria` and `wallstreet` themes might use a common version of
`civicrm.css` (but have their own versions of `bootstrap.css`).  You may
manipulate the `search_order` to define your own fallback sequence:

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  $themes['astoria'] = array(
    'ext' => 'org.civicrm.theme.newyork',
    'title' => 'Astoria',
    'prefix' => 'astoria/',
    'search_order' => array('astoria', 'newyork-base', '*fallback*'),
  );
  $themes['wallstreet'] = array(
    'ext' => 'org.civicrm.theme.newyork',
    'title' => 'Wall Street',
    'prefix' => 'wallstreet/',
    'search_order' => array('wallstreet', 'newyork-base', '*fallback*'),
  );
  $themes['newyork-base'] = array(
    'ext' => 'org.civicrm.theme.newyork',
    'title' => 'New York (Base Theme)',
    'prefix' => 'base/',
  );
}
```

The corresponding file structure would be:

```
org.civicrm.theme.newyork/info.xml
org.civicrm.theme.newyork/newyork.php
org.civicrm.theme.newyork/base/css/civicrm.css
org.civicrm.theme.newyork/astoria/css/bootstrap.css
org.civicrm.theme.newyork/wallstreet/css/bootstrap.css
```

# Advanced: <code>url_callback</code>

The previous theming examples are based on _file-name conventions_.
However, file-name conventions are fairly static and may be unsuitable in
cases like:

 * Dynamically generated themes defined through an admin GUI
 * Large theme libraries with complex rules for sharing/compiling CSS files
 * Integration with other theming systems that use different file-names

In all these cases, it may be useful to define a `url_callback` which
provides more dynamic, fine-grained control over CSS loading.

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  foreach (array('blue', 'white', 'hicontrast') as $colorScheme) {
    $themes["newyork-{$colorScheme}"] = array(
      'ext' => "org.civicrm.theme.newyork",
      'title' => "New York ({$colorScheme})",
      'url_callback' => "_newyork_css_url",
    );
  }
}

/**
 * @param \Civi\Core\Themes $themes
 * @param string $themeKey
 *   Identify the active theme (ex: 'newyork-blue', 'newyork-hicontrast').
 * @param string $cssExt
 *   Identify the requested CSS file (ex: 'civicrm', 'org.civicrm.volunteer').
 * @param string $cssFile
 *   Identify the requested CSS file (ex: 'css/civicrm.css', 'css/bootstrap.css').
 * @return array
 *   A list of zero or more CSS URLs.
 */
function _newyork_css_url($themes, $themeKey, $cssExt, $cssFile) {
  return array('http://example.com/css/myfile.css');
}
```

The logic in `_newyork_css_url()` is fairly open-ended. A few tricks that may be useful:

 * Wrap other themes using `$themes->resolveUrl(...)`
 * Wrap other callbacks like `\Civi\Core\Themes\Resolvers::simple(...)` or `\Civi\Core\Themes\Resolvers::fallback(...)`
 * Locate files in an extension using `Civi::resources()->getPath(...)` or `Civi::resources()->getUrl(...)`
 * Generate files in a datadir using `Civi::paths()->getPath(...)` or `Civi::paths()->getUrl(...)`

# Advanced: Non-standard CSS files

Generally, one should only override the `civicrm.css` and `bootstrap.css`
files.  If some styling issue cannot be addressed well through those files,
then you should have some discussion about how to improve the coding-conventions
or the style-guide.

However, there may be edge-cases where you wish to override other CSS files.
Your file structure should match the original file structure.  If you wish
to override a CSS file defined by another extension, then include the
extension as part of the name.

<table>
  <thead>
  <tr><td><strong>Original File</strong></td><td><strong>Theme File</strong></td></tr>
  </thead>
  <tbody>
  <tr><td>civicrm-core/<code>css/dashboard.css</code></td><td>org.civicrm.theme.newyork/<code>css/dashboard.css</code></td></tr>
  <tr><td>civicrm-core/<code>ang/crmMailing.css</code></td><td>org.civicrm.theme.newyork/<code>ang/crmMailing.css</code></td></tr>
  <tr><td>org.civicrm.volunteer/<code>css/main.css</code></td><td>org.civicrm.theme.newyork/<code>org.civicrm.volunteer-css/dashboard.css</code></td></tr>
  <tr><td>org.civicrm.rules/<code>style/admin.css</code></td><td>org.civicrm.theme.newyork/<code>org.civicrm.rules-style/admin.css</code></td></tr>
  </tbody>
</table>

If you use a multitheme/prefixed configuration, then theme prefixes apply
accordingly.

<table>
  <thead>
  <tr><td><strong>Original File</strong></td><td><strong>Theme File</strong></td></tr>
  </thead>
  <tbody>
  <tr><td>civicrm-core/<code>css/dashboard.css</code></td><td>org.civicrm.theme.newyork/astoria/<code>css/dashboard.css</code></td></tr>
  <tr><td>civicrm-core/<code>ang/crmMailing.css</code></td><td>org.civicrm.theme.newyork/astoria/<code>ang/crmMailing.css</code></td></tr>
  <tr><td>org.civicrm.volunteer/<code>css/main.css</code></td><td>org.civicrm.theme.newyork/astoria/<code>org.civicrm.volunteer-css/dashboard.css</code></td></tr>
  <tr><td>org.civicrm.rules/<code>style/admin.css</code></td><td>org.civicrm.theme.newyork/astoria/<code>org.civicrm.rules-style/admin.css</code></td></tr>
  </tbody>
</table>


# Internals

CSS files are loaded in CiviCRM by calling `addStyleFile()`, e.g.

```php
Civi::resources()->addStyleFile('civicrm', 'css/civicrm.css');
Civi::resources()->addStyleFile('org.example.mymodule', 'style/non-standard.css');
```

`addStyleFile()` asks the theming service (`Civi::service('themes')` aka
`Civi/Core/Themes.php`) for a list of CSS URLs. When debugging or
customizing the system, you can make a similar request through the
command line ([`cv`](https://github.com/civicrm/cv)).

```
$ cv ev 'return Civi::service("themes")->resolveUrls("greenwich", "civicrm", "css/civicrm.css");'
[
    "http://dmaster.l/sites/all/modules/civicrm/css/civicrm.css?r=gWD8J"
]
```

It may also be useful to inspect the definition of the themes. This allows
you to see the full definition (including default and computed options).
Use the `getAll()` function.

```
$ cv ev 'return Civi::service("themes")->getAll();'
{
    ...
    "greenwich": {
        "name": "greenwich",
        "url_callback": "\\Civi\\Core\\Themes\\Resolvers::simple",
        "search_order": [
            "greenwich",
            "*fallback*"
        ],
        "ext": "civicrm",
        "title": "Greenwich",
        "help": "CiviCRM 4.x look-and-feel"
    }
    ...
}
```
