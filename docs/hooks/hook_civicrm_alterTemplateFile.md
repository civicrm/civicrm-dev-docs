# hook_civicrm_alterTemplateFile

## Summary

This hook is invoked while selecting the tpl file to use to render the
page.

## Definition

    hook_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName)



## Parameters

-   string $formname -name of the form
-   object $form (reference) for object
-   string $context page or form
-   string $tplName - the file name of the tpl - alter this to alter
    the file in use

## Returns

## Example



    /**
     * Alter tpl file to include a different tpl file based on contribution/financial type
     * (if one exists). It will look for
     * templates/CRM/Contribute/Form/Contribution/Type2/Main.php
     * where the form has a contribution or financial type of 2
     * @param string $formName name of the form
     * @param object $form (reference) form object
     * @param string $context page or form
     * @param string $tplName (reference) change this if required to the altered tpl file
     */
    function tplbytype_civicrm_alterTemplateFile($formName, &$form, $context, &$tplName) {
      $formsToTouch = array(
        'CRM_Contribute_Form_Contribution_Main' => array('path' => 'CRM/Contribute/Form/Contribution/', 'file' => 'Main'),

     'CRM_Contribute_Form_Contribution_Confirm' => array('path' =>
    'CRM/Contribute/Form/Contribution', 'file' => 'Confirm'),

    'CRM_Contribute_Form_Contribution_ThankYou' => array('path' =>
    'CRM/Contribute/Form/Contribution', 'file' => 'ThankYou'),
      );


      if(!array_key_exists($formName, $formsToTouch)) {
        return;
      }
      if(isset($form->_values['financial_type_id'])) {
        $type = 'Type' . $form->_values['financial_type_id'];
      }
      if(isset($form->_values['contribution_type_id'])) {
        $type = 'Type' . $form->_values['contribution_type_id'];
      }
      if(empty($type)) {
        return;
      }
      $possibleTpl = $formsToTouch[$formName]['path'] . $type . '/' . $formsToTouch[$formName]['file']. '.tpl';
      $template = CRM_Core_Smarty::singleton();
      if ($template->template_exists($possibleTpl)) {
        $tplName = $possibleTpl;
      }
    }

!!! tip
    While this hook is included in the Form related hooks section it can be used to alter almost all generated content.