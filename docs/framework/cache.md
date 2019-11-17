# Cache Reference

## Using a builtin cache {:#default}

The easiest way to access a cache is to call `Civi::cache()` and request a built-in cache. There are two useful built-in caches:

* `Civi::cache('long')`: This is for things which are more expensive to refresh. By default, it stores data in a SQL table. The data will be long-lived.
* `Civi::cache('short')`: This is for things which are less expensive to refresh. By default, it stores data in a local `array`. The data will be short-lived.

In some environments, the sysadmin [configures with a memory-cache service](#configuration) like Redis or Memcached -- in which case both `short` and `long` will use the memory-cache. The choice between them depeneds on the preferred lifespan in a vanilla/non-optimized environment. To wit:

* If refreshing the data is relatively expensive (*comparable to a remote HTTP request*), use `long`. It's better to have a SQL-based cache than array-based cache. *Sending a SQL query to the cache is preferrable to sending a remote HTTP query.* 
    * __Example__: If a dashlet displays a remote HTTP feed of recent blog posts, it could use `long` cache.
* If refreshing the data is relatively cheap (*comparable to a single on-premises SQL request*), use `short`. It would be silly to use SQL-based cache - because *a cache-hit is no faster than a direct read, and a cache-miss is more expensive than a direct read.* 
    * __Example__: If some data-import code needs to frequently consult the list of `SELECT id, name FROM some_meta_table`, then it could use `short` cache.

### Methods

The `CRM_Utils_Cache_Interface` class lays out the methods for saving and retrieving cached items.

* Set a cache value

    ```php
    Civi::cache()->set('mykey', 'myvalue');
    ```

* Get a cached value

    ```php
    Civi::cache()->get('mykey'); // returns the value, or NULL if not set
    ```

* Delete a cached value

    ```php
    Civi::cache()->delete('mykey');
    ```

* Flush the entire cache

    ```php
    Civi::cache()->clear();
    ```

!!! tip "PSR-16 Compliance (v5.4+)"

    In CiviCRM v5.4+, the cache complies with the PHP-FIG standard [PSR-16](https://www.php-fig.org/psr/psr-16/). `CRM_Utils_Cache_Interface` extends the simple `CacheInterface`, although the implementations differ in a couple small ways:

    * The flush function has two names -- `clear()` (per PSR-16) and `flush()` (which predates PSR-16). These are synonyms.
    * Cache keys in PSR-16 are prohibited from using certain characters.  However, some of these characters were supported in previous versions of CiviCRM's cache interface.  To enable a transition, these restrictions are *not* enforced in a default runtime. However, they *are* enforced during testing, and they can be enabled in `civicrm.settings.php` by toggling `CIVICRM_PSR16_STRICT`.

### Example

```php
/**
 * Finds the magic number, selecting one if necessary.
 *
 * @return int $magicNumber
 *   a magic number between 1 and 100
 */
function findMagicNumber() {
  $magicNumber = Civi::cache()->get('magicNumber');
  if (!$magicNumber) {
    $magicNumber = rand(1,100);
    Civi::cache()->set('magicNumber', $magicNumber);
  }
  return $magicNumber;
}
```

### Configuration

In a stock configuration, the `Civi::cache()` object stores data in a local PHP variable (`ArrayCache`).  This allows frequent, high-speed I/O, but
it only retains data for the scope of one page-request -- which reduces the potential performance gains.

System administrators may configure the default cache to use a more long-term backend, such as `Memcached` or `Redis`.  For more information about
configuring the default cache driver, see [System Administrator Guide => Setup => Caches](https://docs.civicrm.org/sysadmin/en/latest/setup/cache/).

### Aliases

In reading code, you may find these three notations -- which all refer to the same thing which is essentially a 'short' cache as described below:

* `Civi::cache()`
* `Civi::cache('default')`
* `CRM_Utils_Cache::singleton()`

## long and short caches

In CiviCRM Codebase there are generally 2 types of caches discussed 'long' and 'short' caches. Both Long and Short caches can be stored in a cache aggregation system such as Memcached or Redis or APC. For most CiviCRM users that do not implement such caching mechanisms, Long caches are stored in the SQL Database in the `civicrm_cache` table and short caches are stored in an ArrayCache which is just a PHP array instance.

When calling code such as `Civi::cache()->` This resolves to the default cache which is an instance of short cache. This basically around about way is the equivalent of calling `CRM_Utils_Cache::create` with the storage `type` parameter set to `['*memory*', 'ArrayCache']` where as calling `Civi::cache('long')->` or `Civi::cache('settings')` or `Civi::cache('session')` as some examples of 'long' caches this sets the storage type to be `['*memory*', 'SqlGroup', 'ArrayCache']`.

By default neither short or long caches use the withArray parameter which would allow some PHP Thread optimisation. This may change in the future however at present to utilisation a PHP arrayCache in front of say Redis or Memcached etc, then the cache would have to be defined with `withArray => TRUE` or `withArray => fast`.

## Using a custom cache {:#custom}

Generally, it's best to store caches in a memory-backed service like Redis or Memcached. But what happens if the system-configuration doesn't support that?
Perhaps you store the cache in a MySQL table? Or a data-file? Or a PHP array?

The answers should not be the same for all data. For example:

* If the cache is tracking metadata derived from `civicrm_option_value`, then you can get the original data pretty quickly (by querying MySQL).
  Writing the cache to another MySQL table or data-file would serve little benefit.
* If the cache is tracking a remote feed (fetched from another continent via HTTPS), then it's much more expensive to get the original data. In absence of
  Redis/Memcached, you might put the cache in a MySQL table or a data-file.

With a *custom cache object*, a developer gets the same interface (`CRM_Utils_Cache_Interface` / PSR-16), but they can define different preferences
for how to store the cache-data. In particular, you can define a fallback list. Compare these examples:

* `['SqlGroup', 'ArrayCache']` means "If MySQL is available, use that. Otherwise, use a local PHP array."
* `['*memory*', 'SqlGroup', 'ArrayCache']` means "If any memory service is available, then use that. Otherwise, if MySQL is available, use that. As a last resort, use a local PHP array."

You can manually instantiate a custom cache object using the factory-function, `CRM_Utils_Cache::create()`. This is good for demonstrating the concept and for some edge-cases. However,
in typical usage, it's more common to register a named service.

### Example: Manual

```php
// Create a cache object
$cache = CRM_Utils_Cache::create([
  'type' => ['SqlGroup', 'ArrayCache'],
  'name' => 'HelloWorld',
]);
```

A few things to notice here:

* The `type` parameter is an array of preferred storage systems.
* The `name` will be passed down to the storage system -- ensuring that different caches are stored separately.
    * Ex: In `Memcached`/`Redis`, the `name` becomes part of the cache-key.
    * Ex: In `SqlGroup`, the `name` corresponds to the field `civicrm_cache.group_name`.
* There is an optional `withArray` parameter with the following acceptable options.
    * FALSE (default): Reads+writes go directly to the underlying cache
    * TRUE: There's an extra array-based cache-tier in front of the underlying cache. It uses `CRM_Utils_Cache_ArrayDecorator`.
        * This variant is more correct/compliant with PSR-16 in that TTL should be consistent between the front-tier cache and the underlying-cache. It requires a more verbose storage-format, which slightly reduces performance. (Not measurable for 1-4 reads; would be measureable for 1000 reads.)
    * fast: There's an extra array-based cache-tier in front of the underlying cache. It uses `CRM_Utils_Cache_FastArrayDecorator`.
        * This variant is more performant and uses a cleaner/simpler storage-format; however, you're more likely to get stale reads from the front-tier cache. It's not much of practical drawback in typical usage (where the PHP interpreter only runs for <1s; stale info in the front-cache doesn't hang around long anyway). However, the (in)correctness could be an issue in long-run jobs.

Once you have the `$cache` object, it supports all the methods of `CRM_Utils_Cache_Interface` and PSR-16.

```php
// Use the cache object
$value = $cache->get('name');
if ($value === NULL) {
  $cache->set('name', 'Alice');
}

// Flush the contents of the cache
$cache->flush();
```

### Example: Named service

First, we define the service in `Civi\Core\Container` or `hook_civicrm_container`:

```php
use Symfony\Component\DependencyInjection\Definition;

$container->setDefinition("cache.hello", new Definition(
  'CRM_Utils_Cache_Interface',
  [[
    'type' => ['*memory*', 'SqlGroup', 'ArrayCache'],
    'name' => 'HelloWorld',
  ]]
))->setFactory('CRM_Utils_Cache::create');
```

As before, notice that:

* The `type` parameter is an array of preferred storage systems. It will choose the first valid driver.
* The `name` will be passed down to the storage system.
* The service is an instance of `CRM_Utils_Cache_Interface` (PSR-16).
* There is an optional `withArray` parameter as described above as well

Once the service is declared, we can get a reference to the cache in several ways:

* Lookup the service with `Civi::cache('hello')`
* Lookup the service with `Civi::service('cache.hello')`
* Inject the service using the container's dependency-injection

For example, we could use `Civi::cache('hello')` as follows:

```php
// Use the cache object
$value = Civi::cache('hello')->get('name');
if ($value === NULL) {
  Civi::cache('hello')->set('name', 'Alice');
}

// Flush the contents of the cache
Civi::cache('hello')->flush();
```
