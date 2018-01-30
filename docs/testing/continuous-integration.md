# Jenkins

To prevent defects from entering the system or remaining in the system, the tests are executed automatically Jenkins. Jenkins first runs php and javascript style checks through the usage of `civilint` before running unit tests.

Jenkins runs two different types of jobs a PR test job and a matrix Job. The results of the tests are published at [test.civicrm.org][jenkins-test-results] [testing-readme]: https://github.com/civicrm/civicrm-core/blob/master/tests/README.md

## PR Test Jobs

Jenkins runs a "PR Test" job which is triggered whenever a pull request is created or updated in gihub PR test jobs can take anywhere from 5 - 90min to complete. This works for the following repos:

* `civicrm-core`
* `civicrm-packages`
* `civicrm-drupal`
* `civicrm-backdrop`

There are seven main groups or suites  of tests:

* Karma tests - These are javascript unit tests run by Karma and live in the tests/karma directory of the civicrm-core repo.
* CRM Tests - These are phpunit tests generally testing the CRM/ folder of civicrm-core. The tests are found in the `tests/phpunit/CRM` directory of the civicrm-core repo.
* api_v3 Tests - These are phpunit tests that test the funtions associate with APIv3. These can be found in `tests/phpunit/api/v3` directory of the civicrm-core repo.
* Civi Tests - These are tests that mainly test the `Civi()::` facade. They can be found in the `tests/phpunit/civi` directoy of civicrm-core repo.
* E2E Tests - These are tests that test the integration with the Content Management System (CMS) they usually involve testing rest or Soap interfaces. These can be found in `tests/phpunit/E2E` directoy of the civicrm-core repo
* Upgrade Tests - This is a test where it takes a known databasse state loads it into the test db and then runs the upgrade script to try and upgrade the database to the current code version.
* Webtests  - These are selenium webtests that are run occassionally and haven't been well maintained

To run all the tests in one of the suites locally you can use [civi-test-run](/tools/civi-test-run.md).

The tests that are run are variable depending on the repository that is triggered.

* civicrm-core: Upgrade, karma, CRM, api_v3, E2E and Civi tests are run
* civicrm-packages: same as civicrm-core
* civicrm-drupal: Upgrade, Karma and E2E tests are run
* civicrm-backdrop: same as civicrm-drupal

For PR test jobs against civicrm-core and civicrm-packages jenkins only builds a drupal site to run the tests against. If you are fixing an issue with another CMS you may need to build yourself a local test environment with that CMS

If the tests have failed for something that we suspect is a random failure, we can ask Jenkins to run the tests again by commenting in the PR "Jenkins, test this please"

## Matrix Test jobs

The other type of Job that jenkins runs is what is desccribed as a "matrix" job. This is a much more extended version of the PR job and is usually run against multiple different webserver configurations.

The main difference between the `civicrm-core-matrix` job and a PR test job is that it runs more upgrade tests than the CiviCRM Core PR.

The other matrix job is one that runs the webtests.

The matrix jobs operate on two main combinations a PHP5.5 + MySQL5.5 server and a PHP7.0 + MySQL5.7 test seerver.

Due to the length and the test regieme matrix jobs can take from 2 to 24hours to complete depending on the job.

### Build Schedule

Jenkins periodically runs the full suite (once every 4-24hr) on the official codebase.

Code-style is not checked on this build, but all upgrade and web tests are run.


## Jenkins Whitelist

For new (unrecognised by Jenkins) contributors, Jenkins will automatically respond "can an admin verify this patch?", and a Github user with admin permissions may approve running the tests by commenting on the PR "ok to test".

If the user is trusted, CiviCRM administrators can add the person to the whitelist by commenting "add to whitelist".
