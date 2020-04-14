Upgrade tests provide a basic sanity check on the DB upgrade logic –
they ensure that the upgrade process does not cause a crash when upgrading
from an older version.

They are suitable for checking issues in the DB upgrade logic –
but do not check for issues in the administrative experience or in the
CMS-integration.

Upgrade tests are run daily on the Jenkins [continuous integration](continuous-integration.md) server.

Locally you can use [civi-test-run](../tools/civi-test-run.md) to run the same upgrade tests as Jenkins would.
