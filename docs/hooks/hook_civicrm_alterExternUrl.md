# hook_civicrm_alterExternUrl

## Summary

This hook allows you to modify extern urls such as click tracking, tracked opens urls


## Definition

```php
hook_civicrm_alterExternUrl(\GuzzleHttp\Psr7\UriInterface &$url, $path, $query, $fragment, $absolute, $isSSL)
```

##  Parameters

- [\Psr\Http\Message\UriInterface](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface/) `$url` - The currently formulated URL. This may be replaced with a different URL.

- string `$path` - The logical path identifies the desired end-point. By convention, these match the original,
  standalone scripts in `civicrm-core:extern/*.php`. For example, `civicrm-core:extern/open.php` has the logical path `extern/open`.

- string `$query` - URL query parameters as a string

- string `$fragment` - A fragment identifier (anchor) to append to the link)

- bool `$absolute` - Whether to force the output to be an absolute link (beginning with a URI-scheme such as 'http:').

- bool `$isSSL` - Whether to redirect to an HTTPS or an HTTP URL, NULL allows CiviCRM to autodetect, TRUE forces an HTTPS URL.

## Returns

-   `NULL`

## Example: Alternative end-point

The primary use-case for this hook is to *generate the URL for alternative end-points*.

In this example, any request for `extern/open` will be directed to a new end-point `https://example.com/open/tracker/wrapper`.

```php
function MODULENAME_civicrm_alterExternUrl(&$url, $path, $query, $fragment, $absolute, $isSSL) {
  if ($path == 'extern/open') {
    $base = $absolute ? new \GuzzleHttp\Psr7\Uri('https://example.com/open/tracker/wrapper');
                      : new \GuzzleHttp\Psr7\Uri('/open/tracker/wrapper')
    $url = $base->withQuery($query)->withFragment($fragment);
  }
}
```

!!!note "Notes"
* The function examines `$path`, `$query`, `$fragment`, `$absolute` -- these are the main *inputs*.
    * The function outputs the `$url` (per [PSR-7 UriInterface](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface)).
    * The outputted `$url` *incorporates* the requested values, but they do not have to be used *literally*.
      In this case, the requested `$path` (`extern/open`) is very different from the concrete path (`/open/tracker/wrapper`).

## Example: Fine-tuned URL

Another use-case is to *fine-tune* the `$url` -- for example, swapping the hostname or adding a query parameter.  In this case, it is important to ensure that your modifications run *after* the main URL has been constructed. To control the timing, use a [Symfony listener](/hooks/usage/symfony) and set the `$priority`. For example:

```php
function MODULENAME_civicrm_config(/* ... */) {
  /* ... */
  Civi::dispatcher()->addListener(
    'hook_civicrm_alterExternUrl', '_MODULENAME_filterExternUrl', -1000);
}

function _MODULENAME_filterExternUrl($e) {
  // Remove the 'www.' at the start of the hostname.
  $newHost = preg_replace('/^www\./', '', $e->url->getHost());
  $e->url = $e->url->withHost($newHost);
}
```

The example differs from the previous -- when fine-tuning a URL, we may consult the concrete `$url` (`$e->url`) as reference-point. However, it is similar in that it ultimately outputs a new `$url` (`$e->url`).

## Reference

The `$path` determines what values are likely to be seen in the `$query`. Here are some canonical examples:

| Path | Query Parameters | Description |
| -- | -- | -- |
| `extern/url` | `qid={$event_queue_id}&u={$url_id}` | Handle a "click-through" event from a constituent reading a CiviMail newsletter |
| `extern/open` | `q={$event_queue_id}` | Handle an "open" event from a constituent reading a CiviMail newsletter |
