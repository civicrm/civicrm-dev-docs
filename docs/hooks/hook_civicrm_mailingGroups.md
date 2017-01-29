# hook_civicrm_mailingGroups

## Description

This hook is called when composing a mailing. You can include / exclude
other groups as needed.

## Definition

    hook_civicrm_mailingGroups( &$form, &$groups, &$mailings )

## Parameters

-   $form - the form object for which groups / mailings being displayed
-   $groups - the list of groups being included / excluded
-   $mailings - the list of mailings being included / excluded

## Returns

-   null - the return value is ignored

## Example

    function civitest_civicrm_mailingGroups( &$form, &$groups, &$mailings ) {

        // unset group id 4
        unset( $groups[4] );

        // add a fictitious mailing
        $mailings[1] = 'This mailing does not exist';
    }