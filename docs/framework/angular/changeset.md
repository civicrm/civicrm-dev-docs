# AngularJS: Changesets

!!! caution "Work in progress"

    This documentation is still a work in progress.

The [Quick Start](quickstart.md) and [Loader](loader.md) provide examples of
creating *new* screens.  But what if you need to alter an *existing* screen?
CiviCRM allows third-parties to define *changesets* which programmatically
manipulate Angular content before sending it to the client.

## Background

Most AngularJS tutorials focus on idealized projects where a single
developer or product-owner exercises full authority over their application.
But CiviCRM is an _ecosystem_ with a range of stakeholders, including many
developers (authoring indpendent extensions) and administrators (managing
independent deployments with independent configurations).

...

## Hooks

* **[hook_civicrm_alterAngular](../../hooks/hook_civicrm_alterAngular.md)** - Alter the definition of some Angular HTML partials.

## Example

```php
function mailwords_civicrm_alterAngular(\Civi\Angular\Manager $angular) {
  $changeSet = \Civi\Angular\ChangeSet::create('inject_mailwords')
    // ->requires('crmMailing', 'mailwords')
    ->alterHtml('~/crmMailing/BlockSummary.html',
      function (phpQueryObject $doc) {
        $doc->find('.crm-group')->append('
        <div crm-ui-field="{name: \'subform.mailwords\', title: ts(\'Keywords\')}">
          <input crm-ui-id="subform.mailwords" class="crm-form-text" name="mailwords" ng-model="mailing.template_options.keywords">
        </div>
      ');
      });
  $angular->add($changeSet);
}
```

```
cv ang:html:list
cv ang:html:show <file>
cv ang:html:show <file> --diff
```
