!!! tip "Setup"

    The test suites require a small amount of [setup](/testing/index.md#setup).  If your system was created via [buildkit](/tools/buildkit.md) and
    [civibuild](/tools/civibuild.md), then it was handled automatically.

Javascript tests ensure that CiviCRM's JS logic is working as expected – 
for example, ensuring that a custom JS widget adapts correctly to different inputs.

Buildkit includes the tools required for running the tests. Alternatively, 
download Karma and Jasmine by running "npm install" in the civicrm directory.

These test were introduced in Civi v4.6 and are written in the AngularJS 
conventions using [karma] and [jasmine].

## Running Javascript Tests

```bash
$ cd /path/to/civicrm
$ npm test
```

You can also run the karma tests as they would be run by [Jenkins](/testing/continuous-integration.md) using [civi-test-run](/tools/civi-test-run.md).

[karma]: https://karma-runner.github.io/1.0/index.html
[jasmine]: https://jasmine.github.io/2.1/introduction.html
