# SQL Coding Standards

When writing SQL, it is very important that developers protect against [SQL injection](https://en.wikipedia.org/wiki/SQL_injection) by ensuring that all variables are passed into SQL safely and securely.

This page describes the inbuilt parameterization tools available for safely executing SQL.

## `CRM_Core_DAO::executeQuery` {:#executeQuery}

```php
$name = 'John Smith'; /* un-trusted data */
$optedOut = 0;        /* un-trusted data */

$query = "
  SELECT id
  FROM civicrm_contact
  WHERE
    display_name like %1 AND
    is_opt_out = %2";

$result = CRM_Core_DAO::executeQuery($query, array(
  1 => array('%' . $name . '%', 'String'),
  2 => array($optedOut, 'Integer'),
));
```

This example ensures that variables are safely escaped before being inserted into the query. CiviCRM also allows developers to specify the type of variable that should be allowed. In the case of the `%2` ($optedOut) parameter, only an *Integer* input will be permitted.

The variable types available for this can be found in [CRM_Utils_Type::validate](https://github.com/civicrm/civicrm-core/blob/60050425316acb3726305d1c34908074cde124c7/CRM/Utils/Type.php#L378). The query engine then applies appropriate escaping for the type.

## `CRM_Utils_Type::escape` {:#escape}

In some circumstances you may find that a complex query is easier to build by directly escaping values using the `CRM_Utils_Type::escape()` method. It is prefereable to use the form above or the `CRM_Utils_SQL_Select` format

```php
$name = CRM_Utils_Type::escape('John Smith', 'String');
$column = CRM_Utils_Type::escape('civicrm_contact.display_name', 'MysqlColumnNameOrAlias');
$result = CRM_Core_DAO::executeQuery("SELECT FROM civicrm_contact WHERE $column like '%$name%'");
```

## `CRM_Utils_SQL_Select`

Since CiviCRM 4.7 version there has been an alternate way of generating sql. You can use `CRM_Utils_SQL_Select` to generate your query. You can then use all the various `CRM_Core_DAO` methods to then run the query e.g. `fetch()` or `fetchAll()`.
Further information on this method can be found in the [CRM_Utils_SQL_Select class](https://github.com/civicrm/civicrm-core/blob/6db7061/CRM/Utils/SQL/Select.php#L33)

```php
$columnName = CRM_Utils_Type::escape('cm.membership_status', 'MysqlColumnNameOrAlias');
$dao = CRM_Utils_SQL_Select::from('civicrm_contact c')
  ->join('cm', 'INNER JOIN civicrm_membership cm ON cm.contact_id = c.id')
  ->where('!column = @value', array(
    'column' => $columnName,
    'value' => 15,
  ))
  ->where('membership_type_id IN (#types)', array('types', array(1,2,3,4)))
  ->execute();

while ($dao->fetch()) { ... }
```

You can chain with other DAO functions like `fetchAll()`, `fetchValue()` or `fetchMap()`.

```php
$records = CRM_Utils_SQL_Select::from('mytable')
  ->select('...')
  ->execute()
  ->fetchAll();
```
