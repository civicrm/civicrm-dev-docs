# hook_civicrm_alterMailContent

## Summary

This hook is called after getting the content of the mail and before
tokenizing it.

## Definition

    hook_civicrm_alterMailContent(&$content)

## Parameters

-   $content - fields that include the content of the mail

## Details

-   $content - fields include: html, text, subject, groupName, valueName, messageTemplateID

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
