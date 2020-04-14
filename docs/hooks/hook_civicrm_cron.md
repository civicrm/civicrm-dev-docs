# hook_civicrm_cron

## Summary

This hook is called every time the CiviCRM scheduler is polled.

## Notes

The timing and frequency with which this is called will vary depending on
the system configuration.

Introduced in CiviCRM v4.3.

!!! note "This is a low-level approach"
    There are two ways to build on top of the CiviCRM scheduler. **hook_civicrm_cron** is a low-level approach which calls your code with an unpredictable schedule – in some systems, it could be once a day; in others, once every minute, every 5 minutes, every hour, every 2 hours, ad nauseum. You must ensure that your code will behave well in all these situations. Alternatively, the **Job API** is a higher-level approach by which you may register scheduled jobs ("Daily", "Hourly", etc), and the scheduler will make a best effort to match the declared scheduler. See, e.g., ["Create a Module Extension: How does one add a cron job"](../extensions/advanced.md##cron-jobs)


## Definition

    hook_civicrm_cron($jobManager)

## Parameters

-   CRM_Core_JobManager** $jobManager**

## Example

    /**
     * Implementation of hook_civicrm_cron
     *
     * Flag records in a custom table as dirty if they are over 2 days old.
     * Rerunning this logic at various times throughout the day should be safe
     * because there are no guarantees about when it will run.
     */
    function example_civicrm_cron($jobManager) {
      CRM_Core_DAO::executeQuery('UPDATE my_table SET is_dirty = 1 WHERE last_modified < adddate(now(), "-2 day")');
    }
