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

Also, the [API Reference](https://wiki.civicrm.org/confluence/display/CRMDOC/API+Reference) page
on this wiki.

Changelog
---------

Any API change you should be aware of will be recorded on this wiki
documentation at [API changes](https://wiki.civicrm.org/confluence/display/CRMDOC/API+changes)

Examples
========

<h3>Online Examples</h3>

All the API examples included with CiviCRM core are auto-generated so
the validity of the code is certain. 

|Version|URL|
|:-----------|-------------|
|Upcoming Version|[https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples)|
|CiviCRM 4.5|[https://github.com/civicrm/civicrm-core/tree/4.5/api/v3/examples](https://github.com/civicrm/civicrm-core/tree/4.5/api/v3/examples)|
|CiviCRM 4.2 LTS|[https://github.com/CiviCRM42/civicrm42-core/tree/4.2/api/v3/examples](https://github.com/CiviCRM42/civicrm42-core/tree/4.2/api/v3/examples)|

 

<h3>Local Examples</h3>

CiviCRM ships with API examples included in the distribution.  You can
find the examples specific to your installed version at:

<civicrm\_root\>/api/v3/examples

For example, if you have CiviCRM installed with Drupal the location to
the examples would be:
[/path/to/your/drupalroot/sites/all/modules/civicrm/api/v3/examples](http://path/to/your/drupalroot/sites/all/modules/civicrm/api/v3/examples)
