# Queues Reference

CiviCRM has a system for splitting big jobs into smaller tasks on a queue, and provides a couple of ways to process queues.

## Overview

1. Create a queue object via the service provider
2. Create tasks (or other items) for the queue
3. Run the queue

There's a [demo queue](https://lab.civicrm.org/dev/core/blob/master/tools/extensions/org.civicrm.demoqueue/CRM/Demoqueue/Page/DemoQueue.php)
extension that shows how to use an SQL-based queue.

## Definitions

- *Queue*: An object representing a queue, which may be new or existing.
- *Queue type*: A storage backend for the list of queue items.
- *Queue runner*: A class for processing the queued items.
- *Item*: A single job on a queue
- *Task*: A particular type of item, as expected by CiviCRM's queue runner.
- *Release time*: The time at which a job can be considered to have crashed if it is not completed (defaults to 1 hour). Note that it may well not have crashed and could still actually be running, especially in PHP FPM environments!
- *Claim item*: Refers to fetching the first item in a queue (if one exists) unless that item's release time has not been reached (typically meaning that item is currently being processed).
- *Steal item*: Refers to fetching the first item in a queue regardless and setting a new release time.
- *Release item*: Refers to leaving a failed queue item on the queue (e.g. for later retry)

## 1. Creating a queue

