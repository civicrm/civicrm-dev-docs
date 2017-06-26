# hook_civicrm_buildAmount

## Summary

This hook is called when building the amount structure for a
Contribution or Event Page. It allows you to modify the set of radio
buttons representing amounts for contribution levels and event
registration fees.

## Definition

    hook_civicrm_buildAmount( $pageType, &$form, &$amount )

## Parameters

-   $pageType - is this a 'contribution', 'event', or 'membership'
-   $form - reference to the form object
-   $amount - the amount structure to be displayed

## Returns

-   null

## Example

     function civitest_civicrm_buildAmount(
      $pageType,
      &$form,
      &$amount
    ) {
      //sample to modify priceset fee
      $priceSetId = $form->get( 'priceSetId' );
      if ( !empty( $priceSetId ) ) {
        $feeBlock =& $amount;
        // if you use this in sample data, u'll see changes in
        // contrib page id = 1, event page id = 1 and
        // contrib page id = 2 (which is a membership page
        if (!is_array( $feeBlock ) || empty( $feeBlock ) ) {
          return;
        }
        //in case of event we get eventId,
        //so lets apply hook for eventId = 1
        if ( $pageType == 'event' && $form->_eventId != 1 ) {
          return;
        }
        //in case of contrbution
        //for online case we get page id so we could apply for specific
        if ( $pageType == 'contribution' ) {
          if ( !in_array(
              get_class( $form ),
              array( 'CRM_Contribute_Form_Contribution', 'CRM_Contribute_Form_Contribution_Main')
              )
          ) {
            return;
          }
        }
        if ($pageType == 'membership') {
          // give a discount of 20% to everyone
          foreach ( $feeBlock as &$fee ) {
            if ( !is_array( $fee['options'] ) ) {
              continue;
            }
            foreach ( $fee['options'] as &$option ) {
              //for sample lets modify first option from all fields.
              $option['amount']  = $option['amount'] * 0.8;
              $option['label']  .= ' - ' . ts( 'Get a 20% discount since you know this hook' );
            }
          }
        } else {
          // unconditionally modify the first option to be a $100 fee
          // to show our power!
          foreach ( $feeBlock as &$fee ) {
            if ( !is_array( $fee['options'] ) ) {
              continue;
            }
            foreach ( $fee['options'] as &$option ) {
              //for sample lets modify first option from all fields.
              $option['amount'] = 100;
              $option['label']  = ts( 'Power of hooks' );
              break;
            }
          }
        }
      }
    }

It may be me... but I'm pretty sure that this documentation needs to be
updated because since 4.2 everything is in a price set... This code
doesn't actually work to my knowledge.