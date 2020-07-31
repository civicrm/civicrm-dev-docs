# User Interface Reference

## Accordions

CiviCRM uses accordions throughout its UI to minimize the visual weight of the page (hiding less used pieces of content).

There are two types of accordions in use in CiviCRM:

1. The more commonly used accordion widget which hides content already in the rendered page
1. The more advanced widget that uses ajax to render content when the accordion header has been clicked

The format for a common accordion widget (collapsed by default) is as follows:

```html
<div class="crm-accordion-wrapper collapsed">
  <div class="crm-accordion-header">
    Accordion Title here
  </div>
  <div class="crm-accordion-body">
     <div class="crm-block crm-form-block crm-form-title-here-form-block">
       Accordion Body here
     </div>
   </div>
</div>
```

Use `class="crm-accordion-wrapper open"` if you want the accordion body to be open when the page loads.

Dynamic classes that are automatically applied to this type of accordion are as follows:

* `.crm-container .open .crm-accordion-header` - applied when `crm-accordion-body` is visible
* `.crm-container .collapsed .crm-accordion-header` - applied when `crm-accordion-body` is hidden
* `.crm-accordion-header:hover` - css pseudo-class

`crmAccordions` function is automatically included in `common.js` and does
not need to be added to templates that already include that file.

To make an ajax accordion work automatically (including opening and loading accordions on page load):

```smarty
{literal}
// Example from templates/CRM/Contribute/Form/Contribution.tpl
// bind first click of accordion header to load crm-accordion-body with snippet
// everything else taken care of by cj().crm-accordions()
CRM.$(function($) {
  cj('#adjust-option-type').hide();
  cj('.crm-ajax-accordion .crm-accordion-header').one('click', function() {
    loadPanes(cj(this).attr('id'));
  });
  cj('.crm-ajax-accordion:not(.collapsed) .crm-accordion-header').each(function(index) {
    loadPanes(cj(this).attr('id'));
  });
});
// load panes function calls for snippet based on id of crm-accordion-header
function loadPanes( id ) {
  var url = "{/literal}{crmURL p='civicrm/contact/view/contribution' q='snippet=4&formType=' h=0}{literal}" + id;
  {/literal}
  {if $contributionMode}
    url = url + "&mode={$contributionMode}";
  {/if}
  {if $qfKey}
    url = url + "&qfKey={$qfKey}";
  {/if}
  {literal}
  if (! cj('div.'+id).html()) {
    var loading = '<img src="{/literal}{$config->resourceBase}i/loading.gif{literal}" alt="{/literal}{ts escape='js'}loading{/ts}{literal}" />&nbsp;{/literal}{ts escape='js'}Loading{/ts}{literal}...';
    cj('div.'+id).html(loading);
    cj.ajax({
      url : url,
      success: function(data) { cj('div.'+id).html(data).trigger('crmLoad'); }
    });
  }
}
```

## Buttons

Buttons initiate an immediate action.

If you need to offer two opposing functions, such as Edit and Save,
consider using two separate buttons instead of one dual-purpose button
that changes state.

CiviCRM provides a pre-themed button element, to be used for buttons
throughout the system. Button elements may contain any of the Font Awesome
icons (detailed below) provided by CiviCRM.

A Smarty function `{crmButton}` helps automate and standardize button creation.

* Create a button with an icon:

    ```html
    {crmButton href="#" class="button-name" title="Explanatory text" icon="icon-class-name"}Button Text{/crmButton}
    ```

* Create a button *without* an icon:

    ```html
    {crmButton href="#" class="button-name" title="Explanatory text" icon=0}Button Text{/crmButton}
    ```

Note that text should normally be translated with `{ts}`, which is omitted above for clarity.

For example, create an "Edit" button from the Contact View page:

```html
{crmButton title="Edit this item" icon="fa-pencil" class="edit" href="#"}Edit{/crmButton}
```

!!! info "Parameters for {crmButton}"

    The `{crmButton}` function's parameters relate to a mix of the URL, the link itself, and the icon.

    First, parameters available for `{crmURL}` apply to the link that is formed.

    Next, the `icon` parameter picks the icon.

    Finally, any remaining parameters are added as attributes on the `<a>` element.

## Date-picker

