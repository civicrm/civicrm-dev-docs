# EntityRef Fields

## Introduction

This widget was added in CiviCRM v4.5 and supplants the old autocomplete + hidden field techniques. A flexible form widget for finding/selecting/creating contacts and other entities.

## Features

- Developer friendly, takes 1 line of code, no need for additional scripts or templates.
- Works with any entity that has a (searchable) api.
- Supports formatted descriptions and icons in search results.
- Infinite scrolling of search results.
- Add "create new" buttons for contacts (and potentially other entities). This is implemented as a popup for contacts and inline for tags.
- As of CiviCRM v4.6 supports search filters.
- Request "extra" data from the api to be returned along with search results.

## Usage

An entityRef widget can be created via quickform on the server-side, or
using jQuery on the client-side.


### PHP: From a buildForm function

```php
// With no parameters passed in, will create a single contact select
$this->addEntityRef('field_1', ts('Select Contact'));

// Pass some params to allow creation of contacts, set multiple and make the field required
$this->addEntityRef('field_2', ts('Select More Contacts'), ['create => TRUE', 'multiple' => TRUE], TRUE);

// Set some filters in the api (in this case limit results by contact type)
$this->addEntityRef('field_3', ts('Select Organization'), [
  'api' => [
    'params' => ['contact_type' => 'Organization'],
  ],
]);

// Select events instead of contacts - set minimumInputLength to 0 to display results immediately without waiting for search input
$this->addEntityRef('field_4', ts('Select Event'), [
  'entity' => 'event',
  'placeholder' => ts('- Select Event -'),
  'select' => ['minimumInputLength' => 0],
]);

// Use the 'option_value' entity for most "option" lists, e.g. event types, activity types, gender, individual_prefix, custom field options, etc.
$this->addEntityRef('field_5', ts('Activity Type'), [
  'entity' => 'option_value',
  'api' => [
    'params' => ['option_group_id' => 'activity_type'],
  ],
  'select' => ['minimumInputLength' => 0],
]);
```

Please see code-level documentation in [CRM_Core_Form](https://github.com/civicrm/civicrm-core/blob/master/CRM/Core/Form.php#L1813) for all available params.

### JS: Add from client-side

```html
<input name="my_field" placeholder="{ts}- select organization -{/ts}" />

<script type="text/javascript">
  CRM.$(function($) {
    // Note: all params accepted by the php addEntityRef also work here
    // For example, select organization with button to add a new one:
    $('[name=my_field]').crmEntityRef({
      api: {params: {contact_type: 'Organization'}},
      create: true
    });
  });
</script>
```

!!! note
    Instead of passing params into the `jQuery.crmEntityRef` function, an alternative is to attach them as html data attributes. This is how they are passed from php forms onto the client-side.

## Getlist API

A special [api getlist](https://github.com/civicrm/civicrm-core/blob/master/api/v3/Generic/Getlist.php) action exists to support this widget. Getlist is a wrapper around the "get" action which smooths out the differences between various entities and supports features of the select2 widget such as infinite scrolling. It accepts the following settings (passed into the entityRef widget as 'api' property):

- `search_field`: name of field your user is searching on when s/he types (default depends on entity)
- `label_field`: name of field to display (defaults same as search field)
- `description_field`: one or more fields to be shown as description in autocomplete results
- `extra`: other fields you would like the api to retrieve for additional client-side logic. Can be accessed as `$('#element').select2('data').extra`
- `params`: array of params to send to the underlying api (use for setting additional filters e.g. contact type)

Getlist accepts other params but they are managed internally by the widget (e.g `page_num`) and do not need to be accessed directly.

There are four steps to building a Getlist

1.  Defaults
2.  Params
3.  Output
4.  Postprocess

### Defaults

This step ensures minimal requirements for building a Getlist result. It is primarily concerned with properties consumed in the Output stage.

### Params

This step manipulates the parameters to the API magic get function.  The core function ensures the api params include the return fields needed by Output. You should be sure to ensure the same return fields if you override this, or call the core function in your override. This step immediately precedes the API Entity Get.

### Output

This step immediately follows the API Entity Get and allows for manipulation of the result of the Entity get API call.

### Postprocess

Is concerned with flattening API-chained calls and the extra fields into the main API response values array. There is no way to extend this step.

### Customizing the output

Some entities are more complex and require additional pre/post processing of autocomplete results, for example to format the description differently or sort the results by date instead of alphabetically. For this, the following generic functions can be overridden: `_civicrm_api3_generic_getlist_defaults`, `_civicrm_api3_generic_getlist_params` and `_civicrm_api3_generic_getlist_output`. See [contact](https://github.com/civicrm/civicrm-core/blob/4.7.14/api/v3/Contact.php#L1248) and [event](https://github.com/civicrm/civicrm-core/blob/4.7.14/api/v3/Event.php#L237) apis for example implementations.

#### `_civicrm_api3_{$entity}_getlist_defaults($apiParams)`

Unlike `_params` and `_output`, this is not an override of the core function. The return of your function will be fed in as the $apiDefaults (with precedence)  to the [core defaults function](https://github.com/civicrm/civicrm-core/blob/4.7.14/api/v3/Generic/Getlist.php#L33). Despite the name, the defaults are only used by Getlist. If you do include a params array in the return, it will persist as defaults to API Entity get, but user supplied values will override them.

The input is not the API request but the params array of the API request. You most likely will implement this hook if you want to ensure settings get passed to a your own custom `_output` implementation, or change core behavior such as page size or set default label or id fields for your custom entity.

#### `_civicrm_api3_{$entity}_getlist_params(&$getlistRequest)`

Override function that can be used to alter API Entity Get parameters. You must call the [core function](https://github.com/civicrm/civicrm-core/blob/4.7.14/api/v3/Generic/Getlist.php#L143) if you do not ensure the needed return-field params in your override.

The parameter is a custom "object"(array) that is the product of the `_defaults()` step. The top level consists of settings used by `_output()` as well as a "params" array which will be passed the API Entity get call. The argument is passed by-reference and your override should not have a return.

#### `_civicrm_api3_{$entity}_getlist_output($result, $request, $entity, $fields)`

Override function that  is expected to iterate over the "values" portion of the API result  and return a values array that is passed again to `api3_create_success()`.

You will likely want to call the [core function](https://github.com/civicrm/civicrm-core/blob/4.7.14/api/v3/Generic/Getlist.php#L159) from your override.

## Select2

On the client side, this widget is rendered using `jQuery.select2`. See [api documentation for the select2 widget](http://ivaynberg.github.io/select2/).
