# hook_civicrm_check

## Summary

This hook is called by the "System Check" api.

## Notes

This runs on a regular basis (currently once a day, as well as whenever the status page is visited or `System.check` API is called).

Typically your extension would add results by appending one or more
`CRM_Utils_Check_Message` objects to the $messages array. Constructing
a `CRM_Utils_Check_Message` requires the following parameters:

-   **Name:** A unique string for this type of message (no two messages in the array may have the same name).
    This will be the name given to a StatusPreference record if your message is hushed or disabled.
-   **Description:** Long description html string
-   **Title:** Short title plain text string
-   **Severity:** A [PSR-3 string](http://www.php-fig.org/psr/psr-3/).
-   **Icon:** A [font-awesome icon](https://fortawesome.github.io/Font-Awesome/icons/) string (optional).

See `CRM_Utils_Check::checkAll` for more details about the system check api.

## Availability

Introduced in CiviCRM v4.6.3.

## Definition

    hook_civicrm_check(&$messages)

## Parameters

-   **`&$messages`** `CRM_Utils_Check_Message[]`: Array of messages your hook can append to
-   **`$statusNames`** `array|null`: If only certain checks were requested, check this array and return early if your messages are not called for.
-   **`$includeDisabled`** `bool`: If your hook skips disabled checks (which it should!) this param tells you to bypass your skippage.

## Example

    /**
     * Implementation of hook_civicrm_check
     *
     * Add a check to the status page/System.check results if $snafu is TRUE.
     */
    function mymodule_civicrm_check(&$messages, $statusNames, $includeDisabled) {
    
      // Early return if $statusNames doesn't call for our check
      if ($statusNames && !in_array('mymoduleSnafu', $statusNames)) {
        return;
      }
      
      // If performing your check is resource-intensive, consider bypassing if disabled
      if (!$includeDisabled) {
        $disabled = \Civi\Api4\StatusPreference::get()
          ->setCheckPermissions(FALSE)
          ->addWhere('is_active', '=', FALSE)
          ->addWhere('domain_id', '=', 'current_domain')
          ->addWhere('name', '=', 'mymoduleSnafu')
          ->execute()->count();
        if ($disabled) {
          return;
        }
      }
    
      $snafu = (1 + 1 == 2); // Perform check here
      
      if ($snafu) {
        $messages[] = new CRM_Utils_Check_Message(
          'mymoduleSnafu',
          ts('Situation normal, all funnied up'),
          ts('SNAFU Found'),
          \Psr\Log\LogLevel::WARNING,
          'fa-flag'
        )
        // Optionally add extended help
        ->addHelp(ts('This text will appear in a help bubble if the user clicks on the help icon.'));
      }
    }
