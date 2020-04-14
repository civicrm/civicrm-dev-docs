# civi-test-run

`civi-test-run` is a script which runs one or more test suites locally. It is compatible with `civibuild`-based deployments.

## Installation

`civi-test-run` is included within [buildkit](buildkit.md).

## Usage

Run without arguments to see the exact usage:

```bash
$ civi-test-run
```

## Test types

The test type is one of:

-  `all` - Run all standard CiviCRM test suites
-  `karma` - Run the KarmaJS test suite
-  `phpunit-api` - Run the `api_v3` test suite
-  `phpunit-civi` - Run the `Civi/` test suite
-  `phpunit-crm` - Run the `CRM` test suite
-  `phpunit-e2e` - Run the `E2E` test suite
-  `upgrade` - Run the upgrade test suite
