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
