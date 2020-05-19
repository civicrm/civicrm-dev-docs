# hook_civicrm_fieldOptions

## Summary

This hook allows you to dynamically modify the option list for any field (including custom fields).

## Definition

    hook_civicrm_fieldOptions($entity, $field, &$options, $params)

## Parameters

-   **$entity** (string): API entity e.g. 'Contact', 'Email',
    'Contribution'
-   **$field** (string): Name of field e.g. 'phone_type_id',
    'custom_12'
-   **$options** (array): Array of key=>label options. Your hook may
    modify these at will.
-   **$params** (array): Parameters sent to the pseudoconstant lookup
    function. Especially noteworthy among them is *context*.

## See Also

See [Pseudoconstant (option list) Reference](../framework/pseudoconstant.md)
for more information about how option lists work and the *context*
parameter.

## Example

    function example_civicrm_fieldOptions($entity, $field, &$options, $params) {
      if ($entity == 'Case' && $field == 'case_type_id') {
        if (!CRM_Core_Permission::check('access all cases and activities')) {
          // Remove access to certain case types for non-authorized users
          unset($options[3], $options[5]);
        }
      }
    }
