# Cache Reference

## Using the default cache {:#default}

`Civi::cache()` is the simplest way to access the cache, automatically using the default cache type (described in [Configuration](#configuration)).

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
    Civi::cache()->flush();
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

In reading code, you may find these three notations -- which all refer to the same thing:

* `Civi::cache()`
* `Civi::cache('default')`
* `CRM_Utils_Cache::singleton()`

## Using a custom cache {:#custom}

Generally, it's best to store caches in a memory-backed service like Redis or Memcached. But what happens if the system-configuration doesn't support that?
Perhaps you store the cache in a MySQL table? Or a data-file? Or a PHP array?

The answers are not the same for all data. For example:

* If the cache is tracking metadata derived from `civicrm_option_value`, then you can get the original data pretty quickly (by querying MySQL).
  Writing the cache to another MySQL table or a data-file would just slow things down.
* If the cache is tracking a remote feed (fetched from another continent via HTTPS), then it's much more expensive to get the original data. In absence of
  Redis/Memcached, you might put the cache in a MySQL table or a data-file.

With a *custom cache object*, a developer gets the same interface (`CRM_Utils_Cache_Interface`), but they can define different preferences
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

Once you have the `$cache` object, it supports all the methods of `CRM_Utils_Cache_Interface`.

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
* The service is an instance of `CRM_Utils_Cache_Interface`.

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
