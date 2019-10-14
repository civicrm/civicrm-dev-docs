# APIv4 Usage

Every API call consists of three elements: the *entity*, *action*, and *parameters*.  For example, consider a few commonly-used entities along with the supported actions and parameters:

(*For full, up-to-date details about specific entities and parameters, use the [API Explorer](/api/index.md#api-explorer).*)

The API is available in many different environments (such as PHP, REST, and Javascript), and the notation differs slightly in each environment.
However, if you understand the canonical notation, then other environments will appear as small adaptations.

Canonically, an API call is processed by the API kernel.  The `$entity`, `$action`, and `$params` are passed as inputs, and an arrayObject is returned as output.

A couple of significant differences between APIv4 and APIv3 is that in v4 the `check_permissions` flag is set to true by default and on get actions especially but more generally the limit of 25 items returned that v3 has has been removed in favour of coders specifying the limit if they want to in their code calls.

!!! info
   As of CiviCRM version 5,18 not all core entities have been added to APIv4. You should check the API explorer to see which entities are available. If the entity you require is not available then please open a pull request against the [`civicrm-core` repository](https://github.com/civicrm/civicrm-core) to add the entity in or open an (issue)[https://lab.civicrm.org/dev/core] and request that the entity is added.

```php
$result = Civi::service('civi_api_kernel')->run('Contact', 'get', [
  'version' => 4,
  'where' => [
    ['first_name', '=', 'Alice'],
    ['last_name', '=', 'Roberts'],
  ],
]);
```

The result of a successful API call typically looks like this:

```php
[
 { /* DAO Object */ }
]
```

The result of a failed API call typically looks like this:

```php
array(
  'is_error' => 1,
  'error_message' => /* a descriptive error message */,
)
```

!!! note
    A few specialized actions like `getsingle` or `getvalue` may return success in a different format.


## PHP

This is the most common way to call the API. There are 2 formats of API calls a procedural approach and the more traditional approach

Object Oriented Procedural approach:

```php
$contacts = \Civi\Api4\Contact::get()
  ->addWhere('last_name', '=', 'Adams')
  ->setLimit(25)
  ->execute();
```

Note that we use the a format of Object::action and then there are helper functions like AddWhere

```php
try {
  $contacts = civicrm_api4('Contact', 'get', array(
    'where' => [
      ['first_name', '=', 'Alice'],
      ['last_name', '=', 'Roberts'],
    ],
  ]);
}
catch (\API_Exception $e) {
  $error = $e->getMessage();
}
printf("Found %d item(s)\n", count($contacts));
```

This format matches canonical format almost exactly, with a few improvements for usability:

-  The `version => 4` parameter is not required.
-  Errors are reported as PHP exceptions. You may catch the exceptions or (by default) allow them to bubble up.
- You can immediately iterate over the contacts returned

*Note*: If you're writing a Drupal module, a Joomla extension, a WordPress plugin, or a standalone script, then you may need to **bootstrap** CiviCRM before using the API.  See the examples in [Bootstrap Reference](/framework/bootstrap.md).

## REST

To be implemented


## AJAX

```javascript
CRM.api4('entity', 'action', [params], [statusMessage]);
```

If you pass `true` in as the `StatusMessage` param, it will display the default Status Message. This is useful when doing things such as adding tags to contacts or similar. If you wish to do further work based on the result of the API call (e.g use the results from a GET call) you will need to use the [done method](http://api.jquery.com/deferred.done/) to listen for the event. For example:

```javascript
CRM.api4('EntityTag', 'create', {
  values: {"entity_id":5, "tag_id":3}
}).then(function(results) {
  // do something with results array
}, function(failure) {
  // handle failure
});
```

The AJAX interface is automatically available for web-pages generated through CiviCRM (such as standard CiviCRM web-pages, CiviCRM extensions and custom CiviCRM templates).

The AJAX interface could be made available to other parts of the same website (e.g. a Drupal module or WordPress widget) by calling `CRM_Core_Resources::singleton()->addCoreResources()`
from php. Please note that the AJAX interface is subject to [API Security](/security/permissions.md#api-permissions)
and [Same Origin Policy](http://en.wikipedia.org/wiki/Same_origin_policy). To use it from an external site or application, see REST interface documentation.

## Smarty

## Scheduled jobs
Any API call can be configured to be run as a scheduled job. These can be configured in the UI under **Administer -> System Settings -> Scheduled Jobs**. Usually API calls run this way are written with the intent that they be run as scheduled jobs - e.g those with the Job entity or provided by payment processors to run recurring payments.



## Command line

### drush

To be Implemented

### wp-cli

To be Implemented

### cv

```bash
cv api4 Contact.get '{"where":[["first_name", "=", "Alice"], ["last_name", "=", "Roberts"]]}'
```

## API Security

API has security measures built in depending on the way the API is called that can also be turned off or on. API Permissions are also able to be altered via hook. More information on API Security can be found in the [Security Documentation](/security/permissions.md).
