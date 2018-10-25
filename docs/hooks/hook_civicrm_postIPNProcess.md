# hook_civicrm_postIPNProcess

## Summary

This hook allows you to do custom processing of IPN Data following CiviCRM processing.

## Notes

This hook as present only calls when CiviCRM has successfully processed the IPN.

With this hook you can take any of the data including custom data stored via hook_civicrm_alterPaymentProcessorParms into the IPN. 

## Definition

     hook_civicrm_postIPNProcess(&$IPNData);

## Parameters

-   $IPNData - Array of IPN data recieved from a payment processor. 

## Returns

## Example

```php
    function civitest_civicrm_postIPNProcess(&$IPNData) {
      if (!empty($IPNData['custom'])) {
        $customParams = json_decode($IPNData['custom'], TRUE);
        if (!empty($customParams['gaid'])) {
          // trigger GA event id for e-commerce tracking.
        }
      }
    }
```
