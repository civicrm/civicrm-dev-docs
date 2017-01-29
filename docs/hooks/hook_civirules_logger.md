# hook_civirules_logger

## Description

This hook is called for return an object to do logging in CiviRules. The
object should be instance of \Psr\Log\LoggerInterface or null if you
want to disable the logging

## Definition

    hook_civirules_logger(\Psr\Log\LoggerInterface &$logger=null)

## Returns

-   null

## Example

The example below returns a database logger for civirules.

    function civiruleslogger_civirules_logger(\Psr\Log\LoggerInterface &$logger=null) {
      if (empty($logger)) {
        $logger = new CRM_Civiruleslogger_DatabaseLogger();
      }
    }