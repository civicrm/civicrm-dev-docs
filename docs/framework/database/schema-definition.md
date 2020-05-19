# Database schema definition in XML

The database structure for core (as well as any schema defined for extensions) is defined in a series of XML files
([example](https://github.com/civicrm/civicrm-core/blob/master/xml/schema/SMS/History.xml)).
These files are
not packaged in the releases but are available in the GitHub repository. They
are located in
[`/xml/schema`](https://github.com/civicrm/civicrm-core/blob/master/xml/schema).
All the folders within the schema directory also
have matching folders in the main
[`/CRM`](https://github.com/civicrm/civicrm-core/blob/master/CRM)
folder which contain the DAOs and BAOs.

!!! Info
    A [`GenCode` script](https://github.com/civicrm/civicrm-core/blob/master/xml/GenCode.php) (which calls the
    [`CRM_Core_CodeGen_Main` class](https://github.com/civicrm/civicrm-core/blob/master/CRM/Core/CodeGen/Main.php))
    performs the magic of translating the XML files to
    the DAO PHP classes and the database table creation SQL scripts
    `civicrm.mysql` and `civicrm_data.mysql` in the
    [`/sql`](https://github.com/civicrm/civicrm-core/blob/master/sql) folder.
    To update any DAO file you need to update the relevant XML and then run `php Gencode.php` from the XML directory
    (civibuild create will also do this, albeit rebuilding your whole dev site)


Looking in [`/xml/schema/Pledge`](https://github.com/civicrm/civicrm-core/blob/master/xml/schema/Pledge)
    as an example we see 4 files:

- `files.xml`
- `Pledge.xml`
- `PledgePayment.xml`
- `PledgeBlock.xml`

The `files.xml` is just a list of the other files. Each of the other files describes a
table in the database, defining both table-level and field-level metadata
including foreign keys and indexes:

```
<table>
  <base>CRM/SMS</base>
  <class>History</class>
  <name>civicrm_sms_history</name>
  <comment>SMS History can be linked to any object in the application.</comment>
  <add>1.4</add>
  <drop>2.0</drop>
  ... etc
```

An example of a field definition is:

```
  <field>
    <name>amount</name>
    <uniqueName>pledge_amount</uniqueName>
    <title>Total Pledged</title>
    <type>decimal</type>
    <required>true</required>
    <import>true</import>
    <comment>Total pledged amount.</comment>
    <add>2.1</add>
  </field>
```

The rest of the page specifies the valid tags (and their allowable values) for use when defining schema.

## `<table>` {:#table}

Tags acceptable within `<table>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<base>` | text | `CRM/Contribute` | 1 | The directory containing the PHP class file |
| `<class>` | text | `Contribution` | 1 | The name of the PHP class file without the extension |
| `<name>` | text | `civicrm_contribution` | 1 | The full table name in MySQL with prefix
| `<comment>` | text | | 0 or 1 | A description of the purpose of the table
| `<archive>` | `true`/`false` | | 0 or 1 | *Not yet documented*
| `<log>` | `true`/`false` | | 0 or 1 | *Not yet documented*
| `<field>` | [tags](#table-field) |  | 1+ |  |
| `<index>` | [tags](#table-index) |  |  0+ |  |
| `<primaryKey>` | [tags](#table-primaryKey) |  |  0+ |  |
| `<foreignKey>` | [tags](#table-foreignKey) |  |  0+ |  |
| `<dynamicForeignKey>` | [tags](#table-dynamicForeignKey) |  |  0+ | See [notes below](#table-dynamicForeignKey) |


## `<table>` / `<field>` {:#table-field}

Tags acceptable within `<table>` / `<field>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<name>` | text | `total_amount` | 1  | The machine name of the field |
| `<uniqueName>` | text |  | 0 or 1 | Used to prevent name conflicts in the advanced search. Should only be used for core entities |
| `<title>` | text | `Total amount` | 1 | The human-readable name of the field |
| `<type>` | text |  | 1 | See notes below |
| `<length>` | integer |  | 0 or 1 | The max number of characters to allow in the field |
| `<default>` | mixed |  | 0 or 1 | A default value for this field to take when creating new records |
| `<comment>` | text |  | 0 or 1 | A description of the purpose of the field |
| `<headerPattern>` | regex |  | 0 or 1 | *Not yet documented* |
| `<dataPattern>` | regex |  | 0 or 1 | *Not yet documented* |
| `<required>` | `true`/`false` |  | 0 or 1 | When `false`, MySQL will allow this field to be set to `NULL` |
| `<localizable>` | `true`/`false` |  | 0 or 1 | If `true`, in a multilingual site the field will have separate per-language fields. See [this](../../translation/database.md) link when adjusting localizable or adding localizable fields|
| `<import>` | `true`/`false` |  | 0 or 1 | When `true`, this field will be available for use when importing data |
| `<export>` | `true`/`false` |  | 0 or 1 | When `true`, users will be able to include this field in data exports |
| `<rule>` | text |  | 0 or 1 | *Not yet documented* |
| `<value>` |  |  | 0 or 1 | *Not yet documented. Used rarely. Probably not a valid tag* |
| `<values>` |  |  | 0 or 1 | (deprecated) List of values for `enum` type. Now we use the option values table instead. |
| `<collate>` | text | `utf8_bin` | 0 or 1 | Only needs to be set if you want something other than `utf8_unicode_ci` |
| `<html>` | [tags](#table-field-html) |  | 0 or 1 | Settings for the form element to use for this field |
| `<pseudoconstant>` | [tags](#table-field-pseudoconstant) |  | 0 or 1 | See [notes below](#table-field-pseudoconstant) |
| `<serialize>` | text| | 0 or 1  | Method to serialize array data. For new fields use JSON |
| `<crmType>` | text | `CRM_Utils_Type::T_STRING` | 0 or 1 | Primarily used by extensions to specify an acceptable for type as under the `CRM_Utils_Type` class where the MySQL column type doesn't match directly to one. |
| `<phpType` | text | `string` | 0 or 1 | Extensions can override this with a specific format of PHP type of data. |

`<type>` should be one of the following values which correspond to [MySQL data types](https://dev.mysql.com/doc/refman/en/data-types.html)

* `blob`, `boolean`, `char`, `datetime`, `date`, `decimal`, `float`, `int`, `int unsigned`, `longtext`, `mediumblob`, `text`, `timestamp`,  `varchar`

`<serialize>` can be one of the following values `PHP`, `JSON`, `SEPARATOR_BOOKEND`, `SEPARATOR_TRIMMED`, `COMMA`. The Accepted values can be found in `CRM_Core_DAO` Class 

## `<table>` / `<field>` / `<html>` {:#table-field-html}

Tags acceptable within `<table>` / `<field>` / `<html>`

| Tag | Contains | Acceptable when<br>`type` = | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<type>` | text |  | 1 | Acceptable values listed below |
| `<rows>` | integer | `TextArea` | 0 or 1 | The height of the text area (in characters) |
| `<cols>` | integer | `TextArea` | 0 or 1 | The width of the text area (in characters) |
| `<size>` | integer | `Text` | 0 or 1 | The width of the text box (in characters) |
| `<formatType>` | text | `Select Date` | 0 or 1 | *Not yet documented* |
| `<multiple>` | integer | `Select` | 0 or 1 | *Not yet documented* |

`<type>` acceptable values:

* `ChainSelect` - *Not yet documented*
* `CheckBox` - A check box
* *`Checkbox` (used rarely, probably not a valid value)*
* `EntityRef` - Mostly used for `contact_id` fields, *not yet documented fully*
* `file` - Choose a file to upload
* `Radio` - A set of radio buttons
* `RichTextEditor` - A rich text editor
* `Select Date` - A widget to enter a date
* `Select` - Choose from a list of options (commonly used with pseudoconstant fields)
* `TextArea` - Multi-line text field
* *`TexArea` (used rarely, probably not a valid value)*
* `Text` - Single-line text field

`<formatType>` acceptable values:

* `activityDateTime`
* `activityDate`
* `birth`

## `<table>` / `<field>` / `<pseudoconstant>` {:#table-field-pseudoconstant}

Pseudoconstant settings tells the code how to determine the valid options for the field value and must be specified by using *one* of the following *three* methodologies:

### Using the `civicrm_option_value` table

With this methodology, the acceptable field values are taken from rows in the `civicrm_option_value` for a given *option group*.

Tags acceptable within `<table>` / `<field>` / `<pseudoconstant>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<optionGroupName>` | text | `campaign_type` | 1 | The option group name from `civicrm_option_group.name` |
| `<keyColumn>` | text | `name` | 0 or 1 | The column in `civicrm_option_value` which contains values that match *this* field. If this tag is omitted, the `value` column will be used. |

### Using an arbitrary table

With this methodology, the acceptable field values are taken from `<keyColumn>` in `<table>`, with some extra settings that make it different from your typical foreign key.

Tags acceptable within `<table>` / `<field>` / `<pseudoconstant>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<table>` | text | `civicrm_campaign` | 1 | The name of the referenced table containing the values |
| `<keyColumn>` | text | `id` | 1 | The column in the referenced table which contains values that match *this* field |
| `<labelColumn>` | text | `full_name` | 1 | The column in the referenced table which contains a human-readable variant of the value |
| `<nameColumn>` | text | `iso_code` | 0 or 1 | Optionally, the column in the referenced table which contains a machine-readable name of the value. |
| `<condition>` | SQL | `parent_id IS NULL` | 0 or 1 | Extra SQL to add in a `WHERE` clause that will further limit the possible options |

!!! note "`<nameColumn>`"
    In some cases, `<keyColumn>` will reference a column containing integers and `<nameColumn>` will reference a column containing values like `"Individual"`. Setting `<nameColumn>` in these cases allows us to use specify `"Individual"` when making API calls.

### Using a callback function

With this methodology, the acceptable field values are taken from a PHP callback function.

Tags acceptable within `<table>` / `<field>` / `<pseudoconstant>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<callback>` | text | `CRM_Core_SelectValues::eventDate` | 1 | Static reference to a function in the codebase |

## `<table>` / `<index>` {:#table-index}

Tags acceptable within `<table>` / `<index>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<name>` | text |  | 1 | Follows the pattern `index_fieldname_anotherfieldname` |
| `<fieldName>` | text |  | 1+ | The name of the field to use for this index |
| `<unique>` | `true`/`false` |  | 0 or 1 | When `true`, the values in this field (or combination of fields) must be unique across all rows of the table. |

!!! note
    Some older `<name>` values are prefixed with `UI_`. You don't need to do this when adding a new index.

!!! tip
    You can use multiple `<fieldName>` tags to produce a single index on multiple fields.

## `<table>` / `<primaryKey>` {:#table-primaryKey}

Tags acceptable within `<table>` / `<primaryKey>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<name>` | text | `id` | 1 | The name of the field to use for the primary key |
| `<autoincrement>` | `true`/`false` |  | 1 | *Not yet documented: why would I ever want this to be `false`?* |

## `<table>` / `<foreignKey>` {:#table-foreignKey}

Tags acceptable within `<table>` / `<foreignKey>`

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<name>` | text | `contact_id` | 1 | The name of the field in *this* table which stores the value of the field in the *referenced* table |
| `<table>` | text | `civicrm_contact` | 1 | The name of the referenced table, including the table prefix |
| `<key>` | text | `id` | 1 | The name of the field in *referenced* table to which we're pointing (almost always `id`) |
| `<onDelete>` | text |  | 0 or 1 | Specifies what to do with *this* entity when the *referenced* entity is deleted. *The behavior when this tag is omitted is not yet documented.* |

Acceptable values for `<onDelete>`:

* `SET NULL` - set the value of the field in this table to `NULL` when the referenced entity is deleted
* `CASCADE` - delete this entity when the referenced entity is deleted
* `RESTRICT` - don't allow the referenced entity to be deleted unless this entity is first deleted

## `<table>` / `<dynamicForeignKey>` {:#table-dynamicForeignKey}

Tags acceptable within `<table>` / `<dynamicForeignKey>`

A dynamic foreign key can reference different tables depending on the value of a field in *this* table. For example, the `Note` entity can store notes which are associated with `Contact`s and also notes which are associated with `Contribution`s and uses a dynamic foreign key to do so.

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<idColumn>` | text | `entity_id` | 1 | The name of the field in *this* table which stores the value of the primary key in the *referenced* table |
| `<typeColumn>` | text | `entity_table` | 1 | The name of the field in *this* table which stores the table name of the *referenced* table |

## Tags acceptable pretty much anywhere

| Tag | Contains | Example | Acceptable<br>Instances | Purpose |
| -- | -- | -- | -- | -- |
| `<add>` | text | `2.2` | 0 or 1 | The CiviCRM version when this schema setting was added
| `<change>` | text | `3.4` | 0 or 1 | The CiviCRM version when this schema setting was changed
| `<modify>` | text | `3.4` | 0 or 1 | *This appears to be an alias of `<change>` but perhaps is not a valid tag*
| `<drop>` | text | `4.1` | 0 or 1 | The CiviCRM version when this schema setting was removed e.g.
