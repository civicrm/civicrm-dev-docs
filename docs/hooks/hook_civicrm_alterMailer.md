# hook_civicrm_alterMailer

## Summary

This hook is called when CiviCRM prepares an email driver class to
handle outbound message delivery.

## Availability

Introduced in CiviCRM v4.4.

## Definition

    hook_civicrm_alterMailer(&$mailer, $driver, $params)

## Parameters

-   object**$mailer** -The default mailer produced by normal
    configuration; a PEAR "Mail" class (like those returned by
    Mail::factory)

-   string **$driver** -  The type of the default $mailer (eg "smtp",
    "sendmail", "mock", "CRM_Mailing_BAO_Spool")

-   array **$params** - The default config options used to construct
    $mailer

## Example

    /**
     * Implementation of hook_civicrm_alterMailer
     *
     * Replace the normal mailer with our custom mailer
     */
    function example_civicrm_alterMailer(&$mailer, $driver, $params) {
      $mailer = new ExampleMailDriver();
    }

    /**
     * Outbound mailer which writes messages to a log file
     *
     * For better examples, see PEAR Mail.
     *
     * @see Mail_null
     * @see Mail_mock
     * @see Mail_sendmail
     * @see Mail_smtp
     */
    class ExampleMailDriver {
      /**
       * Send an email
       */
      function send($recipients, $headers, $body) {
        // Write mail out to a log file instead of delivering it
        $data = array(
          'recipients' => $recipients,
          'headers' => $headers,
          'body' => $body,
        );
        file_put_contents('/tmp/outbound-mail.log', json_encode($data), FILE_APPEND);
      }
    }

