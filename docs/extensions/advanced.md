# Advanced patterns

!!! caution 
    Some of these instruction maybe deprecated and superceded by better approaches.

## Web Services

There are three options to create an ajax or web-service callback:

-    **Full control:** Add a basic page. Remove the parent::run() call from the run() function, and at the bottom of the run() function, perform your own output (eg "*echo json\_encode($data)*") and then short-circuit processing (eg "*CRM\_Utils\_System::civiExit()*") so that neither Smarty nor the CMS modify the output.
-    **Using ajax helpers (CiviCRM 4.5 and above):** Generate a page with civix as above. Build your data in the run() function. If the client-side request includes *snippet=json* in the url, just append your data to *$this-\>ajaxResponse* array and the rest will happen automatically. If not, you can directly call CRM\_Core\_Page\_AJAX::returnJsonResponse() at the bottom of the run function. See [Ajax Pages and Forms](../framework/ajax.md) documentation.
-    **Using the API:** Add an API function using `civix`. The API function can be called with the API's [AJAX Interface](../api/interfaces.md#ajax). This automatically handles issues like encoding and decoding the request/response.

## Standalone PHP scripts

Instead of creating a standalone script, consider one of these options:

-   Add an API function (using the instructions above). The API function can be called many different ways – cv, PHP, REST, AJAX, CLI, Drush, Smarty, cron, etc. CiviCRM used to include a number of standalone scripts – many of these have been migrated to API functions because this approach is simpler and more flexible.
-   Add a basic page (using the instructions above). At the bottom of the run() function, call "*CRM\_Utils\_System::civiExit()*" to short-circuit theming and CMS processing.

Creating a pure standalone PHP script is a tricky proposition and likely to be brittle compared with the above.

If the script is truly standalone and does not require any services from the CRM or CMS, then you can just add a new `.php` file to the extension... but it won't have access to CiviCRM's APIs, databases, classes, etc. If the standalone script needs those services, then it will need to ***bootstrap*** CiviCRM and the CMS. This is challenging for several reasons:

-   The bootstrap mechanics are different in each CMS (Drupal, Joomla, etc).
-   The bootstrap mechanics are different for single-site installations and multi-site installations.
-   To initiate a bootstrap from a script, one needs to determine the local-path to the CiviCRM settings. However, the local-path of the script is entirely independent of the local-path to the settings – these are determined at the discretion of the site administrator.

If you really need to do it, it's theoretically possibly to emulate an example like "[bin/deprecated/EmailProcessor.php](http://svn.civicrm.org/civicrm/branches/v4.1/bin/deprecated/EmailProcessor.php)". The results will likely be difficult for downstream users to install/use.

## Cron jobs

One can add an API function (using the instructions above) and create a schedule record. In CiviCRM 4.3, the schedule record can be automatically created; to do this, call "civix [generate:api](civix.md#generate-api)" with the option "–schedule Daily" (or "-schedule Hourly", etc). CiviCRM will make a best-effort to meet the stated schedule.

In CiviCRM 4.2, one can use APIs as cron jobs, but the schedule record won't be created automatically. The site administrator must manually insert a scheduling record by navigating to "Administer =\> System Settings =\> Scheduled Jobs".

