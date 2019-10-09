# The CiviCRM API

CiviCRM has a stable comprehensive **API** (Application Programming Interface) that can be used to access and manage data in CiviCRM. The API is the recommended way for any CiviCRM extension, CMS module, or external program to interact with CiviCRM.

Utilizing the API is superior to accessing core functions directly (e.g.calling raw SQL, or calling functions within the BAO files) because the API offers a consistent interface to CiviCRM's features. It is designed to function predictably with every new release so as to preserve backwards compatibility of the API for several versions of CiviCRM. If you decide to use other ways to collect data (like your own SQL statements), you risk running into future problems when changes to the schema andBAO arguments inevitably occur.

The best place to begin working with the API is your own ***test*** install of CiviCRM, using the API explorer and the API parameter list.

For help creating your own API custom calls, see [civix generate:api](/extensions/civix.md#generate-api)

## API explorer

The API explorer is a powerful GUI tool for building and executing API calls.

To access the APIv3 explorer:

1. Go to any CiviCRM site
    * This can even be the [demo site](http://dmaster.demo.civicrm.org/).
1. Within the CivCRM menu, go to **Support > Developer > APIv3 Explorer** or go to the URL `/civicrm/api`.

To access the APIv4 explorer:

1. Go to any CiviCRM site
    * This can even be the [demo site](http://dmaster.demo.civicrm.org/).
1. Within the CivCRM menu, go to **Support > Developer > APIv4 Explorer** or go to the URL `/civicrm/api4`.

!!! warning
    The API explorer actually executes real API calls. It can modify data! So if you execute a `Contact` `delete` call, it will really delete the contact. As such, any experimenting is best done within a test site.

You can select the entity you want to use, for example `Contact` and the action you want to perform, for example `Get`. The API explorer will show you the specific code necessary to execute the API call you have been testing.

## API parameter documentation

From the API explorer, you can get documentation on how to construct your API query. This is done either in the screen as you fill out the GUI to create your API call or in the v3 Explorer there are docs under the Code Docs tab which will point at the relevant aspects of the v3 code base that run the API calls.

## API examples

Within the API Explorer you will be able to attain an example of the code that you should write to call the API. In APIv3, you can also access epscific examples of some API calls from the Examples tab within the explorer. You can also [explore these examples on GitHub](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples).

### API Examples in your extensions

From CiviCRM v5.8 the APIv3 explorer will now be able to show examples that are stored in your extension. The only requirement is that they are found in the same sort of directory structure as core e.g. in `<yourextension>/api/v3/examples/<entity>/<file>`

## Changelog

All important changes made to the APIv3 are recorded in [APIv3 changes](/api/v3/changes.md).
