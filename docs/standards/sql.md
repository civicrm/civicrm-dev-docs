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

Since CiviCRM 4.7 version there has been an alternate way of generating SQL -- use `CRM_Utils_SQL_Select`. Compared to plain `CRM_Core_DAO`, it has three advantages:

 * The syntax uses pithy [sigils](https://en.wikipedia.org/wiki/Sigil_(computer_programming)) for escaping strings (`@value`), numbers (`#value`) and literal SQL (`!value`).
 * The escaping for array-data is transparent (e.g. `field IN (#listOfNumbers)` or `field IN (@listOfStrings)`).
 * It supports more sophisticated `JOIN`, `GROUP BY`, and `HAVING` clauses.
 * You can build and combine queries in piecemeal fashion with `fragment()` and `merge()`.
 * The general style of query-building is fluent.

A typical example might look like this:

```php
$dao = CRM_Utils_SQL_Select::from('civicrm_contact c')
  ->join('cm', 'INNER JOIN civicrm_membership cm ON cm.contact_id = c.id')
  ->where('c.contact_type = @ctype', array(
    'ctype' => 'Individual',
  ))
  ->where('cm.membership_type_id IN (#types)', array(
    'types' => array(1, 2, 3, 4),
  ))
  ->where('!column = @value', array(
    'column' => CRM_Utils_Type::escape('cm.status_id', 'MysqlColumnNameOrAlias'),
    'value' => 15,
  ))
  ->execute();

while ($dao->fetch()) { ... }
```

Equivalently, you may pass all parameters as a separate array:

```php
$dao = CRM_Utils_SQL_Select::from('civicrm_contact c')
  ->join('cm', 'INNER JOIN civicrm_membership cm ON cm.contact_id = c.id')
  ->where('c.contact_type = @ctype')
  ->where('cm.membership_type_id IN (#types)')
  ->where('!column = @value')
  ->param(array(
    'ctype' => 'Individual',
    'types' => array(1, 2, 3, 4),
    'column' => CRM_Utils_Type::escape('cm.status_id', 'MysqlColumnNameOrAlias'),
    'value' => 15,
  ))
  ->execute();

while ($dao->fetch()) { ... }
```

For convenience, you can chain the `execute()` with other DAO functions like `fetchAll()`, `fetchValue()` or `fetchMap()`.

```php
$records = CRM_Utils_SQL_Select::from('mytable')
  ->select('...')
  ->execute()
  ->fetchAll();
```

Further information on this method can be found in the [CRM_Utils_SQL_Select class](https://github.com/civicrm/civicrm-core/blob/6db7061/CRM/Utils/SQL/Select.php#L33)
