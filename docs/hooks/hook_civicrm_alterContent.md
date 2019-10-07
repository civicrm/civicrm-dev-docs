# hook_civicrm_alterContent

## Summary

This hook is invoked after all the content of a CiviCRM form or page is
generated and allows for direct manipulation of the generated content.

## Definition

    hook_civicrm_alterContent(  &$content, $context, $tplName, &$object )

## Parameters

-   string $content - previously generated content
-   string $context - context of content - page or form
-   string $tplName - the file name of the tpl
-   object $object - a reference to the page or form object

## Returns

## Example

    /**
     * Alter fields for an event registration to make them into a demo form.
     */
    function example_civicrm_alterContent( &$content, $context, $tplName, &$object ) {
      if($context == "form") {
        if($tplName == "CRM/Event/Form/Registration/Register.tpl") {
          if($object->_eventId == 1) {
            $content = "<p>Below is an example of an event registration.</p>".$content;
            $content = str_replace("<input ","<input disabled='disabled' ",$content);
            $content = str_replace("<select ","<select disabled='disabled' ",$content);
            $content = $content."<p>Above is an example of an event registration</p>";
          }
        }
      }
    }

!!! tip
    While this hook is included in the Form related hooks section it can be used to alter almost all generated content.