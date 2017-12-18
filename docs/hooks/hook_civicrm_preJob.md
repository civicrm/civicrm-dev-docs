# hook_civicrm_preJob

## Summary

This hook is called before a scheduled job is executed.

## Notes

This hook does not allow aborting the job execution or modifying its parameters.

We suspect this hook will be useful for developers who want to monitor the execution time of scheduled jobs or check whether a job is stuck (started but never ends). It is useful in combination with the hook `hook_civicrm_postJob`.

## Definition

```php
hook_civicrm_preJob($job, $params) 
```

## Parameters

 - $job - instance of CRM_Core_DAO_Job, the job to be executed
 - $params - array of arguments to be given to the job

## Return
None
