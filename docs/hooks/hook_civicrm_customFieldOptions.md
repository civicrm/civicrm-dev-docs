# hook_civicrm_customFieldOptions

## Summary

Deprecated in 4.7 in favor of [hook_civicrm_fieldOptions](hook_civicrm_fieldOptions.md). Use that instead for modifying all option lists, not limited to custom fields.


## Definition

    hook_civicrm_customFieldOptions( $fieldID, &$options, $detailedFormat = false )

## Parameters

-   $fieldID - the custom field ID
-   $options - the current set of options for that custom field. You
    can add/remove existing options. **Important: This array may contain
    meta-data about the field that is needed elsewhere, so it is
    important to be careful to not overwrite the array. Only
    add/edit/remove the specific field options you intend to affect.**
-   $detailedFormat - if true, the options are in an ID => array (
    'id' => ID, 'label' => label, 'value' => value ) format

## Returns

-   null

## Example

    function civitest_civicrm_customFieldOptions($fieldID, &$options, $detailedFormat = false ) {
        if ( $fieldID == 1 || $fieldID == 2 ) {
            if ( $detailedFormat ) {
                $options['fake_id_1'] = array( 'id'    => 'fake_id_1',
                                               'value' => 'XXX',
                                               'label' => 'XXX' );
                $options['fake_id_2'] = array( 'id'    => 'fake_id_2',
                                               'value' => 'YYY',
                                               'label' => 'YYY' );
            } else {
                $options['XXX'] = 'XXX';
                $options['YYY'] = 'YYY';
            }
        }
    }

This syntax may be more convenient if you are a managing differing sets
of options for different fields:

    function EXAMPLE_civicrm_customFieldOptions($fieldID, &$options, $detailedFormat = false ) {
        switch ($fieldID) {
          case 1:
          case 2:
            $detailed_options['fake_id_1'] = array( 'id'    => 'fake_id_1',
                                           'value' => 'Xvalue',
                                           'label' => 'Xlabel' );
            $detailed_options['fake_id_2'] = array( 'id'    => 'fake_id_2',
                                           'value' => 'Yvalue',
                                           'label' => 'Ylabel' );
            break;

          case 3:
            $detailed_options['fake_id_1'] = array( 'id'    => 'fake_id_1',
                                           'value' => 'Avalue',
                                           'label' => 'Alabel' );
            $detailed_options['fake_id_2'] = array( 'id'    => 'fake_id_2',
                                           'value' => 'Bvalue',
                                           'label' => 'Blabel' );
            break;
        }

        if (isset($detailed_options) && !$detailedFormat ) {
          foreach ($detailed_options AS $key => $choice) {
            $options[$choice['value']] = $choice['label'];
          }
        } else {
          $options += $detailed_options;
        }
    }