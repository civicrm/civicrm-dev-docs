# Theme Reference

## Introduction

CiviCRM supports CSS-based theming. You may define a theme which overrides
or supplements a CSS file such as `civicrm.css` and `bootstrap.css`.

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

* __Look-and-Feel (*Theme*)__: The visual appearance that an end-user will recognize. (*Ex: a flat, high-contrast appearance built around blue and white*)
* __Package__: The deliverable that an administrator installs/activates. (*Ex: a CiviCRM extension*)
* __Vocabulary__: The set of HTML/CSS class-names which form a contract between HTML and CSS providers. (*Ex: BootstrapCSS*)

Generally speaking, these are relevant to people acting in different roles:

* A __theme developer__ who wishes to define a new __look-and-feel__ (_theme_) should learn about the __vocabulary__
  by examining its __style guide__; then, he or she can create a __package__ (e.g. CiviCRM extension) which
  includes a __CSS file__ for the __vocabulary__.
* A __site administrator__ who wishes to change the __look-and-feel__ of the site should install the __package__
  provided by the __theme developer__.
* An __application developer__ who wishes to provide a pleasant experience should learn about the __vocabulary__
  by examining its __style guide__. He or she can create pages which load the __CSS file__ and use this __vocabulary__.

We can explore each of these concepts in greater detail.

## Quick Start

To generate an extension with a new, skeletal theme named `newyork`, use [civix](../extensions/civix.md):

```bash
$ civix generate:module org.civicrm.newyork
$ cd org.civicrm.newyork
$ civix generate:theme
Initialize theme "newyork"
Write newyork.theme.php
Write css/civicrm.css
Write css/bootstrap.css
```

Note how this provides theme metadata (`newyork.theme.php`) and two empty CSS files (`css/civicrm.css` and
`css/bootstrap.css`).

## Themes

A *theme* determines the visual appearance that an end-user will recognize.  At time of writing, there are two
important themes in CiviCRM:

* __Greenwich (Village)__: This has been the standard appearance since roughly CiviCRM 3.x. Visually, you can
  recognize Greenwich by the use of grey beveled buttons. Technically, it has been developed in vanilla CSS.
* __Shoreditch__: This is a newer theme developed as an extension during 4.7/5.x. Visually, you can recognize
  Shoreditch by its flat design. Technically, it has been developed in the Bootstrap(S)CSS framework.

!!! note "What's in a name?"

    During a planning session for Shoreditch in Fort Collins, CO, we got repeatedly confused by the lack of a distinct
    name for the current and proposed themes.  We started to use names that paid hommage to the original development
    teams -- naming each after a neighborhood that was close to the original teams' bases of operations.

## Vocabularies {:#vocab}

