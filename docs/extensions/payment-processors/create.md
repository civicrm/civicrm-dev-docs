# Create a Payment-Processor Extension

## Create an extension

See [Create a Module Extension](/confluence/display/CRMDOC/Create+a+Module+Extension).

## Understand the processor you are setting up

You need to check your processor's documentation and understand the flow
of the processor's process and compare it to existing processors (I
intend that people should add descriptions of existing ones at the end
of this wiki entry to assist with this)

Factors you should take note of are:

-   Does your processor require the user to interact directly with
    forms/pages on their servers (like PayPal Express), or are are data
    fields collected by CiviContrib form and submitted "behind the
    scenes"?
-   What fields are required?
-   What authentication method(s) are used between Civi and the payment
    processor servers?
-   In what format is data submitted (soap/xml, arrays...)?
-   What are the steps in completing a transaction (e.g. simple POST and
    response, vs multi-step sequence...). Are transaction results
    returned real-time (PayPal Pro/Express and I think Moneris) - or
    posted back in a separate process (PayPal Standard w/ IPN)?

Note that None of the plugins distributed with CiviCRM use a model where
the donor's credit card info is stored in the CiviCRM site's database.
For PayPal Pro, Authorize.net and PayJunction - Credit card numbers, exp
date and security codes are entered on the CiviCRM contribution page and
immediately passed to the processor / not saved. For PayPal Std, Google
Checkout - the info is entered at the processors' site.

## Determine what 'type' of processor you are dealing with.

In CiviCRM there are four processor 'types'. These are used by CiviCRM
to determine how to process a transaction.


+--------------------+--------------------+--------------------+--------------------+
| type               | billing_mode id   | corresponding      | description        |
|                    |                    | functions called   |                    |
+====================+====================+====================+====================+
| [form](#CreateaPay | 1                  | doDirectPayment(*p | This mode allows   |
| ment-ProcessorExte |                    | arams*)            | the credit card    |
| nsion-form_mode)   |                    |                    | information to be  |
|                    |                    |                    | collected on a     |
|                    |                    |                    | form within        |
|                    |                    |                    | CiviCRM which is   |
|                    |                    |                    | then submitted     |
|                    |                    |                    | directly to the    |
|                    |                    |                    | payment processor  |
|                    |                    |                    | using an API. It   |
|                    |                    |                    | requires an SSL    |
|                    |                    |                    | connection as well |
|                    |                    |                    | as proper security |
|                    |                    |                    | compliance. The    |
|                    |                    |                    | specific           |
|                    |                    |                    | requirements of    |
|                    |                    |                    | this method will   |
|                    |                    |                    | be available from  |
|                    |                    |                    | the service        |
|                    |                    |                    | provider.          |
+--------------------+--------------------+--------------------+--------------------+
| [button](#CreateaP | 2                  | setExpressCheckout | This mode utilizes |
| ayment-ProcessorEx |                    | (*params*)\        | a three-step       |
| tension-button_mod |                    | getExpressCheckout | method for passing |
| e)                 |                    | Details(*token*)\  | information to the |
|                    |                    | doExpressCheckout( | payment processor. |
|                    |                    | *params*)          | The first step     |
|                    |                    |                    | makes a direct     |
|                    |                    |                    | connection to the  |
|                    |                    |                    | payment processor  |
|                    |                    |                    | API to pass        |
|                    |                    |                    | initial            |
|                    |                    |                    | information about  |
|                    |                    |                    | the transaction.   |
|                    |                    |                    | The user is then   |
|                    |                    |                    | redirected to the  |
|                    |                    |                    | payment processor  |
|                    |                    |                    | checkout form with |
|                    |                    |                    | reference          |
|                    |                    |                    | information to the |
|                    |                    |                    | transaction data   |
|                    |                    |                    | previously         |
|                    |                    |                    | provided to the    |
|                    |                    |                    | API. In the second |
|                    |                    |                    | step, the          |
|                    |                    |                    | transaction        |
|                    |                    |                    | information is     |
|                    |                    |                    | retrieved by the   |
|                    |                    |                    | payment processor  |
|                    |                    |                    | using the          |
|                    |                    |                    | reference          |
|                    |                    |                    | information, the   |
|                    |                    |                    | user provides      |
|                    |                    |                    | payment and        |
|                    |                    |                    | shipping           |
|                    |                    |                    | information and is |
|                    |                    |                    | then redirected    |
|                    |                    |                    | back to the        |
|                    |                    |                    | CiviCRM            |
|                    |                    |                    | confirmation page  |
|                    |                    |                    | where CiviCRM      |
|                    |                    |                    | directly requests  |
|                    |                    |                    | the transaction    |
|                    |                    |                    | information from   |
|                    |                    |                    | the payment        |
|                    |                    |                    | processor API.     |
|                    |                    |                    | Once the user      |
|                    |                    |                    | confirms the       |
|                    |                    |                    | payment, CiviCRM   |
|                    |                    |                    | completes the      |
|                    |                    |                    | third step by      |
|                    |                    |                    | making one final   |
|                    |                    |                    | direct request to  |
|                    |                    |                    | the payment        |
|                    |                    |                    | processor API to   |
|                    |                    |                    | confirm the        |
|                    |                    |                    | transaction.       |
+--------------------+--------------------+--------------------+--------------------+
| special            | 3                  | -                  | This mode is       |
|                    |                    |                    | reserved           |
|                    |                    |                    | exclusively for    |
|                    |                    |                    | PayPal Website     |
|                    |                    |                    | Payments Pro. It   |
|                    |                    |                    | enables PayPal to  |
|                    |                    |                    | have both a 'form' |
|                    |                    |                    | and 'button' mode  |
|                    |                    |                    | available.         |
+--------------------+--------------------+--------------------+--------------------+
| [notify](#CreateaP | 4                  | doTransferCheckout | This mode posts    |
| ayment-ProcessorEx |                    | (*params*,*compone | sales information  |
| tension-notify_mod |                    | nt*)               | from the CiviCRM   |
| e)                 |                    |                    | form to the        |
|                    |                    |                    | service provider's |
|                    |                    |                    | checkout page      |
|                    |                    |                    | where a user will  |
|                    |                    |                    | complete the       |
|                    |                    |                    | transaction by     |
|                    |                    |                    | entering payment   |
|                    |                    |                    | details. Upon      |
|                    |                    |                    | success (or        |
|                    |                    |                    | cancellation) the  |
|                    |                    |                    | user is then       |
|                    |                    |                    | returned to the    |
|                    |                    |                    | original site.     |
|                    |                    |                    | Notification may   |
|                    |                    |                    | take place either  |
|                    |                    |                    | upon the return of |
|                    |                    |                    | the user to the    |
|                    |                    |                    | site, or by a      |
|                    |                    |                    | separate direct    |
|                    |                    |                    | connection from    |
|                    |                    |                    | the payment        |
|                    |                    |                    | processor API to a |
|                    |                    |                    | notification       |
|                    |                    |                    | script on the      |
|                    |                    |                    | site. Because this |
|                    |                    |                    | mode does not      |
|                    |                    |                    | request any        |
|                    |                    |                    | payment            |
|                    |                    |                    | information within |
|                    |                    |                    | CiviCRM, it does   |
|                    |                    |                    | not require the    |
|                    |                    |                    | use of SSL. Some   |
|                    |                    |                    | providers will     |
|                    |                    |                    | still require      |
|                    |                    |                    | testing of a       |
|                    |                    |                    | payment form       |
|                    |                    |                    | before authorising |
|                    |                    |                    | live transactions  |
|                    |                    |                    | to be made.        |
|                    |                    |                    | Specific           |
|                    |                    |                    | requirements vary  |
|                    |                    |                    | and will be        |
|                    |                    |                    | available from the |
|                    |                    |                    | service provider.  |
+--------------------+--------------------+--------------------+--------------------+



