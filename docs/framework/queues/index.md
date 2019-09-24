# Queues Reference

CiviCRM has a system for splitting big jobs into smaller tasks on a queue, and provides a couple of ways to process queues.
provides a couple of ways to process queues.

## Overview

1. Create a queue object via the service provider
2. Create tasks (or other items) for the queue
3. Run the queue

There's a [demo queue](https://lab.civicrm.org/dev/core/blob/master/tools/extensions/org.civicrm.demoqueue/CRM/Demoqueue/Page/DemoQueue.php)
extension that shows how to use an SQL-based queue.

## Definitions

- *queue*: an object representing a queue, which may be new or existing.
- *queue runner*: a class for processing the queued items.
- *item*: a single job on a queue
- *task*: a particular type of item, as expected by CiviCRM's queue runner.
- *release time*: the time at which a job can be considered to have crashed if it
  is not completed (defaults to 1 hour). Note that it may well not have crashed
  and could still actually be running, especially in PHP FPM environments!
- *claim item*: refers to fetching the first item in a queue (if one exists)
  unless that item's release time has not been reached (typically meaning that
  item is currently being processed).
- *steal item*: refers to fetching the first item in a queue regardless and
  setting a new release time.
- *release item*: refers to leaving a failed queue item on the queue (e.g. for
  later retry)

## 1. Creating a queue

Two implementations of the [Queue interface](https://lab.civicrm.org/dev/core/blob/master/CRM/Queue/Queue.php) are included, one that keeps
the queue in memory, one that uses SQL.

A Queue has a unique string name and a type. By default, creating a queue with
the same name as an existing queue will remove the existing queue, this
behaviour can be changed by passing FALSE as the `reset` parameter.

The queue *type* is translated directly into the class name, so `Sql` expects a
class called `CRM_Queue_Queue_Sql`. Also note that the codebase gives lots of
examples for type (beanstalk, immediate, interactive...), none of which are
implemented(!). You can have `Memory` or `Sql`.

Example:

```php
$queue = CRM_Queue_Service::singleton()->create([
      'type'  => 'Sql',
      'name'  => 'my-own-queue',
    ]);

```

## 2. Create items/tasks on the queue

You can add anything that's `serialize`-able to a queue, if you have your own
routine to consume that queue, but if you want to use CiviCRM's queue runners
you'll need to use `CRM_Queue_Task` objects. An example of the generic queue use
is in the code comments for [`CRM_Queue_Service`](https://lab.civicrm.org/dev/core/blob/master/CRM/Queue/Service.php#L29)

A task object is created with a callback, arguments, and a title. All of these
must be serializable. Example:

```php
$task = $queue->createItem(new CRM_Queue_Task(
  ['CRM_Demoqueue_Page_DemoQueue', 'doMyWork'], // callback
  ['whatever', ['contact_id' => 123]], // arguments
  "Task $i" // title
));
```

The callback will receive a `CRM_Queue_TaskContext` object which has 2
properties: the queue object, and a `CRM_Core_Error_Log` (under `log`). This
means that it's possible for a task to add more tasks to the queue. By default
items are added to the end of the queue. However, you can use the *weight*
property to change this, e.g. if the main queue has a default
'weight' of zero, you can add queue items before the next items by setting a
lower weight, e.g. -1.

## 3. Run the queue

CiviCRM's `CRM_Queue_Runner` provides two methods:

1. `runAllViaWeb()` This sends the browser to a page with a progress bar on it.
   Ajax requests are used to trigger each job.

2. `runAll()` This runs all the tasks one after another in one go.

This runner can optionally call a callback and issue a redirect to the browser on
completion. By default the runner will stop the queue (and 'release' the current
item) in the case of failure, but you can override that so that failed jobs are
just deleted and processing contintues with the next item.

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

!!!warning
    Server configuration has a big impact on how successfully queues will run.
    Read on!

### Caution: `runAllViaWeb`

If the user closes their browser during a queue being processed via the web then
the current job may (a) stop/crash or (b) continue running in the background
depending on how the server runs PHP.

**There is no way to safely re-connect with the queue.** Re-opening the page at
(`/civicrm/queue/runner?reset=1&qrid=<your-queue-name>`) will immediately cause
the first job in the queue to be re-run -- even if it's already running and the
other tasks will follow.

This method also suffers from timeouts - again dependent on your server
configuration. This can lead to jobs *reporting* as crashed yet actually still
running, which can lead to jobs running out of order, or in parallel.

Example: Imagine a queue with three tasks. Nginx may be configured to allow 5
minutes for PHP FPM to respond to a request. Task 1 is slow and after 5 minutes
nginx returns a *Gateway Timeout* error and PHP merrily continues. The user sees
two buttons: retry or skip. Hitting retry at this point will start a parallel
execution of the current task!  However, if task 1 completes while the user is
thinking about what to do, then the user clicks Skip, they will skip to task 3,
because when task 1 completed it was removed from the queue, leaving task 2 as
the current task.

If one task depends on the completion of the other, this can lead to significant
data corruption.

!!!tip
    Program checks into your tasks so that task *N* has some way to confirm that
    task *N - 1* successfully ran before starting; and possibly that there is no
    other task *N* still running.


### Caution: `runAll`

If your queue uses `runAll()` and is triggered by a Scheduled Job then you need
to understand how your cron is set up. If you run cron by accessing the URL over
http(s), then you're likely to hit timeout issues on big jobs which can cause
problems for your queue and also impact other scheduled jobs.

!!!tip
    The safest way to use `runAll()` is when the script calling it is being run
    by PHP CLI, e.g. by drush or cv, since this usually means the script has no
    maximum execution time.

You may choose to run this separately from the normal CiviCRM cron if your queue
is large, so that it doesn't get in the way of other tasks that may be more time
dependent.

