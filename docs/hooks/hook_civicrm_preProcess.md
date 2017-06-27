# hook_civicrm_preProcess

## Summary

This hook can be used to modify the behavior of a form before the
`buildQuickForm` call.


## Notes

There are some known issues with exception
handling: [https://issues.civicrm.org/jira/browse/CRM-15683](https://issues.civicrm.org/jira/browse/CRM-15683).

## Definition

    hook_civicrm_preProcess($formName, &$form)


## Parameters

-   string $formName - the name of the form
-   object $form - reference to the form object

## Returns

-   null - the return value is ignored