A __vocabulary__ provides a list of CSS class-names.  It defines a contract between an application-developer (who
generates HTML data) and a theme-developer (who generates CSS data). The best way to learn about common
vocabularies is to install the [org.civicrm.styleguide](https://github.com/civicrm/org.civicrm.styleguide/) extension.

There are two important vocabularies which correlate to two important files:

* `crm-*` (aka `civicrm.css`) defines the look-and-feel for any screens based on the __crm-*__ coding convention.
     * __Strength__: This is the traditional vocabulary used on the most screens. It has some smart conventions for
       identifying/selecting fields.
     * __Weakness__: Developed organically. It has gone through some iterations of cleanup/improvement, but its definition
       and terms are not strongly documented.
 * BootstrapCSS (aka `bootstrap.css`) defines the look-and-feel for any screens based on the __Bootstrap__ coding convention.
     * __Strength__: Widely used vocabulary with a larger ecosystem and support tools (e.g. BootstrapSCSS).
     * __Weakness__: This is newer in the CiviCRM ecosystem. Not included with `civicrm-core` -- and there's no
       Greenwich for BootstrapCSS.

The basic purpose of a theme is to provide a copy of each CSS file.

!!! note "Shoreditch as bridge"

    At time of writing, [Shoreditch](https://github.com/civicrm/org.civicrm.shoreditch) is the only theme which implements
    a consistent look-and-feel in both vocabularies. It is intended to be a bridge that provides a consistent
    look-and-feel while other parts of the application transition from `crm-*` to BootstrapCSS.

!!! note "Additional vocabularies"

    If you need to define a new vocabulary for widgets and concepts that don't exist in `crm-*` (`civicrm.css`) or
    BootstrapCSS (`bootstrap.css`), then you can simply choose another filename (`superwidgets.css`) and implement a
    style-guide.  The theme system will allow themes to override any CSS file.

    However, this doesn't mean that *every* CSS file should be overriden.  From the perspective of an application developer,
    adhoc CSS often isn't intended for consumption/override by others.  From the perspective of a theme developer, it
    would be overwhelming to override every CSS file from every extension.

    Instead, approach new vocabularies conscientiously -- a new vocabulary should represent a contract in which both
    application developers and theme developers benefit from a clearer specification.  Use a style-guide to document
    the contract.

## Mechanics

Suppose we have a theme -- such as Greenwich or Shoreditch -- which defines a file -- such as `civicrm.css`. How
does this file get loaded on to the screen?

Somewhere within the application, there is a call to [Resources::addStyleFile()](resources.md), as in:

```php
Civi::resources()->addStyleFile('civicrm', 'css/civicrm.css');
```

The active theme gets first-priority at picking the actual content of `css/civicrm.css`. If it
doesn't provide one, then it falls back to whatever default would normally be loaded.

Internally, `addStyleFile()` accesses the theming service (`Civi::service('themes')`). The
theme service identifies the active-theme and then asks for the concrete URL for the CSS file.

You can simulate this through the command line with [`cv`](https://github.com/civicrm/cv).

```
$ cv ev 'return Civi::service("themes")->getActiveThemeKey();'
"greenwich"
$ cv ev 'return Civi::service("themes")->resolveUrls("greenwich", "civicrm", "css/civicrm.css");'
[
    "http://dmaster.l/sites/all/modules/civicrm/css/civicrm.css?r=gWD8J"
]
```

Each theme has some configuration and metadata.  You can inspect the metadata using `getAll()`.

```
$ cv ev 'return Civi::service("themes")->getAll();'
{
    ...
    "greenwich": {
        "name": "greenwich",
        "url_callback": "\\Civi\\Core\\Themes\\Resolvers::simple",
        "search_order": [
            "greenwich",
            "_fallback_"
        ],
        "ext": "civicrm",
        "title": "Greenwich",
        "help": "CiviCRM 4.x look-and-feel"
    }
    ...
}
```

Internally, `getAll()` emits `hook_civicrm_themes`.  This allows third-party packages to register themes.  The metadata
is cached, but you can clear that cache with a general system flush (`cv flush`).

## Packaging {:#pkg}

*Packaging* is the process of putting the CSS file(s) into a deliverable format that can be installed/activated by an
administator.  CiviCRM supports many package types, such as "CiviCRM extensions", "Drupal modules", and "WordPress
plugins".

### CiviCRM Extension {:#pkg-ext}

To define a new theme, create an extension, e.g.

```bash
civix generate:module org.civicrm.newyork
```

and generate a skeletal theme file:

```bash
cd org.civicrm.newyork
civix generate:theme
```

!!! tip "Multiple subthemes"

    If you prefer to put multiple subthemes in the same extension, then you can pass an extra parameter.
    For example, this would generate themes named `astoria` and `wallstreet`:

    ```
    civix generate:theme astoria
    civix generate:theme wallstreet
    ```

The `generate:theme` command creates a theme definition (eg `newyork.theme.php`) which will be returned via
`hook_civicrm_themes`.  You might edit this file to include a nicer `title`.

```php
// FILE: newyork.theme.php
array(
  'name' => 'newyork',
  'title' => 'New York',
  ...
)
```

Additionally, it creates placeholder copies of `civicrm.css` and `bootstrap.css` (which you can use or edit or replace per taste).

Now activate the theme, e.g.

```bash
cv en newyork
cv api setting.create theme_frontend=newyork theme_backend=newyork
```

Whenever a CiviCRM screen adds a CSS file via `addStyleFile()`, it will
perform a search for the file -- first, looking in the active theme; then,
looking for a fallback in `civicrm-core`.  A typical directory tree would
look like this:

```
org.civicrm.newyork/info.xml
org.civicrm.newyork/newyork.php
org.civicrm.newyork/css/civicrm.css
org.civicrm.newyork/css/bootstrap.css
```

### Drupal/Joomla/WordPress {:#pkg-cms}

In principle, you may package a theme using anything that supports [CiviCRM hooks](../hooks/index.md) -- just implement
`hook_civicrm_themes`.  At time of writing, this technique has not actually been used yet, but a few tips may help:

 * __Define the extension key__:  The previous example defines a
   fully-qualified exension-key (`ext`) with value
   `org.civicrm.newyork`.  For other packages, the naming convention
   is different.  For example, a Drupal module named `foobar` would have the
   extension-key `drupal.foobar`.  (These prefixes are not frequently used;
   we may encounter bugs when using different prefixes.  Patchwelcome.)
 * __Exclude Files__: If the the CMS theme already loads a copy of
   `bootstrap.css` through the CMS, then it may be redundant to load a copy of `bootstrap.css`
   through Civi's theming layer. See "[Advanced: excludes](#excludes)".
 * __Define a callback__:  When loading a CSS file such as `civicrm.css`, the default loader
   tries to read it from your package. However, if your package has a different file structure
   (or if there's a bug in locating your package's folder), you should define a custom
   callback function. See "[Advanced: url_callback](#url_callback)".

### Legacy {:#pkg-legacy}

Prior to CiviCRM v5.8, Civi did not support `hook_civicrm_themes`. Instead, you could
manually deploy CSS files and then configure some settings:

 * `customCSSURL`: Loads an extra CSS file on every CiviCRM screen.
 * `disable_core_css`: Disables the standard call to `addStyleFile('civicrm', 'css/civicrm.css')`.

However, these settings have notable limitations which spurred the development of
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

## Advanced

The metadata for the theme allows several more fields. These fields are most useful if
you intend to bundle multiple subthemes into the same package.

### <code>excludes</code>

CiviCRM theming supports fallbacks: if you don't define `civicrm.css` in
your theme, then it will fallback to using a version that is bundled in
`civicrm-core`.  But what if you want to *exclude* the file completely?  For
example, if you have provided styling rules through a CMS theme, then loading
`civicrm.css` could be redundant.  Use the `excludes` option to disable a file:

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  $themes['newyork'] = array(
    'ext' => 'org.civicrm.newyork',
    'title' => 'New York',
    'excludes' => array('civicrm:css/bootstrap.css'),
  );
}
```

### <code>prefix</code>

If you have several variations on a theme, you may wish to define all of
them in one extension.  For example, the `newyork` extension might define
themes for `astoria` and `wallstreet`.  You can load each variant from a
subfolder:

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  $themes['astoria'] = array(
    'ext' => 'org.civicrm.newyork',
    'title' => 'Astoria',
    'prefix' => 'astoria/',
  );
  $themes['wallstreet'] = array(
    'ext' => 'org.civicrm.newyork',
    'title' => 'Wall Street',
    'prefix' => 'wallstreet/',
  );
}
```

The corresponding file structure would be:

```
org.civicrm.newyork/info.xml
org.civicrm.newyork/newyork.php
org.civicrm.newyork/astoria/css/civicrm.css
org.civicrm.newyork/astoria/css/bootstrap.css
org.civicrm.newyork/wallstreet/css/civicrm.css
org.civicrm.newyork/wallstreet/css/bootstrap.css
```

### <code>search_order</code>

Sometimes you may want to share files among themes; for example, the
`astoria` and `wallstreet` themes might use a common version of
`civicrm.css` (but have their own versions of `bootstrap.css`).  You may
manipulate the `search_order` to define your own fallback sequence:

```php
// FILE: newyork.php
function newyork_civicrm_themes(&$themes) {
  $themes['astoria'] = array(
    'ext' => 'org.civicrm.newyork',
    'title' => 'Astoria',
    'prefix' => 'astoria/',
    'search_order' => array('astoria', '_newyork_common_', '_fallback_'),
  );
  $themes['wallstreet'] = array(
    'ext' => 'org.civicrm.newyork',
    'title' => 'Wall Street',
    'prefix' => 'wallstreet/',
    'search_order' => array('wallstreet', '_newyork_common_', '_fallback_'),
  );
  $themes['_newyork_common_'] = array(
    'ext' => 'org.civicrm.newyork',
    'title' => 'New York (Base Theme)',
    'prefix' => 'common/',
  );
  // Note: "_newyork_common_" begins with "_".  It is a hidden, abstract
  // theme which cannot be directly activated.
}
```

The corresponding file structure would be:

```
org.civicrm.newyork/info.xml
org.civicrm.newyork/newyork.php
org.civicrm.newyork/common/css/civicrm.css
org.civicrm.newyork/astoria/css/bootstrap.css
org.civicrm.newyork/wallstreet/css/bootstrap.css
```

### <code>url_callback</code>

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
      'ext' => "org.civicrm.newyork",
      'title' => "New York ({$colorScheme})",
      'url_callback' => "_newyork_css_url",
    );
  }
}

