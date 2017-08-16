# Backbone Reference

<div class="panelMacro">

+--+
|  |
+--+

</div>



<span id="BackboneReference-status"
class="confluence-anchor-link"></span>

<div class="panel"
style="background-color: #FFFFCE;border-color: #000;border-style: solid;border-width: 1px;">

<div class="panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;border-bottom-color: #000;background-color: #F7D6C1;">

**Table of Contents**

</div>

<div class="panelContent" style="background-color: #FFFFCE;">

<div>

-   [Background](#BackboneReference-Background)
-   [Examples](#BackboneReference-Examples)
-   [External Packages](#BackboneReference-ExternalPackages)
-   [CiviCRM Additions](#BackboneReference-CiviCRMAdditions)

<!-- -->

-   [CRM.Backbone.trackSaved](#BackboneReference-CRM.Backbone.trackSaved)
-   [CRM.Backbone.trackSoftDelete](#BackboneReference-CRM.Backbone.trackSoftDelete)
-   [CRM.Backbone.sync](#BackboneReference-CRM.Backbone.sync)
-   [CRM.Backbone.extend(Model,Collection)](#BackboneReference-CRM.Backbone.extend(Model,Collection))

<!-- -->

-   [Unit Tests](#BackboneReference-UnitTests)

</div>

</div>

</div>

## Background

Backbone is a model-view (MV) framework for Javascript which follows a
minimalist, plugin-oriented architecture: at its core, Backbone defines
a minimal set of classes and utilities for building user-interfaces with
object-oriented Javascript. Like the Drupal and jQuery communities, the
Backbone community has a wide range of plugins to address missing
functionality.

## Examples

Backbone is currently used in the following parts of CiviCRM:

-   Profile Designer (v4.3+): Drag/drop interface for creating
    profile forms. (At time of writing, usable with CiviSurvey)
-   CiviVolunteer Extension (v4.4+): Drag/drop interface for managing
    volunteer assignments
-   CiviHR Extension (v4.4+): Split-pane interface for editing "Jobs"
    and related records

## External Packages



<div class="table-wrap">

+--------------------------+--------------------------+--------------------------+
| Package                  | Description              | Scope of Usage           |
+==========================+==========================+==========================+
| [Underscore](http://docu | General utilities for    | Profile Designer         |
| mentcloud.github.io/unde | working with objects,    |                          |
| rscore/){.external-link} | arrays, and client-side  | CiviVolunteer            |
|                          | HTML templates           |                          |
|                          |                          | CiviHR                   |
+--------------------------+--------------------------+--------------------------+
| [Backbone](http://docume | MV* framework. Defines  | Profile Designer         |
| ntcloud.github.io/backbo | three key base-classes:  |                          |
| ne/){.external-link}     |                          | CiviVolunteer            |
|                          | -   Backbone.Model (for  |                          |
|                          |     representing         | CiviHR                   |
|                          |     individual           |                          |
|                          |     data records)        |                          |
|                          | -   Backbone.Collection  |                          |
|                          |     (for representing a  |                          |
|                          |     collection of        |                          |
|                          |     data records)        |                          |
|                          | -   Backbone.View (for   |                          |
|                          |     rendering markup and |                          |
|                          |     responding           |                          |
|                          |     to events)           |                          |
+--------------------------+--------------------------+--------------------------+
| [Backbone.Marionette](ht | An "opinionated"         | Profile Designer         |
| tp://marionettejs.com/){ | Backbone framework. It   |                          |
| .external-link}          | adds more base-classes   | CiviVolunteer            |
|                          | which significantly      |                          |
|                          | reduce the boiler-plate  | CiviHR                   |
|                          | and clutter required for |                          |
|                          | defining & combining     |                          |
|                          | normal Backbone.View     |                          |
|                          | classes.                 |                          |
|                          |                          |                          |
|                          | See also:                |                          |
|                          |                          |                          |
|                          | -   [A simple            |                          |
|                          |     Backbone.Marionette  |                          |
|                          |     tutorial (Blog,      |                          |
|                          |     Apr 2012)](http://da |                          |
|                          | vidsulc.com/blog/2012/04 |                          |
|                          | /15/a-simple-backbone-ma |                          |
|                          | rionette-tutorial/){.ext |                          |
|                          | ernal-link}              |                          |
|                          | -   [Tutorial: A full    |                          |
|                          |     Backbone.Marionette  |                          |
|                          |     application (Blog,   |                          |
|                          |     May 2012)](http://da |                          |
|                          | vidsulc.com/blog/2012/05 |                          |
|                          | /06/tutorial-a-full-back |                          |
|                          | bone-marionette-applicat |                          |
|                          | ion-part-1/){.external-l |                          |
|                          | ink}                     |                          |
|                          | -   [Backbone.Marionette |                          |
|                          | .js:                     |                          |
|                          |     A Simple             |                          |
|                          |     Introduction (eBook, |                          |
|                          |     July 2013)](https:// |                          |
|                          | leanpub.com/marionette-g |                          |
|                          | entle-introduction){.ext |                          |
|                          | ernal-link}              |                          |
+--------------------------+--------------------------+--------------------------+
| [Backbone.ModelBinder](h | A two-way link between   | CiviHR                   |
| ttps://github.com/theiro | Backbone "models" and    |                          |
| ncook/Backbone.ModelBind | HTML "forms" – form      |                          |
| er){.external-link}      | fields can be            |                          |
|                          | initialized using data   |                          |
|                          | from models, and models  |                          |
|                          | can be updated using     |                          |
|                          | form fields.             |                          |
+--------------------------+--------------------------+--------------------------+
| [Backbone.Forms](https:/ | Like                     | Profile Designer         |
| /github.com/powmedia/bac | Backbone.ModelBinder,    |                          |
| kbone-forms){.external-l | this can define a        |                          |
| ink}                     | two-way link between     |                          |
|                          | Backbone "models" and    |                          |
|                          | HTML "forms" – however,  |                          |
|                          | it goes a step further   |                          |
|                          | by auto-generating the   |                          |
|                          | HTML form based on the   |                          |
|                          | "model schema".          |                          |
+--------------------------+--------------------------+--------------------------+

</div>

## CiviCRM Additions

#### CRM.Backbone.trackSaved

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Tracking saved/unsaved status**

</div>

<div class="codeContent panelContent">

    // Setup model class
    var MyModel = Backbone.Model.extend({...});
    CRM.Backbone.trackSaved(MyModel);


    // Use the class
    var model = new MyModel({id: 123});
    model.fetch();
    // assert: model.isSaved() === true -- because our client matches server
    model.set('property', 'value'):
    // assert: model.isSaved() === false -- because our client deviates from server
    // event: saved(model,is_saved)
    model.save();
    // assert: model.isSaved() === true -- because our client matches server
    // event: saved(model,is_saved)

</div>

</div>

Note: The fetch() and save() methods each trigger an AJAX call –
***after completing*** the AJAX call, the save-status will be updated.
If you want to update a view based on the save-status, it's best to
define a callback for the model's "saved" event. However, you *can*
update the view using "success", "error", or "sync" callbacks – but you
***must*** use
[_.defer()](http://documentcloud.github.io/underscore/#defer){.external-link}
before checking isSaved():

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Using isSaved() with success & error**

</div>

<div class="codeContent panelContent">

    var model = new MyModel({
      property1: value1,
      property2: value2,
      ...
    });
    model.save({}, {
      success: function(model) {
        // model.isSaved() may return weird results, so defer a moment...
        _.defer(function(){
          console.log('Success! isSaved()=' + model.isSaved()); // displays "true"
        });
      },
      error: function(model) {
        // model.isSaved() may return weird results, so defer a moment...
        _.defer(function(){
          console.log('Disaster! isSaved()=' + model.isSaved()); // displays "false"
        });
      }
    });

</div>

</div>

#### CRM.Backbone.trackSoftDelete

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Using soft deletion**

</div>

<div class="codeContent panelContent">

    var MyModel = Backbone.Model.extend({...});
    CRM.Backbone.trackSoftDelete(MyModel);

    // Create an example model
    var model = new MyModel({id: 123});
    // assert: model.isSoftDeleted() === false


    // Flag a model for deletion
    model.setSoftDeleted(true);
    // assert: model.isSoftDeleted() === true
    // event: softDeleted(model, is_soft_deleted)


    // Remove a deletion flag
    model.setSoftDeleted(false);
    // assert: model.isSoftDeleted() === false
    // event: softDeleted(model, is_soft_deleted)


    // Perform save or deletion (depending on whether isSoftDeleted())
    model.save();
    // If isSoftDeleted()==false, call normal save()
    // If isSoftDeleted()==true, call destroy() instead

</div>

</div>



#### CRM.Backbone.sync

The Backbone.sync framework is generally used for loading and saving
data through web-services. The default implementation of Backbone.sync
is heavily driven by URLs – to indicate that a client-side Model (or
Collection) is tied to a server resource, one sets the "url" property on
the Model (or Collection) and pays careful attention to the path and
parameters in the URL. In APIv3, we focus less on URLs and more on the
triplet of "*entity,action,params*". To use CRM.Backbone.sync, one omits
the "url" and instead adds the properties "crmEntityName" (which
corresponds to APIv3's *entity*) and "toCrmCriteria()" (which
corresponds to APIv3's *params*).

Using CRM.Backbone.sync requires setting multiple properties on each
class. To ensure that these are handled correctly, one shouldn't call
them directly. Instead, use the CRM.Backbone.extendModel and
CRM.Backbone.extendCollection helpers to mix-in the necessary
properties.

#### CRM.Backbone.extend(Model,Collection)

To define models & collections which APIv3 for persistence, use the
extendModel() and extendCollection() helpers.

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Define model & collection classes**

</div>

<div class="codeContent panelContent">

    var ContactModel = Backbone.Model.extend({
      ...
    });
    CRM.Backbone.extendModel(ContactModel, "Contact");

    var ContactCollection = Backbone.Collection.extend({
      model: ContactModel
    });
    CRM.Backbone.extendCollection(ContactCollection);

</div>

</div>

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Create a new contact record**

</div>

<div class="codeContent panelContent">

    var contact = new ContactModel({
      contact_type: 'Individual',
      first_name: 'Bat',
      last_name: 'Man'
    });
    contact.save({}, {
      success: function() { ... }
      error: function()  { ... }
    });

</div>

</div>

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Load a specific contact record**

</div>

<div class="codeContent panelContent">

    var contact = new ContactModel({
      id: 123
    });
    contact.fetch({
      success: function() { ... }
      error: function()  { ... }
    });

</div>

</div>

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Load all organizations**

</div>

<div class="codeContent panelContent">

    var contacts = new ContactCollection([], {
      // crmCriteria defines query parameters per APIv3
      crmCriteria: {contact_type: 'Organization'}
    });
    contacts.fetch({
      success: function() {
        console.log("Loaded " + contacts.length + " contact(s)");
      },
      error: function() {
        console.log("Failed to load contacts");
      }
    });

</div>

</div>

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Update list of emails for contact #123 (immediately)**

</div>

<div class="codeContent panelContent">

    // Update the list of email addresses for contact 123
    var emails = new EmailCollection([], {
      crmCriteria: {contact_id: 123}
    });
    emails.fetch(...);

    ...

    // Add an email on client and server (with an immediate AJAX request)
    var email = emails.create({
      contact_id: 123,
      email: 'new-email@example.com'
    }, ...);


    ...

    // Modify an email on client and server (with an immediate AJAX request)
    emails.get(456).set('on_hold', 1);
    emails.get(456).save(...);

    ...

    // Remove an email on client and server (with an immediate AJAX request)
    emails.get(789).destroy(...);

</div>

</div>

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Update list of emails for contact #123 (delayed-save)**

</div>

<div class="codeContent panelContent">

    // Update the list of email addresses for contact 123
    var emails = new EmailCollection([], {
      crmCriteria: {contact_id: 123}
    });
    emails.fetch(...);

    ...

    // Add a new email on client (but don't send to server yet)
    var email = new EmailModel({
      contact_id: 123,
      email: 'another-email@example.com'
    });
    emails.add(model);

    // Update an email on client (but don't send to server yet)
    emails.get(456).set('on_hold', 1);

    // Remove an email on client (but don't send to server yet)
    emails.get(789).setSoftDeleted(true);

    ...

    // Send all changes to all emails to server (with one AJAX call)
    emails.save(...);

    // NOTE: Use this carefully. This will perform INSERTs, UPDATEs, and/or DELETEs
    // to make the email list match on the client and server. The server will use
    // an algorithm like this:
    //
    // 1. Accept list of records from client.
    // 2. Query list of pre-existing emails on server (matching crmCriteria).
    // 3. Identify records in BOTH client and server. UPDATE them.
    // 4. Identify records in CLIENT but not server. INSERT them.
    // 5. Identify records in SERVER but not client. DELETE them.
    //
    // This is generally appropriate when you know the client has the full,
    // proper collection -- e.g. it's appropriate with the collection of "Email",
    // "Phone", or "Address" records of one contact. However, it's not appropriate
    // for a collection of "Activities" (because concurrent processes may add new
    // activities that are unknown the client -- and those records shouldn't be
    // deleted). If you have a use-case that needs a different / more nuanced
    // reconciliation strategy, post to the forum to discuss.

</div>

</div>

## Unit Tests

The CiviCRM Backbone plugins are tested with qUnit. To run the
unit-tests, use a web-browser to connect to a CiviCRM installation
("http://local.example.com") and request
"*http://local.example.com*/**civicrm/tests/qunit/crm-backbone**"

The source for the unit-tests are stored in "tests/qunit/crm-backbone"
(e.g.
<https://github.com/civicrm/civicrm-core/tree/master/tests/qunit/crm-backbone>).
