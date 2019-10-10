# APIv4 Chaining

It is now possible to do two API calls at once with the first call feeding into the second. E.g. to create a contact with a contribution you can nest the contribution create into the contact create. Once the contact has been created it will action the contribution create using the id from the contact create as `contact_id`. Likewise you can ask for all activities or all contributions to be returned when you do a get.

Note that there are a few supported syntaxes:

Object Oriented way

```php
$results = \Civi\Api4\Contact::create()
  ->addValue('contact_id', 'Individual')
  ->addValue('display_name', 'BA Baracus')
  ->setChain([
    'create_website' => ['Website', 'create', ['values' => ['contact_id' => '$id', 'url' => 'example.com']]],
  ])
  ->execute();
```

Traditional:

```php
civicrm_api('Contact', 'create', [
  'version' => 4,
  'values' => [
    'contact_type' => 'Individual',
    'display_name' => 'BA Baracus',
  ],
  'chain' => [
    'create_website', ['Website', 'create', ['values' => ['url' => 'example.com', 'contact_id' => '$id']]],
  ],
]);
```

If you have 2 websites to create you can pass them as separate key => array pairs just specifiy a unique array key in the chain array

Object Oriented way

```php
$results = \Civi\Api4\Contact::create()
  ->addValue('contact_id', 'Individual')
  ->addValue('display_name', 'BA Baracus')
  ->setChain([
    'create_first_website' => ['Website', 'create', ['values' => ['contact_id' => '$id', 'url' => 'example.com']]],
    'create_second_website' => ['Website', 'create', ['values' => ['contact_id' => '$id', 'url' => 'example.org']]],
  ])
  ->execute();
```

Traditional:

```php
civicrm_api('Contact', 'create', [
  'version' => 4,
  'values' => [
    'contact_type' => 'Individual',
    'display_name' => 'BA Baracus',
  ],
  'chain' => [
    'create_first website', ['Website', 'create', ['values' => ['url' => 'example.com', 'contact_id' => '$id']]],
    'create_second_website', ['Website', 'create', ['values' => ['url' => 'example.org', 'contact_id' => '$id']]],
  ],
]);
```

Currently this supports any entity and it will convert to `entity_id` - i.e. a PledgePayment inside a contribution will receive the `contribution_id` from the outer call.
