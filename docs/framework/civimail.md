# CiviMail Reference

## Introduction

CiviMail is probably one of the most complex parts of CiviCRM that is around. There have been a number of efforts over the years to streamline and to improve how CiviMail performs and the methods by which it generates dynamic content as well. This guide will aim to go through, What settings are available to administrators to tweak the performance of CiviMail, how CiviMail runs its jobs and How Extension Authors can extend CiviMail.

## Settings

In CiviMail there are a number of settings that allows users to improve the speed of their sending. These can be found in `Administer -> CiviMail -> Mailer Settings`. These settings should be configured based on your hosting provider and email provider. There is also a few important settings in `Administer -> CiviMail -> CiviMailComponent Settings`. Some settings allow end users to disable checking for mandatory tokens and others allow you to tell CiviCRM whether or not to automatically dedupe email address. This can be useful to stop users getting multiple copies of your emails. Since 4.6 some settings especially in regards to the auto-deduping are able to be overridden pre-mailing as well. This is done by clicking on the ratchet icon next to the recipients box when composing a CiviMail. 

## Email Selection

By default, contacts are joined to a group with no location or email preference. In this case, the destination email is chosen by taking the primary email of the contact's primary location. If the contact has only one email or location, this works as would be expected.

If the contact has multiple locations, a location preference may be set for each group to which that contact belongs. If the membership has a location preference, the primary email address of that location is chosen for the destination. For advanced users, an email address preference may also be specified at the group membership level, overriding the location preference.

Note that a contact may only have one subscription record for the group, so the mailing will go to at most one of the contact's email addresses.

## Tokens

In 4.7+ there was major changes to the Scheduled Reminders facility which also included changes to CiviMail in so far as how tokens are generated. There is a move to use more of the `Civi\Token\TokenProcessor` sub system as this is more robust. However there have been compatibility layers built in to use the older `CRM_Utils_Token` processors. Developers should aim to work off the `Civi\Token\TokenProcessor` where possible. However there are still some systems that haven't been refactored. Some of the key functions in the older systems are. 

- `CRM_Utils_Token::getTokens` - Retrieves an array of tokens contained in the given string e.g. HTML of an email
- `CRM_Utils_Token::getRequiredTokens` - What are the minimum required tokens for CiviMail
- `CRM_Utils_Token::requiredTokens` - Check that the required tokens are there
- `CRM_Utils_Token::&replace<type>Tokens` - Replaces x type of Tokens where x is User, Contact, Action, Resubscribe etc
- `CRM_Utils_Token::get<type>TokenReplcaement` - Format and escape for use in Smarty the found content for Tokens for x type. This is usually called within `CRM_Utils_Token::&replace<type>Tokens`

Extension Authors are also able to extend the list of tokens by implement ["hook_civicrm_tokens"](/hooks/hook_civicrm_tokens.md). The content of the custom token needs to be set with ["hook_civicrm_tokenValues"](/hooks/hook_civicrm_tokenValues.md).

### Required Tokens

The main required tokens are `{domain.address}` and one of either `{action.optOutUrl}` or `{action.optOut}` or `{action.unsubscribe}` or `{action.unsubscribeUrl}`. When the opt out token is used the user will be able to set the `is_opt_out` flag on their user records. Whereas with the unsubscribe tokens this will only remove them from the groups that were used in the CiviMail.

### Example Tokens

Some example tokens and their meaning

| Token | Value |
| --- | --- |
| `{domain.name}` | Name of this Domain |
| `{domain.address}` | Meta-token constructed by merging the various address components from `civicrm_domain` |
| `{domain.phone}` | Phone Number for this domain | 
| `{domain.email}` | Primary email address to contact this domain |
| `{contact.display_name}` | The contact's `display_name` (also used in the To: header) |
| `{contact.xxx}` | the value of xxx as returned by a `contact.get` api call |
| `{action.forward}` | Link to forward this mailing to an unsubscribed user |
| `{action.donate}` | Link to make a donation |
| `{action.reply}` | mailto: link to reply |
| `{action.unsubscribe}` | mailto: link to unsubscribe | 
| `{action.optOut}` | mailto: link to opt out of the domain |
| `{mailing.groups}` | The list of target groups for this mailing |
| `{mailing.name}` | The name of the mailing |
| `{mailing.name}` | The name of the mailing |
| `{unsubscribe.group}` | A bulleted list of groups from which the contact has been unsubscribed, along with web links to resubscribe. |

