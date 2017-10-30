!!! tip "Setup"

    The test suites require a small amount of [setup](/testing/index.md#setup).  If your system was created via [buildkit](/tools/buildkit.md) and
    [civibuild](/tools/civibuild.md), then it was handled automatically.

[PHPUnit](https://phpunit.de/) tests ensure that CiviCRM's PHP logic is working as expected &mdash; for example,
ensuring that the `Contact.create` API actually creates a contact.

## Binary

PHPUnit provides a command-line tool.  In [buildkit](/tools/buildkit.md), this tool is named `phpunit4`.  In other environments, it might be
`phpunit` or `phpunit.phar`.

## Suites

PHPUnit tests are grouped together into *suites*.  For example, the `CRM` suite includes the tests `CRM_Core_RegionTest`,
`CRM_Import_Datasource_CsvTest`, and many others.  Each suite has its own coding conventions.  For example, all tests in the `CRM` suite extend the
base class `CiviUnitTestCase` and execute on the headless database.

You'll find suites in many places, such as `civicrm-core`, `civicrm-drupal`, and various extensions. In `civicrm-core`, the main suites are:

| Suite   | Type | CMS | Typical Base Class | Comment |
| ------- | ---- | --- | ------------------ | ----------- |
|`api_v3` | [Headless](/testing/index.md#headless) |Agnostic|`CiviUnitTestCase`|Requires `CIVICRM_UF=UnitTests`|
|`Civi`   | [Headless](/testing/index.md#headless) |Agnostic|`CiviUnitTestCase`|Requires `CIVICRM_UF=UnitTests`|
|`CRM`    | [Headless](/testing/index.md#headless) |Agnostic|`CiviUnitTestCase`|Requires `CIVICRM_UF=UnitTests`|
|`E2E`    | [E2E](/testing/index.md#e2e) |Agnostic|`CiviEndToEndTestCase`|Useful for command-line scripts and web-services|
|`WebTest`| [E2E](/testing/index.md#e2e) |Drupal-only|`CiviSeleniumTestCase`|Useful for tests which require a full web-browser|

## Running tests

To run any PHPUnit test, use a command like this:

```bash
$ cd /path/to/my/project
$ phpunit4 ./tests/MyTest.php
```

Note the command involves a few elements, such as the base-path of the project, the name of the PHPUnit binary, and the relative path of the test.

Let's apply this to a more realistic example.  Suppose we used `civibuild` to create a Drupal 7 site with a copy of `civicrm-core` in the typical
folder, `sites/all/modules/civicrm`.  Test files are stored under `./tests/phpunit`.  To run a typical test like `CRM_Core_RegionTest`, you might
execute:

```bash
$ cd ~/buildkit/build/dmaster/sites/all/modules/civicrm
$ phpunit4 ./tests/phpunit/CRM/Core/RegionTest.php
```

This command ought to work.  It's well-formed.  It *would* work in many cases -- but here it produces an error:

```
PHPUnit 4.8.21 by Sebastian Bergmann and contributors.

EEEEEEEEE

Time: 450 ms, Memory: 17.75Mb

There were 9 errors:

1) CRM_Core_RegionTest
exception 'RuntimeException' with message '_populateDB requires CIVICRM_UF=UnitTests'...
```

What's going on?  The `CRM` suite (and its siblings, `api_v3` and `Civi`) has a special requirement: set the environment variable `CIVICRM_UF`.  This
revised command should correct the issue:

```bash
$ cd ~/buildkit/build/dmaster/sites/all/modules/civicrm
$ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/CRM/Core/RegionTest.php
```

!!! tip "Using PhpStorm for local debugging"

    PhpStorm is an IDE which provides built-in support for executing tests with a debugger -- you can set breakpoints and inspect variables while the tests run.

    Once you've successfully run a test on the command-line, you can take it to the next level and [run the tests within PhpStorm](/tools/phpstorm.md#testing).

!!! tip "Using `civi-test-run` for continuous integration"

    In continuous-integration, one frequently executes a large number of tests from many suites.  [civi-test-run](/tools/civi-test-run.md) is a
    grand unified wrapper which runs *all* CiviCRM test suites, and it is more convenient for use in CI scripts.

!!! tip "Using the legacy wrapper"

    Up through CiviCRM v4.6, the CiviCRM repository included a custom, forked version of PHPUnit. One would execute this command as:

    ```bash
    $ cd /path/to/civicrm
    $ cd tools
    $ ./scripts/phpunit CRM_Core_RegionTest
    ```

    As of v4.7+, there is no longer a fork, and you can use standard PHPUnit binaries. For backward compatibility,
    v4.7+ still includes a thin wrapper script (`tools/scripts/phpunit`) which supports the old calling convention.

!!! tip "Selecting tests with `AllTests.php`"

    In `civicrm-core`, there are several suites (`CRM`, `api_v3_`, etc). Each suite has a file named `AllTests.php` which can be used as follows:

    ```bash
    $ cd /path/to/civicrm
    $ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/CRM/AllTests.php
    ```

!!! tip "Selecting tests with `--filter`, `--group`, etc"

    The PHPUnit CLI supports a number of [filtering options](https://phpunit.de/manual/current/en/textui.html). For example,
    execute a single test function, you can pass `--filter`, as in:

    ```bash
    $ env CIVICRM_UF=UnitTests phpunit4 ./tests/phpunit/CRM/Core/RegionTest.php --filter testOverride
    ```

!!! tip "Selecting tests with PHPUNIT_TESTS"

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
