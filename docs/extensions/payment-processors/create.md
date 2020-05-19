# Create a Payment Processor Extension

This page explains how to develop a payment processor extension.

## Intro

A payment processor integration extension typically includes:

1. a Payment Processor class (required) - this provides CiviCRM with a standard interface/API bridge to the third party processor, and a way for CiviCRM to know which features are supported. This must extend `CRM_Core_Payment`.

2. an IPN class - optional, IPN means *Instant Payment Notification*, although they are usually asynchronous and not "instant". Many third parties talk instead about *Webhooks*.

    It refers to the data sent from the third party on various events e.g.:

    - completed/confirmed payment,
    - cancellation of recurring payment,
    - often many more situations - depends heavily on the third party, often configurable in their account administration facilities

    CiviCRM provides a base class for IPN classes `CRM_Core_Payment_BaseIPN`, and a menu route at `civicrm/payment/ipn/<N>` where `<N>` is the payment processor ID.

3. Customisations and additions to the settings. e.g. might use an private *API key instead* of a *password*.

4. Other libraries and helpers for the particular Payment Processor service.


## The Payment Class

A payment processor object *extends* `CRM_Core_Payment`. This class provides CiviCRM with a standard interface/API bridge to the third party processor. It should be found at:

```
<myextension>/CRM/Core/Payment/MyExtension.php
```

The class handles data to do with the third party processor's needs. Different methods will require different data from CiviCRM, e.g. from Contribution or ContributionRecur records, and configuration data for the Payment Processor Service.

!!!important
    Try to avoid infringing on CiviCRM's logic. The methods in your extension should take inputs, communicate with the third party, and return output data that CiviCRM can use to perform its logic. If you find your extension is sending emails, duplicating logic, updating or creating records in CiviCRM, outputting user content (e.g. status messages) then stop, check and consider separating out your code into different methods.

Because things get complex and because `CRM_Core_Payment` is a bridge between CiviCRM's logic and the payment processor service's needs, it's all too easy to end up combining business logic (like updating membership end dates, or deciding whether to send a receipt email) with your calls to the external service. Your extended `CRM_Core_Payment` class should **not** alter business logic. If you find yourself needing to then first discuss this e.g. on the dev channel at <https://chat.civicrm.org> to check if there's a better way.

CiviCRM's Contribution and Event pages are obvious users of your extended `CRM_Core_Payment` class but you should not assume that those are the only consumers; it should be able to be used by other processes too, e.g. Drupal webform or an entirely bespoke process. **Therefore they should not call functions that assume a user context** such as redirects, exits, or setting status messages like `CRM_Core_Error::statusBounce`.

!!! note
    Most methods should throw a `Civi\Payment\Exception\PaymentProcessorException` when they are unable to fulfill the expectations of a method.

## Introducing `PropertyBag` objects

Currently as of CiviCRM v5.24 `$params` is a sprawling array with who-knows-what keys. However, we are moving to a more prescriptive, typed way to pass in parameters by using a `Civi\Payment\PropertyBag` object instead of an array.

This object has getters and setters that enforce standardised property names and a certain level of validation and type casting. For example, the property `contactID` (note capitalisation) has `getContactID()` and `setContactID()`.

For backwards compatibility, this class implements `ArrayAccess` which means if old code does `$propertyBag['contact_id'] = '123'` or `$propertyBag['contactID'] = 123` it will translate this to the new `contactID` property and use that setter which will ensure that accesing the property returns the integer value *123*. When this happens deprecation messages are emitted to the log file. New code should not use array access.

### Checking for existence of a property

Calling a getter for a property that has not been set will throw a `BadMethodCall` exception.

Code can require certain properties by calling
`$propertyBag->require(['contactID', 'contributionID'])` which will throw an `InvalidArgumentException` if any property is missing. These calls should go at the top of your methods so that it's clear to a developer.

You can check whether a property has been set using `$propertyBag->has('contactID')` which will return `TRUE` or `FALSE`.

### Multiple values, e.g. changing amounts

All the getters and setters take an optional extra parameter called `$label`. This can be used to store two (or more) different versions of a property, e.g. 'old' and 'new'

