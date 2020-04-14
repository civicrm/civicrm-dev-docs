# CiviCRM WP REST API Wrapper

Starting in CiviCRM Version 5.25 CiviCRM's [extern](https://github.com/civicrm/civicrm-core/tree/master/extern) scripts have been exposed as WordPress REST endpoints.

Requirements:

- PHP 7.1+
- WordPress 4.7+
- CiviCRM version 5.25

## Endpoints

1. `civicrm/v3/rest` - a wrapper around `civicrm_api3()`

    **Parameters**:

    - `key` - **required**, the site key
    - `api_key` - **required**, the contact api key
    - `entity` - **required**, the API entity
    - `action` - **required**, the API action
    - `json` - **optional**, json formatted string with the API parameters/argumets, or `1` as in `json=1`

    It mimics CiviCRM's REST [interface](../interfaces.md), by default all calls to `civicrm/v3/rest` return XML formatted results, to get `json` formatted result pass `json=1` or a json formatted string with the API parameters, like in the example 2 below.

    **Examples**:

    1. `https://example.com/wp-json/civicrm/v3/rest?entity=Contact&action=get&key=<site_key>&api_key=<api_key>&group=Administrators`

    2. `https://example.com/wp-json/civicrm/v3/rest?entity=Contact&action=get&key=<site_key>&api_key=<api_key>&json={"group": "Administrators"}`

2. `civicrm/v3/url` - a substition for `civicrm/extern/url.php` mailing tracking

3. `civicrm/v3/open` - a substition for `civicrm/extern/open.php` mailing tracking

4. `civicrm/v3/authorizeIPN` - a substition for `civicrm/extern/authorizeIPN.php` (for testing Authorize.net as per [docs](https://docs.civicrm.org/sysadmin/en/latest/setup/payment-processors/authorize-net/#shell-script-testing-method))

    **_Note_**: this endpoint has **not been tested**

5. `civicrm/v3/ipn` - a substition for `civicrm/extern/ipn.php` (for PayPal Standard and Pro live transactions)

    **_Note_**: this endpoint has **not been tested**

6. `civicrm/v3/cxn` - a substition for `civicrm/extern/cxn.php`

7. `civicrm/v3/pxIPN` - a substition for `civicrm/extern/pxIPN.php`

    **_Note_**: this endpoint has **not been tested**

8. `civicrm/v3/widget` - a substition for `civicrm/extern/widget.php`

9. `civicrm/v3/soap` - a substition for `civicrm/extern/soap.php`

    **_Note_**: this endpoint has **not been tested**

## Settings

It is recommened to use the hook included in the code to replace the mailing url via the WP REST API.   To do this create a plugin that includes the following:

```php
add_filter( 'civi_wp_rest/plugin/replace_mailing_tracking_urls', '__return_true' )
```

Creating a standalone functionality plugin is recommended.  You can read the [WP docs on Writing a plugin](https://codex.wordpress.org/Writing_a_Plugin), there is a good tutorial on a [Functionality Plugin at ccs-tricks](https://css-tricks.com/wordpress-functionality-plugins/)

Alternatively, but not recommended. you can set the `CIVICRM_WP_REST_REPLACE_MAILING_TRACKING` constant to `true` to replace mailing url and open tracking calls with their counterpart REST endpoints, `civicrm/v3/url` and `civicrm/v3/open`.

_Note: use this setting with caution, it may affect performance on large mailings, see `CiviCRM_WP_REST\Civi\Mailing_Hooks` class._
