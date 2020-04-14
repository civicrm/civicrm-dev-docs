# APIv4 Changelog

*This page lists additions to the APIv4 with each new release of CiviCRM Core.*

Also see: [Differences Between Api v3 and v4](../v4/differences-with-v3.md) and [Hooks Changelog](../../hooks/changes.md).

## CiviCRM 5.23

### 5.23 Added PaymentProcessor and PaymentProcessorType APIv4 Entities

See https://github.com/civicrm/civicrm-core/pull/15624

### 5.23 `$index` param supports array input

CiviCRM 5.23 supports two new modes for the `$index` param - associative and non-associative array. See [CiviCRM Core PR #16257](https://github.com/civicrm/civicrm-core/pull/16257) 

### 5.23 Converts field values to correct data type

The api historically returns everything as a raw string from the query instead of converting it to the correct variable type (bool, int, float). As of CiviCRM 5.23 this is fixed for all DAO-based entities. See [CiviCRM Core PR #16274](https://github.com/civicrm/civicrm-core/pull/16274)

### 5.23 Selects only relevant contact fields by default

The Contact entity in CiviCRM is divided into 3 major types: Individuals, Households and Organizations.
Not all contact fields apply to all contact types, e.g. the `sic_code` field is only used by Organizations,
and the `first_name` and `last_name` fields are only used by Individuals. 

In CiviCRM 5.23 the schema has been augmented with metadata about which fields belong to which contact type, and Api4 now uses this
metadata to select only relevant fields. E.g. fetching a household with the api will not return the `first_name` or `organization_name` fields
as those will always be `null`.

### 5.23 Get actions support selecting fields by * wildcard

The `select` param now supports the `*` wildcard character for matching field names.
See [CiviCRM Core PR #16302](https://github.com/civicrm/civicrm-core/pull/16302).

### 5.23 Delete/Update do not throw error when 0 items found

For consistency across all "batch-style" actions that update/delete records based on a query,
the `Delete` and `Update` actions now simply return an empty result if no matches are found to act upon.
Previously they would throw an exception, which was similar to APIv3 behavior but inconsistent with other
APIv4 batch actions and SQL in general. See [CiviCRM Core PR #16374](https://github.com/civicrm/civicrm-core/pull/16374).