```php
<?php
use Civi\Payment\PropertyBag;
//...
$propertyBag = new PropertyBag();
$propertyBag->setAmount(1.23, 'old');
$propertyBag->setAmount(2.46, 'new');
//...
$propertyBag->getAmount('old'); // 1.23
$propertyBag->getAmount('new'); // 2.46
$propertyBag->getAmount(); // throws BadMethodCall
```

This means the value is still validated and type-cast as an amount (in this example).

### Custom payment processor-specific data

!!! warning "Warning"
    This is currently holding back a fuller adoption of PropertyBag.

Sometimes a payment processor will requrie custom data. e.g. A company called <em>Stripe</em> offers payment processing gateway services with its own API which requires some extra parameters called `paymentMethodID` and `paymentIntentID` - these are what that particular 3rd party requires and separate to anything in CiviCRM (CiviCRM also uses the concept of "payment methods" and these have IDs, but here we're talking about something Stripe needs).

In order for us to be able to implement the `doPayment()` method for Stripe, we'll need data for these custom, bespoke-to-the-third-party parameters passing in, via the `PropertyBag`.

So that any custom, non-CiviCRM data is handled unambiguously, these property names should be prefixed, e.g. `stripe_paymentMethodID` and set using `PropertyBag->setCustomProperty($prop, $value, $label = 'default')`.

The payment class is responsible for validating such data; anything is allowed by `setCustomProperty`, including `NULL`.

**However**, payment classes are rarely responsible for passing data in, this responsibility is for core and custom implementations. Core's contribution forms and other UIs need a way to take the data POSTed by the forms, and arrange it into a standard format for payment classes. They will also have to pass the rest of the data to the payment class so that the payment class can extract and validate anything that is bespoke to that payment processor; i.e. only Stripe is going to know to expect a `paymentMethodID` in this data because this does not exist for other processors. **As of 5.24, this does not exist** and data is still passed into a PropertyBag as an array, which means that unrecognised keys will be added as custom properties, but emit a deprecation warning in your logs.

Best practice right now would be to:

- use PropertyBag getters for the data you want, whether that's a core field or use `getCustomProperty` for anything else.

- where your processor requires adding in custom data to the form, prefix it with your extension's name to avoid ambiguity with core fields. e.g. your forms might use a field called `myprocessor_weirdCustomToken` and you would access this via `$propertyBag->getCustomProperty('myprocessor_weirdCustomToken)`.

----------------------------
**stop reading here, not done the rest yet.**

## IDs galore

There are lots of different mostly string IDs used throughout the process.

### ContributionRecur IDs

Where an ID has a \* by it, the ID, it's up to your payment processor to provide this (if relevant). Everything else should be handled by CiviCRM.

- `id` integer ID, the primary key for the `civicrm_contribution_recur` table.
- `payment_processor_id` integer ID, foreign key to `civicrm_payment_processor.id` which stores the configuration data for the payment processor. Not to be confused with the next item...
- `processor_id` \* string provided by the third party to uniquely identify this recurring payment. Many third parties might provide a *subscription ID* which might be suitable. CiviCRM does not use this internally but it may be useful to payment processors to match a recurring contribution record to the relevant object of the third party's API.
- `payment_token_id` \* string. Optionally used to store a third party token used for administering the recurring payment arrangement.
- `trxn_id` \* string. This is used differently by each payment processor and could be a subscription ID, bank account details, something else or not used.
- `invoice_id` \* string. Must be unique across all ContributionRecur records. May come from the third party or be generated by your payment processor (IS THIS RIGHT?).
- `next_sched_contribution_date` \* Next Scheduled Recurring Contribution
- `financial_type_id` integer points to one of the site's configured Contribution Types like "Donation", or "Event Fees". Payment processors should not normally have anything to do with this; it's not relevant to the third party's purposes.
- `payment_instrument_id` integer points to one of the site's configured *Payment Methods*. **Your payment processor should normally install its own payment method**. The payment instrument for a (recurring or single) contribution is supposed to be set to the payment processor's configured payment instrument value.
- `campaign_id` integer foreign key to a CiviCRM Campaign (if in use). The payment processor should not have much to do with this.
- `contact_id` integer foreign key to a CiviCRM Contact.
- `contribution_status_id`
- <del>`contribution_type_id`</del>, <del>next_sched_contribution</del> - deprecated a long time ago; do not use.

### Contribution records

- `id` integer ID, the primary key for the `civicrm_contribution` table.
- `financial_type_id` integer as for recurring records.
- `contribution_page_id` integer foreign key for payments that were generated by a CiviCRM contribution page.
- `payment_instrument_id` integer as for recurring records.
- `trxn_id` \* string. This is used differently by each payment processor and could be a subscription ID, account+check number, etc. Must be unique.
- `invoice_id` \* string. Must be unique across all Contribution records. May come from the third party or be generated by your payment processor (IS THIS RIGHT?).
- `contribution_recur_id` integer foreign key used when this is part of a recurring contribution.
- `address_id` Conditional foreign key to `civicrm_address.id`. We insert an address record for each contribution when we have associated billing name and address data.
- `campaign_id` integer foreign key to a CiviCRM Campaign (if in use). The payment processor should not have much to do with this.
- `contact_id`/`contribution_contact_id` integer foreign key to a CiviCRM Contact.
- `creditnote_id` string. ??? unique credit note id, system generated or passed in
- <del>`contribution_type_id`</del> <del>`solicitor_id`</del> - deprecated a long time ago; do not use.

# Original page - kept during WIP

This page provides a step-by-step guide to creating a payment processor extension.

It uses an example of one created at the University of California, Merced. It is a basic payment processor which works like this:

1.  Civicrm collects basic information (name, email address,
    amount, etc.) and passes this onto the payment processor and
    redirects to the payment processors website.
2.  The payment processor collects the credit card and any additional
    information and processes the payment.
3.  The payment processor redirects the user back to civicrm at a
    specific url.
4.  The extension then processes the return information and completes
    the payment.

## Research

You need to check your processor's documentation and understand the flow
of the processor's process and compare it to existing processors 

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
    returned real-time (PayPal Pro/Express and Moneris) - or
    posted back in a separate process (PayPal Standard w/ IPN)?

Note that none of the plugins distributed with CiviCRM use a model where
the donor's credit card info is stored in the CiviCRM site's database.
For PayPal Pro, Authorize.net and PayJunction - Credit card numbers, exp
date and security codes are entered on the CiviCRM contribution page and
immediately passed to the processor / not saved. For PayPal Std, Google
Checkout - the info is entered at the processors' site.


## Setup

1. Use civix to [generate an skeleton extension](../civix.md#generate-module)

1. Identify the processor type {:#type}

   Read about [processor types](/extensions/payment-processors/types.md) to find out which type you have.

1. Add the processor to the database

    To insert a new payment processor plugin, a new entry must be added to
    the `civicrm_payment_processor_type` table.

1. Store any function files from your payment processor

    Create an appropriately named folder in the 'packages' directory for any
    files provided by your payment processor which have functions you need

1. Edit the `info.xml` file.

    Edit your [info.xml file](../info-xml.md) and add the [typeInfo section](../info-xml.md#typeInfo) with all relevant child elements.

## Example processor

!!! warning
    The rest of this page is not up to date and needs review. It is left here as some of it may still be helpful, but it should not be considered accurate or best practice.

### Create Initial Processing File

In our example our file name is UCMPaymentCollection so the name of the
file we are going to create within our extension's directory is `CRM/Core/Payment/UCMPaymentCollection.php`

It should have this basic template.

**UCMPaymentCollection.php**

```php
<?php

