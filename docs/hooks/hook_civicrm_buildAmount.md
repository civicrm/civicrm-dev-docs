# hook_civicrm_buildAmount

## Summary

This hook is called when building the amount structure for a
Contribution or Event Page, allowing you to modify the set of radio
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
For an extension that implements this hook see: https://github.com/mattwire/uk.org.som.proratamembership

    function proratamembership_civicrm_buildAmount($pageType, &$form, &$amount) {
      if (!empty($form->get('mid'))) {
        // Don't apply pro-rated fees to renewals
        return;
      }
      //sample to modify priceset fee
      $priceSetId = $form->get('priceSetId');
      if (!empty($priceSetId)) {
        $feeBlock = &$amount;
        if (!is_array($feeBlock) || empty($feeBlock)) {
          return;
        }
        if ($pageType == 'membership') {
          // pro-rata membership per month
          // membership year is from 1st Jan->31st Dec
          // Subtract 1/12 per month so in Jan you pay full amount,
          //  in Dec you pay 1/12
          // 12 months in year, min 1 month so subtract current numeric month from 13 (gives 12 in Jan, 1 in December)
          $monthNum = date('n');
          $monthsToPay = 13-$monthNum;
          foreach ($feeBlock as &$fee) {
            if (!is_array($fee['options'])) {
              continue;
            }
            foreach ($fee['options'] as &$option) {
              // We only have one amount for each membership, so this code may be overkill,
              // as it checks every option displayed (and there is only one).
              if ($option['amount'] > 0) {
                // Only pro-rata paid memberships!
                $option['amount'] = $option['amount'] * ($monthsToPay / 12);
                if ($monthsToPay == 1) {
                  $option['label'] .= ' - Pro-rata: Dec only';
                }
                elseif ($monthsToPay < 12) {
                  $dateObj = DateTime::createFromFormat('!m', $monthNum);
                  $monthName = $dateObj->format('M');
                  $option['label'] .= ' - Pro-rata: ' . $monthName . ' to Dec';
                }
              }
            }
          }
          // FIXME: Somewhere between 4.7.15 and 4.7.23 the above stopped working and we have to do the following to make the confirm page show the correct amount.
          $form->_priceSet['fields'] = $feeBlock;
        }
      }
    }
