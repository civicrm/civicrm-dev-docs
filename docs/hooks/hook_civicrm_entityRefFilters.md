# hook_civicrm_entityRefFilters

## Summary

This hook is called when filters and create links for entityRef field is build.

## Definition

    hook_civicrm_entityRefFilters(&$filters, &$links)

## Parameters

-   array $filters - reference to list of filters
-   array $links - reference to list of create links

## Returns

## Example

     /**
      * Implements hook_civicrm_entityRefFilters().
      *
      * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityRefFilters
      */
     function modulename_civicrm_entityRefFilters(&$filters, &$links) {
       // Add New Staff link on entityRef field of contact
       $links['Contact'][] = [
         'label' => ts('New Staff'),
         'url' => CRM_Utils_System::url('/civicrm/profile/create', 'reset=1&context=dialog&gid=5'),
         'type' => 'Individual',
         'icon' => 'fa-user',
       ];

       // Add Do not email filter on contact entity ref field.
       $filters['Contact'][] = [
         'key' => 'do_not_email',
         'value' => ts('Do Not Email'),
       ];
       // Add Marital status filter on contact entity ref field.
       $filters['Contact'][] = [
         'key' => 'custom_2',
         'value' => ts('Marital status'),
       ];

       // Add custom field of address as filter on contact entity ref field.
       $filters['Contact'][] = [
         'key' => 'custom_34',
         'value' => ts('Belongs to'),
         'entity' => 'Address',
       ];
     }