class edu_ucmerced_payment_ucmpaymentcollection extends CRM_Core_Payment {

  /**
   * mode of operation: live or test
   *
   * @var object
   * @static
   */
  static protected $_mode = null;

  /**
   * Constructor
   *
   * @param string $mode the mode of operation: live or test
   *
   * @return void
   */
  function __construct( $mode, &$paymentProcessor ) {
    $this->_mode             = $mode;
    $this->_paymentProcessor = $paymentProcessor;
    $this->_processorName    = ts('UC Merced Payment Collection');
  }

  /**
   * This function checks to see if we have the right config values
   *
   * @return string the error message if any
   * @public
   */
  function checkConfig( ) {
    $config = CRM_Core_Config::singleton();

    $error = array();

    if (empty($this->_paymentProcessor['user_name'])) {
      $error[] = ts('The "Bill To ID" is not set in the Administer CiviCRM Payment Processor.');
    }

    if (!empty($error)) {
      return implode('<p>', $error);
    }
    else {
      return NULL;
    }
  }

  /**
   * Sets appropriate parameters for checking out to UCM Payment Collection
   *
   * @param array $params  name value pair of contribution datat
   *
   * @return void
   * @access public
   *
   */
  function doPayment(&$params, $component) {

  }
}
```


There are 4 areas that need changing for your specific processor.

The class name needs to match to your chosen class name (directory of
the extension)

```php
class edu_ucmerced_payment_ucmpaymentcollection extends CRM_Core_Payment {
```

The processor name needs to match the name of your processor.

```php
function __construct( $mode, &$paymentProcessor ) {
  $this->_mode             = $mode;
  $this->_paymentProcessor = $paymentProcessor;
  $this->_processorName    = ts('UC Merced Payment Collection');
}
```


You need to process the data given to you in the doTransferCheckout()
method. Here is an example of how it's done in this processor


**UCMPaymentCollection.php-doTransferCheckout**

```php
function doPayment( &$params, $component ) {
    // Start building our paramaters.
    // We get this from the user_name field even though in our info.xml file we specified it was called "Purchase Item ID"
    $UCMPaymentCollectionParams['purchaseItemId'] = $this->_paymentProcessor['user_name'];
    $billID = array(
      $params['invoiceID'],
      $params['qfKey'],
      $params['contactID'],
      $params['contributionID'],
      $params['contributionTypeID'],
      $params['eventID'],
      $params['participantID'],
      $params['membershipID'],
    );
    $UCMPaymentCollectionParams['billid'] =  implode('-', $billID);
    $UCMPaymentCollectionParams['amount'] = $params['amount'];
    if (isset($params['first_name']) || isset($params['last_name'])) {
      $UCMPaymentCollectionParams['bill_name'] = $params['first_name'] . ' ' . $params['last_name'];
    }

    if (isset($params['street_address-1'])) {
      $UCMPaymentCollectionParams['bill_addr1'] = $params['street_address-1'];
    }

    if (isset($params['city-1'])) {
      $UCMPaymentCollectionParams['bill_city'] = $params['city-1'];
    }

    if (isset($params['state_province-1'])) {
      $UCMPaymentCollectionParams['bill_state'] = CRM_Core_PseudoConstant::stateProvinceAbbreviation(
          $params['state_province-1'] );
    }

    if (isset($params['postal_code-1'])) {
      $UCMPaymentCollectionParams['bill_zip'] = $params['postal_code-1'];
    }

    if (isset($params['email-5'])) {
      $UCMPaymentCollectionParams['bill_email'] = $params['email-5'];
    }

    // Allow further manipulation of the arguments via custom hooks ..
    CRM_Utils_Hook::alterPaymentProcessorParams($this, $params, $UCMPaymentCollectionParams);

    // Build our query string;
    $query_string = '';
    foreach ($UCMPaymentCollectionParams as $name => $value) {
      $query_string .= $name . '=' . $value . '&';
    }

    // Remove extra &
    $query_string = rtrim($query_string, '&');

    // Redirect the user to the payment url.
    CRM_Utils_System::redirect($this->_paymentProcessor['url_site'] . '?' . $query_string);

    exit();
  }
}
```


### Create Return Processing File

This is the file that is called by the payment processor after it
returns from processing the payment. Let's call it
UCMPaymentCollectionNotify.php

It should have this template.

**UCMPaymentCollectionNotify.php**

```php
<?php

