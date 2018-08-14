# Cache Reference

## Using the cache

`Civi::cache()` is the simplest way to access the cache, automatically using the default cache type (described below). The `CRM_Utils_Cache_Interface` class lays out the methods for saving and retrieving cached items.

### Methods

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
