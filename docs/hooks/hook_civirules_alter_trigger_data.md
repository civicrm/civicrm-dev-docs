# hook_civirules_alter_trigger_data

## Description

This hook is called for altering the trigger data object just before a
trigger is triggered.

## Definition

    hook_civirules_alter_trigger_data(CRM_Civirules_TriggerData_TriggerData &$triggerData )

## Returns

-   null

## Example



    /**
    * Implements hook_civirules_alter_trigger_data
    *
    * Adds custom data to the trigger data object
    */
    function civirules_civirules_alter_trigger_data(CRM_Civirules_TriggerData_TriggerData &$triggerData) {

      //also add the custom data which is passed to the pre hook (and not the post)

      CRM_Civirules_Utils_CustomDataFromPre::addCustomDataToTriggerData($triggerData);

    }