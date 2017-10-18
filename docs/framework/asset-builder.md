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
// Get the URL to `api-fields.json`.
$url = \Civi::service('asset_builder')->getUrl('api-fields.json');

...

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
handful of chosen entities.  Simply pass the chosen entities into
`getUrl()`, then update the definition to use `$params['entities']`, as in:

```php
// Get the URL to `api-fields.json`. This variant only includes
// a few contact-related entities.
$contactEntitiesUrl = \Civi::service('asset_builder')
  ->getUrl('api-fields.json', array(
    'entities' => array('Contact', 'Phone', 'Email', 'Address'),
  )
);

// Get the URL to `api-fields.json`. This variant only includes
// a few case-related entities.
$caseEntitiesUrl = \Civi::service('asset_builder')
  ->getUrl('api-fields.json', array(
    'entities' => array('Case', 'Activity', 'Relationship'),
  )
);

...

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

## CSS Example

In this example, we want to use the CiviCRM logo image provided by core in our extension's CSS.  Extensions can refer to their own files using relative paths, but given the variety of possible installation locations for both CiviCRM core and extensions then neither relative or absolute paths work to refer to a core file.

Using asset builder, we can create a template file, process it at run time then serve up the result to our page.

Our module name is `org.example.myextension`

Create `css/my_css_template.css` with content:

```css
.logo_class {background: url(LOGO_URL)}
```

In `myextension.php`:

```php
function myextension_civicrm_coreResourceList(&$list, $region) {
   ...
   // To include the file without any processing we could use:
   // CRM_Core_Resources::singleton()->addStyleFile('org.example.myextension', 'css/my_css.css');
   // replace that with the following:
   
   // use the asset_builder service to get the url of an asset labeled 'mycss'
   $url = \Civi::service('asset_builder')->getUrl('mycss');

   // load the processed style on the page 
   CRM_Core_Resources::singleton()->addStyleUrl($url);
}

// Use the buildAsset hook to process the css template
function myextension_civicrm_buildAsset($asset, $params, &$mimetype, &$content) {
  // Check for the asset of interest
  if ($asset !== 'mycss') return;

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

Check it is functioning correctly with:
```
$ cv ev '$x = \Civi::service("asset_builder")->render("mycss"); echo $x["content"];' 
```

To see the generated URL use:
```
$ cv ev 'return \Civi::service("asset_builder")->getURL("mycss");' 
```

Notes:

1. The result is cached normally so this does not add significant overhead.

1. If your extension is providing multiple CSS files, they can be combined and processed together by looping around the `file_get_contents()` line reducing the number of http requests your extension pages need to make.
