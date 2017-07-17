# SQL Coding Standars

When writing SQL developers should be concious to try to always ensure that all variables that are passed into the SQL are done in a safe and secure maner.

In CiviCRM the best way is to use the inbuild paramatisation tools to do the job. 

```php
$name = 'John Smith';
$genderId = 2;
$result = CRM_Core_DAO::executeQuery("SELECT FROM civicrm_contact WHERE display_name like %1 AND gender = %2", array(
  1 => array('%' . $name . '%', 'String'),
  2 => array($genderId, 'Integer'),
));
```

The Above example not only ensures that variables get escaped before being inserted into the query. CiviCRM also allows developers to specify the type of variable that should be allowed. In the case of the 2nd variable we are limiting it to only Integers. 

The Types of variables that you can specify can be found in [CRM_Utils_Type::validate](https://github.com/civicrm/civicrm-core/blob/master/CRM/Utils/Type.php#L378). The Query engine will then apply the appropriate escaping for the type. If you are unable to create a query similar to the above the next best thing is to ensure that all variables are escaped before being put into the query

```php
$name = CRM_Utils_Type::escape('John Smith', 'String');
$column = CRM_Utils_Type::escape('civicrm_contact.display_name', 'MysqlColumnNameOrAlias');
$result = CRM_Core_DAO::ExecuteQuery("SELECT FROM civicrm_contact WHERE $column like '%$name%'");
```
