# APIv4 Usage

Every API call consists of three elements: the *entity*, *action*, and *parameters*. Some commonly used entities in CiviCRM include the following:

| Entity     | Description                                |
|------------|--------------------------------------------|
| `Contact`  | An individual, organization, or household. |
| `Activity` | A phone call, meeting, or email message. that has occurred (or will occur) at a specific date and time. |
| `Address`  | A street-address related to a contact.     |

Each entity supports a number of actions. Consider these samples with commonly-used actions and parameters for the `Contact` entity:

| Action   | Parameters                           | Description                     |
|----------|--------------------------------------|---------------------------------|
| `create` | `#!json {"values":{"contact_type":"Individual", "first_name":"Jane", "last_name":"Doe"}}` | Create a new contact of type "Individual" with first name "Jane" and last name "Doe" |
| `get`    | `#!json {"where":[["last_name", "=", "Doe"]], "limit":25}`                | Fetch the first 25 contacts with the last name "Doe" |
| `delete` | `#!json {"where":[["id", "=", 42]]}` | Delete the contact with id "42" |

(*For full, up-to-date details about specific entities and parameters, use the [API Explorer](/api/index.md#api-explorer).*)

!!! info
    As of CiviCRM version 5.18, not all core entities have been added to APIv4. You should check the API Explorer to see which entities are available. If the entity you require is not available then please open a pull request against the [`civicrm-core` repository](https://github.com/civicrm/civicrm-core) to add the entity or open an [issue](https://lab.civicrm.org/dev/core) and request that the entity is added.

The API is available in many different environments (such as PHP, REST, and JavaScript), and the notation differs slightly in each environment.
However, if you understand the canonical notation, then other environments will appear as small adaptations.

Canonically, an API call is processed by the API kernel. The `$entity`, `$action`, and `$params` are passed as inputs, and an `arrayObject` is returned as output.

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

```json
[
  { /* DAO Object */ }
]
```

The result of a failed API call typically looks like this:

```json
{
  "error_code": 0
}
```

!!! info
    Some parts of APIv4 differ significantly from APIv3, including the handling of `check_permissions` and the default limit for returned objects being removed. For details, refer to [Differences Between APIv3 and APIv4](/api/v4/differences-with-v3.md).

## PHP

This is the most common way to call the API. There are two formats of API calls: an object-oriented approach and the more traditional procedural style.

### Object-Oriented (OOP)

```php
$contacts = \Civi\Api4\Contact::get()
  ->addWhere('last_name', '=', 'Adams')
  ->setLimit(25)
  ->execute();
```

The API is first invoked using a static method in the form of `#!php Entity::action()`.
The returned object implements a number of helper methods like `#!php addWhere()`.
These helper methods use method chaining, which allows multiple methods to be chained together in one statement as shown above.

### Traditional (Procedural)

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

This format matches the canonical format almost exactly, with a few improvements for usability:

- The `version => 4` parameter is not required.
- Errors are reported as PHP exceptions. You may catch the exceptions or (by default) allow them to bubble up.
- You can immediately iterate over the contacts returned.

*Note*: If you're writing a Drupal module, a Joomla extension, a WordPress plugin, or a standalone script, then you may need to **bootstrap** CiviCRM before using the API.  See the examples in [Bootstrap Reference](/framework/bootstrap.md).

## REST

APIv4 is not yet available via REST. This is being tracked in [dev/core#1310](https://lab.civicrm.org/dev/core/issues/1310).

## AJAX

```javascript
CRM.api4('entity', 'action', [params], [index]);
```

If you wish to do further work based on the result of the API call (e.g use the results from a GET call) you will need to use the [done method](http://api.jquery.com/deferred.done/) to listen for the event. For example:

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

The AJAX interface could be made available to other parts of the same website (e.g. a Drupal module or WordPress widget) by calling `#!php CRM_Core_Resources::singleton()->addCoreResources()`
from PHP. Please note that the AJAX interface is subject to [API Security](/security/permissions.md#api-permissions)
and [Same Origin Policy](http://en.wikipedia.org/wiki/Same_origin_policy). To use it from an external site or application, see REST interface documentation.

## Smarty

APIv4 is not yet available as a Smarty function.

## Scheduled jobs

APIv4 is not yet available for scheduled jobs.

## Command line

### drush

APIv4 is not yet available as a drush command.

### wp-cli

APIv4 is not yet available as a wp-cli command.

### cv

`cv` supports multiple input formats for APIv4. The API Explorer uses the JSON format in generated code:

```bash
cv api4 Contact.get '{"where":[["first_name", "=", "Alice"], ["last_name", "=", "Roberts"]]}'
```

This format may be cumbersome to enter manually, so the same API request could also be written like this:

```bash
cv api4 Contact.get +w 'first_name = "Alice"' +w 'last_name = "Roberts"'
```

For more examples, refer to the output of `cv --help api4`.

## API Security

API has security measures built in depending on the way the API is called that can also be turned off or on. API Permissions are also able to be altered via hook. More information on API Security can be found in the [Security Documentation](/security/permissions.md).
