# hook_civicrm_searchTasks

## Summary

This hook is called to display the list of actions allowed after doing a
search, allowing you to inject additional actions or to remove existing actions.

## Definition

    hook_civicrm_searchTasks( $objectType, &$tasks )

## Parameters

-   $objectType - the object for this search - activity, campaign,
    case, contact, contribution, event, grant, membership, and pledge
    are supported.
-   $tasks - the current set of tasks for that custom field. You can
    add/remove existing tasks. Each task is an array with a title (eg
    'title'  => ts( 'Add Contacts to Group')) and a class (eg 'class'
    => 'CRM_Contact_Form_Task_AddToGroup'). Optional result
    (boolean) may also be provided. Class can be an array of classes
    (not sure what that does :( ). The key for new Task(s) should not
    conflict with the keys for core tasks of that $objectType, which
    can be found in CRM/$objectType/Task.php.

## Returns

-   null

## Example (Disable an existing task)

    function civitest_perm () {
      return array(
        'access add contacts to group search action'
      );
    }


    function civitest_civicrm_searchTasks($objectType, &$tasks ) {
        if ( $objectType == 'contact' ) {
            // remove the action from the contact search results if the user doesn't have the permission
            if (! user_access( 'access add contacts to group search action' )) {
                unset($tasks[CRM_Core_Task::GROUP_ADD]);
            }
        }
    }

## Example (Add a new task)

    function smsconversation_civicrm_searchTasks( $objectName, &$tasks ){
      if($objectName == 'contact'){
        $tasks[] = [
          'title' => 'SMS - schedule a conversation',
          'class' => 'CRM_SmsConversation_Form_ScheduleMultiple'
        ];
      }
    }
