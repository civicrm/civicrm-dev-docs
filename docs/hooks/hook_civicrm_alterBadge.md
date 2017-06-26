# hook_civicrm_alterBadge

## Summary

This hook allows you to modify the content and format of name badges.

## Availability

Available in 4.5+.

## Definition

    hook_civicrm_alterBadge($labelName, &$label, &$format, &$participant);

## Parameters

-       $labelName - a string containing the name of the Badge format
    being used
-       $label - the CRM_Badge_BAO_Badge object, contains
    $label->pdf object
-       $format - the $formattedRow array used to create the
    badges--contains information like font and positioning
    -   there is one entry for each element (6 in total, as you can see
        here /civicrm/admin/badgelayout?action=update&id=1&reset=1) with
        array of values for each element. Each array has the following
        keys: token, value, text_alignment, font_style (bold, italic,
        normal), font_size, font_name (the options for each key are
        the options that are available on this screen:
        civicrm/admin/badgelayout?action=update&id=1&reset=1), for
        example:

        \
         token => {participant.participant_role}

        text_alignment => C

        font_style => bold

        font_size => 20

        font_name => courier

        value => Staff



-       $participant - array of token values for participant that will
    be displayed on badge, includes contact_id

## Returns

## Example



    function hook_civicrm_alterBadge($labelName, &$label, &$format, &$participant) {
      if ($labelName == 'My Custom Badge') {
        // change the font size for contact_id=12
        if ($participant['contact_id']==12){
          foreach ($format['token'] as $valueFormat){
            $valueFormat['font_size'] = 10;
          }
        }
      }
    }