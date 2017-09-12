# Pseudoconstant Reference aka Option Lists

## Introduction

CiviCRM defines a **Pseudoconstant** as: *A list of options associated with a field, the contents of which rarely changes*. This documentation uses the terms *pseudoconstant* and *option list* interchangeably.

### Field

Every option list is associated with one or more fields, for example the "gender" pseudoconstant is associated with the Contact field `gender_id`.

### Key, Name, Label

Each option in a list generally has 3 parts:

-   **Key** (also referred to as **ID**): Usually an integer, this is the value that is stored in the database.
-   **Name** (also referred to as **machine** name): This is a unique identifier string which is both human and machine readable. Developers typically use names in their code when they need to refer to a particular option, because it makes the code more readable and  more portable (since option IDs are not guaranteed to be the same in every database).
-   **Label**: This is the localized string shown to the user to represent the option. Labels should *not* be relied on by developers since they are subject to change, translation, and string-replacement.

### Option List Storage

-   Most option lists are stored in the database in the `civicrm_option_value` table. The name of each list is specified in the `civicrm_option_group` table.
    -   The `option_value` api allows CRUD functions on option lists stored in this table.
    -   Additionally, the `custom_field` api provides a convenient method for creating a field and an associated option list at once.
-   Other larger or more complex option lists have their own dedicated tables, e.g. `civicrm_relationship_type`, `civicrm_state_province`, `civicrm_location_type`
    -   Each of these tables has its own api for manipulating the values.
-   A few option lists are stored as *enum* values, for example the contact `preferred_mail_format` field is of type *enum.*
-   A few others are not in the database at all, but hard-coded as php arrays. The list `{0: 'No', 1: 'Yes'}` is an example of this for  boolean fields.

### Option List Retrieval

As of CiviCRM 4.4 (and to a limited extent 4.3) there are option retrieval methods which bridge these different ways of storage and return options in a predictable way for every field in the database.

!!! note "Historical note"
    Prior to 4.4, the `CRM_*_Pseudoconstant` classes contained 1 function per option list. Those single-purpose methods are deprecated and in 4.4 many have been removed. The 'constant' api is therefore also *deprecated* in CiviCRM 4.3 and 4.4 in favor of the api.getoptions method.

| Method | Version Added | Purpose | Example |
| --- | --- | --- | --- |
| `CRM_Core_Pseudoconstant::getName` | 4.4 | Retrieve an option name given its key | `$baoName = "CRM_Core_BAO_Address"; $key = 1228; $name = CRM_Core_Pseudoconstant::getName($baoName, 'country_id', $key); echo $name; // 'US'` |
| `CRM_Core_Pseudoconstant::getLabel` | 4.4 | Retrieve an option label given its key  | `$baoName = "CRM_Core_BAO_Address"; $key = 1228; $label = CRM_Core_Pseudoconstant::getLabel($baoName, 'country_id', $key); echo $label; // 'United States'` |
| `CRM_Core_Pseudoconstant::getKey` | 4.4 | Retrieve an option key given its name  | `$baoName = "CRM_Core_BAO_Address"; $iso = 'US' ; // note that iso-codes are used as machine names for countries $key = CRM_Core_Pseudoconstant::getKey($baoName, 'country_id', $iso); echo $key; // 1228` |
| `CRM_*_BAO_*::buildOptions` | 4.4 | Retrieve a list of options for a field | `CRM_Contact_BAO_Contact::buildOptions('gender_id');` Note: accepts additional params related to context. See below. |
| `api.getoptions` | 4.3 | In 4.3 - retrieve option list for a field - works for many but not all fields. In 4.4 - Api wrapper around `CRM_*_BAO_*::buildOptions ` - works for all fields and passes additional params to buildOptions. | `civicrm_api3('contact', 'getoptions', array('field' => 'gender_id'));` Note: accepts additional params in 4.4. See below. |

#### Context

The format of an option list needs to be slightly different depending on the context you're working in. For example if you have machine names as input and need to store the values in the database, you need the list to contain ids and names instead of ids and translated labels. The BAO `buildOptions` method (and the `api.getoptions` wrapper) accept a 'context' string which controls formatting. Quoting from the `CRM_Core_BAO::buildOptionsContext` code:

| Context | Returned Options | Label Field | Key Field |
| --- | --- | --- | --- |
|`'get` | All options are returned, even if they are disabled | Translated labels | Integer Option Value |
|`create` | Options are filtered appropriately for the object being created/updated | Translated Labels | Integer Option Value |
|`search` | Searchable options are returned | Translated Labels | Integer Option Value |
|`validate` | All options are returned, even if they are disabled | machine names | Integer Option Value |
|`abbreviate`| Enabled options are returned | Abbreviations of labels | Integer Option Value |
|`match` | Enabled options are returned using machine names as keys | Translated Labels | Machine Names |

* Abbreviate is only applicable at present to `state_province_id` and `country_id` fields

When used in 'create' mode, buildOptions/api.getoptions accepts additional info about the object being created/updated. It is recommended to pass in all known properties of the object so the BAO has all the info it needs for filtering. For example when creating an address, the list of states will depend on the selected country:

```php
// Example using the BAO
CRM_Core_BAO_Address::buildOptions('state_province_id', 'create', array('country_id' => 1228));
// Will return a list of states in the United States

// Same example using the api
civicrm_api3('address', 'getoptions', array(
  'field' => 'state_province_id',
  'context' => 'create',
  'country_id' => 'US', // Note the api automatically translates machine names to keys. So 'US' and 1228 would both be acceptable input here.
);
// Will return a list of states in the United States
```

#### Api Getfields

Option lists can also be retrieved as part of an api.getfields request:

```php
civicrm_api3('phone', 'getfields', array('options' => array('get_options' => 'all')));
```

Will return all fields plus their option lists for a given entity. You can also pass an array of field-names instead of 'all' to only retrieve options for certain fields.

### Caching

Option lists are cached in memory for performance, so in writing your code you should not have to worry about the impact of retrieving the same option list twice.

In most circumstances, this cache is automatically flushed if an option list gets modified. To manually flush CiviCRM's pseudoconstant cache, call:

```php
CRM_Core_PseudoConstant::flush();
```
