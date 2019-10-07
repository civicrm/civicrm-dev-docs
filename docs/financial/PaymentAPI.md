CiviCRM originally supported contributions that had a single amount and were paid with a single payment. 

Double entry bookkeeping support was added, including for Accounts Receivable. 

Support for partial payments against an outstanding amount was gradually added. 

Refunds and credit notes for paid but now no longer owed amounts were also added.

The complexity that has been added to what is being recorded regarding payments against an owed contribution motivated the creation of a new Payment API.

Historically, it has been possible to record a payment against a contribution by changing its payment status, for example from Pending to Completed. 

It is now best practice to use the Payment.create API call.

After a contribution has been created, for example using the best practice Order.create api call, here are some examples of using the Payment API on it.

Full payment for a simple one line item Contribution.


Partical payment of a one line item Contribution.


Subsequent payment a partially paid one line item Contribution.


Mark a previously recorded payment as failed.


Cancel a previously recorded payment. Sample use case: please don't cash the cheque I gave you as I now want to pay by credit card.


By way of contrast, here is how to cancel a previously created contribution. I've decided I don't want to give anything anymore / go to the event.


Cause the Refund through a payment processor of a contribution payment. Monish to fill in.


Record the offline refund of part or all of a contribution. Monish to fill in.




