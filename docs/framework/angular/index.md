# AngularJS: Overview

AngularJS is a client-side framework for development of rich web
applications.  The core CiviCRM application uses AngularJS for several
administrative screens, and extensions increasingly use AngularJS for
"leaps" that add or replace major parts of the application.

This documentation aims to explain how AngularJS works within a CiviCRM
context.


## AngularJS versions {:#versions}

* CiviCRM use AngularJS 1.x which has documentation at [docs.**angularjs.org**](https://docs.angularjs.org)
* In version 2.x (and onwards) the framework is just called "Angular" and is a [significantly  different](https://angular.io/guide/ajs-quick-reference) framework from 1.x. The *Angular* website is *angular.io*, which you should steer clear of while learning *AngularJS*.


!!! tip

    To determine the specific version of AngularJS used within your site:

    1. Go to the default Angular *base page* for your site at `http://example.org/civicrm/a`
    1. Open a browser console
    1. Evaluate `angular.version` within the console





## Two cultures

CiviCRM is an extensible PHP application (similar to Drupal, Joomla, or
WordPress).  In this culture, the common expectation is that an
*administrator* installs the main application.  To customize it, they
download, evaluate, and configure a set of business-oriented modules.  The
administrator's workflow is dominated by web-based config screens and CLI
commands.

AngularJS is a frontend, Javascript development framework.  In this culture,
the expectation is that a *developer* creates a new application.  To
customize it, they download, evaluate, and configure a set of
function-oriented libraries.  The developer's workflow is dominated by CLI's
and code.

The CiviCRM-AngularJS integration must balance the expectations of these
two cultures.  The balance works as follows:

 * __Build/Activation__: The process of building or activating modules
   should meet administrators' expectations.  It should be managed by the
   PHP application.  (This means that you won't see `gulp` or `grunt`
   orchestrating the final build -- because PHP logic fills that role.)
 * __Frontend Code uses Angular (JS+HTML)__: The general structure of the
   Javascript and HTML files should meet the frontend developers'
   expectations.  These files should be grounded in the same notations and
   concepts as the upstream AngularJS framework.  (This means that AngularJS
   is not abstracted, wrapped, or mapped by an intermediary like
   HTML_QuickForm, Symfony Forms or Drupal Form API.)
 * __Backend Code uses Civi API (PHP)__: The general structure of
   web-services should meet the backend developers' expectations.  These are
   implemented in PHP (typically with CiviCRM APIv3).

## Basics

AngularJS is a client-side Javascript framework, and it interacts with
CiviCRM in two major ways.  To see this, let's consider an example AngularJS
page -- it's an HTML document that looks a lot like this:

```html
<!-- URL: https://example.org/civicrm/a -->
 1: <html>
 2: <head>
 3:   <link rel="stylesheet" href="**all the CSS files**" />
 4:   <script type="text/javascript" src="**all the Javascript files**"></script>
 5:   <script type="text/javascript">var CRM = {**prefetched settings/data**};</script>
 6: </head>
 7: <body>
 8:   <div>...site wide header...</div>
 9:   <div ng-app="crmApp"></div>
10:   <div>...site wide footer...</div>
11: </body>
12: </html>
```

The first interaction comes when CiviCRM generates the initial HTML page:

 * CiviCRM listens for requests to the path `civicrm/a`. (It does this in a
   way which is compatible with multiple CMSs -- Drupal, Joomla, WordPress, etc.)
 * CiviCRM builds the list of CSS/JS/JSON resources in lines 3-5.  (It does this in a
   way which allows extensions to add new CSS/JS/JSON. See also:
   [Resource Reference](../../framework/resources.md).)
 * CiviCRM ensures that the page includes the site-wide elements, such as
   lines 8 and 10. (It does this in a way which is compatible with multiple CMSs.)

Once the page is loaded, it works just like any AngularJS 1.x application.
It uses concepts like `ng-app`, "module", "directive", "service", "component", and
"partial".

!!! seealso "Read more about AngularJS 1.x"
    A good resource for understanding AngularJS concepts is [the
    official AngularJS tutorial](https://code.angularjs.org/1.5.11/docs/tutorial).

The second interaction comes when the AngularJS application loads or stores
data.  This uses the CiviCRM API.  Key concepts in CiviCRM API include
"entity", "action", "params", the "API Explorer", and the bindings for PHP/Javascript/CLI.

!!! seealso "Read more about CiviCRM API"
    A good resource for understanding CiviCRM API concepts is the [APIv3:
    Intro](../../api/index.md).

In the remainder of this document, we'll try to avoid in-depth discussion
about the internals of AngularJS 1.x or APIv3.  You should be able to follow
the discussion if you have a beginner-level understanding of both.
