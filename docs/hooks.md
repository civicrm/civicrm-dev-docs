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

## Hook naming

The names of all the hook functions follow a pattern:
 
`EXTENSION-NAME_civicrm_HOOK-NAME`

The two parts that you'll be changing are:

1. ==EXTENSION-NAME==: Depending on your installation this can change. In 
[Drupal][drupal] it will be the name of your extension. In 
[Joomla][joomla] it will be "joomla".
2. ==HOOK-NAME==: This is the name of the event you want to hook into, for 
example `validateForm`. You'll need to check the reference for a full list of 
hooks that are available.

So if you were creating an extension called `superextension` in Drupal and 
wanted to do something right after your extension was installed then your 
function would be:

```php
<?php
function superextension_civicrm_install() {
  // do something here
}
```
 
!!! tip
    To see what the parameters for your new function should be just check the 
    documentation, in this case 
    [hook_civicrm_install](/hooks/hook_civicrm_install/)

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

## Pitfalls of hooks

Because you have little control over what CiviCRM passes to your hook function,
it is very helpful to look inside those objects (especially `$objectRef`) to
make sure you're getting what you expect.

A good debugger is indispensable here. See the 
[page on debugging](/dev-tools/debugging/) for more information on setting up
 a debugger for your development environment.

!!! warning
    From time to time an new release of the CiviCRM can deprecate or change 
    certain hooks. Keep this in mind when upgrading, and make sure you
    check the release notes before upgrading. 

## Organizing Your Hooks

You may find that some of your hooks target a lot of different cases. Such 
hooks can quickly get out of control, and maintaining them can be a nightmare.

You might find it helpful when implementing a hook to delegate certain 
operations to different functions instead of lumping it all in together in 
the main hook.

If you're using [Civix](/extensions/civix/) to create your extension it will 
automatically generate wrapper code for your hook. 

For more information you can checkout the README in this 
[zip file][wrapper-zip] for setting up an example Drupal module that 
illustrates this technique.

## Examples of using hooks

In all of these examples, you'll put the code we provide into your
`myhooks.module` file if using Drupal, or the `civicrmHooks.php` file if using
Joomla!. Be sure to upload the file after each change to the appropriate
location on your server to see the new code take effect.

Because the majority of users currently use CiviCRM with Drupal we'll assume 
you're using Drupal for the rest of the example. But don't worry Joomla! users, 
the concept is the same and just requires some tweaks to get it working. Have a
look at the [Joomla help][joomla] for more instructions.

### Setting Text on a Form

To implement `hook_civicrm_buildForm` from within the "myextension" extension 
you would add the following function to your main .php or .module file (or a 
file always included by that script):
                
```php
<?php

function myextension_civicrm_buildForm($formName, &$form) {
  // note that form was passed by reference
  $form->assign('intro_text', ts('hello world'));
 }
```

As long as the extension is enabled, this function will be called every time 
CiviCRM builds a form.

### Sending an Email Message When an Individuals Was Edited

In order to have CiviCRM tell you when an Individual was edited, define the
`civicrm_pre` hook. This lets you see the incoming edits as well as the values
of the existing record, because you may want to include that information in the
email.

```php
<?php
function myextension_civicrm_pre( $op, $objectName, $objectId, &$objectRef ) {
  // Make sure we just saved an Individual contact and that it was edited
	if ($objectName != "Individual" || $op != "edit") {
	  return;
  }

  // send the email
  $emailSubject = "An Individual was edited";
  $emailBody = sprintf("Someone edited Individual with ID %d\n", $objectId);
  $emailRecipient = 'johndoe@example.org';
  
  mail( $emailRecipient, $emailSubject, $emailBody );
}
```

### Validating Form Content

If you have experience with other hook-based systems, you might think that the
`civicrm_pre` hook is the one to use for validations. But this is not the case
in CiviCRM because, even though the `civicrm_pre` hook is called before the
record is saved to the database, you cannot abort the action from this hook.

This is where form validation hooks come in. When you return true from a 
validation hook CiviCRM saves the new or updated record. When you return an 
error array instead, CiviCRM aborts the operation and reports your error to 
the user.

```php
<?php

function myextension_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {  
  
  $errors = array();
  
  // check we're targeting the right form
  if ($formName != 'My_Contact_Form') {
    return true;
  }
    
   $firstName = CRM_Utils_Array::value( 'first_name', $fields );
  
   // ensure that firstName is present and valid
   if (!$firstName) {
      $errors['first_name'] = ts( 'First name is a required field' );
   } elseif (strlen($firstName) > 50) {
      $errors['first_name'] = ts( 'First name must be less than 50 characters');
   }
   
  return empty($errors) ? true : $errors;
}
```

### Custom mail merge token

The CiviMail component lets you customize a bulk email message using mail merge
tokens. For instance, you can begin your message with, "Hi,
{recipient.first_name}!" and when John Doe receives it, he'll see, "Hi, John!"
whereas when Suzy Queue receives it, she'll see, "Hi, Suzy!"

Besides the built-in tokens, you can use a hook to create new custom tokens.
Let's make a new one that will show the largest contribution each recipient has
given in the past.

```php
<?php 
/**
 * Implement this hook so we can add our new token to the list of tokens
 * displayed to CiviMail users and set the default
 * 
 * @param array $tokens
*/
function myextension_civicrm_tokens(&$tokens) {
  if (isset($tokens['contribution'])) {
    return;
  }
  
  $tokens['contribution'] = array('contribution.max' => 'Max Contribution');
}

/**
 * @param array $details
 *   The array to store the token values indexed by contactIDs (unless single)
 * @param array $contactIDs
 *   An array of contactIDs
 * @param int $jobID
 *   The jobID if this is associated with a CiviMail mailing.
 * @param array $tokens
 *   The list of tokens associated with the content
 * @param string $className
 *   The top level className from where the hook is invoked
 *
 * @return null
*/
function myextension_civicrm_tokenValues(&$details, $contactIDs, $jobID, $tokens, $className) {
  
  // validate that we're targeting the right event
  if ($className != SomeCustomClass::class) {
    return;
  }
  
  // fetch the maximum contribution here
  foreach ($contactIDs as $contactID) {
    $max = my_function_to_get_the_max($contactID);
    $details[$contactID]['contribution.max'] = $max;
  }  
}
```

[drupal]: hooks/setup/drupal
[joomla]: hooks/setup/joomla
[wrapper-zip]: http://wiki.civicrm.org/confluence/download/attachments/86213379/callhooks.zip?version=1&modificationDate=1372586243000&api=v2