## Add the processor to the database

To insert a new payment processor plugin, a new entry must be added to
the `civicrm_payment_processor_type` table.

##  Store any function files from your payment processor

Create an appropriately named folder in the 'packages' directory for any
files provided by your payment processor which have functions you need

##  Write your processor

OK, the groundwork is laid but writing the processor is the hard bit.

Depending on your billing mode there are different considerations - I
have less information and it is less checked on the first two. The file
will live in `CRM/Core/Payment` and have the same name as entered into
your processor_type table.


### Form mode

The function called by this billing mode is

doDirectPayment()

If you return to the calling class at the end of the function the
contribution will be confirmed. Values from the $params array will be
updated based on what you return.  If the transaction does not succeed
you should return an error to avoid confirming the transaction.

The params available to `doDirectPayment()` are: -

- qfKey
- email-(bltID)
- billing_first_name (=first_name)
- billing_middle_name (=middle_name)
- billing_last_name (=last_name)
- location_name-(bltID) = billing_first_name + billing_middle_name + billing_last_name
- street_address
- (bltID)
- city-(bltID)
- state_province_id-(bltID) (int)
- state_province-(bltID) (XX)
- postal_code-(bltID)
- country_id-(bltID) (int)
- country-(bltID) (XX)
- credit_card_number
- cvv2 - credit_card_exp_date - M - Y
- credit_card_type
- amount
- amount_other
- year (credit_card_exp_date =&gt; Y)
- month (credit_card_exp_date =&gt; M)
- ip_address
- amount_level
- currencyID (XXX)
- payment_action
- invoiceID (hex number. hash?)

bltID = Billing Location Type ID. This is not actually seen by the
payment class.

### Button Mode

the function called by this billing mode is

`setExpressCheckout`

The customer is returned to confirm.php with the rfp value set to 1 and

`getExpressCheckoutDetails`

is called when the form is processed

`doExpressCheckout` is called to finalise the payment - a result is
returned to the civiCRM site.

