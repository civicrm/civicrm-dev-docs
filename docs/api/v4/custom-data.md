# APIv4 and Custom Data

CiviCRM has a flexible custom data system which allows nearly any entity to be extended. It comes in two distinct flavors: Single-record and Multi-record.
For more background see the user guide chapter: [Creating Custom Fields](https://docs.civicrm.org/user/en/latest/organising-your-data/creating-custom-fields/).

## Single-Record Custom Data

Because single-record fields extend an entity 1-to-1, the API treats the custom fields as an extension of the regular fields.
For example, normally an Event has fields like `id`, `title`, `start_date`, `end_date`, etc.
Adding a custom group named "Event_Extra" and a field named "Room_number" would be accessible from the API as "Event_Extra.Room_number".
You would retrieve it and set it as if it were any other field.
 
!!! tip
    The `name` of a field is not to be confused with the `label`. The API refers to custom groups/fields by name and not user-facing labels which are subject to translation and alteration.

## Multi-Record Custom Data

Multiple record custom data sets are treated by the API as entities, which work similarly to other entities attached to contacts (Phone, Email, Address, etc.). For example, creating a multi-record set named "Work_History" could then be accessed via the API as an entity named "Custom_Work_History" (traditional style) or via the `CustomValue` php class (OO style). Creating a record would be done like so:

**PHP (traditional):**
```php
civicrm_api4('Custom_Work_History', 'create', [
  'values': ['entity_id': 202, 'Test_Field': 555]
]);
```
**Javascript:**
```javascript
CRM.api4('Custom_Work_History', 'create', {
  values: {"entity_id":202, "Test_Field":555}
});
```

**PHP (OO):** Note that the object-oriented style uses the `CustomValue` class:

```php
\Civi\Api4\CustomValue::create('Work_History')
  ->addValue('entity_id', 202)
  ->addValue('Test_Field', 555)
  ->execute();
```

## Field Types and I/O Formats

New custom fields can be configured to store different types of data (Text, Date, Number, URL, etc.). In most cases the I/O format via the api will be a string, however there are a few exceptions:

- **Date fields:** Input format is anything understood by `strtotime`, e.g. "now" or "-1 week" or "2020-12-25". Output format is the ISO string "YYYY-MM-DD HH:MM:SS".
- **Checkbox/multi-select fields:** I/O format is an array of option values.

## Try It Out

Once you have created some custom data in your system, look for it in the API Explorer. Single-record data will appear as fields on the entities they extend, and multi-record data will appear in the list of entities.

!!! tip
    In the Api Explorer look for your multi-record custom entities under "C" alphabetically as they all start with the prefix "Custom_".
