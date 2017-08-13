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



# Coding Standards



The coding standards described here have been written [years after the
start of the
project](http://civicrm.org/blogs/eileen/you-owe-me-3-tests-function-kitchen-sink){.external-link}.
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

*Note: Please do not squeeze coding standards updates/clean-ups into
otherwise unrelated patches. Only touch code lines that are actually
relevant to the changes you are making, and always create separate and
dedicated issues and patches t*o update existing code for the current
standards.**

Quite a few blog posts have been written over the years in the
[architecture
series](http://civicrm.org/category/civicrm-blog-categories/architecture-series){.external-link} the
oldest one describing the DAO BAO/ Templating and file structures should
still be on your must read list. Eileen's from July 2013 is also a
keeper: <https://civicrm.org/blog/eileen/doing-the-dishes-aka-code-cleanup>.
Please refer to it as a good overview of how to do things in a
standards-compliant way 4.3+.

## Standards relating to specific areas

### Coding and Inline Documentation Standards

-   [PHP Code and Inline
    Documentation](/confluence/display/CRMDOC/PHP+Code+and+Inline+Documentation)
    -   See [IDE Settings to Meet Coding
        Standards](/confluence/display/CRMDOC/IDE+Settings+to+Meet+Coding+Standards)
-   Smarty Templates
-   [Javascript Coding
    Guidelines](/confluence/display/CRMDOC/Javascript+Reference)

### Architecture

-   [API
    Architecture](/confluence/display/CRMDOC/API+Architecture+Standards)
-   [Coding Standards](/confluence/display/CRMDOC/Coding+Standards)
-   [Settings Reference](/confluence/display/CRMDOC/Settings+Reference)
-   [\[Archived\] Settings Metadata and
    Usage](/confluence/display/CRMDOC/%5BArchived%5D+Settings+Metadata+and+Usage)
    <span style="color: rgb(255,102,0);">(should this
    be deleted?)</span>
-   Pseudoconstants (consistent input & output)
-   [Business Application Object (BAO) and Database Application
    Object (DAO) layers](/confluence/display/CRMDOC/Database+layer)
-   General principles:\
    -   Avoid the use of eval().
    -   Business logic should be in the BAO not forms.

## Specific clean up tasks

Developers who are looking to contribute to this effort may want to
consider doing any amount self-contained work on the following areas:

-   Develop & confirm standards listed above (incl decide on smarty
    coding templates, seperation of tpl and js)
-   Move business logic from the forms to the BAO
-   Remove eval() instances
-   Cleanup and centralize the token code.
-   Code duplication should be addressed - e.g the introduction of a
    select function in CRM/Report/Form.php around 3.3 made identical
    functions in child classes obsolete. This is a good taks for someone
    wanting to learn
-   Start auto-generating documentation for internal classes and
    prioritize the classes in most needs of doc/inline help
-   Start experimenting and migrating part of the core code towards
    Symfony2
-   Eliminate duplicate code, move into functions
-   Eliminate duplicate functions, move to parent classes
-   Clean up messy code
-   Look at repurposing the coder module to keep CiviCRM tidy
-   Git rid of unused functions
-   Look through the "todo" "fixme" and "hack" comments and see about
    fixing them
-   As CiviCRM now requires PHP 5.3, modify code to use the Drupal 8
    coding standard of using the PHP keyword const in place of define();
