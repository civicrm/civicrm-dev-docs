!!! tip "Setup"

    The test suites require a small amount of [setup](/testing/index.md#setup).  If your system was created via [buildkit](/tools/buildkit.md) and
    [civibuild](/tools/civibuild.md), then it was handled automatically.

[PHPUnit](https://phpunit.de/) tests ensure that CiviCRM's PHP logic is working as expected &mdash; for example,
ensuring that the `Contact.create` API actually creates a contact.

## Suites

PHPUnit tests are written as *PHP classes* (such as `api_v3_ContactTest`) and grouped together into *suites* (such as `api_v3`).  Each suite
may follow different coding conventions.  For example, all tests in the `api_v3` suite extend the base class `CiviUnitTestCase` and execute on the
headless database.

The `civicrm-core` project includes these suites:


| Suite   | Type | CMS | Typical Base Class | Comment |
| ------- | ---- | --- | ------------------ | ----------- |
|`api_v3` |`headless`|Agnostic|`CiviUnitTestCase`|Requires `CIVICRM_UF=UnitTests`|
|`Civi`   |`headless`|Agnostic|`CiviUnitTestCase`|Requires `CIVICRM_UF=UnitTests`|
|`CRM`    |`headless`|Agnostic|`CiviUnitTestCase`|Requires `CIVICRM_UF=UnitTests`|
|`E2E`    |`e2e`|Agnostic|`CiviEndToEndTestCase`|Useful for command-line scripts and web-services|
|`WebTest`|`e2e`|Drupal|`CiviSeleniumTestCase`|Useful for tests which require a full web-browser|

Each extension may have its own suite(s).

## Running tests

To execute all tests in a suite, run the corresponding `AllTests.php` file. For example, this command runs the full `CRM` suite:

```bash
$ cd /path/to/civicrm
$ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/CRM/AllTests.php
```

To execute a single test class, specify that file, as in:

```bash
$ cd /path/to/civicrm
$ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/CRM/Core/RegionTest.php
```

Generally, note how each example breaks down into a few pieces of information. These
are the elements you would need to run PHPUnit tests in `civicrm-core`, but the formula
would be the same for an extension:

 * Path to codebase. (Ex: `/path/to/civicrm`)
 * If required, any environment variables. (For `headless` tests, use `CIVICRM_UF=UnitTests`)
 * Name of the PHPUnit command. (Ex: `phpunit4`, which is bundled with [buildkit](/tools/buildkit.md))
 * Name of the test file. (Ex: `tests/phpunit/CRM/Core/RegionTest.php` or `tests/phpunit/CRM/AllTests.php`)

!!! tip "PhpStorm"

    PhpStorm is an IDE which provides built-in support for executing tests with a debugger -- you can set breakpoints and inspect variables while the tests run.

    Once you've successfully run a test on the command-line, you can take it to the next level and [run the tests within PhpStorm](/tools/phpstorm.md#testing).

!!! tip "civi-test-run"

    [civi-test-run](/tools/civi-test-run.md) is a grand unified wrapper which runs *all* CiviCRM test suites. It's particularly useful for *continuous-integration*.

!!! tip "Select tests using `--filter`, `--group`, etc"

    The PHPUnit CLI supports a number of [filtering options](https://phpunit.de/manual/current/en/textui.html). For example,
    execute a single test function, you can pass `--filter`, as in:

    ```bash
    $ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/CRM/Core/RegionTest.php --filter testOverride
    ```

!!! tip "Select tests using PHPUNIT_TESTS"

    If you want to hand-pick a mix of tests to execute, set the environment variable `PHPUNIT_TESTS`.  This a space-delimited list of classes and
    functions. For example:

    ```bash
    $ env PHPUNIT_TESTS="MyFirstTest::testFoo MySecondTest" CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/EnvTests.php
    ```

## Writing tests

When writing headless tests for `civicrm-core`, extend the class `\CiviUnitTestCase`.

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

Another option is for your test to implement `TransactionalInterface`.  That
will guarantee that each test will be wrapped in a SQL transaction which
automatically rolls back any database changes.

!!! warning
    Schema changes in your test will cause an auto-commit of all changes, and
    therefore the transaction will be ignored. This includes `TRUNCATE TABLE`,
    because this implicitly drops and re-creates the table. If your tests create
    custom tables or change the database schema please be aware you may need to
    manually reset it.

[civi-test-class]: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
