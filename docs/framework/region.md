# Region Reference

When customizing CiviCRM to display new user-interface elements, one needs to add or replace content in the page output. Prior to CiviCRM 4.2, site administrators could do this by overloading CiviCRM's Smarty templates – or by [creating "extra" templates](http://civicrm.org/blogs/dave-greenberg/now-its-easier-add-custom-behaviors-templates) -- but these solutions do not allow third-party developers (module authors or extension authors) to modify the output. CiviCRM 4.2 introduces the "Region" system which allows downstream developers to add code directly to a page.

The markup in CiviCRM has been broken up into various regions, and each region is assigned a name. Module authors can append their own snippets of code to each region. For example, to add markup at the top of the CiviCRM content area, one would put content in the "page-header" region. A more complete listing of regions is included below.

## Working with regions

A region is defined by placing a `{crmRegion}` tag within a Smarty template. For Example:

```
<body>
  <div id="page-header">
    {crmRegion name="page-header"}
    <div class="navitem"><a href="/">Home</a></div>
    <div class="navitem"><a href="/logout">Logout</a></div>
    {/crmRegion}
  </div>
  <div id="page-body">
    {crmRegion name="page-body"}
    <p>Hello, {$username}!</p>
    {/crmRegion}
  </div>
  <div id="page-footer">
    {crmRegion name="page-footer"}
    <p>Copyright (C) Now</p>
    {/crmRegion}
  </div>
</body>
```

## Adding content to a region.

The crmRegion tag allows developers to specify which section of the page they wish to add their code to. A simple addition would be similar to the following

```php
if (rand(1,100) <= 5) {
  CRM_Core_Region::instance('page-body')->add(array(
    'markup' => '<p>Congratulations! You won the lottery!</p>',
  ));
}
```

For more complex formats (perhaps needing to use a custom smarty) template they can do the following:

```php
if (rand(1,100) <= 5) {
  CRM_Core_Region::instance('page-body')->add(array(
    'template' => 'CRM/Lottery/Congratulations.tpl',
  ));
}
```

Then create a smarty template similar to:

```
<div style="background: red; color: blue; font-size: 5em">
  <marquee>
    <blink>
      Congratulations! You won the lottery!
    </blink>
  </marquee>
</div>
```

The `Add()` method will accept a few different parameters for injecting content into a region: 

| Parameter | Type | Description |
| --- | --- | --- |
| markup | String (HTML) | Add literal HTML code to a region |
| template | String (File path) | Load a Smarty template and add the contents to a region | 
| callback | Mixed (PHP Callable) | filter the content of a region using a callback function. Function signagure would be `function myCallback(&$snippetSpecification, &$html)` | 
| script | String (Javascript) | Add a `<script>` tag with Javascript code |
| jquery | String (Javascript) | Add a `<script>` tag which has been guarded using jQuery conventions `jQuery(function($) { });`
| style | String (CSS) | Add a `<style>` tag with CSS Code |

Any of the above parameters can be combined with the following additional options to organise the content in regions

| Parameter | Type | Description |
| --- | --- | --- |
| name | String | A unique name for the snippet of content being added to the region |
| weight | Int | Relative position of this snippet compare to other snippets (negative - before the default content; positive - after the default content) |
| disabled | bool | Should this snippet be displayed or hidden |

## Replace Content in a Region

If the default content of the region is inappropriate, then you can disable the default content and supply your own: 

```php
CRM_Core_Region::instance('page-body')->update('default', array(
  'disabled' => TRUE,
));
CRM_Core_Region::instance('page-body')->add(array(
  'template' => 'my-alternative-body.tpl',
));
```

## Naming Conventions

When adding a new region in the core templates, use a model similar to the file hierarchy of the template file itself.

For example, if adding a section in `templates/CRM/PCP/Page/PCPInfo.tpl`, the `crmRegion` name should be:

```
{crmRegion name="pcp-page-pcpinfo"}
  < existing html >
{/crmRegion}
```

In some cases, we may want to have multiple regions in a template file. Ideally, model the name on the closest relevant CSS "id". For example, in `templates/CRM/PCP/Form/PCP.tpl`:

```
<div id="pcpFields">
{crmRegion name="pcp-form-pcp-fields"}
  < existing html >
{/crmRegion}
</div>
```

As there are not many such regions in the templates at the moment, please help improve this documentation!

!!! note
    Please keep in mind that changing an existing region name will break existing extensions.

## Header Region {#header}

The HTML `<HEAD>` is a critical part of any HTML document, and most frameworks provide features for managing its construction (e.g. Drupal's `drupal_add_js` or Joomla's `JFactory::getDocument()`). Civi's Resource and Region APIs provide a portable way to add resources to this part of the document. However, because the HEAD contains several kinds of elements, and because each CMS has its own nuances, the mechanics are a little intricate.

