# Coding standards

In general, CiviCRM follow's the [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards), but we have some minor modifications which are noted specifically in the other pages in this chapter.

## Continuous integration

Jenkins will automatically check all pull requests for coding standards conformance, and code which does not meet the standards will not be merged. 


## Tools

If you have a development site with [buildkit]() you can use [Civilint](/tools/civilint.md) to check your code against CiviCRM's coding standards.

You can also [set up your IDE](https://wiki.civicrm.org/confluence/display/CRMDOC/IDE+Settings+to+Meet+Coding+Standards) to lint your code.


## Improving code conformance

If you find code that is not meeting the standards, we encourage you to improve it! But please follow these guidelines when doing so: 

* Create a Jira issue for your coding standards improvements which is separate from any other code changes you happen to be making at the time. 
* Isolate your coding standards improvements into commits which do not contain otherwise unrelated changes.
