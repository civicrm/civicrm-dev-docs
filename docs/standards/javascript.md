# Javascript Reference

## Policy

### Coding standards

Javascript code should follow the same standards as CiviCRM's [PHP coding standards](https://docs.civicrm.org/dev/en/master/standards/php/). You can use inline tools like [JSHint](http://jshint.com/) to help with the linting of javascript files. If you have buildkit installed JSHint is included as part of [CiviLint](https://docs.civicrm.org/dev/en/master/tools/civilint/). Adding hints to your code will enable jshint to check the standards: e.g.

Example to tell JSHint Tell jsHint about any globals in use:
```javascript
/*global CRM, ts */
```
### globals

Declaring a global variable/function is a bad practice in Javascript unless absolutely necessary. Your code should never create globals. In the rare cases that you need to declare variables or functions outside the local scope of your closure, create a namespace within the CRM object

```javascript
CRM.myStuff = {foo: 'bar'}.
```

Note: due to legacy code written before these standards were adopted, CiviCRM still has quite a few other global variables and functions. Aside from the 2 listed below, they all need to be removed from the global scope. You can help!

### Location 

Javascript code should only really be found in three main locations

1 Inline scripts should be included in smarty .tpl template files. This should only be done where its limited to a specific page or form. Inline js must be enclosed in smarty {literal} tags 
2. For any Javascript that acts as untility functions e.g. AJAX or any helper Javascript should go in the js/ folder of civicrm-core repo
3. For all angular javascript code that should go in an ang/ folder

### Progressive Enhancement

PE is a philosophy that the basic functionality of a webpage should not depend on javascript and the site should still be usable if the user has disabled js in their browser. CiviCRM has a 2-part policy regarding this:

* Front-facing pages like contribution pages and event signups should adhere to the standards of PE. All front-end pages should be fully functional with js disabled.
* Back-end pages (which is most of CiviCRM) do not need to be able to run without javascript. When appropriate, a noscript message can be shown to the user e.g. "<noscript>CiviCRM requires javascript. Please enable javascript in your browser and refresh the page.</noscript>"

## Globals

CiviCRM Provides two Javascript globals: 

* CRM - Contains all globally needed variables and functions for CiviCRM. Also contains server-side variables and translations.
* ts - Function for translating strings. Signature is identical to its php counterpart.

## jQuery

CiviCRM includes the latest version of jQuery and a number of jQuery plugins. Because most CMSs also use jQuery, CiviCRM creates its own namespace using the jQuery.noConflict method.

As a result, under most circumstances there are two versions of jQuery on the page with the following names:

* CRM.$ - the version of jQuery included with CiviCRM.
* jQuery - the version of jQuery included with the CMS.

If your script is placed anywhere in the document body, you should access CiviCRM's jQuery using CRM.$.

Exception: CiviCRM reserves a space in the document header where CRM.$ == jQuery (and the CMSs version has been temporarily renamed _jQuery). This allows us to load 3rd party plugins that depend on the name jQuery. You can do so as well by using CRM_Core_Resources. See below.

Note: Some CMSs do not add jQuery to the page, in which case the global jQuery would not exist (except within the header as noted above). Don't rely on it.

Note: Yes, 2 copies of jQuery on the same page is inefficient. There is a solution for Drupal 7 to combine them: https://www.drupal.org/project/civi_jquery
