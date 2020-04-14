## Overview

Hooks are a common way to extend systems. Let's say you want to send a
message to someone in your organization every time a contact is created. An
easy way to do this would be to insert code to send the message in the
CiviCRM core code right where the contact is created. However, as soon as we
upgrade to a newer version all this code will be overwritten. This is where
hooks come in to save the day.

At key points in processing - for example saving
something to the database - CiviCRM checks to see whether you've "hooked in"
some custom code, and runs any valid code it finds.

Hooks allow you to do this by defining a function with a specific name and
adding it to your organisation's CiviCRM installation. The name of the
function indicates the point at which CiviCRM should call it. CiviCRM looks
for appropriate function names and calls the functions whenever it performs
the indicated operations.

Hooks are a powerful way to extend CiviCRM's functionality, incorporate
additional business logic, and even integrate CiviCRM with external systems.
Many CiviCRM developers find themselves using them in nearly every customization
project.

!!! tip
    A good test for whether or not to use a hook is to ask yourself whether
    what you're trying to do can be expressed with a sentence like this: "I want
    X to happen every time someone does Y."

## Usage

There are two ways to use hooks: the traditional method and the Symfony events method.

### Traditional method

The traditional method of using a hook is to create a function with a specific name such as:

```php
function myextension_civicrm_buildForm($formName, &$form) {
  // do something
}
```

This works well in many cases but has its limitations.  For example, if two extensions call the same hook there is no way to determine which runs first.

For details, see [Traditional Hooks](usage/extension.md)

### Symfony method

A newer method that provides greater flexibility is to use Symfony events.

For example:

```php
Civi::dispatcher()->addListener('hook_civicrm_buildForm', "myextension_buildForm", $priority);

function myextension_buildForm($event) {
  // do something
}
```

For more details see [Hooks with Symfony](usage/symfony.md)

## Targeting Certain Events

When you create a hook, it will be called for all the types of entities. For
instance, a `civicrm_post` is called after the creation or modification of any
object of any type (contact, tag, group, activity, etc.). But usually, you want
to launch an action only for a specific type of entity.

So a hook generally starts with a test on the type of entity or type of action.
For instance, if you want to act only when an address was edited, start your
`civicrm_post` hook with:

```php
if ($objectName != "Address" || $op != "edit") {
  return;
}
```

## Pitfalls of Hooks

Because you have little control over what CiviCRM passes to your hook function,
it is very helpful to look inside those objects (especially `$objectRef`) to
make sure you're getting what you expect.

A good debugger is indispensable here. See the
[page on debugging](../tools/debugging.md) for more information on setting up
 a debugger for your development environment.

!!! warning
    From time to time a new release of CiviCRM can deprecate or change
    certain hooks. Keep this in mind when upgrading, and make sure you
    check the release notes before upgrading.

## Packaging Hooks

Hooks are packaged in CMS-agnostic [extensions](../extensions/index.md).

## Organizing Your Hooks

You may find that some of your hooks target a lot of different cases. Such
hooks can quickly get out of control, and maintaining them can be a nightmare.

You might find it helpful when implementing a hook to delegate certain
operations to different functions instead of lumping it all in together in
the main hook.

If you're using [Civix](../extensions/civix.md) to create your extension it will
automatically generate wrapper code for your hook.
