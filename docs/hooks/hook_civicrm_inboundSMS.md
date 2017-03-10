# hook_civicrm_inboundSMS

## Description

This hook is called when an inbound SMS has been received, processed by the
provider extension, but not matched or processed by CiviSMS.

## Definition

```
hook_civicrm_inboundSMS(&$from, &$fromContactID = NULL, &$to, &$toContactID = NULL, &$body, &$trackID)
```

## Parameters

* string `$from` - the phone number the message is from, as set by SMS provider

* int `$fromContactID` - can be set to override default matching

* string `$to` - the optional phone number the message is to, as set by SMS provider

* int `$toContactID` -  can be set to override default matching

* string `$body` - the body text of the message

* string `$trackID` - The tracking ID of the message

## Added

4.7

## Notes

Using this hook you can

## Examples

Alter the incoming SMS From number to match how phone numbers are stored in the database

```php
<?php
function myextension_civicrm_inboundSMS(&$from, &$fromContactID = NULL, &$to, &$toContactID = NULL, &$body, &$trackID) {
  // Alter the sender phone number to match the format used in database
  $from = str_replace('+614', '04', $from);
}
```

Automatically add contacts to a group if the message contains 'SUBSCRIBE'

```php
<?php
function myextension_civicrm_inboundSMS(&$from, &$fromContactID = NULL, &$to, &$toContactID = NULL, &$body, &$trackID) {
  // Add contact to group if message contains keyword
  if (stripos($body, 'SUBSCRIBE') !== false) {
    $escapedFrom = CRM_Utils_Type::escape($from, 'String');
    $fromContactID = CRM_Core_DAO::singleValueQuery('SELECT contact_id FROM civicrm_phone JOIN civicrm_contact ON civicrm_contact.id = civicrm_phone.contact_id WHERE !civicrm_contact.is_deleted AND phone LIKE "%' . $escapedFrom . '"');
    if ($fromContactID) {
      CRM_Contact_BAO_GroupContact::AddContactsToGroup(
        array($fromContactID), 5, 'SMS', 'Added'
      );
    }
  }
}
```

Send an automatic response to incoming messages

```php
<?php
function myextension_civicrm_inboundSMS(&$from, &$fromContactID = NULL, &$to, &$toContactID = NULL, &$body, &$trackID) {
  // Send an automatic response
  $provider = CRM_SMS_Provider::singleton(array('provider_id' => 1));
  $provider->send($from, array('To' => $from), 'Thank you for your message', NULL, NULL);
}
```

Implement custom logic to match the message to the sender

```php
<?php
function myextension_civicrm_inboundSMS(&$from, &$fromContactID = NULL, &$to, &$toContactID = NULL, &$body, &$trackID) {
  // Implement custom matching logic
  // If there are multiple contacts with the phone number, preference the one that has been sent an SMS most recently
  $fromContactID = CRM_Core_DAO::singleValueQuery("
    SELECT civicrm_contact.id FROM civicrm_phone
    JOIN civicrm_contact ON civicrm_contact.id = civicrm_phone.contact_id
    LEFT JOIN civicrm_activity_contact ON civicrm_activity_contact.contact_id = civicrm_contact.id
      AND civicrm_activity_contact.record_type_id = 3
    LEFT JOIN civicrm_activity ON civicrm_activity.id = civicrm_activity_contact.activity_id
      AND civicrm_activity.activity_type_id = 4
    WHERE !civicrm_contact.is_deleted
      AND phone LIKE \"%" . $escapedFrom . "\"
    ORDER BY civicrm_activity.activity_date_time DESC"
  );
}
```
