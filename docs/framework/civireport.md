# CiviReport Reference

## Introduction

CiviCRM Comes with a number of standard report templates which can be useful. Extensions such as [CiviVisualize](https://civicrm.org/extensions/civisualize-missing-data-visualization-extension) have added on capabilities to the CiviReport functionality. This guide will go through the process of developers creating their own new custom report template. Reports in CiviCRM are built based off php report templates.

## Generating Report Template

It is recommended that for any new CiviReport Custom templates that they be done through an Extension. Civix is the standard CiviCRM extension builder has a good tool to help generate a fair amount of the boiler plate code that is needed for a CiviCRM Report Template. See the [Civix Documentation](../extensions/civix.md#generate-report) on how to generate a generic CiviCRM Report Template. 

## Structure of a CiviCRM Report Template

As mentioned in the CiviX Documentation the general file that you will be mainly modifying will be the MyReport.php file. This file will contain a number of standard functions which we will look through. 

## function `__construct()`

This is where you put the field and filter definitions.

### `$this->_columns`

Assign `$this->_columns` to an array of table definitions. The key is any name you want, but it makes sense to use the table name unless you need to use the same table twice in the query.

```php
$this->_columns = array( 
  'civicrm_contact' => array(
    'dao' => 'CRM_Contact_DAO_Contact',
    'fields' => array(
      'display_name' => array(
        'title' => ts('Contact'),
        'required' => TRUE,
      ),
    ),
    'filters' => array(
      'sort_name' => array(
        'title' => ts('Contact'),
      ),
    ),
  ),
  // other tables...
;
```

| Parameter | Value | Description |
| --- | --- | --- |
| `alias` | text | To give the table another name. (It's not clear why this exists because you can achieve the same thing by using a different key? | 
| `bao` | Class name | The BAO for the table, if you need something fancier. If both dao and bao are specified the BAO wins. |
| `dao` | Class name | The DAO for the table |
| `fields` | array | See below. |
| `filters` | array | See below. |
| `group_bys` | array | See below. |
| `order_bys` | array | See below. |

### fields

fields is the list of fields that the user can select to display or not.

| Parameter | Value | Description | 
| --- | --- | --- |
| `alias` | text | To give the table another name, just for this field. This defaults to alias for the table array, or if not set, the key for the table array. See note below. |
| `dbAlias` | text | To give the field another name. See note below. 
| `default` | true/false | Selected by default | 
| `name` | text | To give the field another name. This defaults to the key for this field array. See note below. |
| `no_display` | true/false | Doesn't appear in the output, but can be used in the query |
| `no_repeat` | true/false | repeated fields/value in the column are not allowed |
| `required` | true/false | The user has no choice |
| `statistics` | array | To make query compute stats like sum, count, avg on a numeric field. Applies to both "display columns" and "filters".|  



!!! note
    The way name, alias, and dbAlias work together is that if you set dbAlias directly, then it's whatever you set it to. Otherwise dbAlias gets computed as `alias.name`.

Then beyond that it seems that as long as you make what you use in `select()` and other functions match what you did here, it will work. Basically you use whatever combination of the array key, name, alias, and dbAlias so that you have enough uniqueness and that it generates valid SQL.

### Filters

filters is a list of filters related to the table that the user can use to filter the results.

| Parameter | Value | Description |
| --- | --- | --- |
| `title`  | text | The caption/label | 
| `default` | text | The default value of the filter | 
| `operatorType` | `CRM_Report_Form::OP_XXX` | The widget type. Look in `CRM_Report_Form` for the possible values. Note that some like `DATE` have some built-in functionality for you. Also note that some filters have built-in functionality even without a type, like the `sort_name` in the example above gives the user a choice of "contains", "starts with", etc... |
| `default_op` | text | The default operator of the filter, e.g `'eq'` for `CRM_Utils_Type::T_INT` |
| `options` | array | For widgets that have a selection of values. Pass in an array with value as the key and label as the value, e.g. `array('1'=>'Low', '2'=>'Medium', '3'=>'High')` |
| `group` | true/false | Used for group related form fields for e.g `group_id`. A true value for this flag, makes the query include the condition inside the main `WHERE` clause like a normal query. If false, condition is excluded from main clause. | 
| `no_display` | true/false | When set for a filter makes the filter hidden but can still be used in the query. Required for cases when filter is expected from url and not be present on the form |
| `pseudofield` | true/false | A true value for this flag causes the filter to be excluded from automatic processing by the form's `WHERE`-clause-building logic. Example usage: to build a form control that doesn't directly correspond to a field but whose value somehow (other than by addition to the `WHERE` clause) affects how the query is built. |
| `type` | `CRM_Utils_Type::T_XXX` | The type of data to expect in this filter. Default is `T_STRING`. See `CRM_Utils_Type` for possible values. |

### `group_bys`

`group_bys` is a list of fields from the table that the user can select to group the results.


!!! warning
    Using grouping will likely require you to customize most of the functions like `select()`. Also be on the lookout for table joins you might be making that might have multiple rows on the right side of the join, such as activities to targets. It may also require you to set rules to prevent users from making selections that don't make sense. The user may choose to un-select grouping too.


!!! tip
    `Contribute/Summary.php` seems to be a good example of using `group_bys`.


| Parameter | Value | Description |
| --- | --- | --- |
| `title` | text | The caption/label |
| `default` | true/false | Select By default |
| `frequency` | true/false | if true, more options for group by field are available e.g. `receive_date` can be grouped by month, week, quarter, year.( only for date related field ) |


### `order_bys`

`order_bys` is a list of fields from the table the user can select to affect the ordering of the results. For each array element, the array key should be equal to the field being exposed for sorting.

!!! tip
    `Contact/Summary.php` is a good example of using `order_bys`.

!!! warning
    Defining `order_bys` for columns that are not indexed may negatively affect performance. You may wish to examine the database schema to check for and/or define indexes on `order_by` columns.

| Parameter | Value | Description |
| --- | --- | --- |
| `title` | text | The caption/label |
| `default` | true/false | If true, this `order_by` column is enabled by default in the report |
| `default_weight` | integer | If `default==true`, this parameter indicates the relative weight of this `order_by` column among other default `order_by` columns |
| `default_order` | 'ASC' or 'DESC' | If `default==true`, this parameter indicates wither the sort for this `order_by` column should be `ASCENDING` or `DESCENDING` |
| `default_is_section` | true/false | If `default==true`, this parameter indicates whether or not section headers should be created for this `order_by` column |
| `name` | text | The name of the DAO field being exposed for sorting; only required in the unusual case that the field name is not used as the array key |

## Adding Custom fields

As of 3.1 you can add custom fields to a report by adding / modifying the 'extends' line

```php
protected $_customGroupExtends = array('Contact', 'Individual', 'Household', 'Organization');
```

If this line is in the php file then all **searchable** (check: Is this Field Searchable?) custom data fields that relate to Contact, Individual, Household, or Organisation will be available in the report. Note that 'Contact' ONLY refers to the custom data groups that extend all contacts and does not also include those that extend Individual. This functionality is not limited to 'contact' related fields and activity, relationship etc fields can be added.

All searchable fields of that type will be added. There is not currently a simple option to only select some.

### Auto-including custom fields as `order_bys`

If the property `$this->_autoIncludeIndexedFieldsAsOrderBys` is set to `TRUE`, the report will automatically create `order_by` options for the custom fields added in `$this->_customGroupExtends`.  Since all custom fields are indexed, this does not negatively affect performance.


## Other Report Functions

These reports will use a combination of the following functions `select()`, `where()`, `from()`, `groupBy()`, `alter_display()`, `statistics()`, `formRule()`. Developers should only include one of those functions where the parent function in `CRM_Report_Form` does not meet the needs of the intended report template. Developers should try to not override functions wherever possible. If you do have to override a function then it is recommended that you put in `parent::functioname` so that the standard processing can happen as well

### functions `select()` and `where()` and `selectClause` and `whereClause`
You do not need any of these functions unless you have some special handling. If you do have special handling try to only
override specific fields using `selectClause` or `whereClause` rather than overriding the maing `select` or `where` function 


### function `from()`

This is where you define the tables and joins by setting `$this->_from` to an SQL string.

### function `groupBy()`

This is where you define the grouping clauses by setting `$this->_groupBy` to an SQL string.

### function `alterDisplay()`

This is where you can reformat column values to be links and such. 

You may also replace labels etc in this function - although the parent function will do most of that for you if your
metadata is correct.

### function `statistics()`

This is where you set and execute the query to compute aggregates. As noted above you may need to customize if using grouping. You should return an array in the format:

```php
$statistics['counts']['YOUR-KEY'] = array(
  'title' => ts("YOUR TITLE"),
  'value' => "YOUR VALUE",
  'type' => YOUR-VALUE-TYPE  // e.g. CRM_Utils_Type::T_STRING, default seems to be integer
);
return $statistics
```

### function `formRule()`

Return an array of strings listing any errors you wish to display to the user based on their selections.
