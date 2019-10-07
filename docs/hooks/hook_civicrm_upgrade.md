# hook_civicrm_upgrade

## Summary

This hook is called when an administrator visits the "Manage Extensions"
screen to determine if there are any pending upgrades.

## Notes

As of version 4.7, it is also called periodically by [CiviCRM's system status
utility](https://docs.civicrm.org/user/en/stable/initial-set-up/civicrm-system-status/).

If there are upgrades, and if the administrator chooses to execute them,
the hook is called a second time to construct a list of upgrade tasks.

## Definition

    hook_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL)

## Parameters

-   $op - the type of operation being performed; 'check' or 'enqueue'
-   $queue - (for 'enqueue') the modifiable list of pending up upgrade
    tasks

## Returns

-   For 'check' operations, return array(bool) (TRUE if an upgrade is
    required)
-   For 'enqueue' operations, return void