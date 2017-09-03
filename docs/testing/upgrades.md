Upgrade tests provide a basic sanity check on the DB upgrade logic –
they ensure that the upgrade process does not cause a crash when upgrading
from an older version.

They are suitable for checking issues in the DB upgrade logic –
but do not check for issues in the administrative experience or in the
CMS-integration.

Upgrade tests are run daily on the Jenkins [continuous integration](/testing/continuous-integration.md) server.

## Local Upgrade Testing

Locally you can run the same upgrade tests as Jenkins would using `civi-test-run` as per the following example

```bash
$ civi-test-run "<civibuildname>" "<civiversion>" "<junitdir>" "upgrade"
```