`crmDatepicker` is a jQuery widget with bindings for AngularJS and Quickform.

Usage:

```javascript
$('[name=my_field]').crmDatepicker();
```

With no options passed in this will create a date/time combo field that displays
according to locale preferences and saves in ISO format.

Options can be passed as a plain object. In addition to the [options accepted by the jQuery UI datepicker widget](http://api.jqueryui.com/datepicker/), the following CiviCRM-specific options are available:

| Option | Type | Description |
| -- | -- | -- |
| `allowClear` | bool | provide a button to clear the contents. This defaults to `true` unless the field has the class or attribute "required" |
| `date` | string\|bool | date display format (e.g. "m/d/y") or `true` to use the locale default, or `false` for no date entry. Default: `true`. |
| `time` | number\|bool | time display format (12 or 24) or `true` to use the locale default, or `false` for no time entry. Default: `true`. |

jQuery example of a date-only field in a custom display format:

```javascript
$('[name=my_field]').crmDatepicker({time: false, date: 'dd-mm-yy'});
```

Angular example using the same options with data model binding:

```html
<input crm-ui-datepicker="{time: false, date: 'dd-mm-yy'}" ng-model="myobj.datefield"/>
```

From a php class extending `CRM_Core_Form`:

```php
// Use default settings
$this->add('datepicker', 'field_name', ts('Field Label'));

// Make it required, customize datepicker and remove time field
$this->add(
  'datepicker',
  'field_2',
  ts('Field 2'),
  array('class' => 'some-css-class'),
  TRUE,
  array('time' => FALSE, 'date' => 'mm-dd-yy', 'minDate' => '2000-01-01')
);
```


## Icons

The primary goal of using icons should be to help the user absorb and
process information more efficiently. Icons can be used throughout the
site whenever it would useful to give users a quick visual cue.

CiviCRM's primary icon system is [Font Awesome](http://fontawesome.io/), an
open-source icon font.  Font Awesome version 4.7 is shipped with CiviCRM 
and any of the [Font Awesome icons](https://fontawesome.com/v4.7.0/icons/)
can be included with an element with the classes `crm-i` and the
`fa-...` class corresponding to the icon.

For example, to insert a [bullhorn icon](http://fortawesome.github.io/Font-Awesome/icon/bullhorn/),
use the following:

```html
<div><i class="crm-i fa-bullhorn" aria-hidden="true"></i> Create new announcement</div>
```

See the [Screen reader text](#screen-reader-text) section below for including text necessary to convey information that icons otherwise indicate visually.  Not all icons need screen reader text, but an icon on its own might be visually efficient but require verbal explanation for visually-impaired users.

!!! note "Why `crm-i`?"

    Many websites use Font Awesome, and a site's implementation of the `fa` class might differ from CiviCRM's Font Awesome implementation.  The version might be different, or other styling might be associated with the class.

    To avoid this, CiviCRM uses the `crm-i` class alongside the `fa-...` class for the specific icon.

### Icon helper functions

The construction of icons with text can be repetitive and prone to mistakes or minor inconsistencies.  To help with this, a few helper functions are included.

#### CRM_Core_Page::crmIcon()

**Language:** PHP

**Usage:**

```php
// The Font Awesome icon class to use
$icon = 'fa-birthday-cake';

// Text to appear in the `title` (when hovering) and for screen readers
$text = ts('This contact is celebrating a birthday');

// Whether to display the icon at all (optional)
$condition = birthdayIsToday($contact['birth_date']);

$cake = CRM_Core_Page::crmIcon($icon, $text, $condition);
```

The $condition parameter is optional.  It's a useful tool in the context of a loop where the icon displays an attribute on only some rows.  For example, the same code can be used on the "Default?" column of results but only show a check mark when an item is the default.

#### {icon}

**Language:** Smarty

**Usage:**

```html
{icon icon="fa-birthday-cake" condition=$row.is_birthday}{ts}This contact is celebrating a birthday{/ts}{/icon}
```

This is a wrapper around `CRM_Core_Page::crmIcon()` and the above example will produce identical results to the PHP example.

#### CRM.utils.formatIcon

**Language:** Javascript

**Usage:**

```js
var cake = CRM.utils.formatIcon('fa-birthday-cake', ts('This contact is celebrating a birthday'), row.isBirthday);
```

This is the same as the two above functions, but for Javascript.

#### {copyIcon}

**Language:** Smarty

**Usage:**

```html
{copyIcon name=$field.name title=$field.title}
```

This is a special-purpose helper for batch edit screens where you can click the copy icon to copy the value from the first row down through an entire column.

#### {privacyFlag}

**Language:** Smarty

**Usage:**

```html
{privacyFlag field=do_not_phone condition=$row.do_not_phone}
```

This is a special-purpose helper to flag records for a communication type that the contact prefers not to receive.  Similarly to others, the condition parameter allows the code to be identical for each row, only displaying an icon if the condition is true.

### Icon meaning and consistency

it's helpful to use icons consistently–to help new users understand the
purpose of an option and to help experienced users navigate quickly.
Here are some brief descriptions of the ways that various icons are
used:

| Class | Meaning within CiviCRM | Compare with |
| -- | -- | -- |
| [`.fa-arrows`](https://fontawesome.com/v4.7.0/icon/arrows) | move something (anywhere) | [`.fa-chevron-left`](https://fontawesome.com/v4.7.0/icon/chevron-left) and [`.fa-chevron-right`](https://fontawesome.com/v4.7.0/icon/chevron-right) to advance through a series |
| [`.fa-ban`](https://fontawesome.com/v4.7.0/icon/ban) | indicate something shouldn't be done | [`.fa-trash`](https://fontawesome.com/v4.7.0/icon/trash) to get rid of something<br/><br/>[`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) to cancel what you're doing<br/><br/>[`.fa-exclamation-triangle`](https://fontawesome.com/v4.7.0/icon/exclamation-triangle) to indicate something isn't working |
| [`.fa-bars`](https://fontawesome.com/v4.7.0/icon/bars) | open a menu of options | [`.fa-chevron-right`](https://fontawesome.com/v4.7.0/icon/chevron-right) to advance to the next thing<br/><br/>[`.fa-expand`](https://fontawesome.com/v4.7.0/icon/expand) to make something full-screen |
| [`.fa-bell-o`](https://fontawesome.com/v4.7.0/icon/bell-o) | sound alarms | [`.fa-paper-plane`](https://fontawesome.com/v4.7.0/icon/paper-plane) to send an email notification<br/><br/>[`.fa-exclamation-triangle`](https://fontawesome.com/v4.7.0/icon/exclamation-triangle) to highlight something dangerous |
| [`.fa-bell-slash-o`](https://fontawesome.com/v4.7.0/icon/bell-slash-o) | hush alarms | [`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) to cancel something<br/><br/>[`.fa-user-secret`](https://fontawesome.com/v4.7.0/icon/user-secret) to cloak identity |
| [`.fa-bolt`](https://fontawesome.com/v4.7.0/icon/bolt) | execute something forcefully | [`.fa-floppy-o`](https://fontawesome.com/v4.7.0/icon/floppy-o) to save something normally<br/><br/>[`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) to agree to something innocuous<br/><br/>[`.fa-chevron-right`](https://fontawesome.com/v4.7.0/icon/chevron-right) to advance to the next step<br/><br/>[`.fa-trash`](https://fontawesome.com/v4.7.0/icon/trash) to delete something<br/><br/>[`.fa-undo`](https://fontawesome.com/v4.7.0/icon/undo) to revert to something |
| [`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) | complete something | [`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) to close out without doing anything<br/><br/>[`.fa-chevron-right`](https://fontawesome.com/v4.7.0/icon/chevron-right) to advance to the next step<br/><br/>[`.fa-flag-checkered`](https://fontawesome.com/v4.7.0/icon/flag-checkered) to complete something major<br/><br/>[`.fa-rocket`](https://fontawesome.com/v4.7.0/icon/rocket) to agree to start something big<br/><br/>[`.fa-bolt`](https://fontawesome.com/v4.7.0/icon/bolt) to execute something bold |
| [`.fa-chevron-right`](https://fontawesome.com/v4.7.0/icon/chevron-right) | advance to the next thing | [`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) to complete something<br/><br/>[`.fa-rocket`](https://fontawesome.com/v4.7.0/icon/rocket) to start an epic journey |
| [`.fa-chevron-left`](https://fontawesome.com/v4.7.0/icon/chevron-left) | go back | [`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) to cancel the process<br/><br/>[`.fa-undo`](https://fontawesome.com/v4.7.0/icon/undo) to revert what was done |
| [`.fa-clipboard`](https://fontawesome.com/v4.7.0/icon/clipboard) | paste something, or file onto a case | |
| [`.fa-clock-o`](https://fontawesome.com/v4.7.0/icon/clock-o) | schedule something | [`.fa-history`](https://fontawesome.com/v4.7.0/icon/history) to roll back the clock<br/><br/>[`.fa-calendar`](https://fontawesome.com/v4.7.0/icon/calendar) to display dates<br/><br/>[`.fa-birthday-cake`](https://fontawesome.com/v4.7.0/icon/birthday-cake) to schedule a celebration |
| [`.fa-compress`](https://fontawesome.com/v4.7.0/icon/compress) | make a UI element smaller, or merge two things together | |
| [`.fa-envelope`](https://fontawesome.com/v4.7.0/icon/envelope) | do something about email other than actually sending it *(use judiciously when within CiviMail, where everything is about email)* | [`.fa-paper-plane`](https://fontawesome.com/v4.7.0/icon/paper-plane) to actually send an email<br/><br/>[`.fa-pencil`](https://fontawesome.com/v4.7.0/icon/pencil) to edit a value |
| [`.fa-exclamation-triangle`](https://fontawesome.com/v4.7.0/icon/exclamation-triangle) | provide a warning | [`.fa-info-circle`](https://fontawesome.com/v4.7.0/icon/info-circle) to give information<br/><br/>[`.fa-lightbulb-o`](https://fontawesome.com/v4.7.0/icon/lightbulb-o) to highlight a tip or suggestion<br/><br/>[`.fa-ban`](https://fontawesome.com/v4.7.0/icon/ban) to prohibit something |
| [`.fa-expand`](https://fontawesome.com/v4.7.0/icon/expand) | make a UI element bigger | |
| [`.fa-flag-checkered`](https://fontawesome.com/v4.7.0/icon/flag-checkered) | complete a multi-step action | [`.fa-trophy`](https://fontawesome.com/v4.7.0/icon/trophy) to award a prize<br/><br/>[`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) to finish something quick |
| [`.fa-floppy-o`](https://fontawesome.com/v4.7.0/icon/floppy-o) | save without advancing | [`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) to save and complete<br/><br/>[`.fa-pencil`](https://fontawesome.com/v4.7.0/icon/pencil) to start editing a value |
| [`.fa-info-circle`](https://fontawesome.com/v4.7.0/icon/info-circle) | highlight information | [`.fa-lightbulb-o`](https://fontawesome.com/v4.7.0/icon/lightbulb-o) to highlight a tip or suggestion<br/><br/>[`.fa-exclamation-triangle`](https://fontawesome.com/v4.7.0/icon/exclamation-triangle) to highlight a danger |
| [`.fa-lightbulb-o`](https://fontawesome.com/v4.7.0/icon/lightbulb-o) | an idea to consider | [`.fa-bolt`](https://fontawesome.com/v4.7.0/icon/bolt) to execute something bold<br/><br/>[`.fa-info-circle`](https://fontawesome.com/v4.7.0/icon/info-circle) to provide normative information<br/><br/>[`.fa-exclamation-triangle`](https://fontawesome.com/v4.7.0/icon/exclamation-triangle) to highlight a danger |
| [`.fa-list-alt`](https://fontawesome.com/v4.7.0/icon/list-alt) | display the details of something | [`.fa-television`](https://fontawesome.com/v4.7.0/icon/television) to preview something<br/><br/>[`.fa-expand`](https://fontawesome.com/v4.7.0/icon/expand) and [`.fa-compress`](https://fontawesome.com/v4.7.0/icon/compress) to make something full-screen or a window
| [`.fa-paper-plane`](https://fontawesome.com/v4.7.0/icon/paper-plane) | send something | [`.fa-envelope`](https://fontawesome.com/v4.7.0/icon/envelope) to do something else about email<br/><br/>[`.fa-check`](https://fontawesome.com/v4.7.0/icon/check), [`.fa-chevron-right`](https://fontawesome.com/v4.7.0/icon/chevron-right), [`.fa-bolt`](https://fontawesome.com/v4.7.0/icon/bolt), [`.fa-rocket`](https://fontawesome.com/v4.7.0/icon/rocket) or others if you are doing an action that does not send a message immediately<br/><br/>[`.fa-fax`](https://fontawesome.com/v4.7.0/icon/fax) to send something on curly paper |
| [`.fa-pencil`](https://fontawesome.com/v4.7.0/icon/pencil) | edit a value | [`.fa-wrench`](https://fontawesome.com/v4.7.0/icon/wrench) to edit configuration<br/><br/>[`.fa-floppy-o`](https://fontawesome.com/v4.7.0/icon/floppy-o) to save a value |
| [`.fa-plus-circle`](https://fontawesome.com/v4.7.0/icon/plus-circle) | add a new item | *if you have several of these side-by-side, try to provide more illustrative icons for what you're adding*<br/><br/>[`.fa-bolt`](https://fontawesome.com/v4.7.0/icon/bolt) to force a new thing |
| [`.fa-print`](https://fontawesome.com/v4.7.0/icon/print) | print something | |
| [`.fa-random`](https://fontawesome.com/v4.7.0/icon/random) | swap places | |
| [`.fa-rocket`](https://fontawesome.com/v4.7.0/icon/rocket) | embark upon an adventure | [`.fa-chevron-right`](https://fontawesome.com/v4.7.0/icon/chevron-right) to advance to something less exciting and/or fraught with danger<br/><br/>[`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) to agree to something that is already a done deal<br/><br/>[`.fa-flag-checkered`](https://fontawesome.com/v4.7.0/icon/flag-checkered) to finish a long process<br/><br/>[`.fa-space-shuttle`](https://fontawesome.com/v4.7.0/icon/space-shuttle) if you need to access your payload with the Canada Arm |
| [`.fa-search`](https://fontawesome.com/v4.7.0/icon/search) | search for things | [`.fa-list-alt`](https://fontawesome.com/v4.7.0/icon/list-alt) to display details<br/><br/>[`.fa-search-plus`](https://fontawesome.com/v4.7.0/icon/search-plus) to zoom in |
| [`.fa-television`](https://fontawesome.com/v4.7.0/icon/television) | preview something | [`.fa-search`](https://fontawesome.com/v4.7.0/icon/search) to search for things<br/><br/>[`.fa-list-alt`](https://fontawesome.com/v4.7.0/icon/list-alt) to view the details of something<br/><br/>[`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) to close the edit dialog and see the thing itself |
| [`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) | close something without saving anything, or remove something that hasn't yet been saved | [`.fa-trash`](https://fontawesome.com/v4.7.0/icon/trash) to delete something that has been saved already<br/><br/>[`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) to complete something (that has just been saved or that is to be saved upon clicking the icon)<br/><br/>[`.fa-undo`](https://fontawesome.com/v4.7.0/icon/undo) to roll something back<br/><br/>[`.fa-chevron-left`](https://fontawesome.com/v4.7.0/icon/chevron-left) to return to the previous step<br/><br/>[`.fa-ban`](https://fontawesome.com/v4.7.0/icon/ban) to indicate something shouldn't be done |
| [`.fa-trash`](https://fontawesome.com/v4.7.0/icon/trash) | delete something that's already been saved | [`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) to cancel something that hasn't been saved yet<br/><br/>[`.fa-undo`](https://fontawesome.com/v4.7.0/icon/undo) to roll back a bigger process |
| [`.fa-trophy`](https://fontawesome.com/v4.7.0/icon/trophy) | award something a prize | [`.fa-check`](https://fontawesome.com/v4.7.0/icon/check) to confirm something |
| [`.fa-undo`](https://fontawesome.com/v4.7.0/icon/undo) | undo (or revert) things | [`.fa-chevron-left`](https://fontawesome.com/v4.7.0/icon/chevron-left) to move backwards in a process (revert things in accounting)<br/><br/>[`.fa-trash`](https://fontawesome.com/v4.7.0/icon/trash) to delete something<br/><br/>[`.fa-times`](https://fontawesome.com/v4.7.0/icon/times) to remove something (that hasn't yet been saved) or to exit without saving |
| [`.fa-wrench`](https://fontawesome.com/v4.7.0/icon/wrench) | modify options | [`.fa-pencil`](https://fontawesome.com/v4.7.0/icon/pencil) to edit values<br/><br/>[`.fa-bolt`](https://fontawesome.com/v4.7.0/icon/bolt) to do something drastic |

### Special effects

Font Awesome includes a number of icon features, including spinners,
orientation options, and stacking.  Just replace the `fa` class [in the
examples](http://fortawesome.github.io/Font-Awesome/examples/)
with `crm-i`.

### Older icon system

!!! failure "Deprecated icons"
    Do not use these icons! You should use Font Awesome instead and replace any use of sprites/image icons. Prior to 4.7, icons were included in CiviCRM using the jQuery UI icon set as well as custom-developed icons.  Those use sprites, which limit the size and color options for icons.

To use an existing icon simply find the one you want from the list below
and use the following code (in this example we are using the
`delete-icon`):

```html
<div class="icon delete-icon"></div>
```

!!! tip
    CiviCRM uses image sprites ([more info](http://www.alistapart.com/articles/sprites)) for its two icon sets.

The following **CRM-specific** icons are available:

* `Individual-icon`
* `Group-icon`
* `Household-icon`
* `Individual-subtype-icon`
* `Household-subtype-icon`
* `Organization-subtype-icon`
* `Organization-icon`
* `Activity-icon`
* `Case-icon`
* `Grant-icon`
* `Contribution-icon`
* `Pledge-icon`
* `Membership-icon`
* `Participant-icon`
* `Note-icon`
* `Relationship-icon`

The following **non CRM-specific** icons are available:

| Icon | Replacement |
| -- | -- |
| `edit-icon` | To be decided |
| `delete-icon` | To be decided |
| `dashboard-icon` | To be decided |
| `user-record-icon` | To be decided |
| `inform-icon` | [fa-info-circle](https://fontawesome.com/v4.7.0/icon/info-circle) |
| `tip-icon` | To be decided |

Non CRM-specific icons can be altered to use one of 4 possible colors:

| Class | Resulting color |
| -- | -- |
| `light-icon` | <code style="color: white; background-color:#888888">#888888</code> |
| `dark-icon`  | <code style="color: white; background-color:#222222">#222222</code> |
| `red-icon`   | <code style="color: white; background-color:#cd0a0a">#cd0a0a</code> |
| `blue-icon`  | <code style="color: white; background-color:#2e83ff">#2e83ff</code> |

The default icon color is `light-icon`. To change the color,
simply add the color class to the icon `<div>`. In the example below the
delete icon will be red:

```html
<div class="icon red-icon delete-icon"></div>
```

Non CRM-specific icons used inside of a button will change to
"dark-icons" when you hover over the button (with the exception of the
delete icon, which turns red)

## In-Place Field Editing

This is implemented using:

* [The AJAX API](../api/interfaces.md#ajax)
* The [Jeditable plugin](http://www.appelsiini.net/projects/jeditable) for jQuery

!!! failure "Jeditable EOL"
    The Jeditable plugin is outdated and unmaintained. A suitable replacement will need to be found.

### Wrapper (Entity) Properties

| Property | Default if Omitted | Possible Values | As Markup | As Data |
| -- | -- | -- | -- | -- |
| **Declaration** | *required* | Wrapper must have class `.crm-entity` | `class="crm-entity"` | - |
| **Entity Name** | *required* | Any API entity | `id="contact-123"` | `data-entity="contact"` |
| **Entity ID** | *required* | Numeric id of existing entity or "new" to add an entity | `id="contact-123"` | `data-id="123"` |
| **Default Action** | "setvalue" | Any api action | - | `data-action="create"` |

### Item (Field) Properties

| Property | Default if Omitted | Possible Values | As Markup | As Data |
| -- | -- | -- | -- | -- |
| **Declaration** | *required* | Item must have class `.crm-editable` | `class="crm-editable"` | - |
| **Field Name** | *required* | Any field for this entity | `class="crmf-field_name"` | `data-field="field_name"` |
| **Action** | *(entity default)* | Any api action (overrides default set at entity level) | - | `data-action="update"` |
| **Widget Type** | *text* | text, textarea, select, boolean | - | `data-type="textarea"` |
| **Empty Option** | *-* | A string to label the "null" option if the field is of type select and the user should be allowed to choose nothing. |   | `data-empty-option="{ts}- none -{/ts}"` |
| **Tooltip** | *"Click to edit"* | Any text |   | `data-tooltip="{ts}Help text{/ts}"` |
| **Placeholder** | *(standard edit icon)* | Any markup |   | `data-placeholder="<span>Click to edit</span>"` |
| **Select Options** | *(automatic)* | JSON-encoded options (Note: this is rarely needed as option lists are automatically fetched from the api by crmEditable) |   |   |
| **Refresh** | *false* | Boolean |   | `data-refresh="true"` |
| **Params** |  | JSON-encoded parameters to add to the api call when saving updates | | `data-params='{"key":"value"}'` |

### Use With Checkboxes

If a field is marked-up as `<input type="checkbox">` then the
"widget type" property will be ignored and the checkbox will save via
ajax whenever the user clicks it.

### Example Markup

```html
<table>
  <tr class="crm-entity" id="contact-123">

    <!-- textfield (default type) -->
    <td class="crm-editable crmf-first_name">Fred</td>

    <!-- select list with empty option -->
    <!-- (note: options will be fetched automatically by the api) -->
    <td class="crm-editable crmf-prefix_id"
        data-type="select"
        data-empty-option="{ts}- none -{/ts}">Mr.</td>

    <!-- yes/no select -->
    <td class="crm-editable crmf-is_deceased" data-type="boolean">
      No
    </td>

  </tr>
</table>
```

## Notifications and Confirmations

### Popup Notifications

Popup notifications are the main way of alerting a message to the user in CiviCRM.

#### Javascript

`$.crmError` opens an alert as well as changing the form element to be in
an error state. The alert will be cleared automatically when the input
is changed.

```javascript
$('#form_element').crmError(ts('Wrong input'));
```

`CRM.alert` opens a CiviCRM-themed alert.

```javascript
CRM.alert(message, title, type, options);
```

!!! note
    `CRM.alert()` is generally preferred to javascript's built-in `alert()` function to maintain CiviCRM's consistent UI.

#### PHP

```php
CRM_Core_Session::setStatus($message, $title, $type, $options);
```

#### Message Types

The "type" parameter affects the css class of the notification. Although
it accepts any value, the 4 options supported by CiviCRM's css icons
are:

* *alert* (default)
* *success*
* *info*
* *error*

Additional options are passed as an array (object in js):

*   **unique**: (default: true) Check if this message was already set
    before adding
*   **expires**: how long to display this message before fadeout (in
    ms) set to 0 for no expiration
    defaults to 10 seconds for most messages, 5 if it has a title but no
    body, or 0 for errors or messages containing links

### Confirmations

To open a dialog to display a message and give the user 1 or more
choices:

```javascript
CRM.confirm(options)
  .on('crmConfirm:yes', function() {
    // Do something
  })
  .on('crmConfirm:no', function() {
    // Don't do something
  });
```

The **options** object takes any params accepted by jQuery UI dialog
(e.g. 'title') plus a few others:

*   **message**: defaults to "Are you sure you want to continue?
*   **url**: load content from the server (overrides 'message' param)
*   **options**: associative list of buttons keyed by the name of the
    event they will trigger. Defaults to
    `{no: "Cancel", yes: "Continue"}`.
    You could add another button e.g. `{maybe: ts('Not sure yet')}` and
    when clicked it would trigger a crmConfirm:maybe event.
    Note: in your event handler you can call `event.preventDefault()` to
    stop the dialog from closing.

!!! note
    `CRM.confirm()` is generally preferred to javascript's built-in `window.confirm()` function to maintain CiviCRM's consistent UI.

    Also, unlike `window.confirm()`, `CRM.confirm` is non-blocking and relies on callbacks rather than interrupting script execution.


### Unobtrusive Notifications

These small messages tell the user the status of an api call, form submission, or other asynchronous operation without getting in the way.

**Basic Async Usage**

```javascript
CRM.status(messages, request);
```

*   Messages is an object containing start, success, and error keys.
    Each message can be a string, function returning a string, or null
    to suppress output.
*   Message defaults are {start: "Saving...", success: "Saved", error:
    (no message, displays an alert warning the user that their
    information was not saved)}
*   Request is a jQuery deferred object. If you don't pass one in it
    will be created for you and returned

**Simple Non-Async Usage**

```javascript
CRM.status(message, type);
```

*   For simple usage without async operations you can pass in a string
    as the first param. 2nd param is optional string 'error' if this is
    not a success msg.

**Chaining With CRM.api3**

```javascript
CRM.api3(entity, action, params, messages);
```

*   Unfortunately jQuery deferred objects are not extendable so we can't
    chain CRM.status to an api call. Instead CRM.api3 provides an
    optional 4th param messages which will be passed into CRM.status.
    Pass **true** instead of an object to accept the default messages
    (defaults are set based on action, e.g. "Saving" for create or
    "Loading" for get).

### Static Messages

To display a static alert box in the document:

**From Smarty**

```smarty
{capture assign=infoMessage}{ts}This is just a yellow box with a message.{/ts}{/capture}
{include file="CRM/common/info.tpl" infoType="no-popup"}
```

**From PHP**

```php
CRM_Core_Session::setStatus(ts(This is just a yellow box with a message.), '', 'no-popup');
```

## Screen reader text

A well-designed interface will be immediately apparent visually without too many written instructions, but users accessing the site with a screen reader will not have the benefit of visual elements.  It's often useful to provide text that is hidden visually but will be picked up by screen readers.

!!! tip "Don't rely on titles"

    The `title` attribute on elements is not enough.  You can't count on screen readers picking that up, as a `title` isn't designed to be immediately visible in a browser anyway.

    In fact, icons should generally be given the attribute `aria-hidden="true"` in order to hide the icon from screen readers.

A class `sr-only` is included to provide text that will be picked up by a screen reader but won't appear visually.  For the simplest example, you might state the same thing that an icon conveys visually:

```html
<i class="crm-i fa-heart" aria-hidden="true"></i><span class="sr-only">This contact is a favorite</span>
```

You don't need to explain every icon: many icons exist to highlight text that already explains enough.  However, there are many places where the icon itself tells the story, and those need screen reader text.

There is no limit on the size and scale of screen reader text.  If you have a substantial bit of media that conveys information visually, you could have a whole section of replacement text for screen readers.  This allows more advanced content than what an `alt` attribute would provide.

```html
<img src="gaul-diagram.png"/>
<div class="sr-only">
  All Gaul is divided into three parts:
  <ul>
    <li>one of which the <em>Belgae</em> inhabit</li>
    <li>the <em>Aquitani</em> another</li>
    <li>those who in their own language are called <em>Celts</em>, in ours <em>Gauls</em>, the third</li>
  </ul>
</div>
```

## Section elements

This element is to be used in the case that you have a number of
elements that can be broken down into small groups of information. A
good example of its use is in the contribution page template.

Here each logical grouping of related form elements are wrapped by a
"section" div:

```html
<div class="section amount_other-section">
  <div class="label">
    <label for="amount_other">Other Amount</label>
  </div>
  <div class="content">
    $
    <input
        type="text"
        class="form-text"
        id="amount_other"
        name="amount_other"
        onfocus="useAmountOther();"
        maxlength="10"
        size="10"/>
  </div>
  <div class="clear"/>
</div>
```

The abstraction of this for elements **with** a label is as follows:

```html
<div class="section unique_section_name-section">
  <div class="label">
    <label>Section Label</label>
  </div>
  <div class="content">
    Section Content
  </div>
  <div class="clear"/>
</div>
```

You can generate the above by using the following `.tpl` syntax:

```html
<div class="section {$form.element_name.name}-section">
  <div class="label">{$form.element_name.label}</div>
  <div class="content">{$form.element_name.html}</div>
  <div class="clear"></div>
</div>
```

The abstraction of this for elements *without* a label is as follows
(this will keep the content in line with content from other section's
that *are* using labels):

```html
<div class="section unique_section_name-section">
  <div class="content">Section Content</div>
  <div class="clear"/>
</div>
```

The abstraction of this for elements *without* a label is as follows
(this allow the content to take up the space normally reserved for
labels):

```html
<div class="section unique_section_name-section nolabel-section">
  <div class="content">Section Content</div>
  <div class="clear"/>
</div>
```
