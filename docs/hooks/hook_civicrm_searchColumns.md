# hook_civicrm_searchColumns

## Description

This hook is called after a search is done. This allows the developer to
modify the headers and/or the values that are displayed as part of this
search. The BIGGEST drawback with this hook is that you may need to
modify the result template to include your fields. The result files are
CRM/{Contact,Contribute,Member,Event…}/Form/Selector.tpl. However, if
you use the same number of columns, you can overwrite the existing
columns with the values that you want displayed. This is a HACK, but
avoids template modification.

Sorting: as shown in the examples, if you are replacing columns with
different values then you should unset the 'sort' parameter.  Leaving it
set will sort the rows by the original column values but display the new
column values and therefore appear to be sorting incorrectly.

## Definition

    hook_civicrm_searchColumns( $objectName, &$headers, &$rows, &$selector )

## Parameters

-   $objectName - the object for this search - activity, campaign,
    case, contact, contribution, event, grant, membership, and pledge
    are supported.
-   $headers - array (reference) - the list of column headers, an
    associative array with keys: ( name, sort, order )
-   $rows - array (reference) - the list of values, an associate array
    with fields that are displayed for that component
-   $seletor - array (reference) - the selector object. Allows you
    access to the context of the search

## Returns

-   null

## **Example**

    function civitest_civicrm_searchColumns( $objectName, &$headers,  &$values, &$selector ) {

        if ( $objectName == 'Contact' ) {

            // Lets move a few header around, and overwrite stuff we dont need

            // move email to postal slot
            $headers[5] = $headers[7];

            // move phone to country slot
            $headers[6] = $headers[8];

            // lets change the title of the last two columns to fields we need
            $headers[7]['name'] = 'Source';
            unset( $headers[7]['sort'] );

            $headers[8]['name'] = 'Job Title';
            unset( $headers[8]['sort'] );

            foreach ( $values as $id => $value ) {
                $result = civicrm_api( 'Contact', 'GetSingle',
                                       array( 'version' => 3,
                                              'id' => $value['contact_id'],
                                              'return.contact_source' => 1,
                                              'return.job_title' => 1 ) );

                // store this value so it gets passed to the template
                $values[$id]['source'] = $result['contact_source'];
                $values[$id]['job_title'] = $result['job_title'];
            }

            // remember to modify CRM/Contact/Form/Selector.tpl to see the changes
        }

        if ( $objectName == 'Contribute' ) {
            // rename type to total amount
            foreach ( $headers as $id => $header ) {
                if ( $header['name'] == 'Type' ) {
                    $headers[$id]['name'] = 'Total';
                    unset( $headers[$id]['sort'] );
                }
            }

            foreach ( $values as $id => $value ) {
                $sql = "
    SELECT SUM(total_amount)
    FROM   civicrm_contribution
    WHERE  contact_id = %1
    ";
                $values[$id]['total'] = CRM_Core_DAO::singleValueQuery( $sql,
                       array( 1 => array( $value['contact_id'],
                                          'Integer' ) ) );
                // this is cheating, but allows us NOT to modify the template
                // override the values that we are not using
                $values[$id]['contribution_type'] = $values[$id]['total'];
            }
        }