# hook_civicrm_alterMailContent

## Summary

This hook is called after getting the content of the mail and before
tokenizing it.

## Definition

    hook_civicrm_alterMailContent(&$content)

## Parameters

-   $content - fields that include the content of the mail

## Details

$content - fields include: html, text, subject, groupName, valueName, messageTemplateID, mailingID, template_type
Note that this hook is fired when: 

* creating mailings through the traditional BAO mailer (standard CiviMail)
* creating mailings through FlexMailer (used by Mosaico)
* sending emails using message templates, in CRM_Core_BAO_MessageTemplate

In the latter case there is inherently no mailingID or template_type, so these will not be supplied. Similarly in the 2 former cases the messageTemplateID is not supplied.

## Example

```php
   /**
    * Implement hook_civicrm_alterMailContent
    *
    * Replace invoice template with custom content from file
    */
  function mail_civicrm_alterMailContent(&$content) {
    if (CRM_Utils_Array::value('valueName', $content) == 'contribution_invoice_receipt') {
      $path = CRM_Core_Resources::singleton()->getPath('org.myorg.invoice');
      $html = file_get_contents($path.'/msg/contribution_invoice_receipt.html.tpl');
      $content['html'] = $html;
    }
  }
```
