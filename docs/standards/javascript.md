# Javascript Reference

## Policy

### Coding standards

Javascript code should follow the same standards as CiviCRM's [PHP coding standards](/standards/php.md). You can use inline tools like [JSHint](http://jshint.com/) to help with the linting of javascript files. If you have buildkit installed JSHint is included as part of [CiviLint](../tools/civilint.md). Adding hints to your code will enable jshint to check the standards: e.g.

Example to tell JSHint Tell jsHint about any globals in use:

```javascript
/*global CRM, ts */
```

### Globals

Declaring a global variable/function is a bad practice in Javascript unless absolutely necessary. Your code should never create globals. In the rare cases that you need to declare variables or functions outside the local scope of your closure, create a namespace within the CRM object

```javascript
CRM.myStuff = {foo: 'bar'};
```

!!! note 
    Due to legacy code written before these standards were adopted, CiviCRM still has quite a few other global variables and functions, they all need to be removed from the global scope. You can help!

CiviCRM Provides two Javascript globals:

* `CRM` - Contains all globally needed variables and functions for CiviCRM. Also contains server-side variables and translations.
* `ts` - Function for translating strings. Signature is identical to its php counterpart.

### Location

Javascript code should only really be found in three main locations

1. Inline scripts should be included in smarty .tpl template files. This should only be done where its limited to a specific page or form. Inline js must be enclosed in smarty `{literal}` tags.
2. For any Javascript that acts as utility function, the files should go in the `js/` folder of the `civicrm-core` repo.
3. AngularJS code that should go in the `ang/` folder.

### Progressive Enhancement

Progressive Enhancement (PE) is a philosophy that the basic functionality of a webpage should not depend on javascript and the site should still be usable if the user has disabled js in their browser. CiviCRM has a 2-part policy regarding this:

* Front-facing pages like contribution pages and event signups should adhere to the standards of PE. All front-end pages should be fully functional with js disabled.
* Back-end pages (which is most of CiviCRM) do not need to be able to run without javascript. When appropriate, a noscript message can be shown to the user e.g. `<noscript>CiviCRM requires javascript. Please enable javascript in your browser and refresh the page.</noscript>`

## jQuery

CiviCRM includes the latest version of jQuery and a number of jQuery plugins. Because most CMSs also use jQuery, CiviCRM creates its own namespace using the `jQuery.noConflict` method.

As a result, under most circumstances there are two versions of jQuery on the page with the following names:

* `CRM.$` - the version of jQuery included with CiviCRM.
* `jQuery` - the version of jQuery included with the CMS.

If your script is placed anywhere in the document body, you should access CiviCRM's jQuery using `CRM.$`.

Exception: CiviCRM reserves a space in the document header where `CRM.$ == jQuery` (and the CMSs version has been temporarily renamed `_jQuery`). This allows us to load 3rd party plugins that depend on the name jQuery. You can do so as well by using `CRM_Core_Resources`. See below.

Note: Some CMSs do not add jQuery to the page, in which case the global jQuery would not exist (except within the header as noted above). Don't rely on it.

