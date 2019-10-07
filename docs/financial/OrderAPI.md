The Order API is intended to be used as the primary API for adding, updating, and deleting orders. 

An 'order' is a non-CiviCRM term that corresponds to how CiviCRM uses its contribution object in terms of handling the full life-cycle of a purchase of memberships, event registrations or making a donation. Unlike most APIs, there is no table directly associated with the Order API. 

Donations, memberships and event registrations are all potential line items in an order/contribution. Pledge payments via a contribution's line item are a potential future enhancement.

The Order API wraps the creation of associated objects like memberships and event registrations. In other words, don't create the objects first before adding them as an array of line_item.create parameters; instead rely on the Order API to create them for you. 

On creation, the status of contribution and any related memberships or event registrations is Pending if the contribution is pending. 

If you later remove a line item for a membership or event registration on an update to an order, the Order API will look after changing the status for the related membership and event registration objects.  

Do not try to update the status of a contribution, for example to Completed to reflect a payment, either directly or through the Order API. Instead, do a call to the Payment API for an amount that will complete the required payment. This will transition the status of the contribution to Completed and related membership(s) to New or Current and event registration(s).

As a best practice which we intend to require going forward, the OrderAPI.create should be called with a status of Pending. Then a PaymentAPI.create should be called to record a payment.

Sample Order.create for Simple Contribution

Here is how to create an order for a contribution with a quick config price set. [Rich to provide drawing inspiration from https://github.com/civicrm/civicrm-core/blob/master/tests/phpunit/api/v3/OrderTest.php and https://wiki.civicrm.org/confluence/display/CRM/Order+API]
[Rich: we want to create with Contribution status of Pending, not specify the contribution total

Sample Order.create for Single Membership

Here is how to create an order for a single membership. [Rich to provide]


Sample Order.create for Single Event Registration

Here is how to create an order for a single ticket purchase for an event. [Rich to provide]


Sample Order.create for 4 line items

Here is how to create an order for a membership, an event registration, and two separate contribution line items. [ Rich to provide]

