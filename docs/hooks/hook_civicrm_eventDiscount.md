# hook_civicrm_eventDiscount

## Summary

This hook allows you to apply a customized discount to an event
registration.

## Notes

!!! caution
    This hook is outdated - notable, CiviDiscount does not make use of it.

## Definition

    eventDiscount(&$form, &$params)

## Parameters

-   &$form - An object of type CRM_Event_Form_Registration_Confirm.
-   &$params - An array containing $form->_params.

## Returns

-   mixed