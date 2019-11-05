# APIv4 Actions

*Most entities support the following actions:*

## Get

Search for records based on query parameters.

## Create

Insert new records into the database.

## Update

Update one or more records in the database based on query parameters.

## Save

Create or update one or more records. Passing an `id` determines that an existing record will be updated.

## Replace

Replace an existing set of records with a new or modified set of records. For example, replace a group of "Phone" numbers for a given contact.

!!! warning
    Replace includes an implicit delete action - use with care & test well before using in production.

## Delete

Delete one or more records based on query parameters.

## GetFields

Fetch entity metadata, i.e. the list of fields supported by the entity. Optionally include custom fields; optionally load the option-lists for fields.


