# hook_civicrm_alterBarcode

## Summary

This hook allows you to modify the content that is encoded in barcode.

## Availability

Available in 4.4+.

## Definition

     hook_civicrm_alterBarcode( &$data, $type='barcode', $context='name_badge' );

## Parameters

-   $data - is an associated array with all token values and some
    additional info
    -   $data['current_value'] - this will hold default value set by
        CiviCRM
-   $type - type of barcode ( barcode or qrcode )
-   $context - currently this functionality is implemented only for
    name badges, hence context=name_badge

## Returns

## Example

    function hook_civicrm_alterBarcode(&$data, $type, $context ) {
      if ($type == 'barcode' && $context == 'name_badge') {
        // change the encoding of barcode
        $data['current_value'] = $data['event_id'] . '-' . $data['participant_id'] . '-' . $data['contact_id'];
      }
    }
