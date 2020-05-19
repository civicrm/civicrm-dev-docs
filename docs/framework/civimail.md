# CiviMail Reference

## Introduction

CiviMail is probably one of the most complex parts of CiviCRM that is around. There have been a number of efforts over the years to streamline and to improve how CiviMail performs and the methods by which it generates dynamic content as well. This guide will aim to go through, What settings are available to administrators to tweak the performance of CiviMail, how CiviMail runs its jobs and How Extension Authors can extend CiviMail.

## Settings

In CiviMail there are a number of settings that allows users to improve the speed of their sending. These can be found in `Administer -> CiviMail -> Mailer Settings`. These settings should be configured based on your hosting provider and email provider. There is also a few important settings in `Administer -> CiviMail -> CiviMailComponent Settings`. Some settings allow end users to disable checking for mandatory tokens and others allow you to tell CiviCRM whether or not to automatically dedupe email address. This can be useful to stop users getting multiple copies of your emails. Since 4.6 some settings especially in regards to the auto-deduping are able to be overridden pre-mailing as well. This is done by clicking on the ratchet icon next to the recipients box when composing a CiviMail.

## Email Selection

By default, contacts are joined to a group with no location or email preference. In this case, the destination email is chosen by taking the primary email of the contact's primary location. If the contact has only one email or location, this works as would be expected.

If the contact has multiple locations, a location preference may be set for each group to which that contact belongs. If the membership has a location preference, the primary email address of that location is chosen for the destination. For advanced users, an email address preference may also be specified at the group membership level, overriding the location preference.

Note that a contact may only have one subscription record for the group, so the mailing will go to at most one of the contact's email addresses.

## Tokens {:#tokens}

*Tokens* are an important feature for CiviMail -- they allow you to merge in data about the recipient (e.g. `{contact.first_name}`) and to facilitate workflows with hyperlinks (e.g. `{action.optOutUrl}`).

Tokens were originally developed for CiviMail, but they are now used in several more contexts -- such as individual mailings, print letters, receipts for contributions, and scheduled reminders.
For more details on *how tokens work generally*, see the [Token Reference](token.md).

### Required Tokens

CiviMail imposes an additional requirement that's not part of other token-based applications: *required tokens*. These tokens facilitate best-practices and regulatory-compliance for email marketing, and they must be included in every mailing.

The main required tokens are `{domain.address}` and one of either `{action.optOutUrl}` or `{action.optOut}` or `{action.unsubscribe}` or `{action.unsubscribeUrl}`. When the opt out token is used the user will be able to set the `is_opt_out` flag on their user records. Whereas with the unsubscribe tokens this will only remove them from the groups that were used in the CiviMail.

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
    - Static groups - All contacts belonging to (`status = In`) any group in `crm_mailing_group where mailing_id = job.mailing_id`.
    - Saved searches - All contacts matching the where clause of a saved search linked from groups in `crm_mailing_group`.
    - Previous mailings - All email addresses in `Mailing_Event_Queue` with the `job.mailing_id` keyed from the mail-group join table.
    - Successful deliveries in previous jobs of the same mailing - All email addresses in `Mailing_Event_Queue` where `job.mailing_id = mailing_id` inner join to `Mailing_Event_Delivery` and left join to `Mailing_Event_Bounce` where `Mailing_Event_Delivery.id <> null` and `Mailing_Event_Bounce.id is null`.

### Status

Job `status` can take one of 5 states.

1.  `Scheduled`: All jobs start in this state, and stay there until the `start_date` has passed.
2.  `Running`: Jobs are marked `Running` only after the entire recipient queue (`Mailing_Event_Queue`) has been constructed. If the queuing process is interrupted, the job will remain in `Scheduled` and the queue will be reconstructed the next time the mailer is run.
3.  `Complete`: Jobs are marked complete after the mailer has attempted to send the message to every recipient in the queue (ie. every queue event has a corresponding bounce or delivery event). Note that slow bounce events may continue well after the job has been marked `Complete`.
4.  `Paused`: A job can only be marked paused by the admin interface. The mailer will not act on paused jobs.
5.  `Canceled`: Like paused, but cannot be placed back in the `Running` state.

## Inbound CiviMail Events

Events within CiviMail are usually designed for when CiviMail receives an email in the VERP structure as defined above. There can also be events fired when CiviCRM is preparing emails to send or delivering emails, or processing forms with links generated from CiviMails.

- Delivery
    - Registered after a successful SMTP transaction.
    - Action - Add a new row in `Mailing_Event_Delivery` with the `queue_id`.
- Bounce
    - Registered after an unsuccessful SMTP transaction (fast bounce), or by the inbound processor (slow bounce, see: [CiviMail Mailer Settings](https://wiki.civicrm.org/confluence/display/CRMDOC/CiviMail+Mailer+Settings))
    - Action:
        - Add a new row in `Mailing_Event_Bounce` with the `queue_id`, `bounce_type` and `bounce_reason` returned by the bounce  processor
        - Count the bounce events for `email_id` and compare with the `hold_threshold` for the matching bounce type. If the email address has more than the threshold of any type of bounce, place it on bounce hold.
- Unsbuscribe
    - Registered after either a Successful SMTP transaction or submission on the usubscribe webform
    - Action
        - Removes the contact from the group leaving a note in the `civicrm_subscription_history` table indicating it was from an email and when it happend
        - Add a row in `mailing_event_unsbuscribe` setting the `is_domain = 0`for the new row.
- Opt Out
    - Registered after a successful SMTP transaction or on submisssion of the opt out form.
    - Action
        - Adds a row in `mailing_event_unsubscrive` setting `is_domain = 1`.
        - Updaes the `is_opt_out` field to 1 for the contact
- tracking url
    - Regisreted when a successfull webrequest is recieved and processed
    - Action adds a row into `mailing_event_trackable_url_open` with the current date and the `url_id` that was clicked
- Reply
    - Registered when CiviMail successfully processes an SMTP transaction.
    - Action
        - Adds a record into `mailing_event_reply` table with the relevant `queue_id` and when the reply was procesed
        - Rewrites the mail envelope and sends on the email to the intended reply address as set in the `civicrm_mailing` table
- Queue
    - Registered with CiviMail goes to send a CiviMail and creates queues as above
    - Action - Adds a row to `mailing_event_queue` table with relevant `queue_id`
- Subscribe / Confirm
    - Regisreted CiviMail successfully processes an SMTP transaction or when the relevant form is used
    - Action
        - Adds a row into either `mailing_event_confirm` or `mailing_event_subscribe`
        - if subscribe and double confirm is enabled sends a confirm email, for confirmations and where double confirm is not used, it adds a relevant row into `civicrm_group_contact`and `civicrm_subscription_history` adding the contact to the relevant group.

