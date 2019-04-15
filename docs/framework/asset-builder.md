# Asset Builder

The `AssetBuilder` manages lazily-generated assets, such as aggregated
JS/CSS files.  The first time you request a lazy asset, the `AssetBuilder`
fires a hook which builds the content.  The content is stored in a cache
file, and subsequent requests use the cache file.

Lazy assets are simultaneously dynamic and static:

 * __Dynamic__: They vary depending on the current system configuration.
   You cannot lock-in a singular version of the asset because each
   deployment may need a slightly different version.
 * __Static__: Within a given deployment, the asset is not likely to change.
   It can even be served directly by the web-server (without the overhead
   of PHP/CMS/Civi bootstrap)

!!! note "Example: Batch loading Angular HTML"
    When visiting the AngularJS base page `civicrm/a`, one needs to load a
    mix of many small HTML templates.  It's ideal to aggregate them into one
    bigger file and reduce the number of round-trip HTTP requests.

    This asset is _dynamic_ because extensions can add or modify HTML
    templates.  Two different deployments would have different HTML
    templates (depending on the mix of extensions).  Yet, within a
    particular deployment, the content is _static_ because the HTML doesn't
    actually change at runtime.

!!! tip "Tip: Caching and development"
    If you are developing or patching assets, then the caching behavior may
    get distracting. To bypass the cache, navigate to
    __Administer > System Settings > Debugging__ and enable debugging.


## Usage: Simple asset

There are generally two aspects to using `AssetBuilder` -- creating a URL
for the asset, and defining the content of the asset.

For example, suppose we wanted to define a static file named
`api-fields.json` which lists all the fields of all the API entities.

```php
// Define the content of `api-fields.json` using `hook_civicrm_buildAsset`.
function mymodule_civicrm_buildAsset($asset, $params, &$mimeType, &$content) {
  if ($asset !== 'api-fields.json') return;

  $entities = civicrm_api3('Entity', 'get', array());
  $fields = array();
  foreach ($entities['values'] as $entity) {
    $fields[$entity] = civicrm_api3($entity, 'getfields');
  }

  $mimeType = 'application/json';
  $content = json_encode($fields);
}
```

To quickly test if the asset is defined correctly, run this on the command-line:

```
$ cv ev '$x = \Civi::service("asset_builder")->render("api-fields.json"); echo $x["content"];'
```

Or run this command to obtain the asset's URL:

```
$ cv ev 'return \Civi::service("asset_builder")->getUrl("api-fields.json");'
```

Notice that these commands use `Civi::service("asset_builder")` and the
functions `render(...)` or `getUrl(...)` to manage the asset.  You can call
these functions in your PHP code.  Further down, the fully formed CSS
example will demonstrate this.

!!! note "What does `getUrl(...)` do?"

    In normal/production mode, `getUrl(...)` checks to see if the asset
    already exists.  If necessary, it fires the hook and saves the asset to
    disk.  Finally, it returns the direct URL for the asset -- which allows
    the user to fetch it quickly (without extra PHP/CMS/Civi bootstrapping).

    In debug mode, `getUrl(...)` returns the URL of a PHP script.  The PHP
    script will build the asset every time it's requested.

## Usage: Parameterized asset

What should you do if you need to create a series of similar assets, based on slightly
different permutations or configurations? Add parameters (aka `$params`).

For example, we might want a copy of `api-fields.json` which only includes a
handful of chosen entities.  Simply update the definition to read `$params['entities']`:

```php
// Define the content of `api-fields.json` using `hook_civicrm_buildAsset`.
function mymodule_civicrm_buildAsset($asset, $params, &$mimeType, &$content) {
  if ($asset !== 'api-fields.json') return;

  $fields = array();
  foreach ($params['entities'] as $entity) {
    $fields[$entity] = civicrm_api3($entity, 'getfields');
  }

  $mimeType = 'application/json';
  $content = json_encode($fields);
}
```

Then, in each call to `render(...)` or `getUrl(...)`, pass the `$params` array with a specific value for `entities`.

For example, run this command to get the generated URL for `api-fields.json` for a few contact-related entities:

```
$ cv ev 'return \Civi::service("asset_builder")->getUrl("api-fields.json", array("entities" => array("Contact", "Phone", "Email", "Address")));'
```

