# hook_civicrm_buildForm

## Summary

This hook is invoked when building a form. It can be used to set
the default values of a form element, to change form
elements attributes, and to add new fields to a form.

## Definition

    hook_civicrm_buildForm($formName, &$form)

## Parameters

-   string $formName - the name of the form
-   object $form - reference to the form object

## Returns

## Example

    /**
     * Implements hook_civicrm_buildForm().
     *
     * Set a default value for an event price set field.
     *
     * @param string $formName
     * @param CRM_Core_Form $form
     */
    function example_civicrm_buildForm($formName, &$form) {
      if ($formName == 'CRM_Event_Form_Registration_Register') {
        if ($form->getAction() == CRM_Core_Action::ADD) {
          $defaults['price_3'] = '710';
          $form->setDefaults($defaults);
        }
      }
    }

!!! tip
    To access useful values in your hook_civicrm_buildForm function, consider the following:

        $myPid = CRM_Utils_Array::value('pid', $_GET, '0');  // setting 0 as default if 'pid' is not found
        $myEid = CRM_Utils_Array::value('eid', $_GET, '0');  // setting 0 as default if 'pid' is not found
        $form->getVar( '_gid' );
        $form->setVar( '_gid', VALUE ); // sets the variable

 Change a price set field to be required for a specific event.

    function example_civicrm_buildForm($formName, &$form) {
      if ($formName == 'CRM_Event_Form_Registration_Register') {
        if ($form->_eventId == EVENT_ID) {
          $form->addRule('price_3', ts('This field is required.'), 'required');
        }
      }
    }

!!! tip
    With the QuickForms system that CiviCRM uses, you need to do two things to make fields appear:
    
    1. You need to create the element in the form, which is what you can do in the buildForm() hook,
    2. You need to modify the Smarty template used to display the form so it contains the generated HTML for the new form element.

    The later can be achieved by:

    -   overriding the core CiviCRM template with a new one (either in the custom templates directory, or within the templates directory of an extension),
    -   or dynamically inserting a template part in the page through the Regions API.

    This is demonstrated in the next example.

    NOTE: the Regions API is only available if the template contains Regions in the first place - this is not the case with the Inline edit forms! You will then need to either add fake form elements, or modify existing elements' attributes to reach your goals.



**Add and reposition a form field**

    /// FILE: mymodule/mymodule.module
    function mymodule_civicrm_buildForm($formName, &$form) {
      if (($formName == 'CRM_Contribute_Form_Contribution_Main') && ($form->getVar('_id') == 2)) {
        // Assumes templates are in a templates folder relative to this file
        $templatePath = realpath(dirname(__FILE__)."/templates");
        // Add the field element in the form
        $form->add('text', 'testfield', ts('Test field'));
        // dynamically insert a template block in the page
        CRM_Core_Region::instance('page-body')->add(array(
          'template' => "{$templatePath}/testfield.tpl"
         ));
      }
    }

    /// FILE: mymodule/templates/testfield.tpl
    {* template block that contains the new field *}
    <div id="testfield-tr">
      <div>Test field:</div>
      <div>{$form.testfield.html}</div>
    </div>
    {* reposition the above block after #someOtherBlock *}
    <script type="text/javascript">
      cj('#testfield-tr').insertAfter('#someOtherBlock')
    </script>
