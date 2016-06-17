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

The following examples show how to search for contacts named "Alice
Roberts".

PHP (Procedural)
----------------------

The PHP procedural API is the canonical API – every other API builds on
top of it. It can be used when writing core code, native extensions
(modules), or CMS extensions (modules).

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

<h3>Errors and Exceptions</h3>

There are two common ways to invoke the procedural API. They are nearly
identical, except in the handling of the errors:

 * `civicrm_api3($entity, $action, $params)`: This is the most common
   approach. If the API call encounters an error, then
   it will throw a PHP exception. It is recommended for most use cases
   (application logic, form logic, data-integrations, etc).
 * `civicrm_api($entity, $action, $params)`: This is the older and
   less common approach, and it is useful for implementing generic
   bindings (such as REST, SOAP, AJAX, Smarty, etal). Instead of
   throwing an exception, it returns an array with the properties
   `is_error` and `error_message`.

<h3>Bootstrap</h3>

Before calling the PHP procedural API, one may need to ***bootstrap*** CiviCRM. If you're writing code for CiviCRM Core or for a native CiviCRM extension, then the bootstrap is handled automatically. If you're writing code for a Drupal module, a Joomla extension, or a standalone script, then see the examples in [Bootstrap Reference](/confluence/display/CRMDOC/Bootstrap+Reference). 


PHP (Object Oriented Client)
----------------------------

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
