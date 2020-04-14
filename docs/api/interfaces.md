# API Interfaces

The API has three main interfaces along with the PHP Code that can be used to access the API.

## Javascript {#javascript}

CiviCRM provides a number of different methods to interact with the API when in javascript code. The most common of these is through the AJAX interface which is usually called using jQuery code. The next most common is through the angular interface with a couple of Node.js interfaces

### Javascript AJAX Interface {:#ajax}

The AJAX interface is one of the more common interfaces used within CiviCRM code. The AJAX interface is most commonly seen when used in javascript code. You can get example AJAX interface code out of the [API Explorer](./index.md#api-explorer) as needed.

#### CRM.api3 / CRM.api4

`CRM.api3` and `CRM.api4` is a javascript method produced by CiviCRM as a thin wrapper around a call to `http://example.org/civicrm/ajax/rest`. The standard format of such an API call can be found under the relevant usage sub-chapter of this documentation

#### WP REST API

`WP REST API` is a method introduced in version 5.25 that uses the WordPress REST API to expose the extern scripts. [This method is documented here](v3/wp-rest.md)

#### Tests

[QUnit](../testing/qunit.md) tests for `CRM.api3` can be found in [/tests/qunit/crm-api3](https://github.com/civicrm/civicrm-core/tree/master/tests/qunit/crm-api3).

You can run the tests within a web browser by visiting `/civicrm/dev/qunit/civicrm/crm-api3` within a CiviCRM [development installation](../tools/civibuild.md).

#### Changes

The recommended AJAX interface has changed between CiviCRM versions as follows:

* version 4.2.x - `cj().crmAPI(...)`
* version 4.3.x - `CRM.api(...)`
* version 4.4.x onwards - `CRM.api3()`

For details see [APIv3 changes](v3/changes.md).

### Javascript AngularJS crmAPI {:#angularjs}

With the advent of AngularJS being introduced into the CiviCRM framework, a service was created `crmApi()` which is a variant of `CRM.api3()` for AngularJS. It should be noted that the results are packaged as "promises" by AngularJS. The crmAPI property can be manipulate to mock responses and also the JSON encoder  uses `angular.toJson()` to correctly handle hidden properties. Examples of use are

```javascript
angular.module('myModule').controller('myController', function(crmApi) {
  crmApi('entity_tag', 'create', {contact_id:123, tag_id:42})
    .then(function(result){
      console.log(result);
    });
});
```

### CiviCRM-CV Node.js binding {#cv-node.js}

This is a tool that aims to work locally with Node.js and integrates into Node.js cv commands which allow for the interaction with a local CiviCRM install. For example you could use it to get the first 25 contacts from the database as follows

```javascript
  var cv = require('civicrm-cv')({mode: 'promise'});
  cv('api contact.get').then(function(result){
    console.log("Found records: " + result.count);
  });
```

You can also use all of CV commands such as getting the vars used to make connection to the CiviCRM instance and other site metadata as follows

```javascript
// Lookup the general site metadata. Return the data synchronously (blocking I/O).

var cv = require('civicrm-cv')({mode: 'sync'});
var result = cv('vars:show');
console.log("The Civi database is " + result.CIVI_DB_DSN);
console.log("The CMS database is " + result.CMS_DB_DSN);
```
More information can be found on the [project page](https://github.com/civicrm/cv-nodejs)

### Javascript Node-CiviCRM package {#node-civicrm}

Node CiviCRM is a Node.js package which allows for the interaction with a CiviCRM instance from a remote server. This uses the Rest API to communicate to CiviCRM. For example to get the first 25 individuals from the database can be done as follows

```javascript
var config = {
  server:'http://example.org',
  path:'/sites/all/modules/civicrm/extern/rest.php',
  key:'your key from settings.civicrm.php',
  api_key:'the user key'
};
var crmAPI = require('civicrm')(config);

crmAPI.get ('contact',{contact_type:'Individual',return:'display_name,email,phone'},
  function (result) {
    for (var i in result.values) {
      val = result.values[i];
     console.log(val.id +": "+val.display_name+ " "+val.email+ " "+ val.phone);
    }
  }
);
```

More information can be found on the [project page](https://github.com/TechToThePeople/node-civicrm)

## REST Interface {:#rest}

The REST interface examples can also be found in the API Explorer as with the AJAX interface. The REST interface works very much like the AJAX interface however there is one major difference. The REST interface requires an `api_key` which is attached to the user that will be performing the action against the system and a `site_key` which is the key stored in `civicrm.settings.php`. There must also be a user account in the relevant content management system for the user associated with the API Key. The main reason for this is that the REST interface unlike the AJAX interface is designed for being accessed from an external site. The REST interface defaults to returning XML data however in your API calls you can request to have it return a JSON formatted version. When calling the REST interface you should do it as a `POST` not as a `GET` request. The only API actions that will work over the GET call would be get actions.

To use the REST interface use something along the line of

```
https://www.example.org/path/to/civi/codebase/civicrm/extern/rest.php?entity=thething&action=doit&key=your_site_key&api_key=the_user_api_key
```
Or if you wish to have it return json do the following

```
https://www.example.org/path/to/civi/codebase/civicrm/extern/rest.php?entity=thething&action=doit&key=your_site_key&api_key=the_user_api_key&json=1
```
You can also access the AJAX Interface from the REST function but only as an XHR call through the browser not as a regular page

```
http://www.example.org/civicrm/ajax/rest?entity=contact&action=get&json=1
```
More information on the security of the AJAX interface and permissions needed can be found on the [Permissions Page](../security/permissions.md) in Security.

Example Outputs from the REST Interface are as follows:

Response on searching for contact

```xml
<?xml version="1.0"?>
<ResultSet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <Result>
    <contact_id>1</contact_id>
    <contact_type>Individual</contact_type>
    <sort_name>Doe, John</sort_name>
    <display_name>John G Doe</display_name>
    <do_not_email>0</do_not_email>
    <do_not_phone>0</do_not_phone>
    <do_not_mail>0</do_not_mail>
    <do_not_trade>0</do_not_trade>
    <is_opt_out>0</is_opt_out>
    <home_URL>[http://www.example.com]</home_URL>
    <preferred_mail_format>Both</preferred_mail_format>
    <first_name>John</first_name>
    <middle_name>G</middle_name>
    <last_name>Doe</last_name>
    <is_deceased>0</is_deceased>
    <email_id>2</email_id>
    <email>jdoe@example.com</email>
    <on_hold>0</on_hold>
  </Result>
  ...
  <Result>
    <contact_id>N</contact_id>
    <contact_type>Individual</contact_type>
    <sort_name>test@example.org</sort_name>
    <display_name>test@example.org</display_name>
    <do_not_email>0</do_not_email>
    <do_not_phone>0</do_not_phone>
    <do_not_mail>0</do_not_mail>
    <do_not_trade>0</do_not_trade>
    <is_opt_out>0</is_opt_out>
    <preferred_mail_format>Both</preferred_mail_format>
    <is_deceased>0</is_deceased>
    <email_id>4</email_id>
    <email>test@example.org</email>
    <on_hold>0</on_hold>
  </Result>
</ResultSet>
```

Response to creating a new contact

```xml
<?xml version="1.0"?>
<ResultSet xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <Result>
    <contact_id>4</contact_id>
    <is_error>0</is_error>
  </Result>
</ResultSet>
```

### Setting up API Keys {:#keys}

Before being able to use the REST Interface you will need to have set up the `CIVICRM_SITE_KEY` and the users `API_KEY`. There are three methods of creating API keys for users

!!! warning
    API keys need to be unique and set on a user with appropriate [permissions](../security/permissions.md).

#### Manual Method

You can enter the key directly in the database. This would be done by the following

```sql
UPDATE civicrm_contact
SET api_key = "your_key_you_made_up"
WHERE id = "id_of_the_contact_you_want_to_update"
```

#### API Key Extension

There is now an [API Key Extension](https://civicrm.org/extensions/api-key) which can help manage API Keys of users. If you have enough permissions after installing the extension you will see an API Key Tab appear on the contact screens. You can then add / edit / delete API keys as necessary. To delete a key just blank the value

#### Using the API Explorer.

As per the [Stack Exchange Thread](http://civicrm.stackexchange.com/questions/9945/how-do-i-set-up-an-api-key-for-a-user) It is possible to set the users API Key through the explorer.

### Options Parameters and Chaining in the REST Interface.

When using options in the REST Interface they need to be in array format. This also applies to chaining of API calls. This will mean that users will need to ensure that the arrays are properly html encoded before sending the request. Examples of using Options and Chaining are below

```
http://example.org/civicrm/extern/rest.php?entity=GroupContact&action=get&group_id=2&options[limit]=25&options[offset]=50
```

```
http://example.org/civicrm/extern/rest.php?entity=Contact&action=create&group_id=2&api.Email.replace[values][params][email]=joeblow@example.com&api.Email.replace[values][params][on_hold]=1
```

## Smarty API Interface

When building smarty templates you might find you may want to do lookups from the database for some reason. This maybe to get most recent contribution or other information from a contact's record if you adding templates on. The format of the Smarty API call is very similar to that of the APIv3 calls.

```smarty
{crmAPI entity="nameobject" method="namemethod" var="namevariable" extraparam1="aa" extraparam2="bb" sequential="1"}
```

The format is as follows:

- `entity` - the content you want to fetch, eg. "contact", "activity", "contribution"...
- `method` - `get`, `getcount`, `search`, `search_count` (it shouldn't be a method that seeks to modify the entity, only to fetch data) - note that `search` and `search_count` are deprecated in APIv3
- `var` - the name of the smarty variable you want to assign the result to (eg the list of contacts)
- `extraparams` (optional) - all the other parameters (as many as you want) are simply used directly as the "params" to the api. cf. the example below
- `sequential` (optional) - indicates whether the result array should be indexed sequentially (1,2,3...) or by returned IDs. Although it is optional, the default was '0' in CiviCRM up to version 4.3 and '1' in 4.4 so it is advisable to fix it as desired.
- `return` (optional) - The convention to define the return attributes (`return.sort_name return.country...`) doesn't work with smarty and is replaced by return="attribute1,attribute2,attribute3.."

For example if you wanted to display a list of contacts

```smarty
{crmAPI entity='contact' action="get" var="contacts" sequential="0"}
<ul>
{foreach from=$contacts.values item=contact}
<li id="contact_{$contact.contact_id}">{$contact.sort_name}</li>
{/foreach}</ul>
```

Or if you wanted to display a contacts Activities

```smarty
{crmAPI entity="activity" action="get" var="activities" contact_id=$contactId sequential="0"}
{foreach from=$activities.values item=activity}
<p>Activity { $activity.subject } is { $activity.status_id } </p>
{/foreach}
```

You can also use the Smarty `print_r` to help debug e.g. in the case above you could call `{$activities|@print_r}`

### Using it for a javascript variable

Instead of displaying the data directly, you might want to use it to initialise a javascript variable. You can now add it directly to the template wihout needing to use the AJAX interface. Which will produce same result but will take less time and server resources

```smarty
<script>
var data={crmAPI entity="contact" action="get" contact_type="Individual" ...};
</script>
```
