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
