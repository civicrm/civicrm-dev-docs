# hook_civicrm_inboundSMS

## Summary

This hook is called when an inbound SMS has been received, processed by the
provider extension, but not matched or processed by CiviSMS.

## Availability

4.7.21+

## Definition

```php
hook_civicrm_inboundSMS(&$message)
```

## Parameters

* `CRM_SMS_Message` Object `$message` - The SMS Message


## Examples

Alter the incoming SMS From number to match how phone numbers are stored in the database

```php
function myextension_civicrm_inboundSMS(&$message) {
  // Alter the sender phone number to match the format used in database
  $message->from = str_replace('+614', '04', $message->from);
}
```

Automatically add contacts to a group if the message contains 'SUBSCRIBE'

```php
function myextension_civicrm_inboundSMS(&$message) {
  // Add contact to group if message contains keyword
  if (stripos($message->body, 'SUBSCRIBE') !== false) {
    $escapedFrom = CRM_Utils_Type::escape($message->from, 'String');
    $message->fromContactID = CRM_Core_DAO::singleValueQuery('SELECT contact_id FROM civicrm_phone JOIN civicrm_contact ON civicrm_contact.id = civicrm_phone.contact_id WHERE !civicrm_contact.is_deleted AND phone LIKE "%' . $escapedFrom . '"');
    if ($message->fromContactID) {
      CRM_Contact_BAO_GroupContact::AddContactsToGroup(
        array($message->fromContactID), 5, 'SMS', 'Added'
      );
    }
  }
}
```

Send an automatic response to incoming messages

```php
function myextension_civicrm_inboundSMS(&$message) {
  // Send an automatic response
  $provider = CRM_SMS_Provider::singleton(array('provider_id' => 1));
  $provider->send($message->from, array('To' => $message->from), 'Thank you for your message', NULL, NULL);
}
```

Implement custom logic to match the message to the sender

```php
function myextension_civicrm_inboundSMS(&$message) {
  // Implement custom matching logic
  // If there are multiple contacts with the phone number, preference the one that has been sent an SMS most recently
  $escapedFrom = CRM_Utils_Type::escape($message->from, 'String');
  $message->fromContactID = CRM_Core_DAO::singleValueQuery("
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
