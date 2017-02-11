# hook_civicrm_tokenValues

## Description

This hook is called to get all the values for the tokens registered. Use
it to overwrite or reformat existing token values, or supply the values
for custom tokens you have defined in
[hook_civicrm_tokens](/hooks/hook_civicrm_tokens).\
 See [this
article](https://civicrm.org/blog/colemanw/create-your-own-tokens-for-fun-and-profit) for
usage examples.

## Definition

    hook_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = array(), $context = null)

## Parameters

-   $values - array of values, keyed by contact id
-   $cids - array of contactIDs that the system needs values for.
-   $job - the job_id
-   $tokens - tokens used in the mailing - use this to check whether a
    token is being used and avoid fetching data for unneeded tokens
-   $context - the class name

## Returns

-   null