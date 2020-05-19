# APIv3 Examples

All the APIv3 Examples are generated through Tests in the CodeBase and are auto-generated from those tests so we can be certain of the code that is given. 

## Location of Examples

The most current examples can be found in CiviCRM's GitHub Repo on the [Master Branch](https://github.com/civicrm/civicrm-core/tree/master/api/v3/examples). When you install CiviCRM the Examples that come with the particular version of CiviCRM you have installed can be found in `<civicrm_root>/api/v3/examples`. You will also be able to view them through the [API Explorer](../index.md#api-explorer) by clicking on the **Examples** tab in the Explorer.

## Creating a New Example

If you find that there is an API call or similar or perhaps a parameter for an API call that you feel is missing an example for that would be useful to the community, you can create a new example in the following way:

1. Find the relevant API test file e.g. `tests/phpunit/api/v3/MembershipTest.php`
2. Write your unit test with the API call that you want to create an Example of, however rather than using `$this->callAPISuccess` use `$this->callAPIAndDocument`. The Call API and Document function should be called similar to the following

    ```php
    $description = "This demonstrates setting a custom field through the API.";
    $result = $this->callAPIAndDocument($this->_entity, 'create', $params, __FUNCTION__, __FILE__, $description);
    ```

3. Find in `tests/phpunit/CiviTest/CiviUnitTestCase.php` Find the function `documentMe` and comment out the if (defined) statement.
4. Run the test suite locally for that test e.g. `./tools/scripts/phpunit 'api_v3_MembershipTest'`.
5. Commit results including changes in the Examples dir and open a pull request.
