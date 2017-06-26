# hook_civicrm_validateForm

## Summary

This hook allows you to customize the logic used to validate forms.

## Availability

This hook was introduced in v4.2

## Definition

    /**
     * Implements hook_civicrm_validateForm().
     *
     * @param string $formName
     * @param array $fields
     * @param array $files
     * @param CRM_Core_Form $form
     * @param array $errors
     */
    hook_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors)

## Parameters

-   $formName - Name of the form being validated, you will typically
    use this to do different things for different forms.
-   $fields - Array of name value pairs for all 'POST'ed form values
-   $files - Array of file properties as sent by PHP POST protocol
-   $form - Reference to the civicrm form object. This is useful if you
    want to retrieve any values that we've constructed in the form
-   $errors - Reference to the errors array. All errors will be added
    to this array

## Returns

N/A

## Example

    function MODULENAME_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
        // sample implementation
        if ($formName == 'CRM_Contact_Form_Contact') {
           // ensure that external identifier is present and valid
           $externalID = CRM_Utils_Array::value( 'external_identifier', $fields );
           if (! $externalID ) {
              $errors['external_identifier'] = ts( 'External Identifier is a required field' );
           }
           elseif (! myCustomValidatorFunction($externalID)) {
              $errors['external_identifier'] = ts( 'External Identifier is not valid' );
           }
        }
        return;

    }

    function MYMODULE_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
      if ($formName == 'CRM_Contact_Form_Contact') {
        foreach ($fields['address'] as $key => $address) {
          //if country is set to UK or is empty, check for UK postcode formatting
          if (empty($address['country_id']) || $address['country_id'] == 1226) {
        $postcode = str_replace(' ', '', strtoupper($address['postal_code']));
        $preg = "/^([A-PR-UWYZ]([0-9]([0-9]|[A-HJKSTUW])?|[A-HK-Y][0-9]([0-9]|[ABEHMNPRVWXY])?)[0-9][ABD-HJLNP-UW-Z]{2}|GIR0AA)$/";
        $match = preg_match($preg, $postcode) ? true : false;
        if (!$match) {
          $errors['address[' . $key . '][postal_code]'] = ts('Postcode is not a valid UK postcode');
        }
          }
        }
      }
      return;
    }

You can also manipulate validation errors set by CiviCRM. For example,
to unset a validation error triggered by CiviCRM:

    $form->setElementError('some_field_name', NULL);


Note that only errors set by CiviCRM will appear in the $errors array.
Errors set by the underlying form system, e.g. for required fields, can
not be unset here.



The hook is intended for validation rather than altering form values.
However, should you need to alter submitted values you need to access
the controller container object - ie

          $data = &$form->controller->container();                    $data['values']['Main'][$fieldName] = $newvalue;

In this case the form is 'Main' - in the Contribution Page flow.

>     getAttribute('name');
>
> Probably works to get the name