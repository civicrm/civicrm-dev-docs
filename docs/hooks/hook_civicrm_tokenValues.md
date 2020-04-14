# hook_civicrm_tokenValues

## Summary

This hook is called to get all the values for the tokens registered.

## Notes

Use it to overwrite or reformat existing token values, or supply the values
for custom tokens you have defined in
[hook_civicrm_tokens](hook_civicrm_tokens.md). See [this
article](https://civicrm.org/blog/colemanw/create-your-own-tokens-for-fun-and-profit) for
usage examples.

## Definition

```php
hook_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = [], $context = null)
```

## Parameters

- $values - array of values, keyed by contact id
- $cids - array of contactIDs that the system needs values for.
- $job - the job_id
- $tokens - tokens used in the mailing - use this to check whether a
  token is being used and avoid fetching data for unneeded tokens
- $context - the class name

## Returns

- null

## Example

```php
function customtokens_civicrm_tokenValues(&$values, $cids, $job = null, $tokens = [], $context = null) {
  $group = 'team';
  if(isset($tokens[$group])) {
    $token = 'team_number';
    if (!customtokens_isTokenRequested($tokens, $group, $token)) {
      return;
    }

    foreach ($cids as $cid) {
      // get team (employer) id
          $individualResult = civicrm_api3('Contact', 'getsingle', [
        'return' => ["current_employer_id"],
        'contact_id' => $cid,
      ]);

      // if there is a team (employer) id, get team number for the team (employer)
      if(!$individualResult['is_error'] && isset($individualResult['current_employer_id']) && strlen($individualResult['current_employer_id'])){

        $teamResult = civicrm_api3('Contact', 'getsingle', [
          'return' => ["custom_70"],
          'id' => $individualResult['current_employer_id'],
        ]);

        // if there is a team number, display it as the token
        if(!$teamResult['is_error'] && isset($teamResult['custom_70'])) {
          $values[$cid]['team.team_number'] = $teamResult['custom_70'];
        }
      }
    }
  }
}

/**
 * "Send an Email" and "CiviMail" send different parameters to the tokenValues hook (in CiviCRM 5.x).
 * This works around that.
 *
 * @param array $tokens
 * @param string $group
 * @param string $token
 *
 * @return bool
 */
function customtokens_isTokenRequested($tokens, $group, $token) {
  // CiviMail sets $tokens to the format:
  //   [ 'group' => [ 'token_name' => 1 ] ]
  // "Send an email" sets $tokens to the format:
  //  [ 'group' => [ 0 => 'token_name' ] ]
  if (array_key_exists($token, $tokens[$group]) || in_array($token, $tokens[$group])) {
    return TRUE;
  }
  return FALSE;
}
```
