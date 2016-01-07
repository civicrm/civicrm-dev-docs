
APIv3
=====

CiviCRM has a stable comprehensive **API** (Application Programming
Interface) that can be used to access and manage data in CiviCRM. The
API is the recommended way for any CiviCRM extension, CMS module, or
external program to interact with CiviCRM. This section of the wiki
gives you an introduction to the basics of the API. This page's children
dive into the depths of the API.

Why use the API?
----------------

If you're not familiar with APIs, you might ask why you would use the
API when you could access the core functions (e.g. the BAO files) or the
data in the MySQL database directly?

The answer is that the community will ensure that the API will function
as expected with every new release. Great effort is made to preserve
backwards compatibility of the API for several versions of CiviCRM. If
you decide to use other ways to collect data (like your own MySQL
statements), you are left to your own devices. Changes in the schema and
BAO arguments, etc. will cause you grief.

A few comments before we start
------------------------------

The API is something that we as a community develop and maintain
together. A pretty awesome achievement so a big round of applause for
everyone who contributes! It also means that:

-   the API is continuously developed and improved. Once you have
    mastered the basics from this introduction it is a good idea to
    delve deeper into the wiki and the forums for the latest and
    greatest

-   there is always a chance you run into a bug, if you do please report
    it on the forum or go to the CiviCRM IRC channel if it is a real
    urgency. Obviously it will be greatly appreciated if you fix it,
    send it as a patch with a unit test

-   This introduction is updated by the community too. If you do happen
    to find some mistake, please log in and change

Exploring the API and documentation
-----------------------------------

The best place to learn about the API is your own ***test*** install of
CiviCRM, using the API explorer and the API parameter list. 

Each install comes with two tools that will help you to explore the
wonders of the API and learn a little more about each one:

1.  The**API parameter list**, which shows all available entities which
    can be manipulated by the API and is available
    at http://[CIVICRM\_URL]/civicrm/api/doc (or
    http://[CIVICRM\_URL]/?q=civicrm/api/doc if you do not use clean
    URLs in Drupal). You will first get a list of all the API entities.
    If you click on an entity you will get a list of parameters that are
    available for that specific entity, with the type of the parameter.
    This can be very useful if you want to check what you can retrieve
    with the API and what parameters you can use to refine your get
    action or complete your create or update action.
