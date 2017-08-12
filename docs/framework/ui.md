# Accordions

CiviCRM uses accordions throughout its UI to minimize the visual weight
of the page (hiding less used pieces of content).

There are two types of accordions in use in CiviCRM:

1\) the more commonly used accordion widget which hides content already
in the rendered page

2\) the more advanced widget that uses ajax to render content when the
accordion header has been clicked

The format for a common accordion widget (collapsed by default) is as
follows:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    <div class="crm-accordion-wrapper collapsed">
      <div class="crm-accordion-header">
        Accordian Title here
      </div><!-- /.crm-accordion-header -->
      <div class="crm-accordion-body">
         <div class="crm-block crm-form-block crm-form-title-here-form-block">
           Accordion Body here
         </div><!-- / .crm-block -->
       </div><!-- /.crm-accordion-body -->
    </div><!-- /.crm-accordion-wrapper -->

</div>

</div>

Use class="crm-accordion-wrapper open" if you want the accordion body to
be open when the page loads.

Dynamic classes that are automatically applied to this type of accordion
are as follows:

*.crm-container .open .crm-accordion-header* - applied when
crm-accordion-body is visible\
**.crm-container .collapsed .crm-accordion-header* - applied when
crm-accordion-body is hidden\
*

*.crm-accordion-header:hover* - css pseudo-class

crmAccordions function is automatically included in Common.js and does
not need to be added to templates that already include that file.

------

to make an ajax accordion work automatically (including opening and
loading accordions on page load):

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    // Example from templates/CRM/Contribute/Form/Contribution.tpl// bind first click of accordion header to load crm-accordion-body with snippet
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

</div>

</div>


# Buttons

Buttons initiate an immediate action.

If you need to offer two opposing functions, such as Edit and Save,
consider using two separate buttons instead of one dual-purpose button
that changes state.

CiviCRM provides a pre-themed button element, to be used for buttons
throughout the system. Button elements may contain any of the optional
icons provided by CiviCRM.

The code to create a button **with** an icon:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    <a class="button_name button" href="#">
     <span>
      <div class="icon icon_name-icon"></div>
      Button Text
     </span>
    </a>

</div>

</div>

The code to create a button **without** an icon:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    <a class="button_name button" href="#">
     <span>
      Button Text
     </span>
    </a>

</div>

</div>

an example using the "Edit" button from the Contact View page :

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    <a title="Edit" class="edit button" href="#">
     <span>
     <div class="icon edit-icon"></div>
     Edit
     </span>
    </a>

</div>

</div>


# crmDatepicker

crmDatepicker is a jQuery widget with bindings for Angular and
Quickform.

Usage:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    $('[name=my_field]').crmDatepicker();

</div>

</div>

<span style="font-size: 10.0pt;line-height: 13.0pt;">With no options
passed in this will create a date/time combo field that displays
according to locale preferences and saves in ISO format.</span>

<span style="font-size: 10.0pt;line-height: 13.0pt;"> </span>Options can
be passed as a plain object. Available options:

-   **allowClear**: (bool) provide a button to clear the contents. This
    defaults to *true* unless the field has the class or attribute
    "required"
-   **date**: (string|bool) date display format (e.g. "m/d/y") or *true*
    to use the locale default, or *false* for no date entry.
    Default: *true*.
-   **time**: (number|bool) time display format (12 or 24) or *true* to
    use the locale default, or *false* for no time entry<span>.
    Default: </span>*true*<span>.</span>
-   <span><span>**minDate**: (date|string|number) either a javascript
    date object or a unix timestamp or iso datestring (yyyy-mm-dd).
    Default *undefined*.</span></span>
-   **maxDate**<span
    style="font-size: 10.0pt;line-height: 13.0pt;">: (date|string|number)
    either a javascript date object or a unix timestamp or iso
    datestring
    (yyyy-mm-dd).<span> Default </span>*undefined*<span>.</span></span>
