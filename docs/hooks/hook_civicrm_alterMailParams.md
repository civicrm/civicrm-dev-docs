# hook_civicrm_alterMailParams

## Description

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

re: the changes made today with regard to the context – I think
"singleEmail" should be changed to "activity" – indicating it's
triggered during the send email activity – which may be to one or more
contacts. while "singleEmail" could be interpreted as... a single email
to multiple people... the terminology is confusing. I think it's better
to condition on the actual object type (an activity) as the other
parameter options already do.