session_start( );

require_once 'civicrm.config.php';
require_once 'CRM/Core/Config.php';

$config = CRM_Core_Config::singleton();

// Change this to fit your processor name.
require_once 'UCMPaymentCollectionIPN.php';

// Change this to match your payment processor class.
edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN::main();
```

As you can see this includes UCMPaymentCollectionIPN.php Let's create
this file now. Although this file is very large there is only a small
amount of changes needed.


**UCMPaymentCollectionIPN.php**

```php
<?php

class edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN extends CRM_Core_Payment_BaseIPN {

    /**
     * We only need one instance of this object. So we use the singleton
     * pattern and cache the instance in this variable
     *
     * @var object
     * @static
     */
    static private $_singleton = null;

    /**
     * mode of operation: live or test
     *
     * @var object
     * @static
     */
    static protected $_mode = null;

    static function retrieve( $name, $type, $object, $abort = true ) {
      $value = CRM_Utils_Array::value($name, $object);
      if ($abort && $value === null) {
        CRM_Core_Error::debug_log_message("Could not find an entry for $name");
        echo "Failure: Missing Parameter<p>";
        exit();
      }

      if ($value) {
        if (!CRM_Utils_Type::validate($value, $type)) {
          CRM_Core_Error::debug_log_message("Could not find a valid entry for $name");
          echo "Failure: Invalid Parameter<p>";
          exit();
        }
      }

      return $value;
    }