2.  The **API explorer**, which is available
    at http://[CIVICRM\_URL]/civicrm/api/explorer (or
    http://[CIVICRM\_URL]/?q=civicrm/api/explorer if you do not use
    clean URLs in Drupal). This gives you the possibility to actually
    try out the API in action. You can select the entity you want to
    use, for example ' Contact' and the action you want to perform, for
    example ' Get' . Again, be careful as the API explorer will actually
    perform your actions! So if you delete a contact to check what API
    call to use, it will really delete the contact. The API explorer
    will show you the code you need to have to execute the API call you
    have been testing. To see an example of the API explorer in action
    check
    [http://civicrm.org/API\_version\_3](http://civicrm.org/API_version_3).
    It may be that it uses an older version of the API explorer, but you
    will get the idea!

Public API
Explorer: [http://drupal.sandbox.civicrm.org/civicrm/api/explorer](http://drupal.sandbox.civicrm.org/civicrm/api/explorer) (login
as demo/demo)

On top of these tools, your CiviCRM installation will also contain a
directory full of examples on how the API can be used. These examples
will be inside the api/v3 directory and is called 'examples'. You can
also find the examples on GitHub:
[https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples)

There is also the code-level documentation which can be found at
[http://api.civicrm.org/v3/](http://api.civicrm.org/v3/)

Also, the [API Reference](/confluence/display/CRMDOC/API+Reference) page
on this wiki.

Changelog
---------

Any API change you should be aware of will be recorded on this wiki
documentation at [API changes](/confluence/display/CRMDOC/API+changes)

Examples
========

Online Examples
---------------

All the API examples included with CiviCRM core are auto-generated so
the validity of the code is certain. 

|Version|URL|
|:-----------|-------------|
|Upcoming Version|[https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples)|
|CiviCRM 4.5|[https://github.com/civicrm/civicrm-core/tree/4.5/api/v3/examples](https://github.com/civicrm/civicrm-core/tree/4.5/api/v3/examples)|
|CiviCRM 4.2 LTS|[https://github.com/CiviCRM42/civicrm42-core/tree/4.2/api/v3/examples](https://github.com/CiviCRM42/civicrm42-core/tree/4.2/api/v3/examples)|

 

Local Examples
--------------

CiviCRM ships with API examples included in the distribution.  You can
find the examples specific to your installed version at:

<civicrm\_root\>/api/v3/examples

For example, if you have CiviCRM installed with Drupal the location to
the examples would be:
[/path/to/your/drupalroot/sites/all/modules/civicrm/api/v3/examples](http://path/to/your/drupalroot/sites/all/modules/civicrm/api/v3/examples)

Bindings
========

There are at least 5 different ways of using an API function. In each
environment, the API uses the same names for entities, actions, and
parameters, but the syntax is slightly different. Entities are the data
collections you want to operate on, for example Contact, Event or Group.
The actions are the thing you want to do to the entity, for example get,
create, delete or update.

You can use the API: 

1.  as a PHP function, to run your own code on the same server as
    CiviCRM 
2.  via the AJAX interface, to be called from JavaScript code
3.  via the REST\* interface, can be called from another server via
    http/https calls
4.  as a Smarty function to add data to templates 
5.  from drush on the command line for Drupal installations. 

. The following examples show how to search for contacts named "Alice
Roberts".

PHP (procedural) from CiviCRM 4.2 onwards
-----------------------------------------

The PHP procedural API is the canonical API – every other API builds on
top of it. It can be used when writing core code, native extensions
(modules), or CMS extensions (modules). The first example is the
recommended way of calling the API in PHP, which has been backported
into CiviCRM version 4.2 (LTS).

If you forget to include the API call in a try/catch block, an error in the API call will result in a fatal CiviCRM error

```

try{
   $contacts = civicrm_api3('contact', 'get', array(
      'first_name'  =>  'Alice',
      'last_name'   =>  'Roberts'
   ));
}
catch (CiviCRM_API3_Exception $e) {
   $error = $e->getMessage();
}
printf("Found %d item(s)\n", $contacts['count']);

```


Further examples are in the api/v3/examples folder in your install or on
[GitHub](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples).
Note that the examples are all generated by the test suite. If there is
something missing from the examples post on the API forum board and help
us add it to the test suite. This may be a function or a field within
that function.

Before calling the PHP procedural API, one must ***bootstrap*** CiviCRM. If you're writing code for CiviCRM Core or for a native CiviCRM extension, then the bootstrap is handled automatically. If you're writing code for a Drupal module, a Joomla extension, or a standalone script, then see the examples in [Bootstrap Reference](/confluence/display/CRMDOC/Bootstrap+Reference). 

PHP (procedural) up to CiviCRM 4.2
----------------------------------

In the CiviCRM versions before 4.2, you have to include the version
parameter in the parameter array of your API call. In your code you need
to check if the call has been succesfull by testing the 'is\_error'
result element:

````

$apiParams = array(
   'version'    =>  3,
   'first_name' =>  'Alice',
   'last_name'  =>  'Roberts'
);
$apiResult = civicrm_api('Contact', 'Get', $apiParams);
if (!civicrm_error($apiResult)) {
    //rest of your code
}
/*
 * or alternatively
 */
if (!isset($apiResult['is_error']) || $apiResult['is_error'] == 0) {
   // rest of your code
}


````

PHP (object oriented) since CiviCRM 3.4
---------------------------------------

In CiviCRM version 3.4 an API class was introduced, allowing you to call
the CiviCRM API in an Object Oriented way. The class is called
*class.api.php* and can be found in the api directory. It allows you to
call the API like this:

````

require_once 'your/civicrm/folder/api/class.api.php';
$api = new civicrm_api3();
$apiParams = array(
   'first_name'   =>  'Alice',
   'last_name'    =>  'Roberts'
);
if ($api->Contact->Get($apiParams)) {
   //each key of the result array is an attribute of the api
    echo "\n contacts found ".$api->count;
   'contact_type'=>'Individual','return'=>'sort_name,current_employer')) {
} else {
    echo $api->errorMsg();
} 


````
 

If you call the API in the object oriented fashion, you do not have to
specify 'version' as a parameter

REST
----

````javascript

// For external services
http://www.example.com/sites/all/modules/civicrm/extern/rest.php
  ?api_key=t0ps3cr3t
  &key=an07h3rs3cr3t
  &json=1
  &debug=1
  &version=3
  &entity=Contact
  &action=get
  &first_name=Alice
  &last_name=Roberts
 
// For sessions already authenticated by the CMS (e.g. AJAX)
http://www.example.com/civicrm/ajax/rest
  ?json=1
  &debug=1
  &version=3
  &entity=Contact
  &action=get
  &first_name=Alice
  &last_name=Roberts

````

Obviously you should substitute your site in! You can explore the syntax
and options available using the [api
explorer](http://sandbox.civicrm.org/civicrm/ajax/doc#explorer) (also on
your site!)

Please note that the REST interface is subject to [API Security](/confluence/display/CRMDOC/API+Security).

For more details, see [REST
interface](http://wiki.civicrm.org/confluence/display/CRMDOC/REST+interface). 

AJAX
----

````
CRM.api3('entity', 'action', [params], [statusMessage]);

````

For more details, see [AJAX
Interface](/confluence/display/CRMDOC/AJAX+Interface).

The AJAX interface is automatically available for web-pages generated through CiviCRM (such as ***standard CiviCRM web-page****s***, CiviCRM ***extensions***, and custom CiviCRM ***templates***).
                                                            
The AJAX interface could be made available to other parts of the same website (e.g. a drupal module or wordpress widget) by calling CRM\_Core\_Resources::singleton()-\>addCoreResources() from php. Please note that the AJAX interface is subject to [API Security](/confluence/display/CRMDOC/API+Security) and [Same Origin Policy](http://en.wikipedia.org/wiki/Same_origin_policy). To use it from an external site or application, see REST interface documentation.

Smarty
------

````

{crmAPI var="myContactList" entity="Contact" action="get" version="3" first_name="Alice" last_name="Roberts" }
Found {$myContactList.count} item(s).

````


The smarty call is to add extra information, therefore *create* or
*delete* actions don't make sense in this case.

For more details, see [Smarty API
interface](/confluence/display/CRMDOC/Smarty+API+interface).

Drush
-----

````

## To run on the default Drupal site
drush civicrm-api contact.get first_name=Alice last_name=Roberts
 
## To run on Drupal multisite, specify the site name
drush -l www.example.com civicrm-api contact.get first_name=Alice last_name=Roberts

````


Request Format
==============

Every API call consists of three elements:

-   **Entity name**: a string such as "Contact" or "Activity"
-   **Action name**: a string such as "create" or "delete"
-   **Parameters**: an associative-array (such as the first-name and
    last-name of a new contact record); this varies depending on the
    entity name

Entities
========

There are many entities supported by the CiviCRM API, and the list
expands in every release. For current details in your version, see the
"Documentation" section; in particular, see the "API Explorer" and the
API examples.

For demonstration, consider a few commonly-used entities:


| Entity                   | Description              | Example Parameters       |
|--------------------------|--------------------------|--------------------------|
| Contact                  | An individual, <br /> organization, or <br />house-hold.         | “contact\_type”,<br /> “first\_name”,  <br />“last\_name”, <br />“preferred\_language”       |
| Activity                 | An phone call, meeting,<br /> or email message. that <br /> has occurred (or will <br /> occur) at a specific <br /> date and time| “activity\_type\_id”, <br /> “source\_contact\_id”, <br /> “assignee\_contact\_id”    |
| Address                  | A street-address related <br /> to a contact. | “contact\_id”,  <br /> “street\_address”, <br /> “city”,  <br /> “state\_province\_id”, <br /> "country\_id’     |


Actions
=======

Most entities support the following actions:


create
------

Insert or update one record. (Note: If an *"id*" is specified, then an
existing record will be modified.)

delete
------

Delete one record. (Note: Requires an explicit "*id*".  Note: if you
want to skip the 'recycle bin' for entities that support undelete (e.g.
contacts) you should set \$param['skip\_undelete'] =\> 1);

get
---

Search for records

getsingle
---------

Search for records and return the first or only match. (Note: This
returns the record in a simplified format which is easy to use)

getvalue
--------
Does a **getsingle** & returns a single value - you need to also set

````
$param['return'] => 'fieldname'
````

getcount
--------
Search for records and return the quantity. (Note: In many cases in
early versions queries are limited to 25 so this may not always be
accurate)

getrefcount
-----------

Counts the number of references to a record

getfields
---------

Fetch entity metadata, i.e. the list of fields supported by the entity

getlist
-------

Used for autocomplete lookups by the
[entityRef](/confluence/display/CRMDOC/EntityRef+Fields) widget

getoptions
----------

Returns the options for a specified field e.g.
````
civicrm_api3(
  'contact',
  'getoptions', 
  array('field' => 'gender_id')
  ); 
````

returns

````
array(
  1 => 'Female', 
  2 => 'Male', 
  3 => 'Transgender'
)
````

replace
-------

Replace an old set of records with a new or modified set of records.
(For example, replace the set of "Phone" numbers with a different set of
"Phone" numbers.). 

Warning - REPLACE includes an implicit delete - use with care & test well before using in productions

<del>setvalue</del>
-------------------

**Deprecated.** Use the create action with the param 'id' instead.

<del>update</del>
-----------------

**Deprecated.** Use the create action with the param 'id' instead.


Parameters
==========

There are many parameters accepted by the CiviCRM API. Most parameters
depend on the entity – for current details in your version, see the
"Documentation" section; in particular, see the "API Explorer" and the
API examples. However, some parameters are particularly dynamic or
generic; these may not be explained well by the auto-generated
documentation. The format for passing options as parameters using the
REST interface is explained at [REST
interface\#optionsparameters](/confluence/display/CRMDOC/REST+interface#RESTinterface-optionsparameters).


**sequential**
--------------

* **Action**: get

* **Type**: bool

* **Default**: FALSE

* **Compatibility**: ??

* **Description**:

Determine whether the returned records are indexed sequentially (0, 1, 2, ...) or by ID.

Without sequential 
````
$result= civicrm_api('UFMatch','Get', array('version' =>3, 'uf_id' => $user->uid);
$contactid=$contact['values'][$result['id']]['contact_id'] );
````
With sequential
````
$result= civicrm_api('UFMatch','Get', array('version' =>3, 'uf_id' => $user->uid, 'sequential' => 1);
$contactid=$result['values'][0]['contact_id'] );
````

Note that a single record is returned in this example - whenever a single record is returned the entity_id of that record should be in $result['id']
 
### Example: sequential

````

  civicrm_api('UFMatch','Get', array(
 'version' => 3,
 'uf_id' => $user->uid,
 'sequential' => 1,
));

````

**options.limit**
-----------------

* **Action**: get

* **Type**: int

* **Default**: 25

* **Compatibility**: ??

* **Description**:

The maximum number of records to return

### Example: options.limit

````
civicrm_api('UFMatch','Get', array(
  'version' => 3,
  'uf_id' => $user->uid,
  'options' => array(
    'limit' => 25,
  ),
));
````


**options.offset**
------------------

* **Action**: get

* **Type**: int

* **Default**: 0

* **Description**: 

The numerical offset of the first result record

### Example: options.offset

````
civicrm_api('UFMatch','Get', array(
  'version' => 3,
  'uf_id' => $user->uid,
  'options' => array(
    'limit' => 25,
    'offset' => 50,
  ),
));
````

**options.sort**
----------------

* **Action**: get

* **Type**: ??

* **Default**: ??

* **Parameters**: field name, order (ASC / DESC)
* **Description**: 

The criterion to sort on

### Example: options.sort

````
civicrm_api3('Contact', 'get', array(
  'sequential' => 1,
  'return' => "contact_type",
  'options' => array('sort' => "contact_type ASC"),
));
````

**options.reload**
------------------

* **Action**: create

* **Type**: bool

* **Default**: FALSE

* **Compatibility**: v4.4+

* **Description**:

Whether to reload and return the final record after the saving process
completes.

### Example: options.reload

```
civicrm_api('Contact', 'create', array(
  'version' => 3,
  'contact_type' => 'Individual',
  'first_name' => 'First',
  'last_name' => 'Last',
  'nick_name' => 'Firstie',
  'options' => array(
    'reload' => 1,
  ),
));
```

**options.match**
-----------------

* **Action**: create | replace

* **Type**: string | array

* **Default**: NULL

* **Compatibility**: v4.4+

* **Description**:

Attempt to update an existing record by matching against the specified
field.
<br />
If **one** matching record already exists, then the record will be
**updated**.
<br />
If **no** matching record exists, then a new one will be **inserted**.
<br />
If **multiple** matching records exist, then return an **error**.

### Example: options.match

```
civicrm_api('contact', 'create', array(
  'version' => 3,
  'contact_type' => 'Individual',
  'first_name' => 'Jeffrey',
  'last_name' => 'Lebowski',
  'nick_name' => 'The Dude',
  'external_identifier' => '1234',
  'options' => array(
    'match' => 'external_identifier',
  ),
));
```

**options.match-mandatory**
---------------------------

* **Action**: create | replace

* **Type**: string | array

* **Default**: NULL

* **Compatibility**: v4.4+

* **Description**:

Attempt to update an existing record by matching against the specified
field.
<br />
If **one** matching record already exists, then the record will be
updated.
<br />
If **no** matching record exists, then return an **error**.
<br />
If **multiple** matching records exist, then return an **error**.
 
### Example: options.match-mandatory

```
civicrm_api('contact', 'create', array(
  'version' => 3,
  'contact_type' => 'Individual',
  'first_name' => 'Jeffrey',
  'last_name' => 'Lebowski',
  'nick_name' => 'The Dude',
  'external_identifier' => '1234',
  'options' => array(
    'match-mandatory' => 'external_identifier',
  ),
));
```


**Custom Data**
---------------

Custom data attached to entities is referenced by custom\_N where N is
the unique numerical ID for the custom data field.

To set a custom field, or find entities with custom fields of a
particular value, you typically use a parameter like this:

```
$param['custom_N'] => 'value';
```

To return custom data for an entity, you typically pass a param like the
following:

```
$param['return.custom_N'] => 1;
```

*or (depending on which API entity you are querying)*
```
$param['return'] => 'custom_N';
```

*or* 
```
$param['return'] => 'custom_N,custom_O,custom_P';
```

For setting custom date fields, (ie CustomValue create), date format is YmdHis, for example: 20050425000000.

This is just a brief introduction; each API may have different requirements and allow different formats for accessing the custom data.  See the [API function documentation](/confluence/display/CRMDOC/Using+the+API) and also read the comments and documentation in each API php file (under civicrm/CRM/api/v3 in your CiviCRM installation) for exact details, which vary for each API entity and function.

For more details and examples, [see the tutorial on using custom data with the API here](/confluence/display/CRMDOC/Using+Custom+Data+with+the+API).

Response Format
===============

The response from an API call is always an associative-array. The
response format can vary depending on the action, but generally
responses meet one of these two structures:

Success
-------

````
$result['is_error'] = 0
$result['version'] = 2 or 3 as appropriate
$result['count'] = number of records in the 'values' element of the $result array
$result['values'] = an array of records
````

Please note that the **getsingle** response will not have a $result['values'] holding the records, but a $result array with the fields from the selected record. The response $result will only have an  'is\_error' attribute if there actually is an error.


Error
-----

````
$result['is_error'] = 1
$result['error_message'] = An error message as a string.
````

Chaining
========

It is now possible to do two api calls at once with the first call feeding into the second. E.g. to create a contact with a contribution you can nest the contribution create into the contact create. Once the contact has been created it will action the contribution create using the id from the contact create as 'contact\_id'. Likewise you can ask for all activities or all contributions to be returned when you do a get. Some examples will explain

* [https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/ContactCreate.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/ContactCreate.php)

* [https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/ContactGet.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/ContactGet.php)

* [https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArray.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArray.php)

* [https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArrayFormats.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArrayFormats.php)

* [https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArrayValuesFromSiblingFunction.php](https://github.com/civicrm/civicrm-core/blob/master/api/v3/examples/Contact/APIChainedArrayValuesFromSiblingFunction.php)

Note that there are a few supported syntaxes

```
civicrm('Contact', 'Create', 
array(
    'version' => 3, 
    'contact_type' => 'Individual', 
    'display_name' => 'BA Baracus', 
    'api.website.create' => array('url' => 'Ateam.com'));
```

is the same as

````
civicrm('Contact', 'Create', array(
    'version' => 3, 
    'contact_type' => 'Individual', 
    'display_name' => 'BA Baracus', 
    'api.website' => array('url' => 'Ateam.com'));
````

If you have 2 websites to create you can pass them as ids after the '.'
or an array

````
civicrm('Contact', 'Create', array(
    'version' => 3, 
    'contact_type' => 'Individual', 
    'display_name' => 'BA Baracus', 
    'api.website.create'    => array('url' => 'Ateam.com',),
    'api.website.create.2'  => array('url' => 'warmbeer.com', ));

````

OR

````
civicrm('Contact', 'Create', array(
    'version' => 3, 
    'contact_type' => 'Individual', 
    'display_name' => 'BA Baracus', 
    'api.website.create' => array(
        array('url' => 'Ateam.com', ), 
        array('url' => 'warmbeer.com', )));
````

the format you use on the way in will dictate the format on the way out.

Currently this supports any entity & it will convert to 'entity\_id' - ie. a PledgePayment inside a contribution will receive the contribution\_id from the outer call