### Notify mode

The function called is

`doTransferCheckout`

The details from here are processor specific but you want to pass
enough details back to your function to identify the transaction. You
should be aiming to have these variables to passthrough the processor to
the confirmation routine:

* contactID
* contributionID
* contributionTypeID
* invoiceID
* membershipID(contribution only)
* participantID (event only)
* eventID (event only)
* component (event or contribute)
* qfkey

Handling the return was the tricky part.

In order to keep the return url short (because paymentexpress appends a
long hex string) our return url goes to a file (in the extern folder )
which redirects through to the 'main' routine in paymentExpressIPN.php
(IPN = instant payment notification). I have stuck a copy of the
framework of it as an attachment to this post

> note - you need to  re-initialise the environment to get civi
> functions to work
>
> require_once '../civicrm.config.php';\
> require_once 'CRM/Core/Config.php';
>
> $config =& CRM_Core_Config::singleton();
>
>  An appropriate structure for the return routine file is:
>
> function newOrderNotify( $success, $privateData,
> $component,$amount,$transactionReference ) {
>
>         $ids = $input = $params = array( );
>
> this version in the paymentexpress file is not processor specific -
> pass it the variables above and it will complete the transaction.
> Success is boolean, the private data array holds, the component is
> (lower case) 'event' or 'contribute' , amount is obvious, transaction
> reference is any processor related reference.
>
> contactID, contributionID, contributionTypeID,invoiceID,
> membershipID(contribution only), participantID (event only), eventID
> (event only)
>
> static function getContext( $privateData, $orderNo)
>
> generic function - taken from google version - retrieves information
> to complete transaction (required?)
>
> private data as above
>
> orderno - transactionreference is OK
>
> Static function main(blah, blah,  blah)
>
> this function is processor specific - it  converts whatever form your
> processor response is into the variables required for the above
> function and if necessary redirects the browser using
>
> CRM_Utils_System::redirect( $finalURL );

## Information about existing Payment processors

**Describe them here so people can see if they are good matches.....**

Google Checkout doesn't support recurring payments nor concurrent
multiple payments (as of April 16, 2009).

(It would be great if a matrix of Payment Processor Features could be
included in the Civi documentation.)

## Porting payment processor code from a previous version of CiviCRM to 4.0

**This has been verified for processor of the type "form", not tested
for others (notify and button).**

Besides looking into the packaging format in order to create a clean
extension, the main changes are:

-   it is no longer necessary to have a MyProcessor.php file in
    `CRM/Contribute/Payment/`, event, and so on.
-   make sure your MyProcessor class implements a "singleton" function
    (see other processors for examples). If this function is missing you
    may have a blank page because Apache has crashed (segfaulted).


## Testing Processor Plugins {:#testing}

Here's some suggestions of what you might test once you have written
your payment processor plug in.

!!! important
    Don't forget that you need to search specifically for TEST transactions

    ie from this page `civicrm/contribute/search&reset=1` chose "find test transactions".

### Std Payment processor tests

1. Can process Successful transaction from

    - Event
    - Contribute Form
    - Individual Contact Record (for on-site processors only)

    Transaction should show as confirmed in CiviCRM and on the payment processor

2. Can include `, . & = ' "` in address and name fields without problems. Overlong ZIP code is handled

3. Can process a failed transaction from a Contribute form

    Can fix up details & resubmit for a successful transaction
    
    e-mail address is successfully passed through to payment processor and
    payment processor sends e-mails if configured to do so.
    
    The invoice ID is processed and maintained in an adequate manner

7. Any result references and transaction codes are stored in an adequate
manner

    Recurring Payment Processor tests
    
    !!! note
        IN Paypal Manager the recurring billing profiles are in Service Settings/Recurring Billing/ Manage Profiles

1. Process a recurring contribution. Check

    - wording on confirm page is acceptable
    - wording on thankyou pages is acceptable
    - wording on any confirmation e-mails is acceptable
    - the payment processor shows the original transaction is successful 
    - the payment processor shows the correct date for the next transaction 
    - the payment processor shows the correct total number of transactions and / or the correct final date

2. Try processing different types of frequencies. Preferably test a monthly contribution on the last day of a month where there isn't a similar day in the following month (e.g. 30 January)

3. Process a transaction without filling in the total number of transactions (there should be no end date set)

4. Process a recurring contribution with the total instalments set to 1 (it should be treated as a one-off rather than a rec urring transaction). It should not show 'recurring contribution' when you search for it in CiviCRM

5. PayflowPro - check that you can edit the frequencies available on the configure contribution page form

6. Depending on your processor it may be important to identify the transactions that need to be updated or checked. You may wish to check what it being recorded in the civicrm_contribution_recur table for payment processor id, end date and next transaction date.

### Specific Live tests

1. Successful and unsuccessful REAL transactions work

2. Money vests into the bank account

3. For recurring transactions wait for the first recurrent transaction
to vest