For more examples of tokens and token replacement see the [Token documentation](https://wiki.civicrm.org/confluence/display/CRMDOC/Tokens)

### HTML vs Text

Since we support both HTML and Text formatting of outgoing mail, we will need rules for how the tokens are used in both cases. This is mainly an issue for action tokens, but certain other tokens (such as **domain.address**) may be formatted differently in HTML mode.

In HTML content, action tokens should always be used as if they were URLs. If the action is an email address, a mailto: prefix will be added automatically

```
Example HTML 
To unsubscribe from this mailing list, click <a href="{action.unsubscribe}">here</a>.

Example text
Send email to {action.unsubscribe} to unsubscribe from this mailing list.
```

### I18n

In order to support locale-specific token codes, a separate class will be created to map tokens to their localized equivalent. Instead of matching directly on a token, the token replacement code will match on the localized version of the code. Substitution then continues as before.

### NULL values and Defaults

Many non-required contact fields are exposed to the token processor, and not every contact will have values for every token. By default, if there is no value for a token field, it is replaced with a blank string. To override this behavior, use either Smarty or `hook_civicrm_tokens()` and `hook_civicrm_tokenValues()`. The following is an example of using Smarty to override a token (note that since Smarty sees tokens as constants, they will never register as empty. The `{capture assign=variable}{token}{/capture}` is a workaround for this):

```
{capture assign=first_name}{contact.first_name}{/capture}
 
Hello {$first_name|default:Friend}!
```

## URL Tracking

When `URL Tracking` is enabled in a mailing, all links in the body of the message will be inserted into the `civicrm_mailing_trackable_url` table. The links will then be translated to point to a redirect script which is passed the `civicrm_mailing_event_queue.id` and the trackable URL ID as `GET` parameters, and registers the event.

One limitation of the current URL tracking is that the link text in HTML messages should not contain "http" otherwise it will be replaced by the tracking link. So instead this  `<a href="http://www.site.com">http://www.site.com</a>`
use a format without the http such as `<a href="http://www.site.com">www.site.com</a>`

## VERP Headers

All outgoing mail uses the following associated VERP addresses:

| Address | Use |
| --- | --- |
| `b.JOB.QUEUE.HASH@FIXME.ORG` | Return-path/bounce handling |
| `reply.JOB.QUEUE.HASH=EMAIL@FIXME.ORG`  | Reply to the author of the mailing, if configured |
| `unsubscribe.JOB.QUEUE.HASH=EMAIL@FIXME.ORG` | Unsubscribe from the group(s) of this mailing |
| `optOut.JOB.QUEUE.HASH=EMAIL@FIXME.ORG` | Unsubscribe from the domain |

Where `JOB` is the ID of the Job, `QUEUE` is the ID of the queue event for this particular message, and `HASH` is the SHA-1 of the job ID, contact's email ID, and contact ID. The email and contact IDs are never exposed to the recipient within the message. The recipient's email address is VERP-encoded (`=EMAIL`) in the address.

Additionally, the following addresses are used only for inbound processing:

| Address | Use |
| --- | --- |
| `subscribe.DOMAIN.GROUP@FIXME.ORG` | Subscribe a contact to a group |
| `confirm.CONTACT.SUBSCRIBE.HASH=EMAIL@FIXME.ORG` | Confirm double opt-in for group subscription |

For more information see this [Stack Exchange Question](https://civicrm.stackexchange.com/questions/2314/how-does-civimail-reply-tracking-and-forwarding-work).

## Jobs

### Queuing

Given a job, find all email addresses that should receive the mailing (`job.mailing_id`). 'email address' is taken to mean the primary email of the primary location of a contact, unless one (or both) of the email and location fields in the contact-group join table are non-null.  For inclusion, only contacts where `do_not_email = 0` and `on_hold = 0` are considered.

If a job has `is_retry = 1`, the queue is generated by finding all jobs with the same `mailing_id` and joining to bounce event (through queue). Otherwise, the queue is generated as follows:

- Included recipients (`group_type = Include`) 
    - Static groups - All contacts belonging to (`status = In`) any group in `crm_mailing_group where mailing_id = job.mailing_id`. 
    - Saved searches - All contacts matching the where clause of a saved search linked from groups in `crm_mailing_group`.
    - Previous mailings - All email addresses in `Mailing_Event_Queue` with the `job.mailing_id` keyed from the mailing-group join table. Only jobs with `Complete` status should be considered.

- Excluded recipients (`group_type = Exclude`)
    - Static groups
        - All contacts belonging to (`status = In`) any group in `crm_mailing_group where mailing_id = job.mailing_id`.
        -   Saved searches - All contacts matching the where clause of a saved search linked from groups in `crm_mailing_group`.
        -   Previous mailings -   All email addresses in `Mailing_Event_Queue` with the `job.mailing_id` keyed from the mail-group join table.
        -   Successful deliveries in previous jobs of the same mailing  -   All email addresses in `Mailing_Event_Queue` where `job.mailing_id = mailing_id` inner join to `Mailing_Event_Delivery` and left join to `Mailing_Event_Bounce` where `Mailing_Event_Delivery.id <> null` and `Mailing_Event_Bounce.id is null`.

### Status

Job `status` can take one of 5 states.

1.  `Scheduled`: All jobs start in this state, and stay there until the `start_date` has passed.
2.  `Running`: Jobs are marked `Running` only after the entire recipient queue (`Mailing_Event_Queue`) has been constructed. If the queuing process is interrupted, the job will remain in `Scheduled` and the queue will be reconstructed the next time the mailer is run.
3.  `Complete`: Jobs are marked complete after the mailer has attempted to send the message to every recipient in the queue (ie. every queue event has a corresponding bounce or delivery event). Note that slow bounce events may continue well after the job has been marked `Complete`.
4.  `Paused`: A job can only be marked paused by the admin interface. The mailer will not act on paused jobs.
5.  `Canceled`: Like paused, but cannot be placed back in the `Running` state.

## Events

- Delivery
    - Registered after a successful SMTP transaction.
    - Action - Add a new row in `Mailing_Event_Delivery` with the `queue_id`.
- Bounce
    - Registered after an unsuccessful SMTP transaction (fast bounce), or by the inbound processor (slow bounce, see: [CiviMail Mailer Settings](https://wiki.civicrm.org/confluence/display/CRMDOC41/CiviMail+Mailer+Settings))
    - Action:
        - Add a new row in `Mailing_Event_Bounce` with the `queue_id`, `bounce_type` and `bounce_reason` returned by the bounce  processor
        - Count the bounce events for `email_id` and compare with the `hold_threshold` for the matching bounce type. If the email address has more than the threshold of any type of bounce, place it on bounce hold.
