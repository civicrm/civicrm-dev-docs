# hook_civicrm_geocoderFormat

## Summary

This hook allows you to manipulate the Address object during geocoding,
for instance to extract additional fields from the geocoder's returned
XML.

## Availability

This hook was first available in CiviCRM 4.7.7.

## Definition

     hook_civicrm_geocoderFormat($geoProvider, &$values, $xml)

## Parameters

-   @param string $geoProvider - A short name for the geocoder. Core
    geocoders are 'Google' and 'Yahoo.'
-   @param array $values - The address that was passed to the geocoder.
-   @param SimpleXMLElement $xml - The response from the geocoder.





## Details

## Example

**Populate the "county ID" field when using the Google geoprovider.**

    function countylookup_civicrm_geocoderFormat($geoProvider, &$values, $xml) {
      if($geoProvider !== 'Google') {
        exit;
      }
      foreach ($xml->result->address_component as $test) {
        $type = (string) $test->type[0];
        if ($type == 'administrative_area_level_1') {
          $stateName = (string) $test->long_name;
        }
        if ($type == 'administrative_area_level_2') {
          $countyName = (string) $test->long_name;
        }
      }
      // Take off the word "County".
      $countyName = trim(str_replace('County', '', $countyName));
      // For < 4.7 compatibility, do 2 API calls instead of a join
      $result = civicrm_api3('StateProvince', 'get', array(
        'return' => array("id"),
        'name' => $stateName,
      ));
      $state_province_id = $result['id'];
      $result = civicrm_api3('County', 'get', array(
        'sequential' => 1,
        'state_province_id' => $state_province_id,
      //  'state_province_id.name' => $stateName,
        'name' => $countyName,
      ));
      $countyId = $result['id'];
      $values['county_id'] = $countyId;
    }