### General Design

- Registering resources
  - All CiviCRM code (either in core or extensions) registers Javascript and CSS resources using the Resource API. (`CRM_Core_Resources::singleton()->addScriptUrl(...)`).
  - The Resource API builds on top of the Region API. Resources may be added to the "html-header" region – or to any other region. (We focus on "html-header" because it's special.)
  - A list of resources accumulates inside `CRM_Core_Region::instance('html-header')`
  - There is a list of standard resources that are registered on (almost) every CiviCRM page. The list is generated  by `CRM_Core_Resources::singleton()->addCoreResources()` and `CRM/common/jquery.files.tpl`. (Note: The `addCoreResources()` function provides a very coarse-grained way to register resources and often loads scripts unnecessarily. It should eventually be replaced by something more fine-grained.)
- Rendering resources
  - In each CMS, we identify a hook that (a) runs for every page request, (b) runs after the primary Civi code, but (c) runs before the final rendering of the HTML `<HEAD>`. For example, in Drupal 7, `hook_page_build` provides this; in WordPress, the `wp_head` action provides this.
  - In that hook, we check to see if CiviCRM has bootstrapped for any reason. If so, then we render the region (`CRM_Core_Region::instance('html-header')->render()`) and output it using CMS-appropriate techniques. If CiviCRM hasn't bootstrapped, then we don't do anything.
  - The render process allows the CMS driver to override some aspects. For example, when rendering a Javascript URL, `CRM_Utils_System_*` is given a chance to process the URL and forgo normal formatting. `CRM_Utils_System_Drupal` uses this to call `drupal_add_js`. `CRM_Utils_System_WordPress` does nothing – it relies on the default behavior (i.e. outputting a `<SCRIPT>` tag)

This design incurres negligible overhead in composing the html-header (unless Civi has bootstrapped for some reason), it also allows for one Resource API on any page even if the primary rendering responsibility for the page doesn't belong to Civi. (For example, this is useful when Civi injects additional forms onto Drupal's user-registration screen, when Civi defines rearrangeable blocks in Drupal, or when Civi shortcodes are used to embed forms within WordPress pages.). It allows but isn't required, each CMS to handle teh management of the `<script>` markup.

### CMS Notes

#### Drupal 6

