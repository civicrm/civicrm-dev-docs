The easiest way to get setup it to use buildkit. Buildkit will install the tools
necessary for running the tests and creating local CiviCRM sites.

You can download [CiviCRM buildkit][buildkit] which includes all the test tools. 
From there you can create a test site using [these instructions][buildkit-create].

!!! info 
    When writing new tests or making any changes make sure you run [civilint] 
    to ensure your changes match our coding style.

[buildkit]: tools/buildkit.md
[buildkit-create]: https://buildkit.civicrm.org/#/tutorials
