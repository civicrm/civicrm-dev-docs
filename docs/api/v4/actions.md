# APIv4 Actions

Most entities support the following actions:

## create

Insert one records into the database.

## Update

Update a record in the database.

## delete

Delete one or more records. (Note: Requires an explicit `id`. Note: if you want to skip the 'recycle bin' for entities that support undelete (e.g. contacts) you should set `$param['skip_undelete'] => 1);`

## get

Search for records

## getfields

Fetch entity metadata, i.e. the list of fields supported by the entity

## replace

Replace an old set of records with a new or modified set of records. (For example, replace the set of "Phone" numbers with a different set of "Phone" numbers.).

Warning - REPLACE includes an implicit delete - use with care & test well before using in productions

## Save

Create or update one or more records
