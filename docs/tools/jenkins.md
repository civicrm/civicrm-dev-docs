# Jenkins continuous integration

Pull-requests are tested automatically with build-bot software called [Jenkins](https://jenkins.io/) which runs on [tests.civicrm.org](http://tests.civicrm.org/). Key things to know:

* If you are a new contributor, the tests may be placed on hold pending a
  cursory review. One of the administrators will post a comment like
  `jenkins, ok to test` or `jenkins, add to whitelist`.
* The pull-request will have a colored dot indicating its status:
    * **Yellow**: The automated tests are running.
    * **Red**: The automated tests have failed.
    * **Green**: The automated tests have passed.
* If the automated test fails, click on the red dot to investigate details. Check for information in:
    * The initial summary. Ordinarily, this will list test failures and error messages.
    * The console output. If the test-suite encountered a significant error (such as a PHP crash),
      the key details will only appear in the console.
* Code-style tests are executed first. If the code-style in this patch is inconsistent, the remaining tests will be skipped.
* The primary tests may take 20-120 min to execute. This includes the following suites: `api_v3_AllTests`, `CRM_AllTests`, `Civi\AllTests`, `civicrm-upgrade-test`, and `karma`
* There are a handful of unit tests which are time-sensitive and which fail sporadically. See: https://forum.civicrm.org/index.php?topic=36964.0
* The web test suite (`WebTest_AllTests`) takes several hours to execute. [It runs separately -- after the PR has been merged.](https://test.civicrm.org/job/CiviCRM-WebTest-Matrix/)

For detailed discussion about automated tests, see [Testing](/testing/setup.md)
