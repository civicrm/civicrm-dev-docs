# The CiviCRM API

CiviCRM has a stable comprehensive **API** (Application Programming
Interface) that can be used to access and manage data in CiviCRM. The
API is the recommended way for any CiviCRM extension, CMS module, or
external program to interact with CiviCRM.

Utilizing the API is superior to accessing core functions directly (e.g.
calling raw SQL, or calling functions within the BAO files)
because the API offers a consistent interface to CiviCRM's features. It is
designed to function predictably with every new release so as to preserve
backwards compatibility of the API for several versions of CiviCRM. If
you decide to use other ways to collect data (like your own SQL statements),
you risk running into future problems when changes to the schema and
BAO arguments inevitably occur.

The best place to begin working with the API is your own ***test*** install of
CiviCRM, using the API explorer and the API parameter list.


## API explorer

The API explorer gives you the possibility to actually
try out the API in action and is available at

```text
http://[CIVICRM\_URL]/civicrm/api/explorer
```

You can select the entity you want to
use, for example `Contact` and the action you want to perform, for
example `Get`. Again, be careful as the API explorer will actually
perform your actions! So if you delete a contact to check what API
call to use, it will really delete the contact. The API explorer
will show you the specific code necessary to execute the API call you
have been testing.

Try out the [API explorer on the demo site], *after you login as demo/demo*.

[API explorer on the demo site]: http://drupal.sandbox.civicrm.org/civicrm/api/explorer


## API parameter list

The API parameter list shows all available entities which
can be manipulated by the API and is available at:

```text
http://[CIVICRM_URL]/civicrm/api/doc
```

You will first get a list of all the API entities.
If you click on an entity you will get a list of parameters that are
available for that specific entity, with the type of the parameter.
This can be very useful if you want to check what you can retrieve
with the API and what parameters you can use to refine your get
action or complete your create or update action.


## API Examples

CiviCRM ships with API examples included in the distribution. You can
find the examples specific to your installed version at:

`<civicrm_root>/api/v3/examples`

[Explore these examples on GitHub](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples)


## Changelog

All important changes made to the API are be recorded on the wiki at:
[API changes](https://wiki.civicrm.org/confluence/display/CRMDOC/API+changes)
