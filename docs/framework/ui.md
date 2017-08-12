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
