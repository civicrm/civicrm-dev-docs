# hook_civicrm_buildStateProvinceForCountry

## Summary

This hook is called during the ajax callback that is used to build the
options that display in the State/Province select widget for a specific
country, and can be used to alter the list of State/Province options for
particular countries.

## Definition

    hook_civicrm_buildStateProvinceForCountry( $countryID, &$states )

## Parameters

-   @param string $countryID Country ID for which State/Province data
    is being looked up
-   @param array $states array of State/Province data relating to
    country being looked up (keys = State/Province ID, values =
    State/Province name)

## Returns

-   null

## Availability

-   Available since 4.1

## Example

The example below reorders the Irish State list so that Dublin is at the
top of the list (by default, Dublin would be placed in the list in
alphabetical order.

    /*
     * Implements hook_civicrm_buildStateProvinceForCountry().
     *
     * Reorder the dublin states so that Dublin is at the top and dublin sub
     * states are ordered nicely.
     */
    function ourclient_civicrm_buildStateProvinceForCountry( $countryID, &$states ) {
      // First separate out the Dublin options.
      $topStates = array();
      foreach ($states as $key => $value) {
        if (preg_match('/Dublin/', $value)) {
          $topStates[$key] = $value;
        }
        else {
          $otherStates[$key] = $value;
        }
      }
      ksort($topStates);
      $states = $topStates + $otherStates;
    }