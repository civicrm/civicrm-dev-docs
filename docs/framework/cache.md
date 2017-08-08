# Cache Reference

## Using the cache

Civi::cache() is the simplest way to access the cache, automatically
using the default cache type (described below).  The
CRM_Utils_Cache_Interface class lays out the methods for saving and
retrieving cached items.

### Methods

#### Set a cache value

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    Civi::cache()->set('mykey', 'myvalue');

</div>

</div>

#### Get a cached value

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    Civi::cache()->get('mykey'); // returns the value, or NULL if not set

</div>

</div>

#### Delete a cached value

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    Civi::cache()->delete('mykey');

</div>

</div>

#### Flush the entire cache

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    Civi::cache()->flush();

</div>

</div>

### Example

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

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

</div>

</div>

## Cache types

This is selected in civicrm.settings.php (at or near line 276), where
CIVICRM_DB_CACHE_CLASS is defined.

#### ArrayCache

This is the default, using an in-memory array.

#### Memcache

This is for the PHP Memcache extension.

#### Memcached

This is for the PHP Memcached extension.

#### APCcache

This is for the PHP APC extension.

#### NoCache

This caches nothing
