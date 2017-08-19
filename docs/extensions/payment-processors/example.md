# Example of creating a payment processor extension

<div class="panelMacro">

Use a module extension instead of a payment processor extension

This page has some useful bits, but if you want to contribute a new payment processor for CiviCRM, you should use the module extension, as per Create an Extension.
</div>



<div>

-   [Overview](#Exampleofcreatingapaymentprocessorextension-Overview)
-   [Creating the
    extension](#Exampleofcreatingapaymentprocessorextension-Creatingtheextension)

<!-- -->

-   [1. Create the
    extension directory.](#Exampleofcreatingapaymentprocessorextension-1.Createtheextensiondirectory.)
-   [2. Create the
    info.xml file.](#Exampleofcreatingapaymentprocessorextension-2.Createtheinfo.xmlfile.)

<!-- -->

-   [Billing
    modes](#Exampleofcreatingapaymentprocessorextension-Billingmodes)

<!-- -->

-   [3. Create the default payment processor
    file](#Exampleofcreatingapaymentprocessorextension-3.Createthedefaultpaymentprocessorfile)

<!-- -->

-   [Form](#Exampleofcreatingapaymentprocessorextension-Form)
-   [Button](#Exampleofcreatingapaymentprocessorextension-Button)
-   [Notify](#Exampleofcreatingapaymentprocessorextension-Notify)

<!-- -->

-   [Create initial
    processing file.](#Exampleofcreatingapaymentprocessorextension-Createinitialprocessingfile.)
-   [Create return processing
    file](#Exampleofcreatingapaymentprocessorextension-Createreturnprocessingfile)

<!-- -->

-   [4. Add any additional libraries
    needed](#Exampleofcreatingapaymentprocessorextension-4.Addanyadditionallibrariesneeded)

</div>

## Overview

This tutorial will help you create a new payment processor extension.
It will use an example of one created at the University of California,
Merced.  It is a basic payment processor which works like this:

1.  Civicrm collects basic information (name, email address,
    amount, etc.) and passes this onto the payment processor and
    redirects to the payment processors website.
2.  The payment processor collects the credit card and any additional
    information and processes the payment.
3.  The payment processor redirects the user pack to cvicrm at a
    specific url.
4.  The extension then processes the return information and completes
    the payment.

## Creating the extension

### 1. Create the extension directory.

The extension directory needs to be created in a specific way. It is in
the form of extensionid.type.extensionName.  So in the extension we are
creating it uses edu.ucmerced.payment.ucmpaymentcollection

### 2. Create the info.xml file.

In your new extension directory create an info.xml file. The layout of
the file will look like this:

<div class="code panel" style="border-style: solid;border-width: 1px;">

<div class="codeHeader panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;">

**info.xml**

</div>

<div class="codeContent panelContent">

    <?xml version="1.0" encoding="UTF-8" ?>
     <extension key="[Your Directory Name]" type="payment">
      <file>[Name of the payment processor file]</file>
      <name>[Name of your application with Underscores]</name>
      <description>[Description of your application]</description>
      <urls>
        <url desc="Main Extension Page">[extension url]</url>
        <url desc="Documentation">[documentation url]</url>
        <url desc="Support">[support url]</url>
        <url desc="Licensing">[license url]</url>
      </urls>
      <license>[extension license]</license>
      <maintainer>
        <author>[Author Name]</author>
        <email>[Author Email Address]</email>
      </maintainer>
      <releaseDate>[Release Date]</releaseDate>
      <version>[Extension Version Number]</version>
      <develStage>[Extension status ex. stable, development, etc.]</develStage>
      <compatibility><ver>[compatible with which versions of civicrm]</ver></compatibility>
      <comments>[Any additional comments]</comments>
      <typeInfo>
       <userNameLabel>[Label to use for the username field]</userNameLabel>
       <passwordLabel>[Label to use for the password field]</passwordLabel>
       <signatureLabel>[Label to use for the signature field]</signatureLabel>
       <subjectLabel>[Label to use for the subject field]</subjectLabel>
       <urlSiteDefault>[The url to use to process live site transactions]</urlSiteDefault>
       <urlApiDefault>[NEEDS A DESCRIPTION]</urlApiDefault>
       <urlRecurDefault>[NEEDS A DESCRIPTION]</urlRecurDefault>
       <urlSiteTestDefault>[The url to use to process test site transactions]</urlSiteTestDefault>
       <urlApiTestDefault>[NEEDS A DESCRIPTION]</urlApiTestDefault>
       <urlRecurTestDefault>[NEEDS A DESCRIPTION]</urlRecurTestDefault>
       <urlButtonDefault>[NEEDS A DESCRIPTION]</urlButtonDefault>
       <urlButtonTestDefault>[NEEDS A DESCRIPTION]</urlButtonTestDefault>
       <billingMode>[See below for description of billing modes]</billingMode>
       <isRecur>[NEEDS A DESCRIPTION]</isRecur>
       <paymentType>[NEEDS A DESCRIPTION]</paymentType>
      </typeInfo>
    </extension>

</div>

</div>

#### Billing modes

-   **form** - a form is where the credit card information is collected
    on a form on your site and submitted to the payment processor
-   **button** - As I understand it buttons rely on important
    information (success, variables etc) being communicated directly
    between your server and the payment processor. (e.g. in the paypal
    express method. the customer is transferred to the server to enter
    their details but the transaction is not pushed through until an
    html request is sent from your server to the processor and the
    server replies with the response. The server also uses html to query
    certain variables from the server. From what I remember CURL is used
    for this.) The user's session remains intact but I'm not sure if
    session variables or variables sent from the payment processor are
    used to identify the transaction and customize what the user sees
-   **notify** - the notify method deals with a situation where there is
    not a direct two way communication between your server and the
    processor and there is a need for your server to identify which
    transaction is being responded to. This was the method I worked with
    (and seemingly most people who were looking at it at the same time).
    The payment processor I worked on sends two confirmations - one html
    GET via the user's browser and a later html GET from the payment
    processor server. If the user's browser never returns the processor
    needs to be able to figure out which transaction is involved & to
    complete it. If the GET is from the user's browser it needs to do
    the same thing but also redirect the user appropriately.

So for the extension we are building it looks like

<div class="code panel" style="border-style: solid;border-width: 1px;">

<div class="codeHeader panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;">

**info.xml**

</div>

<div class="codeContent panelContent">

    <?xml version="1.0" encoding="UTF-8" ?>
     <extension key="edu.ucmerced.payment.ucmpaymentcollection" type="payment">
      <file>UCMPaymentCollection</file>
      <name>UCMPaymentCollection</name>
      <description>UC Merced Payment Processor</description>
      <urls>
        <url desc="Main Extension Page"></url>
        <url desc="Documentation"></url>
        <url desc="Support"></url>
        <url desc="Licensing"></url>
      </urls>
      <license>AGPL</license>
      <maintainer>
        <author>Adam Moore</author>
        <email>mail@example.com</email>
      </maintainer>
      <releaseDate>2010-12-16</releaseDate>
      <version>1.0</version>
      <develStage>stable</develStage>
      <compatibility><ver>3.3</ver></compatibility>
      <comments></comments>
      <typeInfo>
       <userNameLabel>Purchase Item ID</userNameLabel>
       <passwordLabel></passwordLabel>
       <signatureLabel></signatureLabel>
       <subjectLabel></subjectLabel>
       <urlSiteDefault>https://live.exmpale.com</urlSiteDefault>
       <urlApiDefault></urlApiDefault>
       <urlRecurDefault></urlRecurDefault>
       <urlSiteTestDefault>https://test.example.com</urlSiteTestDefault>
       <urlApiTestDefault></urlApiTestDefault>
       <urlRecurTestDefault></urlRecurTestDefault>
       <urlButtonDefault></urlButtonDefault>
       <urlButtonTestDefault></urlButtonTestDefault>
       <billingMode>notify</billingMode>
       <isRecur>0</isRecur>
       <paymentType>1</paymentType>
      </typeInfo>
    </extension>

</div>

</div>

### 3. Create the default payment processor file

The methods that exist are different depending on what Billing Mode you
used above.

#### Form

**Method Called:**\
doDirectPayment()

**Notes:**

If you return to the calling class at the end of the function the
contribution will be confirmed. Values from the $params array will be
updated based on what you return. If the transaction does not succeed
you should return an error to avoid confirming the transaction.

**Available Parameters:**

-   qfKey
-   email-(bltID)
-   billing_first_name
-   billing_middle_name
-   billing_last_name
-   location_name = billing_first_name + billing_middle_name +
    billing_last_name
-   streeet_address
-   city
-   state_province_id
-   state_province
-   postal_code
-   country_id
-   country
-   credit_card_number
-   cvv2 - credit_card_exp_date - M - Y
-   credit_card_type
-   amount
-   amount_other
-   year - credit_card_exp_date year
-   month - credit_card_exp_date month
-   ip_address
-   amount_level
-   currencyID
-   payment_action
-   invoiceID

#### Button

**Method Called:**\
setExpressCheckout()

**Notes:**\
The customer is returned to confirm.php with the rfp value set to 1 and

getExpressCheckoutDetails()

is called when the form is processed

doExpressCheckout() is called to finalise the payment - a result is
returned to the civiCRM site.

**Available Parameters:**

#### Notify

**Method Called:**\
doTransferCheckout()

**Notes:**\
The details from here are processor specific but you want to pass enough
details back to your function to identify the transaction. You should be
aiming to have these variables to passthrough the processor to the
confirmation routine:

-   contactID
-   contributionID
-   contributionTypeID
-   invoiceID
-   membershipID(contribution only)
-   participantID (event only)
-   eventID (event only)
-   component (event or contribute)
-   qfkey

**Available Parameters:**

**Example:**

##### Create initial processing file.

In our example our file name is UCMPaymentCollection so the name of the
file we are going to create is UCMPaymentCollection.php

It should have this basic template.

<div class="code panel" style="border-style: solid;border-width: 1px;">

<div class="codeHeader panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;">

**UCMPaymentCollection.php**

</div>

<div class="codeContent panelContent">

    <?php

    require_once 'CRM/Core/Payment.php';

    class edu_ucmerced_payment_ucmpaymentcollection extends CRM_Core_Payment {
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
       * singleton function used to manage this object
       *
       * @param string $mode the mode of operation: live or test
       *
       * @return object
       * @static
       *
       */
      static function &singleton( $mode, &$paymentProcessor ) {
          $processorName = $paymentProcessor['name'];
          if (self::$_singleton[$processorName] === null ) {
              self::$_singleton[$processorName] = new edu_ucmerced_payment_ucmpaymentcollection( $mode, $paymentProcessor );
          }
          return self::$_singleton[$processorName];
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

      function doDirectPayment(&$params) {
        CRM_Core_Error::fatal(ts('This function is not implemented'));
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
      function doTransferCheckout( &$params, $component ) {

      }
    }

</div>

</div>

There are 4 areas that need changing for your specific processor.

The class name needs to match to your chosen class name (directory of
the extension)

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    class edu_ucmerced_payment_ucmpaymentcollection extends CRM_Core_Payment {

</div>

</div>

The processor name needs to match the name of your processor.

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    function __construct( $mode, &$paymentProcessor ) {
      $this->_mode             = $mode;
      $this->_paymentProcessor = $paymentProcessor;
      $this->_processorName    = ts('UC Merced Payment Collection');
    }

</div>

</div>

This method should call your class name

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    static function &singleton( $mode, &$paymentProcessor ) {
        $processorName = $paymentProcessor['name'];
        if (self::$_singleton[$processorName] === null ) {
            self::$_singleton[$processorName] = new edu_ucmerced_payment_ucmpaymentcollection( $mode, $paymentProcessor );
        }
        return self::$_singleton[$processorName];
    }

</div>

</div>

You need to process the data given to you in the doTransferCheckout()
method. Here is an example of how it's done in this processor

<div class="code panel" style="border-style: solid;border-width: 1px;">

<div class="codeHeader panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;">

**UCMPaymentCollection.php-doTransferCheckout**

</div>

<div class="codeContent panelContent">

    function doTransferCheckout( &$params, $component ) {
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

</div>

</div>

##### Create return processing file

This is the file that is called by the payment processor after it
returns from processing the payment. Let's call it
UCMPaymentCollectionNotify.php

It should have this template.

<div class="code panel" style="border-style: solid;border-width: 1px;">

<div class="codeHeader panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;">

**UCMPaymentCollectionNotify.php**

</div>

<div class="codeContent panelContent">

    <?php

    session_start( );

    require_once 'civicrm.config.php';
    require_once 'CRM/Core/Config.php';

    $config = CRM_Core_Config::singleton();

    // Change this to fit your processor name.
    require_once 'UCMPaymentCollectionIPN.php';

    // Change this to match your payment processor class.
    edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN::main();

</div>

</div>

As you can see this includes UCMPaymentCollectionIPN.php Let's create
this file now. Although this file is very large there is only a small
amount of changes needed.

<div class="code panel" style="border-style: solid;border-width: 1px;">

<div class="codeHeader panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;">

**UCMPaymentCollectionNotify.php**

</div>

<div class="codeContent panelContent">

    <?php

    require_once 'CRM/Core/Payment/BaseIPN.php';

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
        function newOrderNotify( $success, $privateData, $component, $amount, $transactionReference ) {
            $ids = $input = $params = array( );

            $input['component'] = strtolower($component);

            $ids['contact']          = self::retrieve( 'contactID'     , 'Integer', $privateData, true );
            $ids['contribution']     = self::retrieve( 'contributionID', 'Integer', $privateData, true );

            if ( $input['component'] == "event" ) {
                $ids['event']       = self::retrieve( 'eventID'      , 'Integer', $privateData, true );
                $ids['participant'] = self::retrieve( 'participantID', 'Integer', $privateData, true );
                $ids['membership']  = null;
            } else {
                $ids['membership'] = self::retrieve( 'membershipID'  , 'Integer', $privateData, false );
            }
            $ids['contributionRecur'] = $ids['contributionPage'] = null;

            if ( ! $this->validateData( $input, $ids, $objects ) ) {
                return false;
            }

            // make sure the invoice is valid and matches what we have in the contribution record
            $input['invoice']    =  $privateData['invoiceID'];
            $input['newInvoice'] =  $transactionReference;
            $contribution        =& $objects['contribution'];
            $input['trxn_id']  =    $transactionReference;

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
                    $contribution->trxn_id =
                        $ids['event']       . CRM_Core_DAO::VALUE_SEPARATOR .
                        $ids['participant'] ;
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

</div>

</div>

Let's start out with the minor changes that are necessary

Change the class name to fit your class. You can add any ending just as
long as it's consistent everywhere.

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    class edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN extends CRM_Core_Payment_BaseIPN {

</div>

</div>

Again match class name to fit your class

<div class="code panel" style="border-width: 1px;">

<div class="codeContent panelContent">

    static function &singleton( $mode, $component, &$paymentProcessor ) {
    if ( self::$_singleton === null ) {
                self::$_singleton = new edu_ucmerced_payment_ucmpaymentcollection_UCMPaymentCollectionIPN( $mode, $paymentProcessor );
            }
    return self::$_singleton;
    }

</div>

</div>

Insert your processing code into static function main()

<div class="code panel" style="border-style: solid;border-width: 1px;">

<div class="codeHeader panelHeader"
style="border-bottom-width: 1px;border-bottom-style: solid;">

**UCMPaymentCollectionNotify.php - main()**

</div>

<div class="codeContent panelContent">

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

</div>

</div>

### 4. Add any additional libraries needed

In the case of this payment processor we needed nusoap. So in the
extension directory we created a libraries directory and put it there.
Make sure you look at any licensing restrictions before distributing
your extension with the new library.
