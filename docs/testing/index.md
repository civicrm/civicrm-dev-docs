## Overview

The CiviCRM suite spans the full stack -- it includes low-level helpers and user-facing applications; it includes
server-side PHP and client-side Javascript; it touches multiple business areas (such as contributions, mailings, and
cases); it includes a common core project and an ecosystem of add-ons. This requires a lot of testing.

Depending on the scope of the test and the relevant language, one chooses among these tools:

<table>
  <thead>
    <tr>
      <th></th>
      <th>Description</th>
      <th>PHP</th>
      <th>Javascript</th>
      <th>Other</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>
        Unit Test
      </th>
      <td>
        Basic testing of a single function or class<br/>
        <b>Trade-off</b>: <em>Fast but minimal/synthetic environment</em><br/>
        <b>Good for testing</b>: <em>Helpers, libraries</em>
      </td>
      <td>
        <a href="phpunit">PHPUnit</a>
      </td>
      <td>
        <a href="karma">Karma</a>
      </td>
      <td>
      </td>
    </tr>
    <tr>
      <th>
        Headless Test
        </th>
      <td>
        Mid-level testing with headless database<br/>
        <b>Trade-off</b>: <em>Mid-level speed and realism</em><br/>
        <b>Good for testing</b>: <em>Data management APIs, portable backend services</em><br/>
      </td>
      <td>
        <a href="phpunit">PHPUnit</a>
      </td>
      <td>
      </td>
      <td>
      </td>
    </tr>
    <tr>
      <th>
        End-to-End Test (E2E)
      </th>
      <td>
        Full-stack testing with CMS, HTTPd, etc<br/>
        <b>Trade-off</b>: <em>Slow but realistic environment</em><br/>
        <b>Good for testing</b>: <em>Screens, pageflows, integrations</em><br/>
      </td>
      <td>
        <a href="phpunit">PHPUnit</a><br/>
        <!-- <a href="codeception">Codeception</a><br/> -->
        <s><a href="selenium">Selenium</a></s>
      </td>
      <td>
        <!-- <a href="protractor">Protractor</a><br/> -->
        <s><a href="qunit">QUnit</a></s>
      </td>
      <td>
        <a href="upgrades">Upgrades</a><br/>
        <a href="manual">Manual</a><br/>
      </td>
    </tr>
  </tbody>
</table>

!!! caution "Be mindful of test types"

    Each style of testing (unit, headless, E2E) has distinctive practices.  For example, headless tests periodically reset the entire database, but
    E2E tests don't. These concepts are discussed in more detail below.

## Setup

Many test suites require information about your local development environment.  For example, headless tests may require credentials for an extra
MySQL database, and end-to-end tests may require credentials for a CMS.

The test tools obtain this information via [cv](https://github.com/civicrm/cv).  If your build was created by [buildkit](../tools/buildkit.md) and
[civibuild](../tools/civibuild.md), then `cv` can fetch *all* the information automatically. Other builds may require some manual configuration.

To inspect the configuration, run:

```bash
$ cd /path/to/civicrm
$ cv vars:show
```

This should display a number of properties, including:

* Credentials for an empty test database (`TEST_DB_DSN`)
  * (The database user requires SUPER privilege in order to set innodb_flush_log_at_trx_commit.)
* Credentials for an administrative CMS user (`ADMIN_USER`, `ADMIN_PASS`, `ADMIN_EMAIL`)
* Credentials for a non-administrative CMS user (`DEMO_USER`, `DEMO_PASS`, `DEMO_EMAIL`)

If these are missing or blank, then you need to fill them in. Initialize and edit the configuration file:

```bash
$ cv vars:fill
$ vi ~/.cv.json
```

!!! tip "Tip: Database snapshots"

    Many tests interact with the database. In case they mess up the database, you should retain a snapshot of your baseline DB.

    If you used `civibuild`, it automatically retained a DB snapshot when you last (re)installed the site.
    See [civibuild restore](../tools/civibuild.md#rebuild) for more information.

!!! tip "Tip: Multi-user systems"

    By default, `cv` uses the configuration file `~/.cv.json`. However, if this build is accessed by many user accounts, then
    you can use a shared configuration file. Run `export CV_CONFIG=/path/to/shared/file.json` and then call `cv vars:fill`.

## Architecture

Most automated tests require access to some mix of **resources** -- such as source-code files, databases, or URLs.  To access these resources, test
frameworks should build on the [cv](https://github.com/civicrm/cv) command.

There are three types of tests. Each handles these resources differently.

### Minimal unit test {:#unit}

A minimal **unit test** focuses on testing a discrete technical artifact, such as a function, class, or file.  Minimal unit tests are **loosely**
**coupled**, and they generally shouldn't require an external service (such as a database or web-server).  The narrow scope and loose-coupling makes
these **fast** -- so you can execute a large suite of tests in a short time.  However, it also makes them **less representative**.  This type of
testing is ideal for helpers, utilities, and libraries which have innately low coupling.  They're also useful if you need to test a large range of
possible inputs/permutations.

!!! tip "Unit tests and safety"

    In a minimal unit-test, running the test should have no side-effects -- ie it should not change data-files or database tables.

!!! tip "Unit tests and bootstrap"

    In a framework like `phpunit` or `karma`, a minimal unit test might call `cv php:boot --level=classloader`
    or `cv path -d [civicrm.root]` to gain access to Civi's source code.

### Headless test {:#headless}

The most common kind of automated test in `civicrm-core` is a **headless test**.  Headless tests use a *nearly complete* CiviCRM environment;
however, there is no CMS or web-server, and all data is stored on a private, headless database.  This is ideally suited to testing data-management
APIs (where the DBMS is an important part of the system) and other portable services (where you wouldn't expect the CMS to influence behavior).

!!! tip "Headless tests and safety"

    In a headless unit-test, the test takes ownership over a private, test-only, *headless* copy of CiviCRM.  Headless tests commonly take steps to
    keep the database at a clean baseline, such as (a) rolling back a SQL transaction or (b) truncating a few data tables or (c) resetting the entire
    database.

!!! tip "Headless tests and bootstrap"

    In a framework like `phpunit` or `codeception`, a headless test might call `cv php:boot --level=full`. To avoid bootstrapping a full CMS, it
    would need an environment variable `CIVICRM_UF=UnitTests`.

### End-to-end test {:#e2e}

An **end-to-end test** (E2E) focuses on testing a full stack, including some combination of CRM, CMS, web-server, file-system, database, and web-browser.
These tests are the most **representative** of real-world usage, because they tie together so many components.  However, this **high coupling** also makes
them brittle (because a design-change or fault in any part of the system can disrupt the expected flow).  This type of testing is ideal for screens,
pageflows, and integrations which have innately high coupling.

!!! tip "End-to-end tests and safety"

    In an E2E test, the test runs against an active, local CiviCRM installation.  By default, each test makes **persistent** changes, which can
    create side-effects for other tests. Be conscientious about (a) checking pre-conditions (in case other tests have side-effects) and
    (b) cleaning up to prevent side-effects (that might break other tests).

!!! tip "End-to-end tests and bootstrap"

    In a framework like `phpunit` or `codeception`, an E2E test might call `cv php:boot --level=full`. However, it would not need to explicitly
    set `CIVICRM_UF`. Depending on the active CMS, this value is determined automatically.
