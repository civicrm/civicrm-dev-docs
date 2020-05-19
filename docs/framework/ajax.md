# Ajax Pages and Forms

## Background

The CiviCRM page request lifecycle has always been closely coupled with traditional web browsing. In recent versions we have been using more ajax, but adapting our page/form controllers to that context has been somewhat cumbersome. In 4.5 several new layers have been added to facilitate using traditional html forms/pages with ajax.

## High Level Sugar

### CRM.popup

Handler for a jQuery event e.g. `$('a').click(CRM.popup);`

!!! note "Automatic via CSS Class"
    Often you do not need to write javascript at all to use this function. CiviCRM will automatically call it when any `<a>` with class `crm-popup` is clicked.

    This function invokes the entire ajax stack (`jQuery dialog`, `crmSnippet`, `jquery.forms`, and `jquery.validate`) by calling `CRM.loadForm` and `CRM.refreshParent` when appropriate.

#### Options

Default options can be overridden by adding to the markup of the triggering element:

- `data-popup-type`: page or form (default 'form') determines which method is used, `CRM.loadPage` or `CRM.loadForm`
- `data-popup-settings`: settings object to be passed to `CRM.loadPage` or `CRM.loadForm`

#### Events

!!! note "Event Buffering"
    This function buffers `crmFormSuccess` type events and then triggers `crmPopupFormSuccess` once when the dialog is closed. This prevents the underlying content from being needlessly refreshed more than once if multiple save operations are performed in the popup.

The following events will be triggered on the `<a>` element which initiated the popup (see CRM.loadForm below for events triggered on the popup itself):

- `crmPopupOpen`: Triggered when the popup is opened. This event passes a copy of the dialog, in case you need to attach handlers to or otherwise access the dialog or its content.
- `crmPopupFormSuccess`: Triggered when the popup is closed if any data was saved. In typical use, one would call CRM.refreshParent in response to this event (this happens automatically if the link has class `crm-popup`).
- `crmPopupClose`: Triggered when the popup is closed (regardless of saved data).

### CRM.refreshParent

An event callback for `CRM.popup` or a standalone function to refresh the content around a given element.

This function is automatically called for any `<a class="crm-popup">` after `crmPopupFormSuccess`, and can also be used to refresh any section of the page.

### crm.livePage.js

