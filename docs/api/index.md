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

The API explorer is a powerful GUI tool for building and executing API calls.

To access the API explorer:

1. Go to any CiviCRM site
    * This can even be the [demo site](http://dmaster.demo.civicrm.org/).
1. Within the CivCRM menu, go to **Support > Developer > API Explorer** or go to the URL `/civicrm/api`.

!!! warning
    The API explorer actually executes real API calls. It can modify data! So if you execute a `Contact` `delete` call, it will really delete the contact. As such, any experimenting is best done within a test site.

You can select the entity you want to
use, for example `Contact` and the action you want to perform, for
example `Get`. The API explorer
will show you the specific code necessary to execute the API call you
have been testing.

## API parameter documentation

From the API explorer, you can click on the **Code Docs** tab to find documentation for each API entity/action. You will first get a list of all the API entities. If you click on an entity you will get a list of parameters that are available for that specific entity, with the type of the parameter. This can be very useful if you want to check what you can retrieve with the API and what parameters you can use to refine your get
action or complete your create or update action.

## API examples

From the API explorer, you can click on the **Examples** tab to find examples of API calls which are based on automated tests within the source code. You can also [explore these examples on GitHub](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples).

## Changelog

All important changes made to the API are be recorded on the wiki at:
[API changes](/api/changes.md)
