!!! tip "Setup"

    The test suites require a small amount of [setup](/testing/index.md#setup).  If your system was created via [buildkit](/tools/buildkit.md) and
    [civibuild](/tools/civibuild.md), then it was handled automatically.

[PHPUnit](https://phpunit.de/) tests ensure that CiviCRM's PHP logic is working as expected &mdash; for example,
ensuring that the `Contact.create` API actually creates a contact.

## Command name

PHPUnit is a command-line tool, but the command name varies depending on how it was installed. For example:

* In [buildkit](/tools/buildkit.md), this command is named `phpunit4`.
* In other environments, it might be `phpunit` or `phpunit.phar` or `phpunit.bat`.

For the following examples, we'll use `phpunit4`.

## Suites

PHPUnit tests are grouped together into *suites*.  For example, the `CRM` suite includes the tests `CRM_Core_RegionTest`,
`CRM_Import_Datasource_CsvTest`, and many others.

Each suite has its own coding conventions.  For example, all tests in the `CRM` suite extend the base class `CiviUnitTestCase` and execute on the
headless database. They require a special environment variable (`CIVICRM_UF`).

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

Note how the command involves a few elements, such as the base-path of the project, the name of the PHPUnit binary, and the relative path of the test.

Let's apply this to a more realistic example.  Suppose we used `civibuild` to create a Drupal 7 site with a copy of `civicrm-core` in the typical
folder, `sites/all/modules/civicrm`.  To run a typical test file like `tests/phpunit/CRM/Core/RegionTest.php`, you might execute:

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


## Writing tests for core

As we mentioned in "Suites" (above), the coding conventions vary depending on the suite.  We'll consider a few different ways to write tests.

### CiviUnitTestCase

`CiviUnitTestCase` forms the basis of [headless testing](/testing/index.md#headless) and [unit testing](/testing/index.md#unit) in `civicrm-core`.  In the
three main test suites (`CRM`, `Civi`, and `API`), the vast majority of tests extend `CiviUnitTestCase`. This base-class is generally appropriate for
writing `civicrm-core` tests which execute against a headless database and the standard, baseline schema. Subclasses follow a naming convention
which parallels the primary core code.

For example, if you were writing a test for `CRM/Foo/Bar.php`, then you would create `tests/phpunit/CRM/Foo/BarTest.php`:

```php
/**
 * @group headless
 */
class CRM_Foo_BarTest extends CiviUnitTestCase {
  public function testSomething() {
    $fooBar = new CRM_Foo_Bar();
    $this->assertEquals(1234, $fooBar->getOneTwoThreeFour());
  }
}
```

Tests based on `CiviUnitTestCase` have a few distinctive features:

* When you first start running the tests, they reset the headless database to a standard baseline. The DB reset generally runs once for each test-class; it does not run for each test-function.
* If you define a `setUp()` or `tearDown()` function, be sure to call the `parent::setUp()` or `parent::tearDown()`.
* In the `setUp()` function, you can call `$this->useTransaction()`.  This will wrap all your test functions with a MySQL transaction (`BEGIN`/`ROLLBACK`); any test data you create will be automatically cleaned up.
    * __Caveat__: Some SQL statements implicitly terminate a transaction -- e.g. `CREATE TABLE`, `ALTER TABLE`, and `TRUNCATE`. Consequently, you should only use `useTransaction()` if the tests perform basic data manipulation (`SELECT`, `INSERT`, `UPDATE`, `DELETE`).
* Executing tests based on `CiviUnitTestCase` requires setting an environment variable, `CIVICRM_UF=UnitTests`.
* The tests belong to `@group headless`.

### CiviEndToEndTestCase

`CiviEndToEndTestCase` forms the basis of CMS-neutral [end-to-end testing](/testing/index.md#e2e) in `civicrm-core`.

For example, one might create an end-to-end test for a web service `civicrm/my-web-service` by creating `tests/phpunit/E2E/My/WebServiceTest.php`:

```php
/**
 * @group e2e
 */
class E2E_My_WebServiceTest extends CiviEndToEndTestCase {
  public function testSomething() {
    $url = cv('url civicrm/my-web-service');
    list (, $content) = CRM_Utils_HttpClient::singleton()->post($url, array());
    $this->assertRegExp(';My service is working;', $content);
  }
}
```

Tests based on `CiviEndToEndTestCase` have a few distinctive features:

* You can call Civi classes and functions directly within the test process (eg `CRM_Utils_HttpClient::singleton()` or `civicrm_api3('Contact','get', ['id'=>123])`). In-process code executes with the permissions of an administrative user.
* You can perform work in a sub-process by either:
    * Sending HTTP requests back to the system -- as in `CRM_Utils_HttpClient::singleton()->post(...)`.
    * Using `cv()` to run the scripting tool [cv](https://github.com/civicrm/cv) -- as in `cv('url civicrm/my-web-service')` or `cv('api contact.get id=123')`.
* The global variable `$_CV` provides configuration data about the running system, such as example usernames and passwords. Use `cv vars:show` to view an example.
* The tests belong to `@group e2e`.
* There is no automated cleanup procedure. Write defensive code which cleans up after itself and checks that its environment is sufficiently clean.

!!! tip "Mixing in-process and sub-process work"

    End-to-end testing allows you to perform in-process work (eg `civicrm_api3('Contact','get', ['id'=>123])`) or sub-process work (eg `cv('api contact.get id=123')`).
    In-process calls are faster, but they're not as realistic. It's generally safest to pick one style or the other for a particular test because this categorically prevents
    issues with cache-coherence. Never-the-less, it is possible to mix the styles -- as in the example above.

<!-- FIXME: Document CiviSeleniumTestCase -->

## Writing tests for extensions

### civix

If you are writing an extension using [civix](/extensions/civix.md), the quickest way to create a new test is to generate skeletal code with [civix generate:test](/extensions/civix.md#generate-test).

The generator includes templates for different styles of testing. To generate a [basic unit test](/testing/index.md#unit), [headless test](/testing/index.md#headless), or [end-to-end test](/testing/index.md#e2e), specify `--template`. For example:

```
$ civix generate:test --template=phpunit CRM_Myextension_MyBasicUnitTest
$ civix generate:test --template=headless CRM_Myextension_MyHeadlessTest
$ civix generate:test --template=e2e CRM_Myextension_MyEndToEndTest
```

The resulting tests will extend `PHPUnit_Framework_TestCase` and employ various utilities, such as `HeadlessInterface` or `Civi\Test`. These are described more in the [Reference](#reference).

### From scratch

If you've worked with PHPUnit generally, you can build tests from first principles and incorporate CiviCRM. Although we're presenting in the context of PHPUnit and Civi extensions, the advice is more general -- it may be applied to other kinds of deliverables (such as Civi-CMS integrations and modules).

The first step -- as with any PHPUnit project -- is to create a `phpunit.xml.dist` file and specify a boostrap script.

```xml
<?xml version="1.0"?>
<phpunit bootstrap="tests/phpunit/bootstrap.php" ...>
  <testsuites>
    <testsuite name="My Test Suite">
      <directory>./tests/phpunit</directory>
    </testsuite>
  </testsuites>
  <filter>
    <whitelist>
      <directory suffix=".php">./</directory>
    </whitelist>
  </filter>
</phpunit>
```

At a minimum, the `bootstrap.php` script should register CiviCRM's class-loader, e.g.

```php
eval(`cv php:boot --level=classloader`);
```

Additionally, if you're going to create any custom PHPUnit utilities (your own base-classes, traits, listeners), then load those files or register your own class-loader.

If the tests require a fully functional CiviCRM environment, then you might perform a more complete bootstrap, e.g.

* `cv php:boot --level=settings` -- Load CiviCRM and its settings files, but do *not* bootstrap a CMS.
* `cv php:boot --level=full` -- Bootstrap the full CiviCRM+CMS. (This is appropriate for end-to-end testing.)
* `cv php:boot --level=full --test` -- Bootstrap CiviCRM and fake CMS in a headless test environment. (This is appropriate for headless testing.)

Next, you could create a boilerplate test:

```php
class MyTest extends PHPUnit_Framework_TestCase {
  public function testSomething() { ... }
}
```

If you aim to write *pure, basic* unit-tests, then you're ready to go -- the test function has access to any CiviCRM classes. (And, if you fully bootstrapped, then it also has access to a working database environment.)

However, *pure, basic unit-tests* usually don't get very far in testing Civi -- because a large number of services involve constants, globals, or singletons which are difficult to mock.
Most tests are *headless* or *end-to-end*, and a couple of tricks will help build those:

* It helps to establish a starting environment -- what mix of database tables and extensions should be activated as the test starts? Creating this environment can be resource-intensive, so be tactical: only do the expensive stuff when you really need to.
* For in-process, headless tests, it helps if each test-run resets the in-process state. Call `Civi::reset()` and/or `CRM_Core_Config::singleton(TRUE,TRUE)`.
* For multi-process, end-to-end tests, it helps to have utility functions for launching sub-processes. For example, you might have utilities for sending HTTP requests or invoking `cv`.

Of course, these are recurring problems for developers in the Civi community. The [Reference](#reference) below describes some utilities and techniques. The `civix` templates make heavy use of these, but you can also assemble these pieces yourself.

## Reference

### \Civi\Test

`Civi\Test::headless()` and `Civi\Test:e2e()` help you to define a baseline environment -- by installing extensions, loading SQL files, etc. Consider a few examples:

```php
// Use the stock schema and stock data in the headless DB.
Civi\Test::headless()->apply();

// Use the stock schema and install this extension (i.e. the
// extension which contains __DIR__).
Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();

// Use the stock schema, as well as some special SQL statements
// and extensions.
Civi\Test::headless()
      ->sqlFile(__DIR__ . '/../example.sql')
      ->install(array('org.civicrm.foo', 'org.civicrm.bar'))
      ->apply();

// Use the existing Civi+CMS stack, and also install this
// extension.
Civi\Test::e2e()
      ->installMe(__DIR__)
      ->apply();

// Use the existing Civi+CMS stack, and do a lot of
// crazy stuff
Civi\Test::e2e()->
      ->uninstall('*')
      ->sqlFile(__DIR__ . '/../example.sql')
      ->installMe(__DIR__)
      ->callback(function(){
        civicrm_api3('Widget', 'frobnicate', array());
      }, 'mycallback')
      ->apply();
```

A few things to note:

* `Civi\Test::headless()` and `Civi\Test::e2e()` are similar -- both allow you to declare a sequence of setup steps. Differences:
    * `headless()` only runs on a headless DB, and it can be very aggressive about resetting the system. For example, it may casually reset all your option-groups, drop all custom-data, and uninstall all extensions.
    * `e2e()` only runs with a live CMS (Drupal/WordPress/etc), and it has a lighter touch. It tends to leave things in-place unless you specifically instruct otherwise.
* `Civi\Test` is lazy (in a good way). It keeps track of how the environment is configured, and it only makes a change when necessary.
    * Ex: If you call `Civi\Test` as part of `setUp()`, it will be executed several times (for every test). However, it will usually be a null-op. It will only incur a notable performance penalty when you call with *different* configurations.
    * How: Everytime you run `apply()`, it computes a signature for the requested steps. If the signature is already stored (table `civitest_revs`), then it does nothing. If the signature is new/changed, then it runs.
* `Civi\Test` is stupid. It only knows what you tell it.
    * Ex: If you independently executed `INSERT INTO civicrm_contact` or `TRUNCATE civicrm_option_value`, it won't reset automatically.
    * Tip: If you know that your test cases are particularly dirty, you can force `Civi\Test` to execute by calling `apply(TRUE)` (aka `apply($force === TRUE)`). This may incur a significant performance penalty for the overall suite.
* PATCHWELCOME: If you need to test with custom-data, consider adding more helper functions to `Civi\Test`. Handling custom-data at this level (rather than the test body) should reduce the amount of work spent on tearing-down/re-creating custom data schema, and it should allow better use of transactions.

### \Civi\Test\Api3TestTrait

Many CiviCRM tests focus on APIv3 or call APIv3 incidentally. This can be as simple as:

```php
public function testContactGet() {
  $results = civicrm_api3('Contact', 'get', array('id' => 1));
  $this->assertEquals(1, $results['values'][1]['contact_id'])
}
```

This is pretty intuitive. If there's an error while running the API call, it will throw an exception.

However, the exceptions aren't always easy to read.  The `Api3TestTrait` (CivCRM v5.1+) provides helper functions which report API failures in a more
presentable fashion.  For example, one would typically say:

```php
use \Civi\Test\Api3TestTrait;

public function testContactGet() {
  $results = $this->callApiSuccess('Contact', 'get', array('id' => 1));
  $this->assertEquals(1, $results['values'][1]['contact_id'])
}
```

For a more complete listing of `callApi*()` and `assertApi*()` functions, inspect the trait directly.

### \Civi\Test\CiviTestListener

The `CiviTestListener` is a PHPUnit plugin which allows you to mix-in common test behaviors. You can enable it in `phpunit.xml.dist` using the `<listener>` tag:

```xml
<phpunit ...>
  <!-- ... -->
  <listeners>
    <listener class="Civi\Test\CiviTestListener">
      <arguments/>
    </listener>
  </listeners>
  <!-- ... -->
</phpunit>
```

Once the listener is enabled, you can mix-in behaviors with various interfaces. For example, one might mix several features into `MyFancyTest`:

```php
class MyFancyTest extends PHPUnit_Framework_TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {
```

Let's consider each interface that's available.

#### EndToEndInterface

The `\Civi\Test\EndToEndInterface` marks a test-class as [end-to-end](/testing/index.md#e2e), which means:

* CiviCRM errors will generally be converted to PHP exceptions.
* The test will only run on a live environment (`CIVICRM_UF=Drupal`, `CIVICRM_UF=WordPress`, et al). If you try to run in a headless environment, it will throw an error.
* The test will automatically bootstrap a live environment (if you haven't already booted).
* The test must be flagged with a PHPUnit annotation, `@group e2e`.

#### HeadlessInterface

The `\Civi\Test\HeadlessInterface` marks a test-class as [headless](/testing/index.md#headless), which means:

* CiviCRM errors will generally be converted to PHP exceptions.
* The test will only run on a headless environment (`CIVICRM_UF=UnitTests`). If you try to run in any other environment, it will throw an error.
* The test will automatically bootstrap a headless environment (if you haven't already booted).
* The test will automatically reset common global/static variables at the start of each test function.
* The test must be flagged with a PHPUnit annotation, `@group headless`.
* In addition to `setUp()` and `setUpBeforeClass()`, one may implement the function `setUpHeadless()`. This is usually used to call `Civi\Test::headless()`.

#### HookInterface

The `\Civi\Test\HookInterface` simplifies testing of CiviCRM hooks. Your test call may register hook listeners by adding a new function `hook_civicrm_foo()` function. For example:

```php
class MyTest extends PHPUnit_Framework_TestCase implements HeadlessInterface, HookInterface {
  public function testSomething() {
    civicrm_api3('Contact', 'create', [...]);
  }

  public function hook_civicrm_post($op, $objectName, $objectId, &$objectRef) {
    // listen to hook_civicrm_post
  }
```

The mechanism for registering hooks only applies within the current PHP process -- the hooks would not work when using multiple PHP processes (HTTP/cv). Consequently, `HookInterface` is only compatible with headless testing -- not with E2E testing.

#### TransactionalInterface

The `\Civi\Test\TransactionalInterface` simplifies data-cleanup. At the start of each test-function, it will issue a MySQL `BEGIN`; and, at the end of each
test-function, it will issue a MySQL `ROLLBACK`. This means that your test can `INSERT`, `UPDATE`, and `DELETE` data -- but those changes will be automatically
undone. This allows all your tests to execute in the same clean, baseline environment.

However, there are a few caveats:

* Some SQL statements implicitly terminate a transaction -- e.g. `CREATE TABLE`, `ALTER TABLE`, and `TRUNCATE`. If you need these, then don't use `TransactionalInterface`.
* MySQL transactions can only be enforced if all work focuses on one MySQL database using one PHP process. If you have other databases (e.g. Drupal/WP) or other multiple PHP processes (HTTP/cv), then they won't work. Consequently, `TransactionalInterface` is only compatible with headless testing -- not with E2E testing.