    /**
     * Constructor
     *
     * @param string $mode the mode of operation: live or test
     *
     * @return void
     */
    function __construct($mode, &$paymentProcessor) {
      parent::__construct();

      $this->_mode = $mode;
      $this->_paymentProcessor = $paymentProcessor;
    }

    /**
     * The function gets called when a new order takes place.
     *
     * @param xml   $dataRoot    response send by google in xml format
     * @param array $privateData contains the name value pair of <merchant-private-data>
     *
     * @return void
     *
     */
    function newOrderNotify($success, $privateData, $component, $amount, $transactionReference ) {
        $ids = $input = $params = array( );
        $input['component'] = strtolower($component);
        $ids['contact'] = self::retrieve( 'contactID', 'Integer', $privateData, true);
        $ids['contribution'] = self::retrieve( 'contributionID', 'Integer', $privateData, true);
        if ( $input['component'] == "event" ) {
            $ids['event'] = self::retrieve( 'eventID', 'Integer', $privateData, true);
            $ids['participant'] = self::retrieve( 'participantID', 'Integer', $privateData, true);
            $ids['membership'] = null;
        } else {
            $ids['membership'] = self::retrieve( 'membershipID'  , 'Integer', $privateData, false);
        }
        $ids['contributionRecur'] = $ids['contributionPage'] = null;

        if ( ! $this->validateData( $input, $ids, $objects ) ) {
            return false;
        }

        // make sure the invoice is valid and matches what we have in the contribution record
        $input['invoice'] = $privateData['invoiceID'];
        $input['newInvoice'] = $transactionReference;
        $contribution =& $objects['contribution'];
        $input['trxn_id'] = $transactionReference;

        if ( $contribution->invoice_id != $input['invoice'] ) {
          CRM_Core_Error::debug_log_message( "Invoice values dont match between database and IPN request" );
          echo "Failure: Invoice values dont match between database and IPN request<p>";
          return;
        }

        // lets replace invoice-id with Payment Processor -number because thats what is common and unique
        // in subsequent calls or notifications sent by google.
        $contribution->invoice_id = $input['newInvoice'];

        $input['amount'] = $amount;

        if ( $contribution->total_amount != $input['amount'] ) {
          CRM_Core_Error::debug_log_message( "Amount values dont match between database and IPN request" );
          echo "Failure: Amount values dont match between database and IPN request."\
                      .$contribution->total_amount."/".$input['amount']."<p>";
          return;
        }

        require_once 'CRM/Core/Transaction.php';
        $transaction = new CRM_Core_Transaction( );

        // check if contribution is already completed, if so we ignore this ipn

        if ( $contribution->contribution_status_id == 1 ) {
          CRM_Core_Error::debug_log_message( "returning since contribution has already been handled" );
          echo "Success: Contribution has already been handled<p>";
          return true;
        } else {
          /* Since trxn_id hasn't got any use here,
           * lets make use of it by passing the eventID/membershipTypeID to next level.
           * And change trxn_id to the payment processor reference before finishing db update */
          if ( $ids['event'] ) {
            $contribution->trxn_id = $ids['event'] 
              . CRM_Core_DAO::VALUE_SEPARATOR
              . $ids['participant'];
          } else {
            $contribution->trxn_id = $ids['membership'];
          }
        }
        $this->completeTransaction ( $input, $ids, $objects, $transaction);
        return true;
    }


    /**
     * singleton function used to manage this object
     *
     * @param string $mode the mode of operation: live or test
     *
     * @return object
     * @static
     */
    static function &singleton( $mode, $component, &$paymentProcessor ) {
      if ( self::$_singleton === null ) {
        self::$_singleton = new edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN( $mode,
                                             $paymentProcessor );
      }
      return self::$_singleton;
    }

