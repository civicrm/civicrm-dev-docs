!!! abstract
    This area of CiviCRM code and documentation is a work-in-progress. Not all features
    will be documented and the core code underlying this area may change from version
    to version.

## Payment API

Historically, one recorded a payment against a contribution by changing its payment status, for example from Pending to Completed. 

It is now best practice to use the Payment.create API call.

Note that paymentprocessor.pay handles the communication with a payment processor to instigate a payment. Similarly, paymentprocessor.refund handles the communication with a payment processor to instigate a refund.

After a contribution has been created, for example using the best practice Order.create API call, use the Payment.create API action to

* record a payment against the contribution - either fully or partially paying the contribution amount
* record a refund against the contribution

Use the Payment.cancel api to reverse a refunded payment.

