# hook_civicrm_pre

## Summary

This hook is called before a db write on some core objects.

## Notes

This hook does not allow the abort of the operation, use a form hook instead.

We suspect the pre hook will be useful for developers building more
complex applications and need to perform operations before CiviCRM takes
action. This is very applicable when you need to maintain foreign key
constraints etc (when deleting an object, the child objects have to be
deleted first). Another good use for the pre hook is to see what is
changing between the old and new data.

!!! tip
    Some of the more esoteric entities may not fire this hook when they're saved. If you happen to find such an entity, please make a PR to core which adds this hook. As an example, you can refer to `CRM_Core_BAO_Dashboard::create()` to find succinct syntax that appropriately calls both `CRM_Utils_Hook::pre()` and `CRM_Utils_Hook::post()`.

## Definition

    hook_civicrm_pre($op, $objectName, $id, &$params)

## Parameters

-   $op - operation being performed with CiviCRM object. Can have the
    following values:
    -   'view' : The CiviCRM object is going to be displayed
    -   'create' : The CiviCRM object is created (or contacts are being
        added to a group)
    -   'edit' : The CiviCRM object is edited
    -   'delete' : The CiviCRM object is being deleted (or contacts are
        being removed from a group)
    -   'update': The contact is being moved to trash or restored(Contact objects
        only)

-   $objectName - can have the following values:
    -   'Activity'
    -   'Address'
    -   'Batch'
    -   'Campaign' (from 4.6)
    -   'Case'
    -   'CaseContact' (from 5.14.0)
    -   'CaseType'
    -   'Contribution'
    -   'ContributionPage'
    -   'ContributionRecur'
    -   'ContributionSoft' (from 5.23.0)
    -   'CustomGroup'
    -   'CustomField'
    -   'Dashboard'
    -   'Domain' (from 5.18.0)
    -   'Email'
    -   'EntityBatch'
    -   'EntityTag' (from 4.7.16)
    -   'Event'
    -   'FinancialAccount'
    -   'FinancialItem'
    -   'Event'
    -   'Individual'
    -   'Household'
    -   'Grant'
    -   'Group'
    -   'GroupContact'
    -   'LineItem'
    -   'Mailing'
    -   'MailingAB'
    -   'MailingEventBounce'
    -   'Membership'
    -   'MembershipBlock'
    -   'MembershipPayment'
    -   'MessageTemplate'
    -   'Organization'
    -   'OpenID'
    -   'Participant'
    -   'ParticipantPayment'
    -   'Pledge'
    -   'PledgePayment'
    -   'Profile' (while this is not really an object, people have
        expressed an interest to perform an action when a profile is
        created/edited)
    -   'RecurringEntity'
    -   'ReportInstance'
    -   'Relationship'
    -   'SmsProvider'
    -   'StatusPreference'
    -   'Survey' (from 5.1.x)
    -   'UFMatch' (when an object is linked to a CMS user record, at the
        request of GordonH. A UFMatch object is passed for both the pre
        and post hooks)
    -   'Website'
    -   'PriceField'
    -   'PriceFieldValue'
    -   'PriceSet'

-   $id is the unique identifier for the object if available

-   &$params are the parameters passed

## Returns

-   None

## Example
