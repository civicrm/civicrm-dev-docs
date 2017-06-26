# hook_civicrm_emailProcessor

## Summary

This hook is called after *each* email has been processed by the script
`bin/EmailProcessor.php`.

## Definition

    hook_civicrm_emailProcessor( $type, &$params, $mail, &$result, $action = null )

## Parameters

-   @param string $type type of mail processed: 'activity' OR 'mailing'
-   @param array &$params the params that were sent to the CiviCRM API
    function
-   @param object $mail the mail object which is an ezcMail class
-   @param array &$result the result returned by the api call
-   @param string $action (optional ) the requested action to be
    performed if the types was 'mailing'

## Returns

-   null

## Availability

This hook was first available in CiviCRM 3.4.0