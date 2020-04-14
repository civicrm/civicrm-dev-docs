# hook_civicrm_contactListQuery

## Summary

Deprecated in favor of [hook_civicrm_apiWrappers](hook_civicrm_apiWrappers.md).

## Notes

!!! warning "Deprecation Notice"
    This hook is called in very few places in version 4.5+ because most contact reference fields have been migrated to go through the api instead of constructing an ad-hoc query. It will be removed in a future version.

Use this hook to populate the list of contacts returned by Contact
Reference custom fields. By default, Contact Reference fields will
search on and return all CiviCRM contacts. If you want to limit the
contacts returned to a specific group, or some other criteria - you can
override that behavior by providing a SQL query that returns some subset
of your contacts. The hook is called when the query is executed to get
the list of contacts to display.

## Definition

    hook_civicrm_contactListQuery( &$query, $queryText, $context, $id )

## Parameters

-   $query - the query that will be executed (input and output
    parameter)**; It's important to realize that the ACL clause is built
    prior to this hook being fired, so your query will ignore any ACL
    rules that may be defined.**Your query must return two columns:\
    -   the contact 'data' to display in the autocomplete dropdown
        (usually contact.sort_name - aliased as 'data')
    -   the contact IDs
-   $queryText - the name string to execute the query against (this is the
    value being typed in by the user)
-   $context - the context in which this ajax call is being made (for
    example: 'customfield', 'caseview')

-   $id - the id of the object for which the call is being made. For
    custom fields, it will be the custom field id

## Notes

!!! tip
    To find the context for a given contactListQuery widget, use firebug console and type in a few letters. You'll see a GET command that looks like this (and includes the context= parameter):[http://drupal.demo.civicrm.org/civicrm/ajax/contactlist?context=caseview&s=ada&limit=10&timestamp=1273015964778](http://drupal.demo.civicrm.org/civicrm/ajax/contactlist?context=caseview&s=ada&limit=10&timestamp=1273015964778)


## Examples

This example limits contacts in my contact reference field lookup
(custom field id=4) to a specific group (group id=5)

        // Connect the hook to your Contact Reference custom field using the field ID (field id=4 in this case)
        if ( $context == 'customfield' &&
             $id == 4 ) {
            // Now construct the query to select only the contacts we want
            // The query must return two columns - contact data, and contact id
            $query = "
    SELECT c.sort_name as data, c.id
    FROM civicrm_contact c, civicrm_group_contact cg
    WHERE c.sort_name LIKE '$queryText%'
    AND   cg.group_id IN ( 5 )
    AND   cg.contact_id = c.id
    AND   cg.status = 'Added'
    ORDER BY c.sort_name ";
        }

    }

[More examples and sample module code in this forums
thread.](http://forum.civicrm.org/index.php/topic,24550.0.html)