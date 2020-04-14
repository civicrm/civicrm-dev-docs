# hook_civicrm_alterAngular

## Summary

This hook alters the definition of some AngularJS HTML partials and allows you to inject [AngularJS changesets](../framework/angular/changeset.md).

## Availability

This hook is available in CiviCRM 4.7.21 and later.

## Definition

     hook_civicrm_alterAngular(&$angular)

## Parameters

- array `$angular` - `\Civi\Angular\Manager`

## Example

```php
function example_civicrm_alterAngular($angular) {
  $angular->add(\Civi\Angular\ChangeSet::create('mychanges')
    ->alterHtml('~/crmMailing/EditMailingCtrl/2step.html', function(phpQueryObject $doc) {
      $doc->find('[ng-form="crmMailingSubform"]')->attr('cat-stevens', 'ts(\'wild world\')');
    })
  );
}
```
