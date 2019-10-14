# APIv4 and Custom Data

Custom data attached to entities is referenced in the format of `custom_group_machine_name.custom_field_machine_name`.

To set a custom field, or find entities with custom fields of a particular value, you typically use a parameter like this:

```php
$values['custom_field_reference'] = 'value';
```

To return custom data for an entity just include the machine name of the custom data in the select array.

For setting custom date fields, (ie CustomValue create), date format is `YmdHis`, for example: `20050425000000`.

This is just a brief introduction; each API may have different requirements and allow different formats for accessing the custom data. See the [API function documentation](/api/index.md) and also read the comments and documentation in each API php file (under `civicrm/CRM/api/v3` in your CiviCRM installation) for exact details,
which vary for each API entity and function.

!!! note 
    When retrieving custom data from a multiple record custom group set the custom data will be returned as an array of custom fields which contains the value and the id of the row in the custom field table.

!!! note
  Setting of multivalue custom data fields is still a work in progress.
