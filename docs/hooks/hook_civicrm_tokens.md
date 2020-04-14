# hook_civicrm_tokens

## Summary

This hook is called to allow custom tokens to be defined.

## Notes

The token values
will need to be supplied by
[hook_civicrm_tokenValues](hook_civicrm_tokenValues.md).

See [this article](https://civicrm.org/blog/colemanw/create-your-own-tokens-for-fun-and-profit)
for usage examples.

## Definition

```php
hook_civicrm_tokens(&$tokens)
```

## Parameters

- $tokens: reference to the associative array of custom tokens that
  are available to be used in mailings and other contexts. This will
  be an empty array unless an implementation of hook_civicrm_tokens
  adds items to it. Items should be added in this format:

```php
$tokens['date'] = [
  'date.date_short' => ts("Today's Date: short format"),
  'date.date_med' => ts("Today's Date: med format"),
  'date.date_long' => ts("Today's Date: long format"),
];
$tokens['party'] = [
  'party.balloons' => ts("Number of balloons"),
];
```

## Returns

- null

## Example

```php
function customtokens_civicrm_tokens(&$tokens) {
  $tokens['team'] = [
    'team.team_number' => 'Team number',
  ];
}
```
