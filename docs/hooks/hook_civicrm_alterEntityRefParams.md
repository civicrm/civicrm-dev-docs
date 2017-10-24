# hook_civicrm_alterEntityRefParams

## Summary

Allows you to modify entityRef field params.

## Availability

This hook is available in CiviCRM 4.7.27+ (maybe depends on the commit).

## Definition

     hook_civicrm_alterEntityRefParams(&$params)

## Parameters

-   @param array $params - parameters of entityRef field

## Details

 This hook is called when entityRef field is rendered in form, which allows you to modify the parameters used to fetch options for this kind of field.


## Example

```php
     function myextension_civicrm_alterEntityRefParams(&$params) {
       // use your custom API to fetch tags of your choice
       if ($params['entity'] == 'tag') {
         $params['entity'] = 'my_tags';
         $params['api'] = array('params' => array('parent_id' => 292));
       }
       ...
     }
```
