# hook_civicrm_alterMailingRecipients

## Summary

This hook is called to allow the user to alter the mailing recipients after they have been constructed.

### Notes

!!! Note
    This hook is called two times, once at the start and once at the end of mail recipients
    building, identified by $context as 'pre' or 'post' respectively

## Availability

This hook was first available in CiviCRM 4.7.32

## Definition

```php
    function hook_civicrm_alterMailingRecipients(&$mailingObject, &$criteria, $context);
```

## Parameters

-   object `$mailingObject` - reference to CRM_Mailing_DAO_Mailing object
-   array `$criteria` - the criteria in terms of SQL fragments Array(string $name => CRM_Utils_SQL_Select $criterion) to manipulate mailing recipients
-   string `$context` - contain 'pre' or 'post' value to indicate when the hook is fired

## Returns

-   null

## Example
```php
    function mymodule_civicrm_alterMailingRecipients(&$mailingObject, &$criteria, $context) {
      // fetch all emails marked is_bulkmail only AND consider only those contacts which are tagged with Volunteer
      if ($context == 'pre') {
        // criteria to choose email which are only used for mass mailing
        $criteria['location_filter'] = CRM_Utils_SQL_Select::fragment()->where("civicrm_email.is_bulkmail = 1");
        // criteria to choose contacts which are tagged 'Volunteer'
        $criteria['tag_filter'] = CRM_Utils_SQL_Select::fragment()
                                  ->join('civicrm_entity_tag', "INNER JOIN civicrm_entity_tag et ON et.entity_id = civicrm_contact.id AND et.entity_table = 'civicrm_contact'")
                                  ->join('civicrm_tag', "INNER JOIN civicrm_tag t ON t.id = et.tag_id")
                                  ->where("t.name = 'Volunteer'");
        // criteria to change order by to use is_bulkmail
        $criteria['order_by'] = CRM_Utils_SQL_Select::fragment()->orderBy('civicrm_email.is_bulkmail')
      }
    }
```