Note: Yes, 2 copies of jQuery on the same page is inefficient. There is a solution for Drupal 7 to combine them: [civi_jquery](https://www.drupal.org/project/civi_jquery).

## Adding Javascript Files

CiviCRM contains a Resource controller which allows extensions to add in .js files into pages and forms as is needed. This can also be done through any hook implementation as well.

An Example of adding in a file called bar.js from the extension com.example.foo

```php
CRM_Core_Resources::singleton()->addScriptFile('com.example.foo', 'bar.js');
```

You can also use CRM_Core_Resources to add in inline scripts such as the following
```php
CRM_Core_Resources::singleton()->addScript('alert("hello");');
```

You can also specify other regions of the page to place the script in (the most common reason for this is because jQuery plugins must be added to the "html-header" region). See [Resource Reference](../framework/resources.md) for more details.

## Using CiviCRM Javascript in non CiviCRM pages

If you are working outside the context of CiviCRM pages (e.g. on a Drupal page, WordPress widget, Joomla page, etc) you need to explicitly tell CiviCRM to load its javascript in to the page header. You can add your own scripts as well.

```php
civicrm_initialize();
$manager = CRM_Core_Resources::singleton();
$manager->addCoreResources();
$manager->addScriptUrl(WP_PLUGIN_URL . '/myplugin/myscript.js', 1, 'html-header');
```

Note when your using CiviCRM's resource manager to add scripts to non civicrm pages they need to be put as region 'html-header' because CiviCRM has no control over any of the other regions in non-civicrm pages.

## Enclosing your code

In Javacript all code needs to be closures to create the variable scope. Without closures, all variables would be global and there would be a significant risk of name collisions.

The simplest closure you could write would be:

```javascript
CRM.$(function($) {
  // your code here
});
```
Remember that `CRM.$` is our alias of jQuery. So the first line is shorthand notation for `CRM.$('document').ready(function($) {`
The function receives jQuery as it's param, so now we have access to jQuery as the familiar `$` for use in our code.

If your code needs to work across multiple versions of CiviCRM, where jQuery was the older cj as well as the current CRM.$ you can use:

```javascript
(function($) {
  // your code here
})(CRM.$ || cj);
```

For more examples you can take a look at a [gist](https://gist.github.com/totten/9591b10d4bc09c78108d) from Tim Otten on javascript alternatives. For more information on javascript closures, [here is some further reading](http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth).

## Accessing CiviCRM API from JS

If the current user has sufficient permissions (usually "Access CiviCRM") then your script can call the api and accomplish almost anything in CiviCRM. The syntax is:

```javascript
CRM.api('entity', 'action', {params}, {success: function});
```
For more details, see [AJAX API](../api/interfaces.md#ajax-interface) docs.

## Server-Side Variables

Just as you can dynamically add scripts to the page you can also add variables. They will be added to the CRM object in JSON format. Because this object is shared by many CiviCRM components, you should choose a unique namespace (typically the name of your extension). Example:

```php
// On the Server:
CRM_Core_Resources::singleton()->addVars('myNamespace', array('foo' => 'bar'));
```

```javascript
// In the JS file or inline script
CRM.alert(CRM.vars.myNamespace.foo); // Alerts "bar"
```

## Localization

As with PHP coding, any string that will be displayed to the user should be wrapped in `ts()` to translate the string. e.g. `ts('hello')`.

When your script file is being added to the page by `CRM_Core_Resources` it will be automatically scanned for all strings enclosed in `ts()` and their translations will be added as client-side variables. The javascript `ts()` function will use these localized strings. It can also perform variable substitution. See example below.

!!! note

    Because translated strings are added to the client-side by `CRM_Core_Resources::addScriptFile` method, they will not be automatically added if you include your js on the page any other way. The `ts()` function will still work and perform variable substitution, but the localized string will not be available. There are 3 solutions to this problem depending on your context:
    
    1. If this is an inline script in a smarty template, use the `{ts}` function (see legacy issues below for an example). Note that `{ts}` cannot handle client-side variable substitution, only server-side template variables.
    2. If this is an inline script in a php file, use the php `ts()` function to do your translation and concatenate the result into your script. Or, if you need client-side variable substitution use the 3rd solution:
    3. If this is a javascript file being added to the page in a nonstandard way (or is one of the above two scenarios but you need client-side variable substitution), you could manually add any needed strings using the `CRM_Core_Resources::addString` method

## UI Elements

CiviCRM ships with a number of UI widgets and plugins to create standardized "look and feel" More information can be found in [UI Elements Reference](../framework/ui.md).

## Automated testing

CiviCRM's testing regimen includes:

 * (Linting) [JSHint](#coding-standards)
 * (Unit testing) [Karma and Jasmine](../testing/karma.md)
 <!-- * (End-to-end testing, for AngularJS) [Protractor and Jasmine](/testing/protractor.md) -->
 * (Deprecated; end-to-end testing) [QUnit](../testing/qunit.md)

## Javascript in Markup

Putting javascript code directly into html tags is deprecated. We are migrating our existing code away from this practice.

```html
<a href="#" onclick="someGlobalFunction(); return false;">Click Here</a>
<script type="text/javascript">
  function someGlobalFunction() {
    // code goes here
  }
</script>
```

The above example has several disadvantages:

- It relies on a global function.
- It doesn't allow for separation of concerns between the presentation layer (html) and the business logic (js code).

```js
CRM.$(function($) {
  $('a.my-selector').on('click', function() {
    // code goes here
  });
});
```

!!! note
    Sometimes you want to add a handler to an element that does not exist yet in the markup (or might be replaced), like each row in a datatable. For that use the delegation provided by jQuery's "on" method and attach the handler to some outer container that is going to be there more permanently.

## ClientSide MVC

In the past, PHP-based webapps like CiviCRM have treated javascript as little more than an extension of css. But increasingly they are realizing the potential of Javascript to handle business logic. Javascript can be used to create robust, responsive, user-friendly webapps. But with this complexity comes the need for structure. While CiviCRM has not officially adopted a clientside MVC framework, version 4.3 includes a new UI for editing profiles which was built using Underscore, Backbone and Marionette. And 4.5 includes a new case-configuration interface built on Angular. In 4.6 CiviMail User Interface was re-written in Angular.

More detail can be found in the [Angular reference documents](../framework/angular/index.md)
