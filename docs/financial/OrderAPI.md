The Order API is intended to be used as the primary API for adding, updating, and deleting orders.

An 'order' is a non-CiviCRM term that corresponds to how CiviCRM uses its contribution object in terms of handling the full life-cycle of a purchase of memberships, event registrations or making a donation. Unlike most APIs, there is no table directly associated with the Order API.

Donations, memberships and event registrations are all potential line items in an order/contribution. Pledge payments via a contribution's line item are a potential future enhancement.

The Order API wraps the creation of associated objects like memberships and event registrations. In other words, don't create the objects first before adding them as an array of `line_item`.create parameters; instead rely on the Order API to create them for you.

On creation, the status of contribution and any related memberships or event registrations is Pending if the contribution is pending.

If you later remove a line item for a membership or event registration on an update to an order, the Order API will look after changing the status for the related membership and event registration objects.

Do not try to update the status of a contribution, for example to Completed to reflect a payment, either directly or through the Order API. Instead, do a call to the Payment API for an amount that will complete the required payment. This will transition the status of the contribution to Completed and related membership(s) to New or Current and event registration(s).

As a best practice which we intend to require going forward, the `Order.create` should be called with a status of Pending. Then a `Payment.create` should be called to record a payment.

## Sample `Order.create` for Simple Contribution

Here is how to record a simple donation.

### Step 1

Call `Order.create` with a structure like the below. Note that we always create orders with status Pending (but see Step 2 below).

```json
{
  "contact_id": 202,
  "total_amount": 1.23,
  "financial_type_id": "Donation",
  "receive_date": "2019-10-08",
  "contribution_status_id": "Pending",
  "line_items" : [
    {
      "line_item": [
        "params": { },
        {
          "qty": 1,
          "unit_price": 1.23,
          "line_total": 1.23,
          "price_field_id" : 1,
        }
      ]
    }
  ]
}
```

Things to note:

1. The outer array keys mostly refer to the Contribution record. We set the `contribution_status_id` to `Pending` when we create an order.

2. The `line_items` is an array of objects each having `params` which describes an entity that needs to be created, and a `line_item` key, described next.

3. The `line_item` structure is also an array of line items that all belong to the entity described in the `params` structure. In this example the `params` structure is empty, and we have a single item under `line_item` which therefore is not related to anything other than the contribution.

4. The `line_item` structures use `price_field_id`. This relates to a particular field in a price set. For our simple contribution we can use `1` for which is a special default price field that is always available.

5. The `line_total` *must* equal the `unit_price` × `qty`

