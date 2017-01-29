# hook_civicrm_buildProfile

## Description

This hook is called while preparing a profile form.

## Definition

    buildProfile($name)

## Parameters

-   $name - the (machine readable) name of the profile.

## Returns

-   null

Can someone say a little more about the purpose of this hook? It's not
immediately obvious how I can use this. I could do something like this:



    function myext_civicrm_buildProfile($name) {

      if ($name === 'MyTargetedProfile) {

        CRM_Core_Resources::singleton()->addScriptFile('org.example.myext', 'some/fancy.js', 100);

      }

    }



... but it would be way more useful if the hook also received a form
object so developers could alter the fields. I seem to recall that
hook_civicrm_buildForm() doesn't get fired for profiles – is that
right?