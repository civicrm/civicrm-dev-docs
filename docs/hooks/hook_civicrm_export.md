# hook_civicrm_export

## Summary

This hook allows to manipulate or change the output of CSV during
export.

## Availability

This hook was first available in CiviCRM 3.2.4, $componentTable and $ids variables are available in CiviCRM 5.8.0

## Definition

     hook_civicrm_export (&$exportTempTable, &$headerRows, &$sqlColumns, $exportMode, $componentTable, $ids)

## Parameters

-   @param string $exportTempTable - name of the temporary export table
    used during export
-   @param array $headerRows - header rows for output
-   @param array $sqlColumns - SQL columns
-   @param int $exportMode - export mode ( contact, contribution, etc...)
-   @param string $componentTable - Name of temporary table
-   @param array $ids - Array of object's ids

## Details

## Example
```php
    function civitest_civicrm_export( $exportTempTable, $headerRows, $sqlColumns, $exportMode, $componentTable, $ids ) {
        $writeHeader = true;
        $offset = 0;
        $limit  = 200;

        $query = "
    SELECT *
    FROM   $exportTempTable
    ";
        require_once 'CRM/Core/Report/Excel.php';
        while ( 1 ) {
            $limitQuery = $query . "
    LIMIT $offset, $limit
    ";
            $dao = CRM_Core_DAO::executeQuery( $limitQuery );

            if ( $dao->N <= 0 ) {
                break;
            }

            $componentDetails = array( );
            while ( $dao->fetch( ) ) {
                $row = array( );

                foreach ( $sqlColumns as $column => $dontCare ) {
                    $row[$column] = $dao->$column;
                }

                $componentDetails[] = $row;
            }
            CRM_Core_Report_Excel::writeHTMLFile( "Export_Records", $headerRows,
                                                 $componentDetails, null, $writeHeader );
            $writeHeader = false;
            $offset += $limit;
        }

        CRM_Utils_System::civiExit( );
    }

    Second example, adding columns to the export and leaving the export to do its thing
    Note example above isn't using pass by reference on the fields and hence may have been that way

    function civitest_civicrm_export ( $exportTempTable, &$headerRows, &$sqlColumns, $exportMode, $componentTable, $ids ) {

      // Only want to do this for contribution export
      if ($exportMode==2) {

        // Check we have contribution_id and contribution_campaign_id before we do the work
        if (empty($sqlColumns['psf_company']) || empty($sqlColumns['psf_company'])) {
          return;
        }

        // Add the four columns for financial coding
        $sql = "ALTER TABLE ".$exportTempTable." ";
        $sql .= "ADD COLUMN psf_company varchar(255) ";
        $sql .= ",ADD COLUMN psf_department varchar(255) ";
        $sql .= ",ADD COLUMN psf_nominal_code varchar(255) ";
        $sql .= ",ADD COLUMN psf_cost_centre varchar(255) ";

        CRM_Core_DAO::singleValueQuery($sql);

        // Populate them from the source table
        $sql = "UPDATE ".$exportTempTable." a ";
        $sql .= "JOIN civicrm_value_psf_financial_coding_9 b ON a.contribution_campaign_id = b.entity_id ";
        $sql .= "SET psf_company = b.psf_company_29 ";
        $sql .= ",psf_department = b.psf_department_30 ";
        $sql .= ",psf_nominal_code = b.psf_nominal_code_31 ";
        $sql .= ",psf_cost_centre = b.psf_cost_centre_32 ";

        CRM_Core_DAO::singleValueQuery($sql);

        // Ensure everything is added to the $headerRows and $sqlColumns
        $headerRows[] = "PSF Company";
        $headerRows[] = "PSF Department";
        $headerRows[] = "PSF Nominal Code";
        $headerRows[] = "PSF Cost Centre";

        $sqlColumns['psf_company'] = 'psf_company varchar(255)';
        $sqlColumns['psf_department'] = 'psf_department varchar(255)';
        $sqlColumns['psf_nominal_code'] = 'psf_nominal_code varchar(255)';
        $sqlColumns['psf_cost_centre'] = 'psf_cost_centre varchar(255)';
      }
    }
```