    /**
     * The function returns the component(Event/Contribute..)and whether it is Test or not
     *
     * @param array   $privateData    contains the name-value pairs of transaction related data
     *
     * @return array context of this call (test, component, payment processor id)
     * @static
     */
    static function getContext($privateData)    {
      require_once 'CRM/Contribute/DAO/Contribution.php';

      $component = null;
      $isTest = null;

      $contributionID = $privateData['contributionID'];
      $contribution = new CRM_Contribute_DAO_Contribution();
      $contribution->id = $contributionID;

      if (!$contribution->find(true)) {
        CRM_Core_Error::debug_log_message("Could not find contribution record: $contributionID");
        echo "Failure: Could not find contribution record for $contributionID<p>";
        exit();
      }

      if (stristr($contribution->source, 'Online Contribution')) {
        $component = 'contribute';
      }
      elseif (stristr($contribution->source, 'Online Event Registration')) {
        $component = 'event';
      }
      $isTest = $contribution->is_test;

      $duplicateTransaction = 0;
      if ($contribution->contribution_status_id == 1) {
        //contribution already handled. (some processors do two notifications so this could be valid)
        $duplicateTransaction = 1;
      }

      if ($component == 'contribute') {
        if (!$contribution->contribution_page_id) {
          CRM_Core_Error::debug_log_message("Could not find contribution page for contribution record: $contributionID");
          echo "Failure: Could not find contribution page for contribution record: $contributionID<p>";
          exit();
        }

        // get the payment processor id from contribution page
        $paymentProcessorID = CRM_Core_DAO::getFieldValue('CRM_Contribute_DAO_ContributionPage',
                              $contribution->contribution_page_id, 'payment_processor_id');
      }
      else {
        $eventID = $privateData['eventID'];

        if (!$eventID) {
          CRM_Core_Error::debug_log_message("Could not find event ID");
          echo "Failure: Could not find eventID<p>";
          exit();
        }

        // we are in event mode
        // make sure event exists and is valid
        require_once 'CRM/Event/DAO/Event.php';
        $event = new CRM_Event_DAO_Event();
        $event->id = $eventID;
        if (!$event->find(true)) {
          CRM_Core_Error::debug_log_message("Could not find event: $eventID");
          echo "Failure: Could not find event: $eventID<p>";
          exit();
        }

        // get the payment processor id from contribution page
        $paymentProcessorID = $event->payment_processor_id;
      }

      if (!$paymentProcessorID) {
        CRM_Core_Error::debug_log_message("Could not find payment processor for contribution record: $contributionID");
        echo "Failure: Could not find payment processor for contribution record: $contributionID<p>";
        exit();
      }

      return array($isTest, $component, $paymentProcessorID, $duplicateTransaction);
    }

