# hook_civicrm_tokens

## Summary

This hook is called to allow custom tokens to be defined. Their values
will need to be supplied by
[hook_civicrm_tokenValues](/hooks/hook_civicrm_tokenValues.md).\
  See [this
article](https://civicrm.org/blog/colemanw/create-your-own-tokens-for-fun-and-profit)
for usage examples.

## Definition

    hook_civicrm_tokens( &$tokens )

## Parameters

-   $tokens: reference to the associative array of custom tokens that
    are available to be used in mailings and other contexts. This will
    be an empty array unless an implementation of hook_civicrm_tokens
    adds items to it. Items should be added in the format:\
     \

        $tokens['date'] = array(
            'date.date_short' => ts("Today's Date: short format"),
            'date.date_med' => ts("Today's Date: med format"),
            'date.date_long' => ts("Today's Date: long format"),
        );
        $tokens['party'] = array(
            'party.balloons' => ts("Number of balloons"),
        );

## Returns

-   null
