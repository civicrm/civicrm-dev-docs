# API Joins

An API "get" action will typically return only the values of the entity
requested. However, there are times when it is advantageous to returned
data from a related entity.  For instance, when querying the API for email
addresses, you may want to return the name of the associated contact from the
Contact entity.

The CiviCRM API supports two methods of returning data from associated entities;
API Joins and [APIv3 Chaining](../v3/chaining.md).  API joins provide higher
performance by making a single SQL query with a
[SQL join](https://dev.mysql.com/doc/refman/5.7/en/join.html), and are
generally preferable to API chaining where available.

## Using an API Join

To use a join in an API call, specify the name of the field on which the
join happens, a period, and the name of the field to reference.  

For instance, to search for all primary emails, returning the email and joining
to also return the contact's display name:
```php
$result = civicrm_api3('Email', 'get', array(
  'return' => array("email", "contact_id.display_name"),
  'is_primary' => 1,
));
```

Alternatively, to return email addresses of everyone whose last name is Smith
by joining to the Contact entity:
```php
$result = civicrm_api3('Email', 'get', array(
  'contact_id.last_name' => "Smith",
));
```

You can join multiple times in one query.  For instance, to return a list of
events, displaying their name, the name of the related campaign, and that
campaign's type:
```php
$result = civicrm_api3('Event', 'get', array(
  'return' => array("title", "campaign_id.name", "campaign_id.campaign_type_id"),
));
```
!!! tip
    Joins are available only with the [get](../v3/actions.md#get),
    [getsingle](../v3/actions.md#getsingle), and [getcount](../v3/actions.md#getcount)
    actions.

## Identifying fields eligible for a join

It is possible to join an entity to any other entity if the
[xml schema](../../framework/database/schema-definition.md)
identifies a [foreign key](../../framework/database/schema-definition.md#table-foreignKey) or
a [pseudoconstant](../../framework/database/schema-definition.md#table-field-pseudoconstant).  The [getfields](../v3/actions.md#getfields) action identifies
fields that are eligible for an API join.

!!! warning
    For historical reasons, some entities have a non-standard API in APIv3
    in order to handle more complicated operations. Those entities -  'Contact',
    'Contribution', 'Pledge', and 'Participant' - can be joined to from another
    table, but you can not join to other tables from them.  This limitation will
    be removed in APIv4.
