Payment API

Historically, one recorded a payment against a contribution by changing its payment status, for example from Pending to Completed. 

It is now best practice to use the Payment.create API call.

Note that paymentprocessor.pay handles the communication with a payment processor to instigate a payment. Similarly, paymentprocessor.refund handles the communication with a payment processor to instigate a refund.

After a contribution has been created, for example using the best practice Order.create api call, use the Payment API to:

- record a full payment
- record a partial payment or subsequent payment
- record that a payment was cancelled (not the same as cancelling the whole contribution)
- record the refund of a payment

