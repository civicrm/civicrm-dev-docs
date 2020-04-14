!!! tip "Setup"

    The test suites require a small amount of [setup](index.md#setup).  If your system was created via [buildkit](../tools/buildkit.md) and
    [civibuild](../tools/civibuild.md), then it was handled automatically.

Web tests ensure the overall system is working as expected – that is, ensuring
that the right things happen when you click on the right buttons.

Examples of web tests include that the event confirmation screen is displayed
when I hit the register for an event button, or that all 23 contacts are
displayed when I search for contacts that live in France.

You can record tests using the Selenium IDE which you can download from the
[Selenium website](http://seleniumhq.org/). Web tests should be recorded using an
instance of CiviCRM that has standard sample data.

To ensure consistency, all tests should be carried out using the standard
CiviCRM sample data.

## Setup

[buildkit](../tools/buildkit.md) should be installed. You will also need Java and Firefox.

## Running the Web Tests

1. Open two terminals

2. From Terminal 1: Launch the Selenium service
    ```bash
    $ cd /path/to/civicrm
    $ cd packages/SeleniumRC/
    $ bash selenium.sh
    Runnning selenium-server-2.35.0
    Mar 06, 2015 8:58:22 PM org.openqa.grid.selenium.GridLauncher main
    INFO: Launching a standalone server
    ```

3. From Terminal 2: Run the tests
    ```bash
    $ cd /path/to/civicrm
    $ cd tools
    $ ./scripts/phpunit WebTest_AllTests
    ```

## See Also

- [Selenium documentation](http://seleniumhq.org/docs/)
- [Selenium reference](http://release.seleniumhq.org/selenium-core/1.0.1/reference.html)
