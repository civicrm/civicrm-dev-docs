# Translation for Developers

When writing new application code, developers should organize their code in a way which is amenable to internationalization, so that it may be localized to various languages and regions of the world.

If you are an extension developer, there is additional documentation in the [Extension translation](/translation/extensions.md] page.

## PHP

The strings hard-coded into PHP should be wrapped in ts() function calls. Here are a few examples:

```
$string = ts('Hello, World!');

$group = array('' => ts('- any group -')) + $this->_group;
```

You can also use placeholders for variables:

```
$string = ts("A new '%1' has been created.", array(
  1 => $contactType
));
```

Note that variables should themselves be translated by your code before passing in, if appropriate.

A few examples to avoid:

```
// Bad: Avoid escaped quotes: this is harder to read:
$string = ts('A new \'%1\' has been created.', array(1 => $contactType));

// Good:
$string = ts("A new '%1' has been created.", array(1 => $contactType));

// Bad: multi-line strings:
// Even if your code editor may not like it, this should be on a single line
// since a change in indentation might change where the line breaks are, which
// would then require re-translating the string.
$string = ts("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin 
  elementum, ex in pretium tincidunt, felis lorem facilisis lacus, vel 
  iaculis ex orci vitae risus. Maecenas in sapien ut velit scelerisque 
  interdum.");
```

Another common error is to use `ts()` to aggregate strings or as a "clever" way of writing shorter code:

```
// Bad: incorrect aggregation
// This will be extremely confusing to translations
// and might give some really bad results in some languages.
$operation = $is_early ? ts('Good morning') : ts('Hi');
$string = ts("%1 %2, how are you?", array(1 => $name));

// Good:
$string = ts("Hi %2, how are you?", array(1 => $name));

if ($is_early) {
  $string = ts("Good morning %2, how are you?", array(1 => $name));
}
```

