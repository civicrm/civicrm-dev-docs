# hook_civicrm_alterPaymentProcessorParams

## Summary

This hook allows you to modify parameters passed to the payment processor.

## Notes

This hook is called during the processing of a contribution after the
payment processor has control, but before the CiviCRM processor-specific code starts a transaction with the back-end payment server (e.g. Authorize.net).

With this hook, you can pass custom parameters, or use features of your back-end that CiviCRM does not "know" about.

## Definition

     hook_civicrm_alterPaymentProcessorParams($paymentObj,&$rawParams, &$cookedParams);

## Parameters

-   $paymentObj - instance of payment class of the payment processor
    invoked
-   $rawParams - the associative array passed by CiviCRM to the
    processor
-   $cookedParams - the associative array of parameters as translated
    into the processor's API.

## Returns

## Example

    function civitest_civicrm_alterPaymentProcessorParams($paymentObj,  &$rawParams,  &$cookedParams) {  
      if ($paymentObj->class_name == Payment_Dummy ) {    
        $employer   = empty($rawParams['custom_1']) ? '' : $rawParams['custom_1'];    
        $occupation = empty($rawParams['custom_2']) ? '' : $rawParams['custom_2'];    
        $cookedParams['custom'] = "$employer|$occupation";  }  
      else if ($paymentObj->class_name == Payment_AuthorizeNet) {    
        //Actual translation for one application:    
        //Employer > Ship to Country (x_ship_to_country)    
        //Occupation > Company (x_company)    
        //Solicitor > Ship-to First Name (x_ship_to_first_name)    
        //Event > Ship-to Last Name (x_ship_to_last_name)    
        //Other > Ship-to Company (x_ship_to_company)    
        $cookedParams['x_ship_to_country']   = $rawParams['custom_1'];    
        $cookedParams['x_company']           = $rawParams['custom_2'];    
        $cookedParams['x_ship_to_last_name'] = $rawParams['accountingCode']; 
        //for now    
        $country_info = da_core_fetch_country_data_by_crm_id($rawParams['country-1']);    
        $cookedParams['x_ship_to_company']   = $country_info['iso_code'];  
      }   
      elseif ($paymentObj->billing_mode == 2) {    
        // Express  Checkout    
        $cookedParams['desc']   = $rawParams['eventName'];    
        $cookedParams['custom'] = $rawParams['eventId'];  
      }
    }
