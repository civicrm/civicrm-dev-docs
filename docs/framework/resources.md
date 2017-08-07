# Resources Reference

## Introduction

Resources such as Javascript files or inline scripts or CSS Style directives are added to various Regions on the page. The method of altering or adding content to regions is using the [Regions Manager](https://wiki.civicrm.org/confluence/display/CRMDOC/Region+Reference). This guide focuses on the Resource Manger which is a wrapper around the Region Manager.

iResources can be static files such as images, stylesheets or JavaScript files, or dynamically generated inline scripts and stylesheet, or JavaScript variables. Some resources require special handling in the HTML header region. As of CiviCRM 4.2, the class `CRM_Core_Resources` is available for linking resources and managing the HTML HEAD – this API works for both CiviCRM core as well as extensions.

!!!note
The following examples assume that you wish to use resources provided by the extension "com.example.foo". To use resources from your own extension, substitute appropriately. To use files provided by CiviCRM core, use the placeholder "civicrm". If you are working outside CiviCRM Core or natvie extensions, You can still use the REsource Manager but you will need to ensure that CiviCRM is bootsrapped. See [Bootstraping guide](/framework/bootstrap/) for more information.

## Javascript

The following examples Javascript into the page footer. This is the default region, and is a good place to put scripts that are not needed on every page of the website. Note if your adding a jQuery Plugin or similar you will need to add that to the HTML HEAD region. 

Language | Example | Example code |
--- | --- | --- |
PHP | Add a Javascript file provided by an extension | ```php CRM_Core_Resources::singleton()->addScriptFile('com.example.foo', 'bar.js');``` |
PHP | Add an external Javascript file | ```php CRM_Core_Resources::singleton()->addScriptUrl('http://www.example.com/bar.js'); ``` |
PHP | Add raw Javascript code | ``` CRM_Core_Resources::singleton()->addScript('alert("hello");'); ``` |
Smarty | Add a Javascript file provided by an extension | ``` {crmScript ext=com.example.foo file=bar.js} ``` |
Smarty | Add an external Javascript file | ``` {crmScript url="http://www.example.com/bar.js"} ``` |

### Javascript Variables

If you need to export runtime data for use with JavaScript, then you can register `vars` with the resource manager. The data will be serialized (with JSON) and made available as part of JavaScript's `CRM` object. Example:

```php
// On the server:
CRM_Core_Resources::singleton()->addVars('myNamespace', array('foo' => 'bar'));
```
```javascript
// In your js file:
CRM.alert(CRM.vars.myNamespace.foo); // Alerts "bar"
```

More infomation can be found in the [Javascript reference](/standards/javascript).

## CSS StyleSheets

Language | Example | Example code |
--- | --- | --- |
PHP | Add a CSS file provided by an extension | ```php CRM_Core_Resources::singleton()->addStyleFile('com.example.foo', 'bar.css');``` |
PHP | Add an external CSS file | ```php CRM_Core_Resources::singleton()->addStyleUrl('http://www.example.com/bar.css'); ``` |
PHP | Add raw CSS code | ```php CRM_Core_Resources::singleton()->addStyle('body { background: black; }'); ``` |
Smarty | Add a CSS file provided by an extension | ``` {crmStyle ext=com.example.foo file=bar.css} ``` | 
Smarty | Add an external CSS file | ```   {crmStyle url="http://www.example.com/bar.css"} ```

## Other Resource URLs

If you need to reference any other kind of resource (such as image or sound file), then use these examples to construct a resource URL:

| Language | Example | Example Code |
--- | --- | --- |
PHP | Get an image URL | ```php CRM_Core_Resources::singleton()->getUrl('com.example.foo', 'bar.png'); ``` |
PHP | Get an extension's base URL | ```php CRM_Core_Resources::singleton()->getUrl('com.example.bar'); ``` |
Smarty | Get an image URL ``` {crmResURL ext=com.example.foo file=bar.png} ``` |
Smarty | Get an extension's base URL | ``` crmResURL ext=com.example.foo } ``` |

!!!note 
`{crmResURL}` vs `{crmURL}`
`{crmResURL}` sounds similar to another Smarty tag, `{crmURL}`, but they are functionally distinct: `{crmURL}` constructs the URL of a dynamic web page, adjusting the path and query parameters of the URL based on the CMS. By contrast, `{crmResURL}` constructs the URL of a static resource file; because the file is static, one may link directly without using the CMS or manipulating query parameters.

## Advanced Options

When including Javascript files or CSS files in the HTML HEAD, the exact placement of the <SCRIPT> and <STYLE> tags can be significant. There are two options for manipulating placement:
* Region: By default, codes are injected inside the HTML <HEAD>. To delay execution of code until later in the transfer of a page, you may want to place the tag further down in the BODY. You can specify the region as one of:
  - "page-header"
  - "page-body"
  - "page-footer" (default)
  - "html-header" (should always be used for jQuery plugins that refer to jQuery as `jQuery` and not `cj`)
* Weight: Within a given region, tags are sorted by a numerical weight (ascending order). The default weight is '0'

For PHP APIs (addScriptFile(), addScriptUrl(), etc), the $weight and $region are optional function arguments which you may tack onto the end of each function call:

```php
CRM_Core_Resources::singleton()->addScriptFile('com.example.foo', 'jquery.bar.js', 10, 'html-header');
CRM_Core_Resources::singleton()->addScriptUrl('http://example.com/bar.css', 10, 'page-header');
```
For Smarty APIs ({crmScript} and {crmStyle}), the optional weight and region parameters may also be added:
```
{crmScript ext=com.example.foo file=bar.js weight=10 region=page-footer}
{crmStyle url="http://example.com/bar.css" weight=10 region=page-footer}
```
