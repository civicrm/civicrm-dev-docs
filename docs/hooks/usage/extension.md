## Getting started

The names of all the hook functions follow a pattern:

`EXTENSION-NAME_civicrm_HOOK-NAME`

1. Review the [list of hooks](../../hooks/list.md) and find the hook that matches your need.
1. Read the documentation page for that hook
1. In your extension, create a function replacing `hook_`  with your extension name, and with the same signature.

So if you were creating an extension called `superextension` and
wanted to do something right after your extension was installed then your
function would be:

```php
function superextension_civicrm_install() {
  // do something here
}
```

## Examples of using hooks

In all of these examples, you'll put the code we provide into your
extension. Be sure to upload the file after each change to the appropriate
location on your server to see the new code take effect.

### Setting Text on a Form

To implement `hook_civicrm_buildForm` from within the "myextension" extension
you would add the following function to your main `myextension.php` file (or a
file always included by that script):

```php
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
function myextension_civicrm_pre($op, $objectName, $objectId, &$objectRef) {
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
