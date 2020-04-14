# Backbone Reference

!!! failure "Deprecated"
    CiviCRM no longer recommends using Backbone. This page is here primarily for archival purposes.

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

* [Underscore](http://documentcloud.github.io/underscore/)
    * General utilities for working with objects, arrays, and client-side HTML templates
    * Scope of usage:
        * Profile Designer
        * CiviVolunteer
        * CiviHR

* [Backbone](http://documentcloud.github.io/backbone/)
    * MV* framework. Defines three key base-classes:
        * `Backbone.Model` - (for representing individual data records)
        * `Backbone.Collection` - (for representing a collection of data records)
        * `Backbone.View` - (for rendering markup and responding to events)
    * Scope of usage:
        * Profile Designer
        * CiviVolunteer
        * CiviHR

* [Backbone.Marionette](http://marionettejs.com/)
    * An "opinionated" Backbone framework. It adds more base-classes which significantly reduce the boiler-plate and clutter required for defining & combining normal `Backbone.View` classes.
    * See also:
        * [A simple Backbone.Marionette tutorial](http://davidsulc.com/blog/2012/04/15/a-simple-backbone-marionette-tutorial/) (Blog, Apr 2012)
        * [Tutorial: A full Backbone.Marionette application](http://davidsulc.com/blog/2012/05/06/tutorial-a-full-backbone-marionette-application-part-1/) (Blog, May 2012)
        * [Backbone.Marionette.js: A Simple Introduction](https://leanpub.com/marionette-gentle-introduction) (eBook, July 2013)
    * Scope of usage:
        * Profile Designer
        * CiviVolunteer
        * CiviHR

* [Backbone.ModelBinder](https://github.com/theironcook/Backbone.ModelBinder)
    * A two-way link between Backbone "models" and HTML "forms" – form fields can be initialized using data from models, and models can be updated using form fields.
    * Scope of usage:
        * CiviHR

* [Backbone.Forms](https://github.com/powmedia/backbone-forms)
    * Like `Backbone.ModelBinder`, this can define a two-way link between Backbone "models" and HTML "forms" – however, it goes a step further by auto-generating the HTML form based on the "model schema".
    * Scope of usage:
        * Profile Designer


## CiviCRM Additions

### `CRM.Backbone.trackSaved`

Tracking saved/unsaved status

```javascript
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
```

!!! note
    The `fetch()` and `save()` methods each trigger an AJAX call – ***after completing*** the AJAX call, the save-status will be updated. If you want to update a view based on the save-status, it's best to define a callback for the model's "saved" event. However, you *can* update the view using "success", "error", or "sync" callbacks – but you ***must*** use [`_.defer()`](http://documentcloud.github.io/underscore/#defer) before checking `isSaved()`. For example:

    ```javascript
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
    ```

### `CRM.Backbone.trackSoftDelete`

Using soft deletion

```javascript
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
```

### `CRM.Backbone.sync`

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

### `CRM.Backbone.extend(Model,Collection)`

To define models & collections which APIv3 for persistence, use the
`extendModel()` and `extendCollection()` helpers.

```javascript
var ContactModel = Backbone.Model.extend({
  ...
});
CRM.Backbone.extendModel(ContactModel, "Contact");

var ContactCollection = Backbone.Collection.extend({
  model: ContactModel
});
CRM.Backbone.extendCollection(ContactCollection);
```

Create a new contact record

```javascript
var contact = new ContactModel({
  contact_type: 'Individual',
  first_name: 'Bat',
  last_name: 'Man'
});
contact.save({}, {
  success: function() { ... }
  error: function()  { ... }
});
```

Load a specific contact record

```javascript
var contact = new ContactModel({
  id: 123
});
contact.fetch({
  success: function() { ... }
  error: function()  { ... }
});
```

Load all organizations

```javascript
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
```

Update list of emails for contact #123 (immediately)

```javascript
    // Update the list of email addresses for contact 123
    var emails = new EmailCollection([], {
      crmCriteria: {contact_id: 123}
    });
    emails.fetch(...);

    //...

    // Add an email on client and server (with an immediate AJAX request)
    var email = emails.create({
      contact_id: 123,
      email: 'new-email@example.com'
    }, ...);

    //...

    // Modify an email on client and server (with an immediate AJAX request)
    emails.get(456).set('on_hold', 1);
    emails.get(456).save(...);

    //...

    // Remove an email on client and server (with an immediate AJAX request)
    emails.get(789).destroy(...);
```

Update list of emails for contact #123 (delayed-save)

```javascript
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
```

## Unit Tests

The CiviCRM Backbone plugins are tested with [QUnit](../testing/qunit.md). To run the
unit-tests, use a web-browser to connect to a CiviCRM installation
(`http://local.example.com`) and request the following:

`http://local.example.com/civicrm/tests/qunit/crm-backbone`

The source for the unit-tests are stored in ["tests/qunit/crm-backbone"](https://github.com/civicrm/civicrm-core/tree/master/tests/qunit/crm-backbone).
