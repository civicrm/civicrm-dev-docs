# hook_civicrm_postJob

## Summary

This hook is called after a scheduled job is executed or was interrupted by an exception.

## Notes

We suspect this hook will be useful for developers who want to monitor the execution time of scheduled jobs or check whether a job is stuck (started but never ends). It can also be used to monitor the execution status of jobs. It is useful in combination with the hook `hook_civicrm_preJob`.

## Definition

```php
hook_civicrm_postJob($job, $params, $result) 
```

## Parameters

 - $job - instance of CRM_Core_DAO_Job, the executed job 
 - $params - array of arguments given to the job
 - $result - It can be:
   + the array returned by the API call of the job
   + the exception that interrupted the execution of the job

## Return
None

## Example

```php
function sencivity_civicrm_postJob($job, $params, $result) {
  if ($result['is_error']) {
    CRM_Core_Error::debug_log_message("Job $job->name failed!");
  }
}
```
