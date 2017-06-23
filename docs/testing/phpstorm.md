## One-click Testing

These instructions assume you already have a working instance of CiviCRM
running locally. Check [setup] for help creating one.

### Locally

From the `Run > Edit Configurations` menu add a new PHPUnit configuration. 

Check "Use alternative configuration file" and choose `phpunit.xml.dist` in your 
CiviCRM root directory.

Expand "Environment variables" and add the following two:

- `TEST_DB_DSN`: Use the DSN to connect to your test database. It is displayed
on completion of civibuild. You should also be able to find it by running 
`cv vars:show`.
- `CIVICRM_UF`: Use "UnitTests" unless you're running WebTests or End-to-end 
tests.

## Adding external libraries

It can be frustrating when writing tests the PHPStorm complains about missing 
classes or undefined methods. This happens because PHPUnit is not included in
the CiviCRM codebase.

To remedy this you can add an external content root. You'll need to clone the 
[phpunit] library locally. Then from that directory check out the latest supported
version of phpunit (4.x right now).

After that you just add the directory to your project include paths by 
[following the instructions on the Jetbrains site][phpstorm-include-paths].

[phpstorm-include-paths]: https://www.jetbrains.com/help/phpstorm/configuring-include-paths.html
[phpunit]: https://github.com/sebastianbergmann/phpunit
[setup]: setup.md