/**
 * Determine the URL for a CSS resource file.
 *
 * @param \Civi\Core\Themes $themes
 * @param string $themeKey
 *   Identify the active theme (ex: 'newyork-blue', 'newyork-hicontrast').
 * @param string $cssExt
 *   Identify the requested CSS file (ex: 'civicrm', 'org.civicrm.volunteer').
 * @param string $cssFile
 *   Identify the requested CSS file (ex: 'css/civicrm.css', 'css/bootstrap.css').
 * @return array|\Civi\Core\Themes::PASSTHRU
 *   A list of zero or more CSS URLs.
 *   To pass responsibility to another URL callback, return
 *   the constant \Civi\Core\Themes::PASSTHRU.
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

In this example, the `newyork` theme *supplements* the `civicrm.css` file (adding its own content afterward)
instead of *overriding*. All other CSS files work as normal overrides.

```php
function _newyork_css_url($themes, $themeKey, $cssExt, $cssFile) {
  $urls = \Civi\Core\Themes\Resolvers::simple($themes, $themeKey, $cssExt, $cssFile);
  switch ("{$cssExt}/{$cssFile}") {
    case 'civicrm/css/civicrm.css':
      $urls = array_merge(
        Civi::service('themes')->resolveUrls('greenwich', $cssExt, $cssFile),
        $urls
      );
  }
  return $urls;
}
```

In our most sophisticated example, the `newyork` theme generates the
`civicrm.css` content dynamically - by combining various CSS files and
evaluating some inline variables (`{{NEWYORK_URL}}`).  This uses the
[asset builder](asset-builder.md) for caching.

```php
function _newyork_civicrm_css_url($themes, $themeKey, $cssExt, $cssFile) {
  switch ("{$cssExt}/{$cssFile}") {
    case 'civicrm/css/civicrm.css':
      return [\Civi::service("asset_builder")->getUrl("newyork-civicrm.css", ['themeKey' => $themeKey])];
    default:
      return \Civi\Core\Themes\Resolvers::simple($themes, $themeKey, $cssExt, $cssFile);

  }
}

function newyork_civicrm_buildAsset($asset, $params, &$mimeType, &$content) {
  if ($asset !== 'newyork-civicrm.css') return;

  $rawCss = file_get_contents(Civi::resources()->getPath('civicrm', 'css/civicrm.css'))
    . "\n" . file_get_contents(E::path('newyork-part-1.css'))
    . "\n" . file_get_contents(E::path('newyork-part-2.css'));

  $vars = [
    '{{CIVICRM_URL}}'=> Civi::paths()->getUrl('[civicrm.root]/.'),
    '{{NEWYORK_URL}}' => E::url(),
  ];
  $mimeType = 'text/css';
  $content = strtr($rawCss, $vars);
}
```

### Extension CSS files

Generally, one should only override the `civicrm.css` and `bootstrap.css`
files.  If some styling issue cannot be addressed well through those files,
then you should probably have some discussion about how to improve the coding-conventions
or the style-guide so that the standard CSS is good enough.

However, there may be edge-cases where you wish to override other CSS files.
The file structure should match the original file structure.  If you wish
to override a CSS file defined by another extension, then include the
extension as part of the name.

<table>
  <thead>
  <tr><td><strong>Original File</strong></td><td><strong>Theme File</strong></td></tr>
  </thead>
  <tbody>
  <tr><td>civicrm-core/<code>css/dashboard.css</code></td><td>org.civicrm.newyork/<code>css/dashboard.css</code></td></tr>
  <tr><td>civicrm-core/<code>ang/crmMailing.css</code></td><td>org.civicrm.newyork/<code>ang/crmMailing.css</code></td></tr>
  <tr><td>org.civicrm.volunteer/<code>css/main.css</code></td><td>org.civicrm.newyork/<code>org.civicrm.volunteer-css/dashboard.css</code></td></tr>
  <tr><td>org.civicrm.rules/<code>style/admin.css</code></td><td>org.civicrm.newyork/<code>org.civicrm.rules-style/admin.css</code></td></tr>
  </tbody>
</table>

If you use a multitheme/prefixed configuration, then theme prefixes apply
accordingly.

<table>
  <thead>
  <tr><td><strong>Original File</strong></td><td><strong>Theme File</strong></td></tr>
  </thead>
  <tbody>
  <tr><td>civicrm-core/<code>css/dashboard.css</code></td><td>org.civicrm.newyork/astoria/<code>css/dashboard.css</code></td></tr>
  <tr><td>civicrm-core/<code>ang/crmMailing.css</code></td><td>org.civicrm.newyork/astoria/<code>ang/crmMailing.css</code></td></tr>
  <tr><td>org.civicrm.volunteer/<code>css/main.css</code></td><td>org.civicrm.newyork/astoria/<code>org.civicrm.volunteer-css/dashboard.css</code></td></tr>
  <tr><td>org.civicrm.rules/<code>style/admin.css</code></td><td>org.civicrm.newyork/astoria/<code>org.civicrm.rules-style/admin.css</code></td></tr>
  </tbody>
</table>