Or, for a few case-related entities, change the `entities` list:

```
$ cv ev 'return \Civi::service("asset_builder")->getUrl("api-fields.json", array("entities" => array("Case", "Activity", "Relationship")));'
```

!!! note "Note: Parameters and caching"
    Each combination of (`$asset`,`$params`) will be cached separately.

!!! tip "Tip: Economize on parameters"
    In debug mode, all parameters are passed as part of the URL.
    `AssetBuilder` will try to compress them, but it can only do so much.
    Fundamentally, long `$params` will produce long URLs.

## Other considerations

!!! note "Compare: How does AssetBuilder differ from [Assetic](https://github.com/kriswallsmith/assetic)?"
    Both are written in PHP, but they address different parts of the process:

     * `AssetBuilder` provides URL-routing, caching, and parameterization.
       Its strength is defining a *lazy lifecycle* for the assets.
     * `Assetic` provides a library of generators and filters.  Its strength
       is defining the *content* of an asset.

    You could use them together -- e.g.  in `hook_civicrm_buildAsset`,
    declare a new asset and use `Assetic` to build its content.

!!! caution "Caution: Confidentiality and lazy assets"
    The current implementation does not take aggressive measures to keep
    assets confidential. For example, an asset built from public JS files
    is fine, but an asset built from permissioned data (contact-records
    or activity-records) could be problematic.

    It may be possible to fix this by computing URL digests differently, but
    (at time of writing) we don't have a need/use-case.

## Example: Dynamic CSS file {:#css-example}

In this example, we want to use the CiviCRM logo in our extension's CSS. The extension's CSS can refer to its own files using relative paths, but the logo is provided by `civicrm-core`, and every deployment can have a different file-structure -- making it impossible to predict the correct path.

Using asset builder, we can create a template file, fill in the proper logo URL, and then serve up the result to our page.

Our module name is `org.example.myextension`.

Create `css/my_css_template.css` with content:

```css
.logo_class {background: url(LOGO_URL)}
```

In `myextension.php`:

```php
/**
 * Implements hook_civicrm_buildAsset().
 *
 * Use hook_civicrm_buildAsset() to define the asset 'mycss'
 * It locates the css template in the extension and the required image from core
 * and substitutes the image path into the css template returning the value via
 * the $content parameter.
 */
function myextension_civicrm_buildAsset($asset, $params, &$mimetype, &$content) {
  // Check for the asset of interest
  if ($asset !== 'mycss.css') return;

  // Find the path to our template css file
  $path = \Civi::resources()->getPath('org.example.myextension', 'css/my_css_template.css');

  // Read in the template
  $raw = file_get_contents($path);

  // Get the URL of the image we want from Core
  // Note that the 'civicrm' string here is a special to refer to the installation location of the core files
  $url = \Civi::resources()->getUrl('civicrm', 'i/logo_sm.png');

  // Replace the LOGO_URL token in the file with the actual url
  // Note that $content is passed by reference to this hook function
  $content = str_replace('LOGO_URL', $url, $raw);

  // Set the mimetype appropriately for the type of content
  // Note that $mimetype is passed by reference to this hook function
  $mimetype = 'text/css';
}
```

Check it is functioning correctly:
```
$ cv ev '$x = \Civi::service("asset_builder")->render("mycss.css"); echo $x["content"];'
```

Get the generated URL:
```
$ cv ev 'return \Civi::service("asset_builder")->getUrl("mycss.css");'
```

Now we can use our newly defined asset in place of a static css file in `myextension.php`:

```php
/**
 * Implements hook_civicrm_coreResourceList().
 */
function myextension_civicrm_coreResourceList(&$list, $region) {
   ...
   // To include the file without any processing we could use:
   // CRM_Core_Resources::singleton()->addStyleFile('org.example.myextension', 'css/my_css.css');
   // replace that with the following:

   // use the asset_builder service to get the url of an asset labeled 'mycss.css'
   $url = \Civi::service('asset_builder')->getUrl('mycss.css');

   // load the processed style on the page
   CRM_Core_Resources::singleton()->addStyleUrl($url);
}
```

Notes:

1. The result is cached normally so this does not add significant overhead.

1. If your extension is providing multiple CSS files, they can be combined and processed together by looping around the `file_get_contents()` line reducing the number of http requests your extension pages need to make.