Before we can read and write items in our queue, we must create a `$queue` object using [CRM_Queue_Service](https://lab.civicrm.org/dev/core/blob/master/CRM/Queue/Service.php).
Each `$queue` is an instance of [CRM_Queue_Queue](https://lab.civicrm.org/dev/core/blob/master/CRM/Queue/Queue.php).

A convenient way to produce a `$queue` is to use the create-or-load pattern. This example will create a queue (if it doesn't exist) or load an existing queue (if it already exists).

```php
// Create or load a SQL-based queue.
$queue = CRM_Queue_Service::singleton()->create([
  'type'  => 'Sql',
  'name'  => 'my-own-queue',
  'reset' => FALSE,
]);
```

The `create()` operation accepts these parameters:

* `type` (*required*): Determines how data is written and read from the queue.
    * CiviCRM includes these queue types:
        * `Sql`: Stores the queue data in CiviCRM's SQL database. This is useful for persistent or multi-process queues.
        * `Memory`: Stores the queue data in PHP's memory. This is useful for short-lived queues.
    * Each type corresponds to a class named `CRM_Queue_Queue_{$type}`. To support an additional queue type (such as "STOMP" or "Beanstalk"), one must implement a new driver class.
* `name` (*required*): Identifies the queue. If two processes instantiate a `Sql` queue with the same name, then they will be working with the same data.
* `reset`: Determines whether `create()` should reset the content of a queue.
    * `TRUE` (*default*): Create a new, empty queue. If there's an existing queue, it is destroyed and re-created.
    * `FALSE`: Only create the queue if needed. If the queue already exists, then load it.

The create-or-load pattern is convenient, but it's not appropriate if you need to ensure that the operation starts with clean slate. In such cases, you may explicitly distinguish between creating and loading a queue:

```php
// Create an empty SQL-based queue. If an existing queue exists, then reset/destroy/recreate it.
$queue = CRM_Queue_Service::singleton()->create([
  'type'  => 'Sql',
  'name'  => 'my-own-queue',
  'reset' => TRUE,
]);

// Load an existing SQL-based queue. If it does not exist yet, then you may encounter an error (driver-dependent).
$queue = CRM_Queue_Service::singleton()->load([
  'type'  => 'Sql',
  'name'  => 'my-own-queue',
]);
```

Any of the three examples can produce a `$queue` object - which we will need in the subsequent steps.

## 2. Create items/tasks on the queue

You can add anything that's `serialize`-able to a queue, if you have your own routine to consume that queue, but if you want to use CiviCRM's queue runners you'll need to use `CRM_Queue_Task` objects. An example of the generic queue use is in the code comments for [`CRM_Queue_Service`](https://lab.civicrm.org/dev/core/blob/master/CRM/Queue/Service.php#L29)

A task object is created with a callback, arguments, and a title. All of these must be serializable. Example:

```php
$task = $queue->createItem(new CRM_Queue_Task(
  ['CRM_Demoqueue_Page_DemoQueue', 'doMyWork'], // callback
  ['whatever', ['contact_id' => 123]], // arguments
  "Task $i" // title
));
```

The callback will receive a `CRM_Queue_TaskContext` object which has 2 properties: the queue object, and a `CRM_Core_Error_Log` (under `log`). This means that it's possible for a task to add more tasks to the queue. By default items are added to the end of the queue. However, you can use the *weight* property to change this, e.g. if the main queue has a default 'weight' of zero, you can add queue items before the next items by setting a lower weight, e.g. -1.

Queue tasks can also specify a specific release time which allows for delayed queue jobs.

Example

```php
/**
 * Save an action into a queue for delayed processing
 *
 * @param \DateTime $delayTo
 * @param CRM_Civirules_ActionEngine_AbstractActionEngine $actionEngine
 */
public static function delayAction(DateTime $delayTo, CRM_Civirules_ActionEngine_AbstractActionEngine $actionEngine) {
  $queue = CRM_Queue_Service::singleton()->create(array(
    'type' => 'Civirules',
    'name' => self::QUEUE_NAME,
    'reset' => false, //do not flush queue upon creation
  ));

  //create a task with the action and eventData as parameters
  $task = new CRM_Queue_Task(
    array('CRM_Civirules_Engine', 'executeDelayedAction'), //call back method
    array($actionEngine) //parameters
  );

  //save the task with a delay
  $dao              = new CRM_Queue_DAO_QueueItem();
  $dao->queue_name  = $queue->getName();
  $dao->submit_time = CRM_Utils_Time::getTime('YmdHis');
  $dao->data        = serialize($task);
  $dao->weight      = 0; //weight, normal priority
  $dao->release_time = $delayTo->format('YmdHis');
  $dao->save();
}
```

## 3. Run the queue

CiviCRM's `CRM_Queue_Runner` provides three methods:

1. `runAllViaWeb()` This sends the browser to a page with a progress bar on it.  Ajax requests are used to trigger each job.

2. `runAll()` This runs all the tasks one after another in one go.

3. `runNext()` - This runs the next task in the queue list

This runner can optionally call a callback and issue a redirect to the browser on completion. By default the runner will stop the queue (and 'release' the current item) in the case of failure, but you can override that so that failed jobs are just deleted and processing continues with the next item.

Example:

```php
$runner = new CRM_Queue_Runner([
  'title' => ts('Demo Queue Runner'),
  'queue' => $queue,
  'onEnd' => ['CRM_Demoqueue_Page_DemoQueue', 'onEnd'],
  'onEndUrl' => CRM_Utils_System::url('civicrm/demo-queue/done'),
]);

// If this is a page:
$runner->runAllViaWeb(); // does not return

// Otherwise:
$runner->runAll();
```
Example 2 Running a queue via a cron job or API method style mechanism

```php
function civicrm_api_job_runspecificqueue($params) {
  $returnValues = array();

  $queue = CRM_Demoqueue_Helper::singleton()->getQueue();
  $runner = new CRM_Queue_Runner([
    'title' => ts('Demo Queue Runner'),
    'queue' => $queue,
    'errorMode' => CRM_Queue_Runner::ERROR_CONTINUE,
  ]);

  $maxRunTime = time() + 30; //stop executing next item after 30 seconds
  $continue = TRUE;

  while(time() < $maxRunTime && $continue) {
    $result = $runner->runNext(false);
    if (!$result['is_continue']) {
      $continue = false; //all items in the queue are processed
    }
    $returnValues[] = $result;
  }
  // Spec: civicrm_api3_create_success($values = 1, $params = array(), $entity = NULL, $action = NULL)
  return civicrm_api3_create_success($returnValues, $params, 'Demoqueue', 'Run');
```

!!!warning
    Server configuration has a big impact on how successfully queues will run. Read on!

### Caution: `runAllViaWeb`

If the user closes their browser during a queue being processed via the web then the current job may (a) stop/crash or (b) continue running in the background depending on how the server runs PHP.

**There is no way to safely re-connect with the queue.** Re-opening the page at (`/civicrm/queue/runner?reset=1&qrid=<your-queue-name>`) will immediately cause the first job in the queue to be re-run -- even if it's already running and the other tasks will follow.

This method also suffers from timeouts - again dependent on your server configuration. This can lead to jobs *reporting* as crashed yet actually still running, which can lead to jobs running out of order, or in parallel.

Example: Imagine a queue with three tasks. Nginx may be configured to allow 5 minutes for PHP FPM to respond to a request. Task 1 is slow and after 5 minutes nginx returns a *Gateway Timeout* error and PHP merrily continues. The user sees two buttons: retry or skip. Hitting retry at this point will start a parallel execution of the current task!  However, if task 1 completes while the user is thinking about what to do, then the user clicks Skip, they will skip to task 3, because when task 1 completed it was removed from the queue, leaving task 2 as the current task.

If one task depends on the completion of the other, this can lead to significant data corruption.

!!!tip
    Program checks into your tasks so that task *N* has some way to confirm that task *N - 1* successfully ran before starting; and possibly that there is no other task *N* still running.


### Caution: `runAll`

If your queue uses `runAll()` and is triggered by a Scheduled Job then you need to understand how your cron is set up. If you run cron by accessing the URL over http(s), then you're likely to hit timeout issues on big jobs which can cause problems for your queue and also impact other scheduled jobs.

!!!tip
    The safest way to use `runAll()` is when the script calling it is being run by PHP CLI, e.g. by drush or cv, since this usually means the script has no maximum execution time.

You may choose to run this separately from the normal CiviCRM cron if your queue is large, so that it doesn't get in the way of other tasks that may be more time dependent.

