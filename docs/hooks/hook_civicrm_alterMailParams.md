# hook_civicrm_alterMailParams

## Summary

This hook is called when an email is about to be sent by CiviCRM.

## Definition

    hook_civicrm_alterMailParams(&$params, $context)

## Parameters

-   $params - the mailing params array
-   $context - the contexts of the hook call are:
    -   "civimail" for when sending an email using civimail,
    -   "singleEmail" for when sending a single email,
    -   "messageTemplate" for when sending an email using a message
        template

## Details

-   $params array fields include: groupName, from, toName, toEmail,
    subject, cc, bcc, text, html, returnPath, replyTo, headers,
    attachments (array)
-   If you want to abort the mail from being sent, set the boolean
    abortMailSend to true in the params array 
