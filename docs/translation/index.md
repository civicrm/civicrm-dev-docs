# Translation for Developers

When writing new application code, developers should organize their code in a way which is amenable to internationalization, so that it may be localized to various languages and regions of the world.

If you are an extension developer, there is additional documentation in the [Extension translation](extensions.md) page.

## PHP

* The strings hard-coded into PHP should be wrapped in `ts()` function calls. For example:

    ```php
    $string = ts('Hello, World!');
    
    $group = array('' => ts('- any group -')) + $this->_group;
    ```

* You can also use placeholders for variables:

    ```php
    $string = ts("A new '%1' has been created.", array(1 => $contactType));
    ```

    Note that variables should themselves be translated by your code before passing in, if appropriate.

* If the string might be singular or plural, use the following syntax:

    ```php
    $string = ts('%count item created',
      array('count' => $total, 'plural' => '%count items created')
    );
    ```

## Javascript

When translating strings in an extension, ts scope needs to be declared. The `CRM.ts` function takes scope as an argument and returns a function that always applies that scope to ts calls:

```js
// This closure gets a local copy of jQuery, Lo-dash, and ts
(function($, _, ts) {
  CRM.alert(ts('Your foo has been barred.'));
})(CRM.$, CRM._, CRM.ts('foo.bar.myextension'));
```

!!! note
    `CRM.ts` is not the same as the global `ts` function. `CRM.ts` is a function that returns a function (javascript is wacky like that). Since your closure gives the local `ts` the same name as the global `ts`, it will be used instead.

!!! important
    Your local version of `ts` could be named anything, but strings in your javascript file cannot be accurately parsed unless you name it `ts`.

## Smarty templates

* The strings hard-coded into templates should be wrapped in `{ts}...{/ts}` tags. For example:

    ```smarty
    {ts}Full or partial name (last name, or first name, or organization name).{/ts}
    ```

* If you need to pass a variable to the localizable string, you should use the following pattern:

    ```smarty
    <div class="status">
      {ts 1=$delName}Are you sure you want to delete <b>%1</b> Tag?{/ts}
    </div>
    ```

## Best practices

The general rules for avoiding errors may be summed up like this:

* If the string needs to be parsed (i.e. is in double quotes) then there's probably an error there.
* No string concatenation in the `ts()` calls.
* The second parameter of the `ts()` call must be an array.
* You must pass a literal string into `ts()`, not a variable.

### Avoid variables inside strings

!!! failure "Bad"
    
    ```php
    $string = ts("The date type '$name' has been saved.");
    ```

!!! success "Good"

    ```php
    $string = ts("The date type '%1' has been saved.", array(1 => $name));
    ```

### Avoid tags inside strings 

!!! failure "Bad"
    
    ```smarty
    {ts}<p>Hello, world!</p>{/ts}
    ```

!!! success "Good"

    ```smarty
    <p>{ts}Hello, world!{/ts}</p>
    ```

Hyperlinks within larger blocks of text are an exception to this rule, where you should place the `<a>` tags within the `ts`. Any link parameters should be provided as arguments to the ts. For example:

!!! failure "Bad"

    ```smarty
    {ts}Here is a block of text with a link to the <a href="https://www.civicrm.org" target="_blank">CiviCRM Web Site</a>.{/ts}
    ```

!!! success "Less bad"

    ```smarty
    {ts 1='href="https://www.civicrm.org" target="_blank"'}Here is a block of text with a link to the <a %1>CiviCRM Web Site</a>.{/ts}
    ```

For `title` attributes in `<a>` links, within CiviCRM these usually only appear in links that aren't within a larger block of text or where there is no clickable text, such as a datepicker icon. In this situation, the title text needs to be translated:

!!! failure "Bad"

    ```smarty
    {ts}<a href="https://www.example.org/civicrm/something?reset=1" title="List participants for this event (all statuses)">Participants</a>{/ts}
    ```

!!! failure "Less bad"

    ```smarty
    <a href="https://www.example.org/civicrm/something?reset=1" title="{ts}List participants for this event (all statuses){/ts}">{ts}Participants{/ts}</a>
    ```

If there is no clickable text, just translate the title attribute:

!!! success "Good"

    ```smarty
    <a title="{ts}Select Date{/ts}"><i class="crm-i fa-calendar"></i></a>
    ```

### Avoid multi-line strings

Even if your code editor may not like it, long strings should be on a single line since a change in indentation might change where the line breaks are, which would then require re-translating the string.

!!! failure "Bad"
    
    ```php
    $string = ts("Lorem ipsum dolor sit amet, consectetur adipiscing elit.
      Proin elementum, ex in pretium tincidunt, felis lorem facilisis 
      lacus, vel iaculis ex orci vitae risus. Maecenas in sapien ut velit
      scelerisque interdum.");
    ```

!!! success "Good"

    ```php
    $string = ts("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin elementum, ex in pretium tincidunt, felis lorem facilisis lacus, vel iaculis ex orci vitae risus. Maecenas in sapien ut velit scelerisque interdum.");
    ```

### Avoid strings which begin or end with spaces

!!! failure "Bad"
    
    ```php
    $string = $labelFormat['label'] . ts(' has been created.'),
    ```

!!! success "Good"

    ```php
    $string = ts('%1 has been created.', array(1 => $labelFormat['label'])),
    ```
 
### Avoid escaped quotes

!!! failure "Bad"
    
    ```php
    $string = ts('A new \'%1\' has been created.', array(1 => $contactType));
    ```

!!! success "Good"

    ```php
    $string = ts("A new '%1' has been created.", array(1 => $contactType));
    ```
    
### Use separate strings for plural items

!!! failure "Bad"
    
    ```php
    $string = ts('%1 item(s) created', array(1 => $count));
    ```

!!! success "Good"

    ```php
    $string = ts('%count item created',
      array('count' => $total, 'plural' => '%count items created')
    );
    ```
    
### Ensure that strings have *some* words in them

Another common error is to use `ts()` to aggregate strings or as a "clever" way of writing shorter code:

!!! failure "Bad"

    Incorrect aggregation. This will be extremely confusing to translations and might give some really bad results in some languages.
    
    ```php
    $operation = empty($params['id']) ? ts('New') : ts('Edit'));
    $string = ts("%1 %2", array(1 => $operation, 2 => $contactType));
    ```

!!! success "Less bad"

    ```php
    if (empty($params['id'])) {
      $string = ts("New %1", array(1 => $contactType));
    }
    else {
      $string = ts("Edit %1", array(1 => $contactType));
    }
    ```
    
    Note that this still makes it difficult to use the correct gender.

### Include typography in strings

Typography is different in different languages and thus must be translated along with the string. For example, in French, there must be a space before a colon. 

!!! failure "Bad"
    
    ```smarty
    {ts}Event Total{/ts}:
    ```

!!! success "Good"

    ```smarty
    {ts}Event Total:{/ts}
    ```

## Rationale for using Gettext

In most projects, strings are typically translated by either:

* using Gettext (which is what CiviCRM does),
* using arrays of key/string dictionaries,
* using database lookups of strings (which is what Drupal does).

In order to be support Joomla!, WordPress, Backdrop and eventually other content management systems. Gettext is the standard way to translate strings in PHP, used by most projects.

## Other guides/references

Here are the guides to other popular projects:

* Drupal: <https://www.drupal.org/node/322729>
* Joomla!: <https://docs.joomla.org/Specification_of_language_files>
* WordPress: <https://codex.wordpress.org/I18n_for_WordPress_Developers>
