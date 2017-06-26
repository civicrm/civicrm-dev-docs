# hook_civicrm_unsubscribeGroups

## Summary

This hook is called when CiviCRM receives a request to unsubscribe a
user from a mailing.

## Availability

Introduced in CiviCRM v4.2

## Definition

    hook_civicrm_unsubscribeGroups($op, $mailingId, $contactId, &$groups, &$baseGroups)

## Parameters

-   -   string $op - hard coded to be unsubscribe

    -   int $mailingId - the id of the mailing sent that originated
        this unsubscribe request
    -   int $contactId - the id of the contact that wishes to be
        unsubscribed
    -   array $groups - the list of groups that the contact will be
        removed from
    -   array $baseGroups - the list of base groups (for smart
        mailings) that the contact will be removed from

## Example

    function civitest_civicrm_unsubscribeGroups( $op, $mailingId, $contactId, &$groups, &$baseGroups ) {
      // do the below for even mailing ids only
      // in a real implementation, you will have some logic to restrict what mailings
      // you want to handle the unsub via a different patch
      // this hook basically redirects you to a custom unsubscribe page
      // thanx to parvez @ veda consulting for this example
      if ($op == 'unsubscribe' && $mailingId % 2 == 0) {
        $oConfig                 = CRM_Core_Config::singleton();
        $sUnsubscribeRedirectUrl = $oConfig->unsubscribe_redirect_url;
        if ( !empty( $sUnsubscribeRedirectUrl ) ) {
          CRM_Utils_System::redirect( $sUnsubscribeRedirectUrl );
        } else {
          CRM_Core_Error::statusBounce( 'Unsubscribe URL has not been set.' );
        }
        CRM_Utils_System::civiExit();
      }
    }