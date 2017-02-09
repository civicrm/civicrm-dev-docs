# hook_civicrm_apiWrappers

## Description

This hook allows to add (or remove, but probably not a good idea)
wrappers to be called before and after the api call.

A wrapper is a class that implement two methods to alter the params sent
to the api and the results returned.

Introduced in CiviCRM 4.4.0.

## Definition

    /**
     * Implements hook_civicrm_apiWrappers
     */
    function myextension_civicrm_apiWrappers(&$wrappers, $apiRequest) {
      //&apiWrappers is an array of wrappers, you can add your(s) with the hook.
      // You can use the apiRequest to decide if you want to add the wrapper (eg. only wrap api.Contact.create)
      if ($apiRequest['entity'] == 'Contact' && $apiRequest['action'] == 'create') {
        $wrappers[] = new CRM_Myextension_APIWrapper();
      }
    }



## Wrapper class

The wrapper is an object that contains two methods fromApiInput and
toApiInput, that allows to modify the params before doing the api call
and the result after. \
 It's quite similar to the pre/post hooks in principle.

To take advantage of CiviCRM's php autoloader, this file should be named
path/to/myextension/CRM/Myextension/APIWrapper.php

    class CRM_Myextension_APIWrapper implements API_Wrapper {
      /**
       * the wrapper contains a method that allows you to alter the parameters of the api request (including the action and the entity)
       */
      public function fromApiInput($apiRequest) {
        if ('Invalid' == CRM_Utils_Array::value('contact_type', $apiRequest['params'])) {
          $apiRequest['params']['contact_type'] = 'Individual';
        }
        return $apiRequest;
      }

      /**
       * alter the result before returning it to the caller.
       */
      public function toApiOutput($apiRequest, $result) {
        if (isset($result['id'], $result['values'][$result['id']]['display_name'])) {
          $result['values'][$result['id']]['display_name_munged'] = 'MUNGE! ' . $result['values'][$result['id']]['display_name'];
          unset($result['values'][$result['id']]['display_name']);
        }
        return $result;
      }
    }