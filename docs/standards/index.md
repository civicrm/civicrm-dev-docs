# Coding standards

The coding standards described here have been written
[years after the start of the project](http://civicrm.org/blogs/eileen/you-owe-me-3-tests-function-kitchen-sink).
They describe the most common patterns and best practices for writing
CiviCRM code, and they seek to answer the question, "what is the proper
way of doing X?" They cover not only whitespace and appropriate styles
for writing the code itself but also how to document code at file,
function, line and other levels.

Developers are encouraged to take these standards as an expression of
"what we want" more than of "what we have everywhere."  This means that
if you find yourself working on a piece of code that doesn't follow
these standards, you are encouraged to re-factor it so that it is. IRC
is a good place to come if you have questions or need clarification.

Quite a few blog posts have been written over the years in the
[architecture series](http://civicrm.org/category/civicrm-blog-categories/architecture-series) the
oldest one describing the DAO BAO/ Templating and file structures should
still be on your must read list. Eileen's [post from July 2013](https://civicrm.org/blog/eileen/doing-the-dishes-aka-code-cleanup) is also a
keeper. Please refer to it as a good overview of how to do things in a
standards-compliant way 4.3+.


## CiviCRM vs Drupal

In general, CiviCRM follows the [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards), but we have some minor modifications which are noted specifically in the other pages in this chapter.

## Continuous integration

[Jenkins](../tools/jenkins.md) will automatically check all pull requests for coding standards conformance, and code which does not meet the standards will not be merged. 


## Tools

If you have a development site with [buildkit](../tools/buildkit.md) you can use [Civilint](../tools/civilint.md) to check your code against CiviCRM's coding standards.

You can also [set up your IDE](../tools/phpstorm.md) to lint your code.


## Improving code conformance

If you find code that is not meeting the standards, we encourage you to improve it! But please follow these guidelines when doing so: 
 
* Isolate your coding standards improvements into commits which do not contain otherwise unrelated changes.

### Orange Light code

Orange light code is code that has the feel that it is wrong and should be refactored. Developers who are looking to contribute to this effort may want to consider doing any amount self-contained work on the following code. 

* Using joins on `civicrm_option_value` rather than using PseudoConstants - this has performance implications
* Removing as much as possible passing by reference to functions.
* Increasing code complexity, this has two issues firstly it increase the fragility of the code and also makes it harder to test
* Wherever `$session = CRM_Core_Session(); $userid = $session->get('UserID');` or very similar these calls should be replaced with `CRM_Core_Session::singleton()->getLoggedInContactID();`
* Replace `CRM_Core_Resources->singleton()->add...` and similar with `Civi::resources()->add...`
* Clean up messy code, see if code can be refactored and/or moved into parent classes. Make parent classes more generic and eliminate any duplicate code.
* Increase the usage of Doc blocks to help with auto generation of code
* Move more business logic out of the Forms/API layers to the BAO level wherever possible.
* Remove eval() instances
* Develop & confirm standards listed above (incl decide on smarty coding templates, separation of tpl and js)
* Cleanup and centralize the token code.
* Code duplication should be addressed - e.g the introduction of a select function in CRM/Report/Form.php around 3.3 made identical functions in child classes obsolete. This is a good task for someone wanting to learn
* Look at repurposing the coder module to keep CiviCRM tidy
* Git rid of unused functions
* Look through the "todo" "fixme" and "hack" comments and see about fixing them
* Modify code to use the Drupal coding standard of using the PHP keyword const in place of define();

In CiviCRM we aim to compile SQL in the following order of preference

1. API - Predictable, well tested
3. DAO / BAO - `CRM_Core_DAO()->fetch()` `CRM_Core_DAO->copyValues()`
2. `CRM_Utils_Select`
4. Compiled SQL

In CiviCRM we aim to use the APIs as much as possible as they have a solid test framework and a test contract behind them. This means if something is tested through the phpunit tests then it is on CiviCRM's responsiblity to ensure that does not break. 
