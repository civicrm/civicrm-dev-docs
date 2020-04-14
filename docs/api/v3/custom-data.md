# APIv3 and Custom Data

Custom data attached to entities is referenced by `custom_N` where `N` is the unique numerical ID for the custom data field.

To set a custom field, or find entities with custom fields of a particular value, you typically use a parameter like this:

```php
$params['custom_N'] = 'value';
```

To return custom data for an entity, especially when using the CustomValue API, you typically pass a param like the following:

```php
$params['return.custom_N'] = 1;
```

*or (depending on which API entity you are querying)*

```php
$params['return'] = 'custom_N';
```

*or*

```php
$params['return'] = 'custom_N,custom_O,custom_P';
```

For setting custom date fields, (ie CustomValue create), date format is `YmdHis`, for example: `20050425000000`.

This is just a brief introduction; each API may have different requirements and allow different formats for accessing the custom data. See the [API function documentation](../index.md) and also read the comments and documentation in each API php file (under civicrm/CRM/api/v3 in your CiviCRM installation) for exact details,
which vary for each API entity and function.

## Custom Value get

If developers want to get all custom data related to a particular entity. The best method is to do a `CustomValue.get` API Call. 

```php
$result = civicrm_api3('CustomValue', 'get', array('entity_id' => 1));
```

A sample output would be like the following

```php 
{
	"is_error":0,
	"undefined_fields":["return_child:child_name"],
	"version":3,
	"count":1,
	"id":2,
	"values":[
       {
         "entity_id":"1",
         "latest":"Bam Bam", 
         "id":"2",
         "308":"Pebbles Flintstone",
         "309":"Bam Bam"
 } 
] }
```

For entities other than the Contact Entity you can use an alternate notation to the `custom_n` to specify the custom fields you wish to return. You can use `custom_group_name:custom_field_name`. Read carefully the documentation and parameters in `CustomValue.php` for more alternatives and details.

!!! note 
    When retrieving custom data for contact entity, it will only return one value in the case of a multiple custom group set whereas for other entities (e.g. Address, or using the `CustomValue.get` API) you will get all custom data records that relate to the relevant entity

The CustomValue Entity implicitly determines what the `entity_table` variable should be when it is not supplied. If you find that the implicitly is not working out exactly, then specify the `entity_table` key.

When setting the value of custom data that is of type checkbox or multivalue it is important to note that the options need to be passed in as an array. For example, if you want to set options `a` and `c` of for custom value `2` on `contact` you should do the following

```php
$result = civicrm_api3(
  'Contact',
  'create', 
  array('id' = 2, 'custom_2' => array('a', 'c'))
);
```
