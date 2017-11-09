(*CiviCRM Review Template DEL-1.0*)

<!-- In each category, choose the option that most applies. Delete the others. Optionally, provide more details or explanation in the "Comments". -->

* JIRA ([`r-jira`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-jira))
    * __UNREVIEWED__
    * __PASS__ : The PR has a JIRA reference. (Or: it does not need one.)
    * __ISSUE__: Please file a ticket in [JIRA](http://issues.civicrm.org/) and place it in the subject
    * __COMMENTS__: <!-- optional -->
* Test results ([`r-test`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-test))
    * __UNREVIEWED__
    * __PASS__: The test results are all-clear.
    * __PASS__: The test results have failures, but these have been individually inspected and found to be irrelevant.
    * __ISSUE__: The test failures need to be resolved.
    * __COMMENTS__: <!-- optional -->
* Code quality ([`r-code`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-code))
    * __UNREVIEWED__
    * __PASS__: The functionality, purpose, and style of the code seems clear+sensible.
    * __ISSUE__: Something was unclear to me.
    * __ISSUE__: The approach should be different.
    * __COMMENTS__: <!-- optional -->
* Documentation ([`r-doc`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-doc))
    * __UNREVIEWED__
    * __PASS__: There are relevant updates for the documentation.
    * __PASS__: The changes do not require documentation.
    * __ISSUE__: The user documentation should be updated.
    * __ISSUE__: The administrator documentation should be updated.
    * __ISSUE__: The developer documentation should be updated.
    * __COMMENTS__: <!-- optional -->
* Maintainability ([`r-maint`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-maint))
    * __UNREVIEWED__
    * __PASS__: The change sufficiently improves test coverage, or the change is trivial enough that it does not require tests.
    * __PASS__: The change does not sufficiently improve test coverage, but special circumstances make it important to accept the change anyway.
    * __ISSUE__: The change does not sufficiently improve test coverage.
    * __COMMENTS__: <!-- optional -->
* Run it ([`r-run`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-run))
    * __UNREVIEWED__
    * __PASS__: <!-- describe how you ran it -->
    * __ISSUE__: <!-- describe how you ran it -->
* User impact ([`r-user`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-user))
    * __UNREVIEWED__
    * __PASS__: The change would be intuitive or unnoticeable for a majority of users who work with this feature.
    * __ISSUE__: The change would noticeably impact the user-experience (eg requiring retraining).
    * __PASS__: The change would noticeably impact the user-experience (eg requiring retraining), but this has been addressed with a suitable transition/communication plan.
    * __COMMENTS__: <!-- optional -->
* Technical impact ([`r-tech`](https://docs.civicrm.org/dev/en/latest/standards/review/#r-tech))
    * __UNREVIEWED__
    * __PASS__: The change preserves compatibility with existing callers/code/downstream.
    * __PASS__: The change potentially affects compatibility, but the risks have been sufficiently managed.
    * __ISSUE__: The change potentially affects compatibility, and the risks have **not** been sufficiently managed.
    * __COMMENTS__: <!-- optional -->
