## Jenkins

To prevent defects from entering the system or remaining in the system, 
the tests are executed automatically Jenkins. 

The test system runs twice. Firstly, when a change is proposed through Github 
(as a "pull-request" or "PR"), Jenkins tests it immediately. (This takes 5-90min.) 

Unfortunately, the full test suite would take several hours longer, so Jenkins 
only runs some tests at this stage. 

The results of the tests are published at [test.civicrm.org][jenkins-test-results]

If the tests have failed for something that we suspect is a random failure, we
can ask Jenkins to run the tests again by commenting in the PR "Jenkins, test
this please" 

Jenkins only builds a Drupal site that is built against the branch that your
PR is modifying.  If you need to test a patch against another CMS than you
will want to test the patch in your own environment.

## Jenkins Whitelist 

For new (unrecognised by Jenkins) contributors, Jenkins will automatically
respond "can an admin verify this patch?", and a Github user with admin
permissions may approve running the tests by commenting on the PR "ok to test".

If the user is trusted, CiviCRM administrators can add the person to the
whitelist by commenting "add to whitelist".

## Build Schedule

Jenkins periodically runs the full suite (once every 4-24hr) on the official 
codebase.
 
Code-style is not checked on this build, but all upgrade and web tests are run.

[jenkins-test-results]: https://test.civicrm.org/
[testing-readme]: https://github.com/civicrm/civicrm-core/blob/master/tests/README.md