!!! bug
    The `total_amount` *must* equal the sum of all the `line_total` values. This is a [current bug](https://lab.civicrm.orog/dev/financial/issues/73) - once fixed you will be able to omit the `total_amount` key and the total will be calculated.


Currently the data returned from `Order.create` shows only the fields from the created Contribution. However an `Order.get` API call for the ID will also include an array of `line_items` (see below for example).

### Step 2

Now we have our order set up we can complete the order by adding a payment for the total value: so we call the `Payment.create` API with at least the following parameters:

```json
{
  "contribution_id": 12345,
  "total_amount": 1.23
}
```

The Payment API works with the Order API to update the records, and an `Order.getsingle` request for the contribution will give something like the following:

```json
{
  "contact_id": "202",
    "contact_type": "Individual",
    "contact_sub_type": "",
    "sort_name": "admin@example.com",
    "display_name": "admin@example.com",
    "contribution_id": "95",
    "currency": "USD",
    "contribution_recur_id": "",
    "contribution_status_id": "1",
    "contribution_campaign_id": "",
    "payment_instrument_id": "4",
    "receive_date": "2019-10-08 12:42:35",
    "non_deductible_amount": "0.00",
    "total_amount": "1.23",
    "fee_amount": "0.00",
    "net_amount": "1.23",
    "trxn_id": "",
    "invoice_id": "",
    "invoice_number": "",
    "contribution_cancel_date": "",
    "cancel_reason": "",
    "receipt_date": "2019-10-08 12:42:36",
    "thankyou_date": "",
    "contribution_source": "",
    "amount_level": "",
    "is_test": "0",
    "is_pay_later": "0",
    "contribution_check_number": "",
    "financial_account_id": "1",
    "accounting_code": "4200",
    "campaign_id": "",
    "contribution_campaign_title": "",
    "financial_type_id": "1",
    "contribution_note": "",
    "contribution_batch": "",
    "civicrm_value_donor_information_3_id": "",
    "custom_6": "",
    "custom_5": "",
    "contribution_recur_status": "Completed",
    "payment_instrument": "Check",
    "contribution_status": "Completed",
    "financial_type": "Donation",
    "check_number": "",
    "instrument_id": "4",
    "cancel_date": "",
    "id": "95",
    "contribution_type_id": "1",
    "line_items": [
      {
        "id": "97",
        "entity_table": "civicrm_contribution",
        "entity_id": "95",
        "contribution_id": "95",
        "price_field_id": "1",
        "qty": "1.00",
        "unit_price": "1.23",
        "line_total": "1.23",
        "price_field_value_id": "1",
        "financial_type_id": "1",
        "non_deductible_amount": "0.00",
        "contribution_type_id": "1"
      }
    ]
}
```

Notes:

1. The `contribution_status_id` is now set to 1 (Completed).

2. The line item has inherited the `financial_type_id` from the contribution.

Behind the scenes these API calls have created lots of financial records as listed below. These records will not be removed no matter what happens to the contribution; if the contribution is cancelled/refunded, *more* financial records are added to create bookkeeping adjustment transactions that achieve the desired accounting result, including the preservation of an auditable log.

- A financial item is created against each line item.
- A row in the `civicrm_financial_trxn` table that describes the financial transaction; the transfer of funds between two accounts from the company's chart of accounts (e.g. in Xero/QuickBooks etc.)
- A row in `civicrm_entity_financial_trxn` links the financial item to a Financial Transaction.
- Another row in `civicrm_entity_financial_trxn` links that financial transaction back to the Contribution.


## Sample Order.create for Single Membership

Here is how to create an order for a single membership of type "General".
Again, we follow the 2 steps: Create the order, then complete the order with
the Payment API.

Here's the parameters for the `Order.create` call, which will create a Pending
Contribution, with a Pending Membership.

```json
{
  "contact_id": 202,
  "total_amount": 100.00,
  "financial_type_id": "Member Dues",
  "receive_date": "2019-10-08",
  "contribution_status_id": "Pending",
  "line_items" : [
    {
      "params": {
        "membership_type_id": "General",
        "contact_id": 202,
        "skipStatusCal": 1,
        "status_id": "Pending"
      },
      "line_item": [
        {
          "entity_table":"civicrm_membership",

          "price_field_id":"4",
          "price_field_value_id":"7",
          "qty":"1",
          "unit_price":"100.00",
          "line_total":"100.00"
        }
      ]
    }
  ]
}
```

After this, when we call `Payment.create` to complete the transaction the membership becomes live and its status will be recalculated (e.g. to 'New').

The `Order.get` request returns all the information about the contribution, the line items and the related membership:

```json
{
    "contact_id": "202",
    "contact_type": "Individual",
    "contact_sub_type": "",
    "sort_name": "Wilma",
    "display_name": "Wilma",
    "contribution_id": "101",
    "currency": "USD",
    "contribution_recur_id": "",
    "contribution_status_id": "1",
    "contribution_campaign_id": "",
    "payment_instrument_id": "4",
    "receive_date": "2019-10-09 17:13:10",
    "non_deductible_amount": "0.00",
    "total_amount": "100.00",
    "fee_amount": "0.00",
    "net_amount": "100.00",
    "trxn_id": "",
    "invoice_id": "",
    "invoice_number": "",
    "contribution_cancel_date": "",
    "cancel_reason": "",
    "receipt_date": "2019-10-09 17:13:10",
    "thankyou_date": "",
    "contribution_source": "",
    "amount_level": "",
    "is_test": "0",
    "is_pay_later": "0",
    "contribution_check_number": "",
    "financial_account_id": "2",
    "accounting_code": "4400",
    "campaign_id": "",
    "contribution_campaign_title": "",
    "financial_type_id": "2",
    "contribution_note": "",
    "contribution_batch": "",
    "civicrm_value_donor_information_3_id": "",
    "custom_6": "",
    "custom_5": "",
    "contribution_recur_status": "Completed",
    "payment_instrument": "Check",
    "contribution_status": "Completed",
    "financial_type": "Member Dues",
    "check_number": "",
    "instrument_id": "4",
    "cancel_date": "",
    "id": "101",
    "contribution_type_id": "2",
    "line_items": [
        {
            "id": "103",
            "entity_table": "civicrm_membership",
            "entity_id": "33",
            "contribution_id": "101",
            "price_field_id": "4",
            "qty": "1.00",
            "unit_price": "100.00",
            "line_total": "100.00",
            "price_field_value_id": "7",
            "financial_type_id": "2",
            "non_deductible_amount": "0.00",
            "contribution_type_id": "2"
        }
    ]
}
```


## Sample Order.create for Single Event Registration

Here is how to create an order for a single ticket purchase for an event.

```json
{
  "contact_id": 202,
  "total_amount": 1000.00,
  "financial_type_id": "Event fee",
  "receive_date": "2019-10-08",
  "contribution_status_id": "Pending",
  "line_items" : [
    {
      "params": {
        "event_id": 3,
        "contact_id": 202,
        "role_id": "Attendee",
        "status_id": "Pending from incomplete transaction"
      },
      "line_item": [
        {
          "entity_table":"civicrm_participant",
          "price_field_id":"7",
          "price_field_value_id":"14",
          "qty":"1",
          "unit_price":"1000.00",
          "line_total":"1000.00"
        }
      ]
    }
  ]
}
```

Notes:

1. As with the other examples, we call it with `contribution_status_id` `Pending`.
2. The `params` define the participant.
3. The `line_item` entry defines the price field and its value.
4. On calling `Payment.create` for this order, the participant's status would be changed to Registered.

!!! bug
    Curently you must pass in `"status_id": "Pending from incomplete transaction"` otherwise the participant is created as Registered even before the paymnet has been made.


## Sample Order.create for 4 line items

@todo

Here is how to create an order for a membership, an event registration, and two separate contribution line items. [ Rich to provide]

