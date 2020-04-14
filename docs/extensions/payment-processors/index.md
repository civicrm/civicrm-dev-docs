# Payment Processors

This page documents payment processing in CiviCRM. The key bit to understand is the payment processor object, a civicrm-specific way of representing and integrating an actual real-life payment processor (e.g. like Paypal, etc.). The object and how it is used (as of CiviCRM 4.4) is in need of some love and refactoring, so this page hopes to provide some help in that direction.


## Definitions

Some of these definitions are a bit loose, but should at least help
clarify the use of these over-used words in the rest of the page.

**Payment Processor Provider** is a commercial entity that will sell you
one or more payment processor services.

**Payment Processor** is a real-life service or process that enables a
constituent to give money to the organization that owns the CiviCRM
instance. Many payment processor service providers enable more than one
payment processor. Not all payment processors need to be attached to a
provider (in theory).

**Payment Class** is a php object class that represents a payment using
a processor, and provides methods and properties that integrate the
processor's functions into CiviCRM.

**Payment Type** is a CiviCRM-specific classification of payment types -
an integer representing the 'type' of payment this payment processor
class supports. Almost all existing payment processors are of type 1,
for credit cards, but there is some support in code for 2 ('direct
debit'). The primary use of this type is for generating a base set of
fields in the user form.

**Billing Mode** is a CiviCRM-specific classification of different types
of payment processing: 'form', 'button', or 'notify'. These determine
the user workflow required and how the CiviCRM code works. The concept is
mostly obsolete now.

## Processors

Online payment processing is a relatively recent innovation and is still
evolving. The earliest widely used, and still most widely used payment
processor provider is Paypal, and it has some pretty unique features
both from a user and from a developer perspective. Most other payment
processors created since then provided a simpler set of features, only
work with credit cards, and mostly work in a similar way from a
developer perspective - simply posting a set of named fields/values to
an https url.

More recently, payment processors have started evolving in new ways to
support mobile, swipe payments, and many kinds of non-credit card
payments, and also have developed new ways to deal with security
challenges.

The biggest issue for payment processor interface is the balance between
customization and security. For example - the most secure kinds of
interfaces are usually those hosted on the payment processor servers,
but those are also the least customizable, and risk losing potential
donors, for example. Hosting the payment page on your own site makes for
a more customizable user experience, but creates new security demands on
your whole server and code infrastructure. There are some new methods
now evolving that support a kind of hybrid (e.g. Stripe, and iATS Direct
Post method). There are a complex set of requirements called PCI for any
server dealing with credit card payments.

Because the PCI requirements escalate dramatically when storing credit
card numbers on your server, another key challenge of payment processors
is to provide recurring payment functionality. There are two strategies
here: control the recurring payments with the payment processing service
provider, or use a 'token' based system that allows CiviCRM to control
the amount and schedule of the payments, but use only a token to
identify the payer.

A challenge for any tool like CiviCRM is to provide supporting code and
infrastructure for these payment processors that are all quite diverse
in their ways of working, needs, and language. Two useful examples that
are relatively successful in this challenge are [Drupal's commerce
module](http://drupal.org/project/commerce), and the
[omnipay
project](https://github.com/thephpleague/omnipay).

## Payment Class

CiviCRM's integration of a payment processor is based on subclassing the
abstract class CRM_Core_Payment. That class is defined by the file
`CRM/Core/Payment.php`, and each supported payment processor has a file in
the directory `CRM/Core/Payment` that subclasses this abstract class. Any
extensions that provide a payment processor have to provide a
corresponding subclass file in their own `CRM/Core/Payment directory`.

The key methods are roughly documented in the page on [creating a payment processor](../payment-processors/create.md)
but the main method for all processors to implement is `doPayment`

The payment object is used in several places in the CiviCRM code base.
It's intention is to encapsulate all the details of processing a
payment, so it can be used for a simple donation, a recurring series of
donations, a payment for a membership, a payment for an event, and some
combination of those (e.g. a recurring series of payments for an
auto-renewed membership).

The way that payment objects get created historically was a bit convoluted. 
They are mostly now created by the \Civi\Payment\System

Due to the historical complexity we sometimes use the id of the
live processor in test mode for the test processor although
where possible we use the id of the test processor itself.

## Payment Processor Objects, and in the Database

Most object instantiations in CiviCRM correspond to a row in a similarly
named table. Payment processors are also like this, but just a little
more complicated.

The key tables are:

`civicrm_payment_processor_type` This table provides some
db-configurable values for a "payment processor" as defined above. For
example - the labels for the account name and passwd and the urls used
with that service. Is also contains fields with default values for the
billing mode, payment type and whether it supports recurring billing.
Most importantly, it contains the class name of the payment processor
that corresponds to the file defining the class.

`civicrm_payment_processor` This table provides the
organization-specific implementation details of the payment processor -
most notably the account name and passwd. It has a foreign key into the
payment processor type table, and fields that allow for service-specific
overrides of the billing mode, payment type and whether it supports
recurring billing. This is the table that provides the row that helps
instantiate the payment processor object.

## Payment Objects in Code

As hinted at above, how the payment object is used in the code base is
the most challenging part, particularly how it deals with the variety of
ways that payment processors work.

In theory, all the details of how a payment processor works should be
encapsulated as a payment object method or property. Unfortunately, a
lot of core code has if/then clauses that make quite a lot of
assumptions about the payment processor.

For an extreme example, in some cases there are bits of code in
templates that check if a payment process or is paypal.

Some other examples are:

-   the payment processor fields exposed to the user on payment pages:
    civicrm only provides a core set for credit cards, and another core
    set for direct debit payment type processing.
-   the flow: only the three different billing mode options are allowed.
-   recurring payments: the code assumes that initial contributions are
    not triggered until after the initial request is submitted, and that
    the payment processor will report back on it's payments using the
    IPN method.
-   off-line payments ('pay later') is not implemented as a payment
    processor, but built into the code, with all kinds of configuration
    bits in different places.

## Existing Discussions

Here's a partial list of related discussions (please contribute here):

* <http://forum.civicrm.org/index.php/topic,34170.0.html>
* <http://forum.civicrm.org/index.php/topic,32920.0.html>
* <http://forum.civicrm.org/index.php/board,39.0.html>
* <http://forum.civicrm.org/index.php/topic,34458.0.html>

and examples of challenges with the payment processing code

* <http://forum.civicrm.org/index.php/topic,29095.0.html>
* <http://forum.civicrm.org/index.php/topic,34522.0.html>

