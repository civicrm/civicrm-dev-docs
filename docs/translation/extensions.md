# Extensions Translation

For developing a CiviCRM extension in a way that can be translated, all the best practices described in the Internationalisation for Developers page apply. This page describes special considerations that need to be taken in count for achieving this.

## For translators: Translating strings on Transifex

There is a separate project on Transifex to translate extensions. Each extension has its own "resource". Therefore, when a translator joins a translation team, he/she can translate all extensions. We didn't see a need to separate each extension in a separate project, because each extension should have only one translation (.po) file.

See: https://www.transifex.com/projects/p/civicrm_extensions/

## For administrators: Download translation files for extensions

The easiest way to download translations for extensions is to use the [l10nupdate](https://github.com/cividesk/com.cividesk.l10n.update/) extension.

## For developers: Correct usage of the ts() function

In PHP, Smarty, and JS code, the convention is to perform translations using the `ts()` helper function. This is the same as in core code – with the additional requirement that one must specify the "domain" so that the translation engine can use the correct dictionary (.mo file) at run-time.

PHP:

```php
$string = ts('Hello, %1', array(
  1 => $display_name,
  'domain' => 'org.example.myextension',
));
```
 
Smarty templates:

```
{crmScope extensionKey='org.example.myextension'}
  <p>{ts 1=$display_name}Hello, %1{/ts}</p>
{/crmScope}
```

Javascript:

```js
(function ($, ts){
  $('.foo').click(function greet(display_name) {
    window.alert(ts('Hello, %1', {1: display_name}));
  });
}(CRM.$, CRM.ts('org.example.myextension')));
```

Angular:

```js
$scope.ts = CRM.ts('org.example.myextension');
```

Angular HTML templates:

```
<p>{{ts('Hello, %1', {1: display_name})}}</p>
```
