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

!!! note "Note: Parmaters and caching"
    Each combination of (`$asset`,`$params`) will be cached separately.

!!! tip "Tip: Economize on parameters"
    In debug mode, all parameters are passed as part of the URL.
    `AssetBuilder` will try to compress them, but it can only do so much.
    Fundamentally, long `$params` will produce long URLs.

## Other considerations

!!! note "Compare: How does AssetBuilder differ from [Assetic](https://github.com/kriswallsmith/assetic)?"
    Both are written in PHP, but they address differet parts of the process:

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
