# AngularJS: Alteration

```php
function example_civicrm_alterAngular($angular) {
  $angular->add(\Civi\Angular\ChangeSet::create('mychanges')
    ->alterHtml('~/crmMailing/EditMailingCtrl/2step.html', function(phpQueryObject $doc) {
      $doc->find('[ng-form="crmMailingSubform"]')->attr('cat-stevens', 'ts(\'wild world\')');
    })
  );
}
```


cv ang:html:show