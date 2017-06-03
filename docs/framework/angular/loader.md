# AngularJS: Loader

What happens when a user visits a CiviCR-Angular page, such as
`https://example.org/civicrm/a/#/mailing/new`? Broadly speaking, two steps:

 1. (Server-side) CiviCRM processes the request for `civicrm/a`. It
    displays a web-page with all your Angular modules.
 2. (Client-side) AngularJS processes the request for `mailing/new`.
    It uses an HTML template to setup the UI.

The client-side behavior is well-defined by Angular
[ngRoute](https://docs.angularjs.org/api/ngRoute).  We'll explore the
server-side in greater depth because that is unique to the CiviCRM-Angular
integration.

## The default base page (`civicrm/a`)

CiviCRM includes a default base-page -- any module can add new routes on
this page.  For example, the `crmMailing` module defines a route
`mailing/new`. You can view this at:

 * `https://example.org/civicrm/a/#/mailing/new`

The default base-page is special because *all registered Angular modules
will be included by default*.  You can expect the markup to look roughly
like this:

```html
<html>
<head>
<script type="text/javascript" src="https://example.org/.../angular.js"></script>
<script type="text/javascript" src="https://example.org/.../angular-route.js"></script>
<script type="text/javascript" src="https://example.org/.../crmApp.js"></script>
<script type="text/javascript" src="https://example.org/.../crmCaseType.js"></script>
<script type="text/javascript" src="https://example.org/.../crmMailing.js"></script>
<script type="text/javascript" src="https://example.org/.../crmUi.js"></script>
...
<link rel="stylesheet" href="https://example.org/.../crmUi.css" />
...
</head>
<body>
  <div ng-app="crmApp"></div>
</body>
</html>
```

The PHP application instantiates `AngularLoader`

```php
$loader = new \Civi\Angular\AngularLoader();
$loader->setPageName('civicrm/a');
$loader->setModules(array('crmApp'));
$loader->load();
```

The `load()` function determines the necessary JS/CSS/HTML/JSON resources
and loads them on the page. Roughly speaking, it outputs:



More specifically, the `load()` function gets a list of all available
Angular modules (including their JS/CSS/HTTML files). Then it loads the
files for `crmApp` as well as any dependencies (like `crmUi`).

The most important thing to understand is how it *gets the list of Angular
modules*.  A few Angular modules are bundled with core (eg `crmUi` or
`crmUtil`), but most new Angular modules should be loaded via
[hook_civicrm_angularModules](/hooks/hook_civicrm_angularModules.md)

For example, if you created an extension `org.example.foobar` with an
Angular module named `myBigAngularModule`, then the hook might look like:

```php
/**
 * Implements hook_civicrm_angularModules.
 */
function foobar_civicrm_angularModules(&$angularModules) {
  $angularModules['myBigAngularModule'] = array(
    'ext' => 'org.example.foobar',
    'basePages' => array('civicrm/a'),
    'requires' => array('crmUi', 'crmUtils', 'ngRoute'),
    'js' => array('ang/myBigangularModule/*.js'),
    'css' => array('ang/myBigangularModule/*.css'),
    'partials' => array('ang/myBigangularModule'),
  );
}
```

!!! tip "`civix` code-generator"
    In practice, you usually don't need to implement.  The `civix`
    code-generator creates a file named `ang/{mymodule}.ang.php` and
    automatically loads as part of `hook_civicrm_angularModules`.