    /**
     * This method is handles the response that will be invoked (from UCMercedPaymentCollectionNotify.php) every time
     * a notification or request is sent by the UCM Payment Collection Server.
     *
     */
    static function main() {

    }
}
```

Let's start out with the minor changes that are necessary

Change the class name to fit your class. You can add any ending just as
long as it's consistent everywhere.

```php
class edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN extends CRM_Core_Payment_BaseIPN {
```

Again match class name to fit your class

```php
static function &singleton( $mode, $component, &$paymentProcessor ) {
if ( self::$_singleton === null ) {
            self::$_singleton = new edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN( $mode, $paymentProcessor );
        }
return self::$_singleton;
}
```

Insert your processing code into static function main()


**UCMPaymentCollectionIPN.php - main()**

```php
$config = CRM_Core_Config::singleton();

// Add external library to process soap transaction.
require_once('libraries/nusoap/lib/nusoap.php');

$client = new nusoap_client("https://test.example.com/verify.php", 'wsdl');
$err = $client->getError();
if ($err) {
  echo '<h2>Constructor error</h2><pre>' . $err . '</pre>';
}

// Prepare SoapHeader parameters
$param = array(
  'Username' => 'user',
  'Password' => 'password',
  'TransactionGUID' => $_GET['uuid'],
);

// This will give us some info about the transaction
$result = $client->call('PaymentVerification', array('parameters' => $param), '', '', false, true);

// Make sure there are no errors.
if (isset($result['PaymentVerificationResult']['Errors']['Error'])) {
  if ($component == "event") {
    $finalURL = CRM_Utils_System::url('civicrm/event/confirm',
         "reset=1&cc=fail&participantId={$privateData[participantID]}", false, null, false);
  } elseif ( $component == "contribute" ) {
    $finalURL = CRM_Utils_System::url('civicrm/contribute/transact',
         "_qf_Main_display=1&cancel=1&qfKey={$privateData['qfKey']}", false, null, false);
  }
}
else {
  $success = TRUE;
  $ucm_pc_values = $result['PaymentResult']['Payment'];

  $invoice_array = explode('-', $ucm_pc_values['InvoiceNumber']);

  // It is important that $privateData contains these exact keys.
  // Otherwise getContext may fail.
  $privateData['invoiceID'] = (isset($invoice_array[0])) ? $invoice_array[0] : '';
  $privateData['qfKey'] = (isset($invoice_array[1])) ? $invoice_array[1] : '';
  $privateData['contactID'] = (isset($invoice_array[2])) ? $invoice_array[2] : '';
  $privateData['contributionID'] = (isset($invoice_array[3])) ? $invoice_array[3] : '';
  $privateData['contributionTypeID'] = (isset($invoice_array[4])) ? $invoice_array[4] : '';
  $privateData['eventID'] = (isset($invoice_array[5])) ? $invoice_array[5] : '';
  $privateData['participantID'] = (isset($invoice_array[6])) ? $invoice_array[6] : '';
  $privateData['membershipID'] = (isset($invoice_array[7])) ? $invoice_array[7] : '';

  list($mode, $component, $paymentProcessorID, $duplicateTransaction) = self::getContext($privateData);
  $mode = $mode ? 'test' : 'live';

  require_once 'CRM/Core/BAO/PaymentProcessor.php';
  $paymentProcessor = CRM_Core_BAO_PaymentProcessor::getPayment($paymentProcessorID, $mode);

  $ipn=& self::singleton( $mode, $component, $paymentProcessor );

  if ($duplicateTransaction == 0) {
    // Process the transaction.
    $ipn->newOrderNotify($success, $privateData, $component,
            $ucm_pc_values['TotalAmountCharged'], $ucm_pc_values['TransactionNumber']);
  }

  // Redirect our users to the correct url.
  if ($component == "event") {
    $finalURL = CRM_Utils_System::url('civicrm/event/register',
        "_qf_ThankYou_display=1&qfKey={$privateData['qfKey']}", false, null, false);
  }
  elseif ($component == "contribute") {
    $finalURL = CRM_Utils_System::url('civicrm/contribute/transact',
        "_qf_ThankYou_display=1&qfKey={$privateData['qfKey']}", false, null, false);
  }
}

CRM_Utils_System::redirect( $finalURL );
```
### Populate Help Text on the Payment Processor Administrator Screen
To populate the blue help icons for the settings fields needed for your payment processor at **Administer -> System Settings -> Payment Processors** follow the steps below:

1. Add a template file to your extension with a `!#twig {htxt id='$ppTypeName-live-$fieldname'}` section for each settings field you are using.

    **Example:**

    The help text for the `user-name` field for a payment processor with the name 'AuthNet' would be implemented with code like this:

    ```twig
    {htxt id='AuthNet-live-user-name'}
    {ts}Generate your API Login and Transaction Key by logging in to your Merchant Account and navigating to <strong>Settings &raquo; General Security Settings</strong>.{/ts}</p>
    {/htxt}
```

    see [core /templates/CRM/Admin/Page/PaymentProcessor.hlp](https://github.com/civicrm/civicrm-core/blob/master/templates/CRM/Admin/Page/PaymentProcessor.hlp) for further examples.
1. Add that template to the `CRM_Admin_Form_PaymentProcessor` form using a buildForm hook like so:

    ```php
    if ($formName == 'CRM_Admin_Form_PaymentProcessor') {
        $templatePath = realpath(dirname(__FILE__) . "/templates");
        CRM_Core_Region::instance('form-buttons')->add(array(
          'template' => "{$templatePath}/{TEMPLATE FILE NAME}.tpl",
        ));
      }
     ```

### Add Any Additional Libraries Needed

In the case of this payment processor we needed nusoap. So in the
extension directory we created a libraries directory and put it there.
Make sure you look at any licensing restrictions before distributing
your extension with the new library.


## Testing Processor Plugins {:#testing}

!!! warning
    This section is still mostly valid advice but your extension should also contain a suite of PHPUnit tests that show how your code is supposed to function in all the given situations it handles.

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
