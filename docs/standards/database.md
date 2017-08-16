# Database standards

We have agreed on the following standard that we will work towards

1. Every BAO will have a create function. This will be called by the API & form layer
1. the create function will take a single params array
1. Depending on the params passed in the create function will perform any additional actions like creating activities
1. The create function will call hooks
1. We are moving away from the $ids array being included
1. The add function if it exists will be internal to the BAO layer
1. If any additional actions are to be done when deleting the BAO there should be function del which takes the entity id as the only required parameter
1. The delete action will take any additional tasks like deleting additional object (generally done by code)
1. The delete action will take an array including `['id']`
1. The api will call the del action & fall back onto delete. It is recommended the form layer call the api