-   <span style="font-size: 10.0pt;line-height: 13.0pt;"><span>Any other
    parameter accepted by the[jQuery UI datepicker
    widget](http://api.jqueryui.com/datepicker/){.external-link}.</span></span>

<span>jQuery example of a date-only field in a custom display
format:</span>

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    $('[name=my_field]').crmDatepicker({time: false, date: 'dd-mm-yy'});

</div>

</div>

Angular example using the same options with data model binding:

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**Angular Binding**

</div>

<div class="codeContent panelContent">

    <input crm-ui-datepicker="{time: false, date: 'dd-mm-yy'}" ng-model="myobj.datefield"/>

</div>

</div>

From a php class extending CRM_Core_Form:

<div class="code panel" style="border-width: 1px;">

<div class="codeHeader panelHeader" style="border-bottom-width: 1px;">

**PHP - QuickForm Binding**

</div>

<div class="codeContent panelContent">

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

</div>

</div>

<span>\
</span>


# Icons

The primary goal of using icons should be to help the user absorb and
process information more efficiently. Icons can be used throughout the
site whenever it would useful to give users a quick visual cue.

Starting with version 4.7, CiviCRM's primary icon system is [Font
Awesome](http://fortawesome.github.io/Font-Awesome/){.external-link}, an
open-source icon font.  Font Awesome 4.4 is shipped with CiviCRM 4.7,
and any of the [Font Awesome
icons](http://fortawesome.github.io/Font-Awesome/icons/){.external-link}
can be included with an element with the classes "crm-i" and the
"fa-..." class corresponding to the icon.

<div class="panelMacro">

!!! tip{width="16" height="16"}   Use <https://github.com/mattwire/uk.co.mjwconsult.fontawesome> if you want access to these icons in earlier versions of CiviCRM (for example, if you have an extension, that uses them).


</div>



For example, to insert a [bullhorn
icon](http://fortawesome.github.io/Font-Awesome/icon/bullhorn/){.external-link},
use the following:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    <div><i class="crm-i fa-bullhorn"></i> Create new announcement</div>

</div>

</div>

<div class="panelMacro">

+--+
|  |
+--+

</div>

## Icon meaning and consistency

it's helpful to use icons consistently–to help new users understand the
purpose of an option and to help experienced users navigate quickly.
Here are some brief descriptions of the ways that various icons are
used:

<div class="table-wrap">

+--------------------------+--------------------------+--------------------------+
| Icon class               | Use                      | Compare with             |
+==========================+==========================+==========================+
| fa-trash                 | delete something that's  | fa-times to cancel       |
|                          | already been saved       | something that hasn't    |
|                          |                          | been saved yet           |
|                          |                          |                          |
|                          |                          | fa-undo to roll back a   |
|                          |                          | bigger process           |
+--------------------------+--------------------------+--------------------------+
| fa-arrows                | move something           | fa-chevron-left and      |
|                          | (anywhere)               | fa-chevron-right to      |
|                          |                          | advance through a series |
+--------------------------+--------------------------+--------------------------+
| fa-trophy                | award something a prize  | fa-check to confirm      |
|                          |                          | something                |
+--------------------------+--------------------------+--------------------------+
| fa-random                | swap places              |                          |
+--------------------------+--------------------------+--------------------------+
| fa-print                 | print something          |                          |
+--------------------------+--------------------------+--------------------------+
| fa-clipboard             | file onto a case         |                          |
|                          |                          |                          |
|                          | paste something          |                          |
+--------------------------+--------------------------+--------------------------+
| fa-undo                  | undo things              | fa-chevron-left to move  |
|                          |                          | backwards in a process   |
|                          | (revert things in        |                          |
|                          | accounting)              | fa-trash to delete       |
|                          |                          | something                |
|                          |                          |                          |
|                          |                          | fa-times to remove       |
|                          |                          | something (that hasn't   |
|                          |                          | yet been saved) or to    |
|                          |                          | exit without saving      |
+--------------------------+--------------------------+--------------------------+
| fa-info-circle           | highlight information    | fa-lightbulb-o to        |
|                          |                          | highlight a tip or       |
|                          |                          | suggestion               |
|                          |                          |                          |
|                          |                          | fa-exclamation-triangle  |
|                          |                          | to highlight a danger    |
+--------------------------+--------------------------+--------------------------+
| fa-list-alt              | display the details of   | fa-television to preview |
|                          | something                | something                |
|                          |                          |                          |
|                          |                          | fa-expand and            |
|                          |                          | fa-compress to make      |
|                          |                          | something full-screen or |
|                          |                          | a window                 |
+--------------------------+--------------------------+--------------------------+
| fa-bars                  | open a menu of options   | fa-chevron right to      |
|                          |                          | advance to the next      |
|                          |                          | thing                    |
|                          |                          |                          |
|                          |                          | fa-expand to make        |
|                          |                          | something full-screen    |
+--------------------------+--------------------------+--------------------------+
| fa-search                | search for things        | fa-list-alt to display   |
|                          |                          | details                  |
|                          |                          |                          |
|                          |                          | fa-search-plus to zoom   |
|                          |                          | in                       |
+--------------------------+--------------------------+--------------------------+
| fa-lightbulb-o           | an idea to consider      | fa-bolt to execute       |
|                          |                          | something bold           |
|                          |                          |                          |
|                          |                          | fa-info-circle to        |
|                          |                          | provide normative        |
|                          |                          | information              |
|                          |                          |                          |
|                          |                          | fa-exclamation-triangle  |
|                          |                          | to highlight a danger    |
+--------------------------+--------------------------+--------------------------+
| fa-pencil                | edit a value             | fa-wrench to edit        |
|                          |                          | configuration            |
|                          |                          |                          |
|                          |                          | fa-floppy-o to save a    |
|                          |                          | value                    |
+--------------------------+--------------------------+--------------------------+
| fa-exclamation-triangle  | provide a warning        | fa-info-circle to give   |
|                          |                          | information              |
|                          |                          |                          |
|                          |                          | fa-lightbulb-o to        |
|                          |                          | highlight a tip or       |
|                          |                          | suggestion               |
+--------------------------+--------------------------+--------------------------+
| fa-expand                | make a UI element bigger |                          |
+--------------------------+--------------------------+--------------------------+
| fa-compress              | make a UI element        |                          |
|                          | smaller                  |                          |
|                          |                          |                          |
|                          | merge two things         |                          |
|                          | together                 |                          |
+--------------------------+--------------------------+--------------------------+
| fa-rocket                | embark upon an adventure | fa-chevron-right to      |
|                          |                          | advance to something     |
|                          |                          | less exciting and/or     |
|                          |                          | fraught with danger      |
|                          |                          |                          |
|                          |                          | fa-check to agree to     |
|                          |                          | something that is        |
|                          |                          | already a done deal      |
|                          |                          |                          |
|                          |                          | fa-flag-checkered to     |
|                          |                          | finish a long process    |
|                          |                          |                          |
|                          |                          | fa-space-shuttle if you  |
|                          |                          | need to access your      |
|                          |                          | payload with the Canada  |
|                          |                          | Arm                      |
+--------------------------+--------------------------+--------------------------+
| fa-plus-circle           | add a new item           | if you have several of   |
|                          |                          | these side-by-side, try  |
|                          |                          | to provide more          |
|                          |                          | illustrative icons for   |
|                          |                          | what you're adding       |
|                          |                          |                          |
|                          |                          | fa-bolt to force a new   |
|                          |                          | thing                    |
+--------------------------+--------------------------+--------------------------+
| fa-bolt                  | execute something        | fa-floppy-o to saving    |
|                          | forcefully               | something normally       |
|                          |                          |                          |
|                          |                          | fa-check to agree to     |
|                          |                          | something innocuous      |
|                          |                          |                          |
|                          |                          | fa-chevron-right to      |
|                          |                          | advance to the next step |
|                          |                          |                          |
|                          |                          | fa-trash to delete       |
|                          |                          | something                |
|                          |                          |                          |
|                          |                          | fa-undo to revert to     |
|                          |                          | something                |
+--------------------------+--------------------------+--------------------------+
| fa-television            | preview something        | fa-search to search for  |
|                          |                          | things                   |
|                          |                          |                          |
|                          |                          | fa-list-alt to view the  |
|                          |                          | details of something     |
|                          |                          |                          |
|                          |                          | fa-times to close the    |
|                          |                          | edit dialog and see the  |
|                          |                          | thing itself             |
+--------------------------+--------------------------+--------------------------+
| fa-times                 | close something without  | fa-trash to delete       |
|                          | saving anything          | something that has been  |
|                          |                          | saved already            |
|                          | remove something that    |                          |
|                          | hasn't yet been saved    | fa-check to complete     |
|                          |                          | something (that has just |
|                          |                          | been saved or that is to |
|                          |                          | be saved upon clicking   |
|                          |                          | the icon)                |
|                          |                          |                          |
|                          |                          | fa-undo to roll          |
|                          |                          | something back           |
|                          |                          |                          |
|                          |                          | fa-chevron-left to       |
|                          |                          | return to the previous   |
|                          |                          | step                     |
+--------------------------+--------------------------+--------------------------+
| fa-check                 | complete something       | fa-times to close out    |
|                          |                          | without doing anything   |
|                          |                          |                          |
|                          |                          | fa-chevron-right to      |
|                          |                          | advance to the next step |
|                          |                          |                          |
|                          |                          | fa-flag-checkered to     |
|                          |                          | complete something major |
|                          |                          |                          |
|                          |                          | fa-rocket to agree to    |
|                          |                          | start something big      |
|                          |                          |                          |
|                          |                          | fa-bolt to execute       |
|                          |                          | something bold           |
+--------------------------+--------------------------+--------------------------+
| fa-chevron-right         | advance to the next      | fa-check to complete     |
|                          | thing                    | something                |
|                          |                          |                          |
|                          |                          | fa-rocket to start an    |
|                          |                          | epic journey             |
+--------------------------+--------------------------+--------------------------+
| fa-chevron-left          | go back                  | fa-times to cancel the   |
|                          |                          | process                  |
|                          |                          |                          |
|                          |                          | fa-undo to revert what   |
|                          |                          | was done                 |
+--------------------------+--------------------------+--------------------------+
| fa-floppy-o              | save without advancing   | fa-check to save and     |
|                          |                          | complete                 |
|                          |                          |                          |
|                          |                          | fa-pencil to start       |
|                          |                          | editing a value          |
+--------------------------+--------------------------+--------------------------+
| fa-wrench                | modify options           | fa-pencil to edit values |
|                          |                          |                          |
|                          |                          | fa-bolt to do something  |
|                          |                          | drastic                  |
+--------------------------+--------------------------+--------------------------+
| fa-paper-plane           | send something           | fa-envelope to do        |
|                          |                          | something else about     |
|                          |                          | email                    |
|                          |                          |                          |
|                          |                          | fa-check,                |
|                          |                          | fa-chevron-right,        |
|                          |                          | fa-bolt, fa-rocket or    |
|                          |                          | others if you are doing  |
|                          |                          | an action that does not  |
|                          |                          | send a message           |
|                          |                          | immediately              |
|                          |                          |                          |
|                          |                          | fa-fax to send something |
|                          |                          | on curly paper           |
+--------------------------+--------------------------+--------------------------+
| fa-envelope              | do something about email | fa-paper-plane to        |
|                          | other than actually      | actually send an email   |
|                          | sending it               |                          |
|                          |                          | fa-pencil to edit a      |
|                          | (use judiciously when    | value                    |
|                          | within CiviMail, where   |                          |
|                          | everything is about      |                          |
|                          | email)                   |                          |
+--------------------------+--------------------------+--------------------------+
| fa-flag-checkered        | complete a multi-step    | fa-trophy to award a     |
|                          | action                   | prize                    |
|                          |                          |                          |
|                          |                          | fa-check to finish       |
|                          |                          | something quick          |
+--------------------------+--------------------------+--------------------------+
| fa-bell-o                | sound alarms             | fa-paper-plane to send   |
|                          |                          | an email notification    |
|                          |                          |                          |
|                          |                          | fa-exclamation-triangle  |
|                          |                          | to highlight something   |
|                          |                          | dangerous                |
+--------------------------+--------------------------+--------------------------+
| fa-bell-slash-o          | hush alarms              | fa-times to cancel       |
|                          |                          | something                |
|                          |                          |                          |
|                          |                          | fa-user-secret to cloak  |
|                          |                          | identity                 |
+--------------------------+--------------------------+--------------------------+
| fa-clock-o               | schedule something       | fa-history to roll back  |
|                          |                          | the clock                |
|                          |                          |                          |
|                          |                          | fa-calendar to display   |
|                          |                          | dates                    |
|                          |                          |                          |
|                          |                          | fa-birthday-cake to      |
|                          |                          | schedule a celebration   |
+--------------------------+--------------------------+--------------------------+

</div>

## Special effects

Font Awesome includes a number of icon features, including spinners,
orientation options, and stacking.  Just replace the "fa" class [in the
examples](http://fortawesome.github.io/Font-Awesome/examples/){.external-link}
with "crm-i".



------------------------------------------------------------------------

# Older icon system

<div class="panelMacro">

+--+
|  |
+--+

</div>



To use an existing icon simply find the one you want from the list below
and use the following code (in this example we are using the
"delete-icon"):

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    <div class="icon delete-icon"></div>

</div>

</div>

<div class="panelMacro">

!!! tip{width="16" height="16"}   CiviCRM uses image sprites ([more info](http://www.alistapart.com/articles/sprites){.external-link}) for its two icon sets.


</div>

### The following **CRM-specific** icons are available:

-   Individual-icon
-   Group-icon
-   Household-icon

<!-- -->

-   Individual-subtype-icon
-   Household-subtype-icon
-   Organization-subtype-icon

<!-- -->

-   Organization-icon
-   Activity-icon
-   Case-icon
-   Grant-icon
-   Contribution-icon
-   Pledge-icon
-   Membership-icon
-   Participant-icon
-   Note-icon
-   Relationship-icon

### The following **non CRM-specific** icons are available:

-   edit-icon
-   delete-icon
-   dashboard-icon
-   user-record-icon
-   inform-icon
-   tip-icon

Non CRM-specific icons can be altered to use one of 4 possible colors:

-   light-icon = #888888
-   dark-icon = #222222
-   red-icon = #cd0a0a
-   blue-icon = #2e83ff

The default icon color is "light-icon" or #222222. To change the color,
simply add the color class to the icon div - in the example below the
delete icon will be red:

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    <div class="icon red-icon delete-icon"></div>

</div>

</div>

Non CRM-specific icons used inside of a button will change to
"dark-icons" when you hover over the button (with the exception of the
delete icon, which turns red)
