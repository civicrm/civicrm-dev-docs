!!! abstract
    This area of CiviCRM code and documentation is a work-in-progress. Not all features
    will be documented and the core code underlying this area may change from version
    to version.

The financial subsystem in civicrm encompasses:

- contributions
- recurring contributions
- payments
- refunds
- prices
- discounts
- premiums (things given away to contacts who donate a lot)
- accounting information
- integrations with payment processors
- integrations with accounting systems

There are strong relationships between the financial area of CiviCRM and memberships, event registrations, and pledges.

Due to the importance of data integrity, the tight coupling of related business operations, and large number of tables in the implementation, there is a strong focus on using higher level business APIs rather than lower level table oriented operations. 

Key concepts include:

- order (sometimes called invoice)
- line item
- bookkeeping entries which encompass at a minimum a debit and credit financial account, an amount and a date
- financial account, which corresponds to an account in a financial system's (like QuickBooks) chart of accounts
- financial type, which organisations can use to categorise their contributions (like "Donation") for their own management purposes (e.g. "Donation for X"), accounting purposes (which financial accounts are used) and regulartory requirements (e.g. to identify taxable contributions)

The main purpose of this documentation is to support

- non-core systems for creating orders, eg a Drupal webform or WordPress Caldera Forms replacement of CiviCRM Contribution and Event Pages.
- payment processor integrations in all of their variety.
