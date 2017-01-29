# hook_civicrm_entityTypes

## Description

This hook is called for declaring managed entities via API. [See
here](https://wiki.civicrm.org/confluence/display/CRMDOC/Create+a+Module+Extension#CreateaModuleExtension-Addanewentity)
for a more complete description of creating a managed entity.

## Definition



  ----------------------------------------------------------
  `hook_civicrm_`{.java .plain}entityTypes(&$entityTypes)
  ----------------------------------------------------------



The *$entityTypes* is an array, where each item has the properties:

-   **name**: *string, required* – a unique short name (e.g.
    "ReportInstance")
-   **class**: *string, required* – a PHP DAO class (e.g.
    "CRM_Report_DAO_Instance")
-   **table**: *string, required* – a SQL table name (e.g.
    "civicrm_report_instance")
-   **fields_callback**: *array, optional* – a list of callback
    functions which can modify the DAO field metadata.
    (*function($class, &$fields)*) Added circa 4.7.11+
-   **items_callback**: *array, optional* – a list of callback
    functions which can modify the DAO foreign-key metadata.
    (*function($class, &$links)*) Added circa 4.7.11+

The main key for *$entityTypes* should be a DAO name (e.g.
*$entityTypes['CRM_Report_DAO_Instance']*), although this has not
always been enforced.

## Returns

-   null

## Example: Add new entities

    /** * Implements hook_civicrm_entityTypes. * * @param array $entityTypes *   Registered entity types. */function volunteer_civicrm_entityTypes(&$entityTypes) { $entityTypes['CRM_Volunteer_DAO_Need'] = array(   'name' => 'VolunteerNeed',   'class' => 'CRM_Volunteer_DAO_Need',   'table' => 'civicrm_volunteer_need', ); $entityTypes['CRM_Volunteer_DAO_Project'] = array(   'name' => 'VolunteerProject',   'class' => 'CRM_Volunteer_DAO_Project',   'table' => 'civicrm_volunteer_project', ); $entityTypes['CRM_Volunteer_DAO_ProjectContact'] = array(   'name' => 'VolunteerProjectContact',   'class' => 'CRM_Volunteer_DAO_ProjectContact',   'table' => 'civicrm_volunteer_project_contact', );}



## Example: Alter entity metadata (v4.7.11?+)

    /** * Implements hook_civicrm_entityTypes. * * @param array $entityTypes *   Registered entity types. */function example_civicrm_entityTypes(&$entityTypes) {  $entityTypes['CRM_Contact_DAO_Contact']['fields_callback'][] = function($class, &$fields) {    unset($fields['created_date']['export']);  };}