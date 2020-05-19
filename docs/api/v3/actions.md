# APIv3 Actions

Most entities support the following actions:

## create

Insert or update one record. (Note: If an `id` is specified, then an
existing record will be modified.)

## delete

Delete one record. (Note: Requires an explicit `id`. Note: if you
want to skip the 'recycle bin' for entities that support undelete (e.g.
contacts) you should set `$param['skip_undelete'] => 1);`

## get

Search for records

## getsingle

Search for records and return the first or only match. (Note: This
returns the record in a simplified format which is easy to use)

## getvalue
Does a `getsingle` and returns a single value - you need to also set
`$param['return'] => 'fieldname'`.

## getcount
Search for records and return the quantity. (Note: In many cases in
early versions queries are limited to 25 so this may not always be
accurate)

## getrefcount

Counts the number of references to a record

## getfields

Fetch entity metadata, i.e. the list of fields supported by the entity

## getlist

Used for autocomplete lookups by the
[entityRef](./../../framework/quickform/entityref.md) widget

## getoptions

Returns the options for a specified field e.g.
```php
civicrm_api3(
  'Contact',
  'getoptions',
  array('field' => 'gender_id')
);
```

returns

```php
array(
  1 => 'Female',
  2 => 'Male',
  3 => 'Transgender'
)
```

## replace

Replace an old set of records with a new or modified set of records.
(For example, replace the set of "Phone" numbers with a different set of
"Phone" numbers.).

Warning - REPLACE includes an implicit delete - use with care & test well
before using in productions

## getunique

Returns all unique fields (other than 'id' field) for a given entity.
```php
civicrm_api3('Contribution', 'getunique');
```

return 

```php
{
    "is_error": 0,
    "version": 3,
    "count": 2,
    "values": {
        "UI_contrib_trxn_id": [
            "trxn_id"
        ],
        "UI_contrib_invoice_id": [
            "invoice_id"
        ]
    }
}
```

## <del>setvalue</del>

**Deprecated.** Use the create action with the param 'id' instead.

## <del>update</del>

**Deprecated.** Use the create action with the param 'id' instead.
