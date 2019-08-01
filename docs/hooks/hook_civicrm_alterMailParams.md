# hook_civicrm_alterMailParams

## Summary

This hook is called when an email is being prepared for sending by CiviCRM.

## Definition

    hook_civicrm_alterMailParams(&$params, $context)

## Parameters

-   $params - the mailing params array
-   $context - the contexts of the hook call are:
    -   "civimail" for when sending an email using CiviMail,
    -   "flexmailer" for when sending an email using CiviMail and
        [FlexMailer](https://civicrm.org/extensions/flexmailer)
        (e.g. for Mosaico),
    -   "singleEmail" for when sending a single email,
    -   "messageTemplate" for when sending an email using a message
        template

## Details

-   $params array fields include: groupName, from, toName, toEmail,
    subject, cc, bcc, text, html, returnPath, replyTo, headers (array),
    attachments (array), and possibly others depending on context.
-   If you want to abort the mail from being sent, set the boolean
    abortMailSend to true in the params array
-   Note that this hook is called twice when sending a message template, once
    early in the message generation (before tokens are applied, with the context
    `messageTemplate`) and then later from `CRM_Utils_Mail::send()` with the
    context `singleEmail`.


## Adding custom headers to the email

You can add custom headers by appending to `$params['headers']`. Example:

    ``` php
    $params['headers']['X-My-Header'] = 'my header value';
    ```

The `headers` key may not exist in the `$params` array when passed into the hook.

For CiviMail based emails you can also add headers by simply adding a key
directly to the `$params` array, however as CiviMail also supports the above, so
it might be safer to use the `headers` key when adding headers as this is
supported across all methods.

Study the source before atttempting to set or unset non-custom headers this way!