Drupal 6 does not directly provide a suitable hook. However, it can be emulated: theme('page',...) executes at a suitable time in the request lifecycle, and its behavior can be influenced by manipulating the theme-registry:
 - Implement [hook_theme_registry_alter](http://api.drupal.org/api/drupal/developer!hooks!core.php/function/hook_theme_registry_alter/6).
 - If the 'page' handler is a template file, then add a preprocess function.
 - If the 'page' handler is a function, then replace the function with a wrapper.

At time of writing, `<SCRIPT>` and `<STYLE>` tags are outputted via `drupal_add_js()` and `drupal_add_css()`. Other tags use `drupal_set_html_head()`.

#### Drupal 7

Drupal 7 provides two hooks which appear suitable ([hook_page_build](http://api.drupal.org/api/drupal/modules!system!system.api.php/function/hook_page_build/7) and [hook_page_alter](http://api.drupal.org/api/drupal/modules!system!system.api.php/function/hook_page_alter/7)). We use [hook_page_build](http://api.drupal.org/api/drupal/modules!system!system.api.php/function/hook_page_build/7).

At time of writing, `<SCRIPT>` and `<STYLE>` tags are outputted via `drupal_add_js()` and `drupal_add_css()`. Other tags use `drupal_add_html_head()`.

#### Joomla

The system plugin (plgSystemCivicrmsys) uses [onBeforeCompileHead](http://docs.joomla.org/Plugin/Events/System).

At time of writing, tags are outputted as plain HTML markup.

#### WordPress

The `civicrm_wp_main()` registers a callback for the [wp_head](http://codex.wordpress.org/Plugin_API/Action_Reference/wp_head) action.

At time of writing, tags are outputted as plain HTML markup.

## List of Regions

### Core

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| ajax-snippet | (FIXME) | All AJAX pages based on AHAH/crmSnippet | 4.5.3+ | 
| billing-block | The set of fields required for credit-card information, bank information, etc| Contribution pages; Event registration pages| 4.2.1+ | 
| default-report-header | The HTML `<HEAD>` in the default value for report headers.| This will not affect report instances where a (presumably customized) header has been saved.  (N.B. use hook_civicrm_preProcess() as hook_civicrm_buildForm will come too late to affect the default value.)| 4.6.11+ | 
| export-document-header | The HTML `<HEAD>` in a PDF export.| `CRM_Utils_PDF_Utils::html2pdf` strips out all `<head>` information (including anything in the html-header region) going into a PDF.| 4.6.11+ | 
| form-body | (FIXME)| templates/CRM/Form/default.tpl |  4.5.0+ | 
| form-bottom | (FIXME)| templates/CRM/Form/default.tpl |  4.5.0+ | 
| form-buttons | (FIXME)| CRM/common/formButtons.tpl |  4.5.0+ | 
| form-top | (FIXME)| templates/CRM/Form/default.tpl |  4.5.0+ | 
| html-header | The HTML `<HEAD>` which contains metadata, scripts, styles, etc| All pages. (Note: WP support is inconsistent pending refactor.) | 4.2.0+ | 
| page-header | The top of the CiviCRM content area. (Note: This may be different from the CMS's header section.) | All pages | 4.2.0+ | 
| page-body | The main CiviCRM content | All pages | 4.2.0+ | 
| page-footer | The bottom of the CiviCRM content area. (Note: This may be different from the CMS's footer section.) | All pages | 4.2.0+ | 
| profile-form-(NAME) | Custom contact create/edit-screen based on a profile form | (NAME) corresponds to the internal name (`civicrm_uf_group.name`) | 4.4.0+ | 
| profile-search-(NAME) | Custom contact search/listing-screen based on a profile form | (NAME) corresponds to the internal name (`civicrm_uf_group.name`) | 4.4.0+ | 
| profile-view-(NAME) | Custom contact view-screen based on a profile form | (NAME) corresponds to the internal name (`civicrm_uf_group.name`) | 4.4.0+ |

### Price Set

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| price-set-1| The block with the price set (currently only 1 per page but 1 in name for potential for more)| `CRM/Price/Form/PriceSet.tpl` |  4.6.4 | 

### Contributon Page 

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| contribution-confirm-recur-membership| (FIXME)| CRM/Contribute/Form/Contribution/Confirm.tpl |  4.3.0+ | 
| contribution-confirm-recur| (FIXME)| CRM/Contribute/Form/Contribution/Confirm.tpl |  4.3.0+ | 
| contribution-confirm-billing-block| Information related to the payment method used (eg. credit card last 4 digits/expiry date)| CRM/Contribute/Form/Contribution/Confirm.tpl |  4.3.0+ | 
| contribution-thankyou-recur-membership| (FIXME)| CRM/Contribute/Form/Contribution/ThankYou.tpl |  4.3.0+ | 
| contribution-thankyou-recur| (FIXME)| CRM/Contribute/Form/Contribution/ThankYou.tpl |  4.3.0+ | 
| contribution-thankyou-billing-block| Information related to the payment method used (eg. credit card last 4 digits/expiry date)| CRM/Contribute/Form/Contribution/ThankYou.tpl |  4.3.0+ | 
| contribution-main-pledge-block | Pledge block on contribution page| CRM/Contribute/Form/Contribution/Main.tpl |  4.7 |

### Contribution Page Administration

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| contribute-form-contributionpage-addprouct-main| Wrapper region around the main fields on add product settings tab| CRM/Contribute/Form/ContributionPage/AddProduct.tpl |  4.7.13+ | 
| contribute-form-contributionpage-addproduct-post| Wrapper region after the main fields on add product settings tab| CRM/Contribute/Form/ContributionPage/AddProduct.tpl |  4.7.13+ | 
| contribute-form-contributionpage-amount-main| Wrapper region around the main fields on amount settings tab| CRM/Contribute/Form/ContributionPage/Amount.tpl |  4.7.13+ | 
| contribute-form-contributionpage-amount-post| Wrapper region after the main fields on amount settings tab| CRM/Contribute/Form/ContributionPage/Amount.tpl |  4.7.13+ | 
| contribute-form-contributionpage-custom-main| Wrapper region around the main fields on the profile settings tab| CRM/Contribute/Form/ContributionPage/Custom.tpl |  4.7.13+ | 
| contribute-form-contributionpage-custom-post| Wrapper region after the main fields on the profile settings tab| CRM/Contribute/Form/ContributionPage/Custom.tpl |  4.7.13+ | 
| contribute-form-contributionpage-premium-main | Wrapper region around the main fields on the premium settings tab| CRM/Contribute/Form/ContributionPage/Premium.tpl |  4.7.13+ | 
| contribute-form-contributionpage-premium-post| Wrapper region after the main fields on the premium settings tab| CRM/Contribute/Form/ContributionPage/Premium.tpl |  4.7.13+ | 
| contribute-form-contributionpage-settings-main| Wrapper region around the main fields on the main settings tab| CRM/Contribute/Form/ContributionPage/Settings.tpl |  4.7.13+ | 
| contribute-form-contributionpage-settings-post| Wrapper region after the main fields on the main settings tab| CRM/Contribute/Form/ContributionPage/Settings.tpl |  4.7.13+ | 
| contribute-form-contributionpage-thankyou-main"| Wrapper region around the main fields on the thank you settings tab| CRM/Contribute/Form/ContributionPage/ThankYou.tpl |  4.7.13+ | 
| contribute-form-contributionpage-thankyou-post| Wrapper region after the main fields on the thank you settings tab| CRM/Contribute/Form/ContributionPage/ThankYou.tpl |  4.7.13+ | 
| contribute-form-contributionpage-widget-main| Wrapper region around the main fields on the widget settings tab| CRM/Contribute/Form/ContributionPage/Widget.tpl |  4.7.13+ | 
| contribute-form-contributionpage-widget-post| Wrapper region after the main fields on the widget settings tab| CRM/Contribute/Form/ContributionPage/Widget.tpl |  4.7.13+ | 

### Event Pages

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| event-page-eventinfo-actionlinks-top| (FIXME)| CRM/Event/Page/EventInfo.tpl |  4.4.0+ | 
| event-page-eventinfo-actionlinks-bottom| (FIXME)| CRM/Event/Page/EventInfo.tpl |  4.4.0+ |
| event-confirm-billing-block| Information related to the payment method used (eg. credit card last 4 digits/expiry date)| CRM/Event/Form/Registration/Confirm.tpl |  5.13+ | 
| event-thankyou-billing-block| Information related to the payment method used (eg. credit card last 4 digits/expiry date)| CRM/Event/Form/Registration/ThankYou.tpl |  5.13+ |

### Personal Campaign Pages

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| pcp-form-pcp-fields| Configuration fields for enabling PCP on events/contributions.| CRM/PCP/Form/PCP.tpl |  4.3.0+ | 
| pcp-form-campaign| PCP page creation/edit.| CRM/PCP/Form/Campaign.tpl |  4.3.0+ | 
| pcp-page-pcpinfo| PCP page displayed to a visitor.| CRM/PCP/Page/PCPInfo.tpl |  4.3.0+ | 

### Manage Premiums

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| contribute-form-managepremiums-standard-fields| The non-collapsible fields at the top of the form.| CRM/Contribute/Form/ManagePremiums.tpl | 4.7.2+ | 
| contribute-form-managepremiums-other-fields| The collapsible fields at the bottom of the form. | CRM/Contribute/Form/ManagePremiums.tpl | 4.7.2+ | 

### User Dashboard (CMS front-end)

| Region Name | Type | Details | CiviCRM Version |
| --- | --- | --- | --- |
| crm-activity-userdashboard-pre| Region immediately before the user's activities section.| CRM/Activity/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-activity-userdashboard-pre| Region immediately after the user's activities section. | CRM/Activity/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-contact-relationshipselector-pre| Region immediately before the user's relationships listing.| CRM/Contact/Page/View/RelationshipSelector.tpl | 4.7.29+ | 
| crm-contact-relationshipselector-post| Region immediately after the user's relationships listing. | CRM/Contact/Page/View/RelationshipSelector.tpl | 4.7.29+ | 
| crm-contact-userdashboard-groupcontact-pre| Region immediately before the user's groups.| CRM/Contact/Page/View/UserDashBoard/GroupContact.tpl | 4.7.29+ | 
| crm-contact-userdashboard-groupcontact-post| Region immediately after the user's groups. | CRM/Contact/Page/View/UserDashBoard/GroupContact.tpl | 4.7.29+ | 
| crm-contribute-pcp-userdashboard-pre| Region immediately before the user's PCP(s).| CRM/Contribute/Page/PcpUserDashboard.tpl | 4.7.29+ | 
| crm-contribute-pcp-userdashboard-post| Region immediately after the user's PCP(s). | CRM/Contribute/Page/PcpUserDashboard.tpl | 4.7.29+ | 
| crm-contribute-userdashboard-pre| Region immediately before the user's contribution list.| CRM/Contribute/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-contribute-userdashboard-post| Region immediately after the user's contribution list. | CRM/Contribute/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-event-userdashboard-pre| Region immediately before the user's list of participating events.| CRM/Event/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-event-userdashboard-post| Region immediately after the user's list of participating events. | CRM/Event/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-member-userdashboard-pre| Region immediately before the user's memberships.| CRM/Member/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-member-userdashboard-post| Region immediately after the user's memberships. | CRM/Member/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-pledge-userdashboard-pre| Region immediately before the user's pledge(s) list.| CRM/Pledge/Page/UserDashboard.tpl | 4.7.29+ | 
| crm-pledge-userdashboard-post| Region immediately after the user's pledge(s) list. | CRM/Pledge/Page/UserDashboard.tpl | 4.7.29+ | 
