The easiest way to get setup it to use buildkit. Buildkit will install the tools
necessary for running the tests and creating local CiviCRM sites.

You can download [CiviCRM buildkit](/tools/buildkit) which includes all the test tools.
From there you can create a test site using [civibuild](/tools/civibuild).

!!! info
    When writing new tests or making any changes make sure you run [civilint](/tools/civilint)
    to ensure your changes match our coding style.

!!! tip
    If you are using PhpStorm, you can [run the tests from within PhpStorm](/tools/phpstorm/#testing) (which is especially helpful because you can set breakpoints and inspect variables while the tests run).