Every API call consists of three elements:

-   **Entity name**: a string such as "Contact" or "Activity"
-   **Action name**: a string such as "create" or "delete"
-   **Parameters**: an associative-array (such as the first-name and
    last-name of a new contact record); this varies depending on the
    entity name

Entities
========

There are many entities supported by the CiviCRM API, and the list
expands in every release. For current details in your version, see the
"Documentation" section; in particular, see the "API Explorer" and the
API examples.

For demonstration, consider a few commonly-used entities:


| Entity                   | Description              | Example Parameters       |
|--------------------------|--------------------------|--------------------------|
| Contact                  | An individual, <br /> organization, or <br />house-hold.         | “contact\_type”,<br /> “first\_name”,  <br />“last\_name”, <br />“preferred\_language”       |
| Activity                 | An phone call, meeting,<br /> or email message. that <br /> has occurred (or will <br /> occur) at a specific <br /> date and time| “activity\_type\_id”, <br /> “source\_contact\_id”, <br /> “assignee\_contact\_id”    |
| Address                  | A street-address related <br /> to a contact. | “contact\_id”,  <br /> “street\_address”, <br /> “city”,  <br /> “state\_province\_id”, <br /> "country\_id’     |




Response Format
===============

The response from an API call is always an associative-array. The
response format can vary depending on the action, but generally
responses meet one of these two structures:

<h3>Success</h3>

````
$result['is_error'] = 0
$result['version'] = 2 or 3 as appropriate
$result['count'] = number of records in the 'values' element of the $result array
$result['values'] = an array of records
````

Please note that the **getsingle** response will not have a $result['values'] holding the records, but a $result array with the fields from the selected record. The response $result will only have an  'is\_error' attribute if there actually is an error.


<h3>Error</h3>

````
$result['is_error'] = 1
$result['error_message'] = An error message as a string.
````
