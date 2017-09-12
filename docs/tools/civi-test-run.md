# civi-test-run

`civi-test-run` is a script which runs one or more test suites locally.

## Installation

`civi-test-run` is included within [buildkit](/tools/buildkit.md).

## Usage

Run without arguments to see the exact usage:

```bash
$ civi-test-run
```

## Test types

The test type is one of:

-  `all` - Run all standard CiviCRM Test Suites
-  `phpunit-e2e` - Run the E2E test suite
-  `phpunit-civi` - Run the `Civi/` Test Suite
-  `phpunit-api` - Run the `api_v3` Test Suite
-  `phpunit-crm` - Run the `CRM` Test Suite

