# AngularJS: Loader

What happens when a user visits a CiviCRM-Angular page? For example, let's
consider this URL:

 * `https://example.org/civicrm/a/#/caseType`

Broadly speaking, two things happen:

 1. (Server-side) CiviCRM processes the request for `civicrm/a`. It
    displays a web-page with all your Angular modules -- such as
    `ngRoute`, `crmAttachment`, `crmCaseType`, `crmUi`, and so on.
 2. (Client-side) AngularJS processes the HTML/JS/CSS.  It finds that the
    module `crmCaseType` includes a route for `#/caseType` and loads the
    appropriate HTML template.

The client-side behavior is well-defined by Angular
[ngRoute](https://docs.angularjs.org/api/ngRoute).  We'll explore the
server-side in greater depth because that is unique to the CiviCRM-Angular
integration.

!!! caution "Caution: Discusses new/experimental interfaces"

    Some elements of this document have been around since CiviCRM v4.6
    and should remain well-supported. Other elements are new circa v4.7.21
    and are still flagged experimental.

## Library of modules

The CiviCRM-Angular loader needs a list of available AngularJS modules.
This list depends on your system configuration (e.g.  which CiviCRM
extensions are enabled).  To view the current list, run `cv`:

```
$ cv ang:module:list
For a full list, try passing --user=[username].
+-------------------+-------------+------------------------------------------------------------------------------------+
| name              | basePages   | requires                                                                           |
+-------------------+-------------+------------------------------------------------------------------------------------+
| angularFileUpload | civicrm/a   |                                                                                    |
| bw.paging         | (as-needed) |                                                                                    |
| civicase          | (as-needed) | crmUi, crmUtil, ngRoute, angularFileUpload, bw.paging, crmRouteBinder, crmResource |
| crmApp            | civicrm/a   |                                                                                    |
| crmAttachment     | civicrm/a   | angularFileUpload, crmResource                                                     |
| crmAutosave       | civicrm/a   | crmUtil                                                                            |
| crmCaseType       | civicrm/a   | ngRoute, ui.utils, crmUi, unsavedChanges, crmUtil, crmResource                     |
...snip...
```

!!! tip "Tip: More options for `cv ang:module:list`"
    Use `--columns` to specify which columns to display. Ex:

    ```
    $ cv ang:module:list --columns=name,ext,extDir
    ```

    Use `--out` to specify an output format. Ex:

    ```
    $ cv ang:module:list --out=json-pretty
    ```

    Use `--user` to specify a login user. This may reveal permission-dependent modules. Ex:

    ```
    $ cv ang:module:list --user=admin
    ```

Under-the-hood, this library of modules is built via
[hook_civicrm_angularModules](../../hooks/hook_civicrm_angularModules.md), e.g.

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

!!! tip "Tip: Generating skeletal code with `civix`"
    In practice, one usually doesn't need to implement this hook directly.
    Instead, generate skeletal code with `civix`.  For details, see
    [AngularJS: Quick Start](quickstart.md).

## Default base-page

CiviCRM includes a "base-page" named `civicrm/a`.  By default, this page
includes the core AngularJS files as well as all the modules in the library.

The page is generated with a PHP class, `AngularLoader`, using logic like this:

```php
$loader = new \Civi\Angular\AngularLoader();
$loader->setModules(array('crmApp'));
$loader->setPageName('civicrm/a');
$loader->load();
```

The `load()` function determines the necessary JS/CSS/HTML/JSON resources
and loads them on the page. This will include:

 * Any AngularJS modules explicitly listed in `setModules(...)`. (Ex: `crmApp`)
 * Any AngularJS modules with matching `basePages`. (Ex: The value `civicrm/a`
   is specified by both `setPageName(...)` [above] and `myBigAngularModule` [above].)
 * Any AngularJS modules transitively required by the above.

!!! note "What makes `civicrm/a` special?"
    When declaring a module, the property `basePages` will default to
    `array('civicrm/a')`.  In other words, if you don't specify otherwise,
    all modules are loaded on `civicrm/a`.

!!! note "How does `load()` output the `<script>` tag(s)?"
    `load()` uses [CRM_Core_Resources](../resources.md)
    to register JS/CSS files.

## Other base-pages

Loading all Angular modules on one page poses a trade-off.  On one hand, it
warms up the caches and enables quick transitions between screens.  On the
other hand, the page could become bloated with modules that aren't actually
used.

If this is a concern, then you can create new base-pages which are tuned to
a more specific use-case.  For example, suppose we want to create a page
which *only* has the CiviCase administrative UI.

1. Create a skeletal CiviCRM page:

    ```
    $ civix generate:page CaseAdmin civicrm/caseadmin
    ```

1. Edit the new PHP file and update the `run()` function.  Create an
`AngularLoader` to load all the JS/CSS files:

    ```php
    public function run() {
      $loader = new \Civi\Angular\AngularLoader();
      $loader->setModules(array('crmCaseType'));
      $loader->setPageName('civicrm/caseadmin');
      $loader->useApp(array(
        'defaultRoute' => '/caseType',
      ));
      $loader->load();
      parent::run();
    }
    ```
    
    !!! note "Initializing AngularJS"
    
        In this example, we called `useApp()`.  This automatically boots
        AngularJS on the client side by outputting the HTML snippet 
        `<div ng-app="crmApp">`.
        This is suitable for a simple, standalone base-page.
    
        If you want to control the AngularJS boot, then omit the call to
        `useApp()`.  Instead, read the [AngularJS Bootstrap
        Guide](https://code.angularjs.org/1.5.11/docs/guide/bootstrap) and
        define your own boot code.

1. Flush the cache

    ```
    $ cv flush
    ```

1. Visit the new page. (The command below will lookup the URL in a Drupal 7 site.)

    ```
    $ cv url 'civicrm/caseadmin/#/caseType'
    "http://dmaster.l/civicrm/caseadmin/#/caseType"
    ```

!!! caution "Embedding Angular in other contexts"

    In the example, we created a new, standalone page.  But you can use
    `AngularLoader` in other ways -- eg, you might listen for
    [hook_civicrm_pageRun](../../hooks/hook_civicrm_pageRun.md) and embed Angular onto a pre-existing,
    non-Angular page.  Some extensions do this -- though it remains to be
    seen whether this is *wise*.
