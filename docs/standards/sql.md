# SQL Coding Standards

When writing SQL developers should ensure all variables are passed into SQL safely and securely.

In CiviCRM this is done using the inbuilt parameterization tools.

```php
$name = 'John Smith';
$optedOut = 0;
$result = CRM_Core_DAO::executeQuery("SELECT FROM civicrm_contact WHERE display_name like %1 AND is_opt_out = %2", array(
  1 => array('%' . $name . '%', 'String'),
  2 => array($optedOut, 'Integer'),
));
```

This example ensures that variables are safely escaped before being inserted into the query. CiviCRM also allows developers to specify the type of variable that should be allowed. In the case of the `%2` ($optedOut) parameter, only an *Integer* input will be permitted.

The variable types available for this can be found in [CRM_Utils_Type::validate](https://github.com/civicrm/civicrm-core/blob/master/CRM/Utils/Type.php#L378). The query engine then applies appropriate escaping for the type.

In some circumstances you may find that a complex query is easier to build by directly escaping values using the `CRM_Utils_Type::escape()` method. It's preferable to use the form above.

```php
$name = CRM_Utils_Type::escape('John Smith', 'String');
$column = CRM_Utils_Type::escape('civicrm_contact.display_name', 'MysqlColumnNameOrAlias');
$result = CRM_Core_DAO::ExecuteQuery("SELECT FROM civicrm_contact WHERE $column like '%$name%'");
```
