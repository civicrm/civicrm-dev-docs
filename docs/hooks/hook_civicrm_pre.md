# hook_civicrm_pre

## Summary

This hook is called before a db write on some core objects. This hook
does not allow the abort of the operation, use a form hook instead.

We suspect the pre hook will be useful for developers building more
complex applications and need to perform operations before CiviCRM takes
action. This is very applicable when you need to maintain foreign key
constraints etc (when deleting an object, the child objects have to be
deleted first). Another good use for the pre hook is to see what is
changing between the old and new data.

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
    -   'trash': The contact is being moved to trash (Contact objects
        only)
    -   'restore': The contact is being restored from trash (Contact
        objects only)

-   $objectName - can have the following values:
    -   'Individual'
    -   'Household'
    -   'Organization'
    -   'Group'
    -   'GroupContact'
    -   'Relationship'
    -   'Activity'
    -   'Contribution'
    -   'Profile' (while this is not really an object, people have
        expressed an interest to perform an action when a profile is
        created/edited)
    -   'Membership'
    -   'MembershipPayment'
    -   'Event'
    -   'Participant'
    -   'ParticipantPayment'
    -   'UFMatch' (when an object is linked to a CMS user record, at the
        request of GordonH. A UFMatch object is passed for both the pre
        and post hooks)
    -   PledgePayment
    -   ContributionRecur
    -   Pledge
    -   CustomGroup
    -   'Campaign' (from 4.6)
    -   'EntityTag' (from 4.7.16)

**

-   $id is the unique identifier for the object if available
-   &$params are the parameters passed

## Returns

-   None

## Example