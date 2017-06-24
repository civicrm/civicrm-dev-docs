Upgrade tests provide a basic sanity check on the DB upgrade logic – 
they ensure that the upgrade process does not cause a crash when upgrading 
from an older version. 

They are suitable for checking issues in the DB upgrade logic – 
but do not check for issues in the administrative experience or in the 
CMS-integration.

Upgrade tests are run daily on the Jenkins [continuous integration][ci] server.

If you are working on a core upgrade you can save some time and check out this
handy [test-helper] to run upgraders from specific versions.

[ci]: /testing/continuous-integration
[test-helper]: https://github.com/civicrm/civicrm-buildkit/blob/master/doc/daily-coding.md#upgrade-tests
