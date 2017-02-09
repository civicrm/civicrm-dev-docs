# hook_civicrm_post

## Description

This hook is called after a db write on some core objects.

pre and post hooks are useful for developers building more complex
applications and need to perform operations before CiviCRM takes action.
This is very applicable when you need to maintain foreign key
constraints etc (when deleting an object, the child objects have to be
deleted first).

## Definition

    hook_civicrm_post($op, $objectName, $objectId, &$objectRef)

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
    -   'Activity'
    -   'Address'
    -   'Case'
    -   'Campaign' (from 4.6)
    -   'Contribution'
    -   'ContributionRecur'
    -   'CustomField'
    -   'CustomGroup'
    -   'CRM_Mailing_DAO_Spool'
    -   'Email'
    -   'Event'
    -   'EntityTag'
    -   'Individual'
    -   'IM'
    -   'Household'
    -   'OpenID'
    -   'Organization'
    -   'Grant'
    -   'Group'
    -   'GroupContact'
    -   'LineItem'
    -   'Membership'
    -   'MembershipPayment'
    -   'Participant'
    -   'ParticipantPayment'
    -   'Phone'
    -   'Pledge'
    -   'PledgePayment'
    -   'Profile' (while this is not really an object, people have
        expressed an interest to perform an action when a profile is
        created/edited)
    -   'Relationship'
    -   'Tag'
    -   'UFMatch' (when an object is linked to a CMS user record, at the
        request of GordonH. A UFMatch object is passed for both the pre
        and post hooks)

-   $objectId - the unique identifier for the object. tagID in case of
    EntityTag
-   $objectRef - the reference to the object if available. For case of
    EntityTag it is an array of (entityTable, entityIDs)

## Returns

-   None

## Example

Here is a simple example that will send you an email whenever an
INDIVIDUAL Contact is either Added, Updated or Deleted:

Create a new folder called example_sendEmailOnIndividual in this
directory
/drupal_install_dir/sites/all/modules/civicrm/drupal/modules/ and then
put the following two files in that directory (change the email
addresses to yours).

    FILE #1 /drupal_install_dir/sites/all/modules/civicrm/drupal/modules/example_sendEmailOnIndividual/example_sendEmailOnIndividual.info

    name = Example Send Email On Individual
    description = Example that will send an email when an Individual Contact is Added, Updated or Deleted.
    dependencies[] = civicrm
    package = CiviCRM
    core = 6.x
    version = 1.0

    FILE #2 /drupal_install_dir/sites/all/modules/civicrm/drupal/modules/example_sendEmailOnIndividual/example_sendEmailOnIndividual.module

     <?php
    function exampleSendEmailOnIndividual_civicrm_post($op, $objectName, $objectId, &$objectRef) {

      /**************************************************************
       * Send an email when Individual Contact is CREATED or EDITED or DELETED
       */
      $send_an_email = false; //Set to TRUE for DEBUG only
      $email_to = 'me@mydomain.com'; //TO email address
      $email_from = 'me@mydomain.com'; //FROM email address
      $email_sbj = 'CiviCRM exampleSendEmailOnIndividual';
      $email_msg = "CiviCRM exampleSendEmailOnIndividual was called.
".$op." ".$objectName."
".$objectId." ";

      if ($op == 'create' && $objectName == 'Individual') {
        $email_sbj .= "- ADDED NEW contact";
        $email_msg .= $objectRef->display_name."
";
        $send_an_email = true;
      } else if ($op == 'edit' && $objectName == 'Individual') {
        $email_sbj .= "- EDITED contact";
        $email_msg .= $objectRef->display_name."
";
        $send_an_email = true;
      } else if ($op == 'delete' && $objectName == 'Individual') {
        $email_sbj .= "- DELETED contact";
        $email_msg .= $objectRef->display_name."
";
        $email_msg .= 'Phone: '.$objectRef->phone."
";
        $email_msg .= 'Email: '.$objectRef->email."
";
        $send_an_email = true;
      }

      if ($send_an_email) {
        mail($email_to, $email_sbj, $email_msg, "From: ".$email_from);
      }

    }//end FUNCTION
    ?>

Once the files are in the directory, you need to login to Drupal admin,
go to Modules and enable our new module and click Save. Now go and edit
a contact and you should get an email!