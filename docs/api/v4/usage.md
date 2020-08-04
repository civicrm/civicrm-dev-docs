# APIv4 Usage

Every API call consists of three elements: the *entity*, *action*, and *parameters*:

**Entity:** The "name" of the API.
CiviCRM entities include Contacts, Activities, Contributions, Events, etc.
Each API entity usually (but not always) corresponds to a table in the database (e.g. the Contact entity is the `civicrm_contact` table).

**Action:** The "verb" of the API. The list of available actions varies for each entity, but in general, most entities support read/write actions `get`, `create`, `save`, `update`, `delete` and `replace`, as well as the metadata actions `getFields` and `getActions`.
 
**Parameters:** Settings or data to pass to the api function. Each action accepts a different set of parameters.

Consider these samples with commonly-used actions and parameters for the `Contact` entity:

| Action   | Parameters                           | Description                     |
|----------|--------------------------------------|---------------------------------|
| `create` | `#!json {"values":{"contact_type":"Individual", "first_name":"Jane", "last_name":"Doe"}}` | Create a new contact of type "Individual" with first name "Jane" and last name "Doe" |
| `get`    | `#!json {"where":[["last_name", "=", "Doe"]], "limit":25}`                | Fetch the first 25 contacts with the last name "Doe" |
| `delete` | `#!json {"where":[["id", "=", 42]]}` | Delete the contact with id "42" |

!!! tip
    For full, up-to-date details about specific entities and parameters, use the [API Explorer](../index.md#api-explorer).

!!! info
    As of CiviCRM version 5.18, not all core entities have been added to APIv4. You should check the API Explorer to see which entities are available. If the entity you require is not available then please open a pull request against the [`civicrm-core` repository](https://github.com/civicrm/civicrm-core) to add the entity or open an [issue](https://lab.civicrm.org/dev/core) and request that the entity is added.

!!! info
    Some parts of APIv4 differ significantly from APIv3, including the handling of `check_permissions` and the default limit for returned objects being removed. For details, refer to [Differences Between APIv3 and APIv4](../v4/differences-with-v3.md).

The API is available in many different environments (such as PHP, CLI, and JavaScript), and the notation differs slightly in each environment. However, if you understand the canonical notation, then other environments will appear as small adaptations.

## PHP

This is the canonical API; all other environments are essentially wrappers around the PHP API.

There are two ways to call the api from PHP - which one you choose is a matter of convenience and personal preference.
For example you may prefer OOP syntax because IDE code editors provide autocompletion.
Or if you need to work with the parameters as an array, traditional syntax will be more convenient.   

[APIv4 PHP Examples](../../img/Api4-PHP-Styles.svg)

### Traditional (Procedural)

*The function `civicrm_api4($entity, $action, [$params], [$index])` accepts an array of parameters and returns the Result.*

```php
$result = civicrm_api4('Contact', 'get', [
  'where' => [
    ['last_name', '=', 'Adams'],
  ],
  'limit' => 25,
]);
```

`$index` provides a convenient shorthand for reformatting the Result array. It has different modes depending on the variable type passed:

* Integer: return a single result array; e.g. `$index = 0` will return the first result, 1 will return the second, and -1 will return the last.
* String: index the results by a field value; e.g. `$index = "name"` will return an associative array with the field 'name' as keys.
* Non-associative array: return a single value from each result; e.g. `$index = ['title']` will return a non-associative array of strings - the 'title' field from each result.
* Associative array: a combination of the previous two modes; e.g. `$index = ['name' => 'title']` will return an array of strings - the 'title' field keyed by the 'name' field.

### Object-Oriented (OOP)

*An `Action` class provides setter methods for each parameter. The `execute()` method returns the Result.*

```php
$result = \Civi\Api4\Contact::get()
  ->addWhere('last_name', '=', 'Adams')
  ->setLimit(25)
  ->execute();
```

### Result

Both OOP and traditional APIs return a **Result** ArrayObject, which can be accessed like an array using e.g. `$result[0]` or `foreach ($result as ...)`. It also has the following methods:

- `$result->first()`: returns the first item, or NULL if not found.
- `$result->last()`: returns the last item, or NULL if not found.
- `$result->itemAt($index)`: returns the item at a given index, or NULL if not found.
- `$result->indexBy($field)`: reindexes the array by the value of a field.
- `$result->column($field)`: reduces the array to a single field.
- `$result->count()`: counts the results.

!!! note
    If you're writing a Drupal module, a Joomla extension, a WordPress plugin, or a standalone script, then you may need to **bootstrap** CiviCRM before using the API.  See the examples in [Bootstrap Reference](../../framework/bootstrap.md).

## REST

APIv4 is not yet available via REST. This is being tracked in [dev/core#1310](https://lab.civicrm.org/dev/core/issues/1310).

## AJAX

The AJAX interface is automatically available for web-pages generated through CiviCRM (such as standard CiviCRM web-pages, CiviCRM extensions and custom CiviCRM templates).

Inputs are identical to the traditional PHP syntax:

```javascript
CRM.api4('entity', 'action', [params], [index])
```

From an Angular app, use the service `crmApi4()` which has an identical signature but works within the `$scope.digest` lifecycle.

Both functions return a Promise, which resolves to a Result array.

```javascript
CRM.api4('Contact', 'get', {
  where: [
    ['last_name', '=', 'Adams'],
  ],
  limit: 25
}).then(function(results) {
  // do something with results array
}, function(failure) {
  // handle failure
});
```

!!! tip
    The AJAX interface could be made available to other parts of the same website (e.g. a Drupal module or WordPress widget) by calling `#!php CRM_Core_Resources::singleton()->addCoreResources()`
    from PHP. Please note that the AJAX interface is subject to [API Security](../../security/permissions.md#api-permissions)
    and [Same Origin Policy](http://en.wikipedia.org/wiki/Same_origin_policy). To use it from an external site or application, see REST interface documentation.

## Smarty

APIv4 is not yet available as a Smarty function.

## Scheduled jobs

APIv4 is not yet available for scheduled jobs.

## Command line

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

### drush

APIv4 is not yet available as a drush command.

### wp-cli

APIv4 is not yet available as a wp-cli command.

## API Security

API has security measures built in depending on the way the API is called that can also be turned off or on. API Permissions are also able to be altered via hook. More information on API Security can be found in the [Security Documentation](../../security/permissions.md).
