# Extensions Translation

For developing a CiviCRM extension in a way that can be translated, all the best practices described in the [Internationalisation for Developers](index.md) page apply. This page describes special considerations that need to be taken into account for extensions.

See also: ["Extension translation" wiki in the Translation project](https://lab.civicrm.org/dev/translation/wikis/extension-translation).

## For translators: Translating strings on Transifex

There is a separate project on Transifex to translate extensions. Each extension has its own "resource". Therefore, when a translator joins a translation team, they can translate all extensions. We didn't see a need to separate each extension in a separate project, because each extension should have only one translation (`.po`) file.

See: <https://www.transifex.com/projects/p/civicrm_extensions/>

Translation strings are sent to Transifex when the extension is "ready for automatic distribution". See: [Publishing Extensions](../extensions/publish.md).

## For administrators: Download translation files for extensions

The easiest way to download translations for extensions is to use the [l10nupdate](https://github.com/cividesk/com.cividesk.l10n.update/) extension.

## For developers: Correct usage of the `E::ts()` function

In PHP, Smarty, and JS code, the convention is to perform translations using the `E::ts()` helper function. This is the same as in core code &mdash; with the additional requirement that one must specify the "domain" so that the translation engine can use the correct dictionary (`.mo` file) at run-time.

!!! note "New in civix 17.08"
    `E::ts()` was added to civix 17.08. The civix file may need to be regenerated. You can read more about it in the [civix upgrade notes](https://github.com/totten/civix/blob/master/UPGRADE.md#upgrade-to-v17081-the-big-e). Extensions may still use the old syntax using `ts()` with the `domain` argument.

!!! note "`E::ts()` and `ts()`"
     `E::ts()` is recommended, but it won't fallback to core. (it might in JS, but not in PHP because we don't have a way to detect if gettext translated or not)
    
PHP:

```php
use CRM_Myextension_ExtensionUtil as E;

class CRM_Myextension_Form_Example {
  function example() {
    $string = E::ts('Hello, %1', array(
      1 => $display_name,
    ));
  }
}
```
 
Smarty templates:

```smarty
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

```html
<p>{{ts('Hello, %1', {1: display_name})}}</p>
```
