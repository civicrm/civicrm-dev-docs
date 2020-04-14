!!! tip "Setup"

    The test suites require a small amount of [setup](index.md#setup).  If your system was created via [buildkit](../tools/buildkit.md) and
    [civibuild](../tools/civibuild.md), then it was handled automatically.

[Karma] is a Javascript testing tool which executes [Jasmine] tests on the command-line.
It was introduced  in Civi v4.6 in tandem with several AngularJS-based UIs.

[Buildkit](../tools/buildkit.md) includes a copy of `karma`. Alternatively,
you can download it by running `npm install` in the `civicrm` directory.

## Running Karma

If you're actively working on Javascript files or Karma tests, then you can
start `karma` in a *watch* mode.  Any time you save a change to disk, it
will automatically re-execute the tests.

```bash
$ cd /path/to/civicrm
$ karma start
```

## Running Karma (Other ways)

You can also run the karma tests as they would be run by [Jenkins](continuous-integration.md) using [civi-test-run](../tools/civi-test-run.md).

[Karma]: https://karma-runner.github.io/1.0/index.html
[Jasmine]: https://jasmine.github.io/2.1/introduction.html
