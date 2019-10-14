# APIv4 Actions

Most entities support the following actions:

## Create

Insert new records into the database.

## Update

Update records in the database.

## Delete

Delete one or more records. Requires an explicit `id`. If you want to skip the 'recycle bin' for entities that support undelete (e.g. contacts) you should set `$param['skip_undelete'] => 1);`

## Get

Search for records

## GetFields

Fetch entity metadata, i.e. the list of fields supported by the entity. There is now an option that you can pass in for get fields which is `loadOptions`. This is the equivilant of the apiv3 getoptions API call. When used for any field that specifies a pseudoconstant it will return the relevant options in an options key. You can also pass in an option `IncludeCustom` which will specifiy whether to include the relevant custom fields for that entity or not.

## Replace

Replace an old set of records with a new or modified set of records. (For example, replace the set of "Phone" numbers with a different set of "Phone" numbers.).

!!! warning
    Replace includes an implicit delete action - use with care & test well before using in production.

## Save

Create or update one or more records