[This little script](https://github.com/civicrm/civicrm-core/blob/master/js/crm.livePage.js) leverages the above to turn any reasonably standard civicrm view (e.g. CRUD tables) into a "live" page with popup action links and ajax refreshes of the main content. It has been added to most admin pages in CiviCRM 4.5.

## API Functions

###  Client-Side

The following js functions have been added to facilitate ajax page/form requests:

#### $().crmSnippet

This is implemented as a [jQuery UI Widget](http://api.jqueryui.com/jQuery.widget). It should be called on a jquery object as follows:

```javascript
$('.my-div').crmSnippet({
  url: CRM.url('civicrm/participant/view', '{reset: 1}')
});
```

Options:

- `url`: (string: default document.location.href)
- `block`: (bool: default true) whether to use the `block_ui` overlay during load.

Methods:

- `isOriginalUrl()`: bool - has the url changed since the snippet was created (e.g. for multistep forms)?
- `resetUrl()`: void - reset url to the original used when the widget was instantiated
- `refresh()`: void - loads the snippet based on the currently set url option
- `option(option, [value])`: mixed - get or set a widget option such as url
- `destroy()`: void - remove the widget and restore the container's original content (if any)

Once initialized it will sit dormant and wait for the "refresh" command before loading anything. The current browser location will be the default url. Example:

```javascript
// This instantiates the widget but otherwise does nothing
$('#my-div').crmSnippet();
// Reload the snippet as needed.
// Since we still haven't set the url, it will use the current document location.
$('document').on('someEvent', function() {
  $('#my-div').crmSnippet('refresh');
});
```

#### CRM.loadPage(url, options)

Wrapper around `crmSnippet`. Returns jquery object with `crmSnippet` widget initialized and loaded (and by default the jQuery dialog widget as well). Accepts any option accepted by crmSnippet, plus:

- `target`: (jQuery selector: default null) target element. Required only if dialog option is set to false.
- `dialog`: (assoc object) settings to pass to jQuery dialog. Set this option to false to disable creating a popup.

Example:

```javascript
// Open a page snippet in a popup - returns a jquery object
var $el = CRM.loadPage('/some/url');
// Access the crmSnippet widget to do something
$el.crmSnippet('refresh');
// Access the dialog widget to do something
$el.dialog('close');
```

#### CRM.loadForm(url, options)

Wrapper around `CRM.loadPage`. Adds ajax form processing functionality to the snippet. By default will open the form in a dialog, handle validation and processing, and close the dialog when complete. Accepts any option accepted by `CRM.loadPage`, plus:

- `ajaxForm`: (assoc object) additional options to pass to `jQuery.ajaxForm`
- `autoClose`: (bool: default true) close containing dialog (if present) on success
- `validate`: (assoc object) additional options to pass to `jQuery.validate`. `CRM.validate.options` are the default. Pass false to disable jQuery validate.
- `refreshAction`: (array: default `['next_new', 'submit_savenext']` on success, if the clicked button was among these types, the form will refresh to the new location returned by the form (e.g. for "Save and New")
- `cancelButton`: (jQuery selector: default `.cancel.form-submit`) how to identify the form's cancel button. Will trigger autoClose.
- `openInline`: (jQuery selector: default `a.button`) what links to open in the same container.
- `onCancel`: (callback fn) function to call when `cancelButton` clicked. If function returns false, the click will not be treated as cancellation and the form will submit normally.

Example:

```javascript
// Open a form in a popup - returns jquery object with crmSnippet, dialog, and ajaxForm initialized
CRM.loadForm('/some/url')
  // Attach an event handler
  .on('crmFormSuccess', function(event, data) {
    // do something after the form is submitted
    // data includes everything returned by the server
  });
```

#### Events

These widgets can be interacted with by listening to their events. The following events are triggered:

- `crmLoad`: Triggered every time a snippet is loaded (form snippets included). Note: This event is also triggered when the page is initially loaded, allowing you to attach handlers to both ajax and non-ajax loading.
- `crmBeforeLoad`: Triggered immediately before content is about to be refreshed (after the ajax operation completes)
- `crmUnload`: Triggered before content is removed or replaced (e.g. refreshing or closing a dialog)
- `crmFormLoad`: Triggered when a form is loaded in addition to the `crmLoad` event.
- `crmFormError`: Triggered when a form is reloaded with validation errors (in addition to the crmLoad and crmFormLoad events).
- `crmAjaxFail`: Triggered whenever crmSnippet is unable to load the requested content.
- `crmFormCancel`: Triggered when the cancel button is clicked on a form.
- `crmFormSubmit`: Triggered before a form is submitted.
- `crmFormSuccess`: Triggered after a form is submitted.

### Server-Side

Normally, nothing needs to be done on the server-side to make a standard CiviCRM quickform/smarty page work with these helpers. If the client-side script requires more information, simply add to `$this->ajaxReponse(array` (automatically available to all classes that extend `CRM_Core_Page` or `CRM_Core_Form`).

## Low Level

The following changes in 4.5 facilitate the above. Low-level methods will rarely need to be accessed directly.

### Server-Side

On the server-side, a generic ajax responder has been added: `CRM_Core_Page_AJAX::returnJsonResponse()`. All http requests will be automatically routed to it if `$_REQUEST` contains the parameter `snippet=json` (constant `CRM_Core_Smarty::PRINT_JSON`). This function automatically outputs:

- `content`: html snippet.
- `status`: usually `success`, or `formError` if returning a form with validation errors.
- `userContext`: the current session user context - very useful for determining the current stage of multi-step workflows.
- `title`: the current page title - automatically used by the higher-level client-side helpers.
- `crmMessages`: any status messages that have been set. These will  automatically be displayed on the client-side.

If in the context of a form, `CRM_Core_Form` extends the output to also include:

- `id`: will attempt to return the id of the object that was created/edited by a form (relies on `$form->_id` being set, which is a convention we mostly follow).
- `buttonName`: generic name of the button that was clicked. Used by `CRM.loadForm` to automatically evaluate the `refreshAction` (e.g. if the "Save and New" button was clicked).
- `action`: name of the form action that was taken.

You can extend the output by appending to the array `$this->ajaxResponse`. This variable is available to all classes that extend `CRM_Core_Page` or `CRM_Core_Form`.

### Client-Side

On the client-side, a handler has been attached to `jQuery.ajaxSuccess` which will automatically display messages returned in any ajax response, regardless of how it was called.

## Examples

Some fun snippets you can paste directly into your browser console for instant ajaxification of CiviCRM.

Make every menu item open without a page refresh

```javascript
// This is really fun but never use it in production
CRM.$(function($) {
  $('#crm-main-content-wrapper')
    // Widgetize the content area
    .crmSnippet()
    // When content changes, change the page title to match
    .on('crmLoad', function(e, data) {
      document.title = data.title;
      $('h1').html(data.title);
    })
    // Just for fun, open all links on the page in a popup (makes the gross assumption that all links are to forms)
    .on('click', 'a', function() {
      CRM.loadForm(this.href).on('crmFormSuccess', function(e, data) {
      // Allow the form to "redirect" us (still with no page refresh)
        $('#crm-main-content-wrapper').crmSnippet('option', 'url', data.userContext).crmSnippet('refresh');
      });
      return false;
    });
  // Ajaxify the menus!
  $('.menu-item a, #crm-create-new-list a').on('click', function() {
    $('#crm-main-content-wrapper').crmSnippet('option', 'url', this.href).crmSnippet('refresh');
    return false;
  });
});
```

## Additional Notes

- Variables added via `CRM_Core_Resources` will be loaded as part of a snippet - new variables will be appended to the CRM object, and existing variables with the same name will be overwritten.
- Scripts added via `CRM_Core_Resources` will be loaded as part of a snippet. This may not always be desirable, e.g. if the same snippet is refreshed multiple times it will reload all scripts every time. To avoid a script loading into a snippet one could specify the `html-header` region for the script. See [Resource Reference](resources.md).
- Javascript header scripts, notably the wysiwyg editor library, are not loaded automatically, which causes many forms to not work without manually loading the necessary scripts prior to the ajax call.
- If the page/form directly issues a redirect it will prevent ajax from working. Best practice is to not use `CRM_Utils_System::redirect()` but instead use `CRM_Core_Session::pushUserContext()` and allow the core controller to take care of the rest.
