# APIv3 Chaining

It is possible to do two API calls at once with the first call feeding into the second. E.g. to create a contact with a contribution you can nest the contribution create into the contact create. Once the contact has been created it will action the contribution create using the id from the contact create as `contact_id`. Likewise you can ask for all activities or all contributions to be returned when you do a `get`.

See [api/v3/examples](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples) within the core source code for a plethora of examples (from unit tests) that use chaining. To start, look at these examples:

-   [APIChainedArray.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArray.ex.php)
-   [APIChainedArrayFormats.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArrayFormats.ex.php)
-   [APIChainedArrayValuesFromSiblingFunction.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArrayValuesFromSiblingFunction.ex.php)

Note that there are a few supported syntaxes:

```php
civicrm_api('Contact', 'create', array(
  'version' => 3,
  'contact_type' => 'Individual',
  'display_name' => 'BA Baracus',
  'api.website.create' => array('url' => 'example.com'),
));
```

is the same as

```php
civicrm_api('Contact', 'create', array(
  'version' => 3,
  'contact_type' => 'Individual',
  'display_name' => 'BA Baracus',
  'api.website' => array('url' => 'example.com'),
));
```

If you have 2 websites to create you can pass them as ids after the `.`
or an array

```php
civicrm_api('Contact', 'create', array(
  'version' => 3,
  'contact_type' => 'Individual',
  'display_name' => 'BA Baracus',
  'api.website.create' => array('url' => 'example.com'),
  'api.website.create.2' => array('url' => 'example.org'),
));
```

or

```php
civicrm_api('Contact', 'create', array(
  'version' => 3,
  'contact_type' => 'Individual',
  'display_name' => 'BA Baracus',
  'api.website.create' => array(
    array('url' => 'example.com'),
    array('url' => 'example.org'),
  ),
));
```

The format you use on the way in will dictate the format on the way out.

Currently this supports any entity and it will convert to `entity_id` - i.e. a PledgePayment inside a contribution will receive the `contribution_id` from the outer call.
