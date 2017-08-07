PHP tests ensure that CiviCRM's PHP logic is working as expected &mdash; for example,
ensuring that the 'Contact.create' API actually creates a contact.

These tests are written with PHPUnit. The PHP tests are grouped into suites
(`api_v3_AllTests`, `CRM_AllTests`, and `Civi\AllTests`).

## Setup

These tests required the latest supported version of PHPUnit. This is included
with [buildkit](/tools/buildkit.md).

!!!warning 
    It is possible that using the wrong configuration for tests will cause your main
    local database to be used for testing, and will leave it unusable afterwards.

To check that everything is configured correctly:

```bash
$ cd /path/to/civicrm
$ cv vars:show
```

Check that `CIVI_DB_DSN` and `TEST_DB_DSN` is configured.  If you installed 
using buildkit this should all be configured for you.

If you want to run unit tests (and not WebTests or E2E tests) set the
environment variable `CIVICRM_UF` to "UnitTests" (eg. in `civicrm.settings.test.php` above). This can also be set using the
`env` command to change the environment just for a single command.

!!! warning
    Beware that if your tests change data in your CMS database
    (creating system users etc.) your local build will be affected when running
    tests.

## Running Tests

From the CiviCRM root directory run the phpunit command, specifying a single
test if necessary.

```bash
$ cd /path/to/civicrm
$ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/CRM/AllTests.php
```

or to run an individual test you could run: 

```
$ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/api/v3/CaseTest.php --filter testCaseCreate
```

!!! note
    You can also specify tests in an environment variable `PHPUNIT_TESTS` (eg. `env PHPUNIT_TESTS="MyFirstTest::testFoo MySecondTest" phpunit EnvTests`
    Then run `phpunit4 ./tests/phpunit/EnvTests.php`.

## Writing Tests

When writing Core tests you should extend from `\CiviUnitTestCase`.

But for extensions you should extend directly from `\PHPUnit_Framework_TestCase`.

!!! note
    Once we move to a PHP5.4 minimum requirement we can break up CiviUnitTestCase.php into `traits` so the helper functions are more accessible to extensions.  Currently you have to copy them into your extensions test environment (eg. `callAPISuccess`).

Test methods naming should follow the pattern:

- Start with test
- The name should describe what the test does, e.g. testCreateWithWrongParamsType

It is also recommended that your tests implement `HeadlessInterface` to run your
test against a fake, headless database. `CiviTestListener` will automatically
boot Civi. These tests do not use a real CMS and are faster.

Alternatively, if you wish to run a test in a live (CMS-enabled) environment,
implement `EndToEndInterface`.

The `\Civi\Test` class offers a range of methods for setting up your test and
installing extensions. Read [the documentation here][civi-test-class] for more
information.

### Test Data

It's important that each test is responsible for setting up the data it requires
and returning the database to the original state after it is complete. To help
with that there are two methods:

- `setUp` is executed before each test method
- `tearDown` is executed after each test method

Sometimes it will be convenient to prepare test data for whole test case -
in such case, you will want to put all the test data creation code in there.

Another option is for your test to implement `TransactionalInterface`.

That will guarantee that each test will be wrapped in a Transaction, that will
rollback automatically at the end of the test.

!!! warning
    Schema changes in your test will cause an auto-commit of all changes, and
    therefore the transaction will be ignored. If your tests create custom tables
    or change the database schema please be aware you may need to manually reset
    it.

[civi-test-class]: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
