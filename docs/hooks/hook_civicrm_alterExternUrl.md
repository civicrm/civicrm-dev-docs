# hook_civicrm_alterExternUrl

## Summary

This hook allows you to modify extern urls such as click tracking, tracked opens urls


## Definition

```php
hook_civicrm_alterExternUrl(\GuzzleHttp\Psr7\UriInterface &$url, $path, $query, $fragment, $absolute, $isSSL)
```

##  Parameters

- \GuzzleHttp\Psr7\UriInterface `$url` - Object references the passed URL.

- string `$path` - the path to the extern php file without the .php extension e.g.`extern/url`

- string `$query` - URL query parameters as a string

- string `$fragment` - A fragment identifier (anchor) to append to the link)

- bool `$absolute` - Whether to force the output to be an absolute link (beginning with a URI-scheme such as 'http:').

- bool `$isSSL` - Whether to redirect to an HTTPS or an HTTP URL, NULL allows CiviCRM to autodetect, TRUE forces an HTTPS URL.

## Returns

-   `NULL`

## Examples

```php
function MODULENAME_civicrm_alterExternUrl(\GuzzleHttp\Psr7\UriInterface &$url, $path, $query, $fragment, $absolute, $isSSL) {
  if ($path == 'extern/url') {
    if ($absolute) {
      $path = 'https://example.com';
    }
    $path . = '/path/to/url/track/wrapper';
    $url->withPath($path);
    $url->withQuery($query);
  }
}
```
