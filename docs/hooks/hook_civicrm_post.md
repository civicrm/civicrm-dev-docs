# hook_civicrm_post

## Summary

This hook is called after a db write on some core objects.

## Notes

pre and post hooks are useful for developers building more complex
applications and need to perform operations before CiviCRM takes action.
This is very applicable when you need to maintain foreign key
constraints etc (when deleting an object, the child objects have to be
deleted first).

!!! note
    These hooks use database transactions.  Don't execute code that updates the same data in the database without using a callback.  Eg. if triggering on a `Membership` entity, don't try and update that membership entity within the hook.  Use CRM_Core_Transaction::addCallback() instead.
    
!!! tip
    Some of the more esoteric entities may not fire this hook when they're saved. If you happen to find such an entity, please make a PR to core which adds this hook. As an example, you can refer to `CRM_Core_BAO_Dashboard::create()` to find succinct syntax that appropriately calls both `CRM_Utils_Hook::pre()` and `CRM_Utils_Hook::post()`.

## Definition

```php
hook_civicrm_post($op, $objectName, $objectId, &$objectRef)
```

## Parameters

-   `$op` - operation being performed with CiviCRM object. Can have the following values:
    -   'view' : The CiviCRM object is going to be displayed
    -   'create' : The CiviCRM object is created (or contacts are being added to a group)
    -   'edit' : The CiviCRM object is edited
    -   'delete' : The CiviCRM object is being deleted (or contacts are being removed from a group)
    -   'update': The contact is being moved to trash or restored (Contact objects only)

-   `$objectName` - can have the following values:
    -   'Activity'
    -   'ActionLog'
    -   'Address'
    -   'Batch'
    -   'Campaign' (from 4.6)
    -   'Case'
    -   'CaseContact' (from 5.14.0)
    -   'CaseType'
    -   'Contribution'
    -   'ContributionRecur'
    -   'ContributionSoft' (from 5.23.0)
    -   'CustomField'
    -   'CustomGroup'
    -   'CRM_Mailing_DAO_Spool'
    -   'Domain' (from 5.18.0)
    -   'Email'
    -   'Event'
    -   'EntityTag'
    -   'EntityBatch'
    -   'Grant'
    -   'Group'
    -   'GroupNesting'
    -   'GroupContact'
    -   'Household'
    -   'Individual'
    -   'IM'
    -   'LineItem'
    -   'Mailing'
    -   'MailingAB'
    -   'Membership'
    -   'MembershipBlock'
    -   'MembershipPayment'
    -   'OpenID'
    -   'Participant'
    -   'ParticipantPayment'
    -   'Phone'
    -   'Pledge'
    -   'PledgeBlock'
    -   'PledgePayment'
    -   'Profile' *(while this is not really an object, people have
        expressed an interest to perform an action when a profile is
        created/edited)*
    -   'RecurringEntity'
    -   'Relationship'
    -   'SmsProvider'
    -   'StatusPreference'
    -   'Survey' (from 5.1.x)
    -   'Tag'
    -   'UFMatch' *(when an object is linked to a CMS user record, at the
        request of GordonH. A UFMatch object is passed for both the pre
        and post hooks)*
    -   'PriceField'
    -   'PriceFieldValue'
    -   'PriceSet'

-   `$objectId` - the unique identifier for the object. `tagID` in case of `EntityTag`
-   `$objectRef` - the reference to the object if available. For case of `EntityTag` it is an array of (`entityTable`, `entityIDs`)

## Returns

-   None

## Example

Here is a simple example that will send you an email whenever an
individual contact is either added, updated or deleted:

Create a new folder called `example_sendEmailOnIndividual` in this
directory
`/drupal_install_dir/sites/all/modules/civicrm/drupal/modules/` and then
put the following two files in that directory (change the email
addresses to yours).

File 1:

`/drupal_install_dir/sites/all/modules/civicrm/drupal/modules/example_sendEmailOnIndividual/example_sendEmailOnIndividual.info`

```txt
name = Example Send Email On Individual
description = Example that will send an email when an Individual Contact is Added, Updated or Deleted.
dependencies[] = civicrm
package = CiviCRM
core = 6.x
version = 1.0
```

File 2:

`/drupal_install_dir/sites/all/modules/civicrm/drupal/modules/example_sendEmailOnIndividual/example_sendEmailOnIndividual.module`

```php
function exampleSendEmailOnIndividual_civicrm_post($op, $objectName, $objectId, &$objectRef) {

$send_an_email = false; //Set to TRUE for DEBUG only
$email_to = 'me@mydomain.com'; //TO email address
$email_from = 'me@mydomain.com'; //FROM email address
$email_sbj = 'CiviCRM exampleSendEmailOnIndividual';
$email_msg = "CiviCRM exampleSendEmailOnIndividual was called.\n".$op." ".$objectName."\n".$objectId." ";

if ($op == 'create' && $objectName == 'Individual') {
  $email_sbj .= "- ADDED NEW contact";
  $email_msg .= $objectRef->display_name."\n";
  $send_an_email = true;
} 
else if ($op == 'edit' && $objectName == 'Individual') {
  $email_sbj .= "- EDITED contact";
  $email_msg .= $objectRef->display_name."\n";
  $send_an_email = true;
} 
else if ($op == 'delete' && $objectName == 'Individual') {
  $email_sbj .= "- DELETED contact";
  $email_msg .= $objectRef->display_name."\n";
  $email_msg .= 'Phone: '.$objectRef->phone."\n";
  $email_msg .= 'Email: '.$objectRef->email."\n";
  $send_an_email = true;
}

if ($send_an_email) {
  mail($email_to, $email_sbj, $email_msg, "From: ".$email_from);
}

}
```

Once the files are in the directory, you need to login to Drupal admin,
go to Modules and enable our new module and click Save. Now go and edit
a contact and you should get an email!

## Example with transaction callback

Here is an example that calls a function `updateMembershipCustomField()` every time a membership is created (or updated).

```php

function example_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if (CRM_Core_Transaction::isActive()) {
    CRM_Core_Transaction::addCallback(CRM_Core_Transaction::PHASE_POST_COMMIT, 'example_civicrm_post_callback', [$op, $objectName, $objectId, $objectRef]);
  }
  else {
    example_civicrm_post_callback($op, $objectName, $objectId, $objectRef);
  }
}

function example_civicrm_post_callback( $op, $objectName, $objectId, $objectRef) {
  if ($objectName == 'Membership' && $op == 'create') {
    updateMembershipCustomField($objectRef->id);
  }
}
```
