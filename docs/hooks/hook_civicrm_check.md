# hook_civicrm_check

## Summary

This hook is called by the "System Check" api.

## Notes

This runs on a regular basis (currently
once a day, as well as whenever the status page is visited or
`System.check` API is called).

Typically your extension would add results by appending one or more
`CRM_Utils_Check_Message` objects to the $messages array. Constructing
a `CRM_Utils_Check_Message` requires the following parameters:

-   **Unique identifier:** A unique string for this type of message (no two messages in the array may have the same identifier).
-   **Description:** Long description html string
-   **Title:** Short title plain text string
-   **Severity:** A [PSR-3 string](http://www.php-fig.org/psr/psr-3/).
-   **Icon:** A [font-awesome
    icon](https://fortawesome.github.io/Font-Awesome/icons/) string
    (optional).

See `CRM_Utils_Check::checkAll` for more details about the system check api.

## Availability

Introduced in CiviCRM v4.6.3.

## Definition

    hook_civicrm_check(&$messages)

## Parameters

-   [CRM_Utils_Check_Message]** $messages**

## Example

    /**
     * Implementation of hook_civicrm_check
     *
     * Add a check to the status page/System.check results if $snafu is TRUE.
     */
    function mymodule_civicrm_check(&$messages) {
      $snafu = TRUE;
      if ($snafu) {
        $messages[] = new CRM_Utils_Check_Message(
          'mymodule_snafu',
          ts('Situation normal, all funnied up'),
          ts('SNAFU'),
          \Psr\Log\LogLevel::CRITICAL,
          'fa-flag'
        )
        // Optionally add extended help
        ->addHelp(ts('This text will appear in a help bubble if the user clicks on the help icon.'));
      }
    }