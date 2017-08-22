# Payment Processor Types

In CiviCRM there are four processor 'types'. These are used by CiviCRM to determine how to process a transaction.

!!! note
    "Processor type" is also referred to as "billing mode"

## `form` {:#form}

This mode allows the credit card information to be collected on a form within CiviCRM which is then submitted directly to the payment processor using an API. It requires an SSL connection as well as proper security compliance. The specific requirements of this method will be available from the service provider.

* `billing_mode_id`: 1
* Functions called:
    * `doDirectPayment($params)`

If you return to the calling class at the end of the function the
contribution will be confirmed. Values from the `$params` array will be
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

## `button` {:#button}

This mode utilizes a three-step method for passing information to the payment processor. The first step makes a direct connection to the payment processor API to pass initial information about the transaction. The user is then redirected to the payment processor checkout form with reference information to the transaction data previously provided to the API. In the second step, the transaction information is retrieved by the payment processor using the reference information, the user provides payment and shipping information and is then redirected back to the CiviCRM confirmation page where CiviCRM directly requests the transaction information from the payment processor API. Once the user confirms the payment, CiviCRM completes the third step by making one final direct request to the payment processor API to confirm the transaction.

* `billing_mode_id`: 2

The main function called by this billing mode is `setExpressCheckout($params)`. The customer is returned to `confirm.php` with the `rfp` value set to 1 and `getExpressCheckoutDetails($token)` is called when the form is processed `doExpressCheckout($params)` is called to finalise the payment - a result is returned to the civiCRM site.

## `notify` {:#notify}

The notify method deals with a situation where there is not a direct two way communication between your server and the processor and there is a need for your server to identify which transaction is being responded to.
 
This mode posts sales information from the CiviCRM form to the service provider's checkout page where a user will complete the transaction by entering payment details. Upon success (or cancellation) the user is then returned to the original site. Notification may take place either upon the return of the user to the site, or by a separate direct connection from the payment processor API to a notification script on the site. Because this mode does not request any payment information within CiviCRM, it does not require the use of SSL. Some providers will still require testing of a payment form before authorising live transactions to be made. Specific requirements vary and will be available from the service provider.

* `billing_mode_id`: 4
* Functions called:
    * `doTransferCheckout($params, $component)`

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
(IPN = instant payment notification). 

note - you need to  re-initialise the environment to get civi
functions to work

```php
require_once '../civicrm.config.php';
require_once 'CRM/Core/Config.php';
$config =& CRM_Core_Config::singleton();
```

An appropriate structure for the return routine file is:

```php
function newOrderNotify(
    $success,
    $privateData,
    $component,
    $amount,
    $transactionReference ) {
  $ids = $input = $params = array( );
}
```

this version in the paymentexpress file is not processor specific -
pass it the variables above and it will complete the transaction.
Success is boolean, the private data array holds, the component is
(lower case) 'event' or 'contribute' , amount is obvious, transaction
reference is any processor related reference.

contactID, contributionID, contributionTypeID,invoiceID,
membershipID(contribution only), participantID (event only), eventID
(event only)

static function getContext( $privateData, $orderNo)

generic function - taken from google version - retrieves information
to complete transaction (required?)

private data as above

orderno - transactionreference is OK

```php
static function main(blah, blah,  blah)
```

this function is processor specific - it  converts whatever form your
processor response is into the variables required for the above
function and if necessary redirects the browser using

```php
CRM_Utils_System::redirect( $finalURL );
```


## `special` {:#special}

This mode is reserved exclusively for PayPal Website Payments Pro. It enables PayPal to have both a 'form' and 'button' mode available.

* `billing_mode_id`: 3
