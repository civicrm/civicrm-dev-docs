How to use hooks
================

TODO:

-   http://wiki.civicrm.org/confluence/display/CRMDOC/Hooks
-   http://wiki.civicrm.org/confluence/display/CRMDOC/Hook+Reference

Hooks are a common way to extend systems. The way they work in CiviCRM is that,
at key points in processing - such as saving a change or displaying a page -
CiviCRM checks to see whether you've "hooked in" some custom code, and runs any
valid code it finds.

For example, let's say you want to send an email message to someone in your
organization every time a contact in a particular group is edited. Hooks allow
you to do this by defining a function with a specific name and adding it to your
organisation's CiviCRM installation. The name of the function indicates the
point at which CiviCRM should call it. CiviCRM looks for appropriate function
names and calls the functions whenever it performs the indicated operations.

Hooks are a powerful way to extend CiviCRM's functionality, incorporate
additional business logic, and even integrate CiviCRM with external systems.
Many CiviCRM developers find themselves using them in nearly every customization
project.

A good test for whether or not to use a hook is to ask yourself whether what
you're trying to do can be expressed with a sentence like this: "I want X to
happen every time someone does Y."


Using hooks with Drupal
-----------------------

In order to start using hooks with a Drupal-based CiviCRM installation, you or
your administrator needs to do the following:

1.  Create a file with the extension .info (for instance, myhooks.info)
    containing the following lines. Replace the example text in the first 2
    lines with something appropriate for your organization, and assign 7.x
    to core if you use Drupal 7.

        name = My Organization's Hooks
        description = Module containing the CiviCRM hooks for my organization
        dependencies[] = civicrm
        package = CiviCRM
        core = 7.x
        version = 7.x-1.0

2.  Create a new file with the extension *.module* (for instance,
    *myhooks.module*) to hold your PHP functions.
3.  Upload both the *.info* and *.module* files to the server running CiviCRM,
    creating a new directory for them under  */sites/all/modules* (for
    instance, */sites/all/modules/myhooks/*) inside your Drupal installation.
    The directory name you create should be short and contain only lowercase
    letters, digits, and underlines without spaces.
4.  Enable your new hooks module through the Drupal administration page.

Note that if you use certain Drupal functions from within CiviCRM, you could
break whatever form you're working with! To prevent very hard-to-troubleshoot
errors, do the following (at least for `user_save()` with Drupal 6, possibly
others):

```php
$config = CRM_Core_Config::singleton();
```

```php
$config->inCiviCRM = TRUE;
```

```php
$user = user_save('',array(..));
```

```php
$config->inCiviCRM = FALSE;
```

Using hooks with Joomla!
------------------------

Hooks may be implemented in Joomla in two ways, depending on the version of
CiviCRM and Joomla you are using. For sites running Joomla 1.5 with CiviCRM up
to and including version 3.4, you implement hooks with a single civicrmHooks.php
in your php override directory. Sites running Joomla 1.6+ and CiviCRM 4+ may
implement with either that single hooks file, or by creating a Joomla plugin.
In general, implementing through a plugin is preferred as you can benefit from
the native access control within the plugin structure, include code that
responds to other Joomla events, organize your hook implementations into
multiple plugins which may be enabled/disabled as desired, and roughly follow
the event-observer pattern intended by Joomla plugins.

As you implement hooks in Joomla, be sure to check the CiviCRM wiki for the
most up-to-date information:

-   [http://tiny.booki.cc/?hooks-in-joomla](http://tiny.booki.cc/?hooks-in-joomla)
-   [http://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+hook+specification\#CiviCRMhookspecification-Proceduresforimplementinghooks%28forJoomla%29](http://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+hook+specification#CiviCRMhookspecification-Proceduresforimplementinghooks%28forJoomla%29)

To implement hooks with a single file, you will do the following:

1.  If you have not done so already, create a new directory on your server to
    store your PHP override files. In Joomla, that is commonly placed in the
    media folder, as it will not be impacted by Joomla and CiviCRM upgrades.
    For example, you might create the following
    folder: `/var/www/media/civicrm/customphp`.
2.  If you have not done so already, configure your system to reference the
    folder you've created as your override directory. Go to: CiviCRM
    Administer > Global Settings > Directories. Change the value of Custom
    PHP Path Directory to the absolute path for the new directory (e.g.,
    "/var/www/media/civicrm/customphp" if you used that suggestion in the
    earlier step). The custom override directory may also be used to store
    modified copies of core files -- thus overriding them. You may want to
    familiarize yourself with its purpose if you are not yet.
3.  Create a file named *civicrmHooks.php* to contain your hook
    implementations, and upload the file to the directory you just created.
4.  Within that file, your hooks will be implemented by calling the hook
    function prefaced by "joomla\_". For example, you would call the buildForm
    hook (used to modify form rendering and functionality) by adding the
    following function to your hook file:

```php
function joomla_civicrm_buildForm( $formName, &$form ) {
    //your custom code
}
```

If you are implementing hooks with a Joomla plugin, you will create a standard,
installable plugin package. At a minimum, a plugin extension will consist of an
xml file (defining the plugin and its parameters), and a php file. Within the
php file, define a class that extends the Joomla JPlugin class, and call your
hooks but adding the appropriate functions. For example, your plugin file may
look like the following:

```
defined('_JEXEC') or die;
jimport('joomla.plugin.plugin');

class plgCiviCRMMyPlugin extends JPlugin {
    public function civicrm_tabs(&$tabs, $contactID) {
    	//your code to alter the contact summary tabs
    }
}
```

The first two lines are required -- the first is for security purposes, and
ensures the code will exit if it has not been called from within Joomla. The
second includes the necessary parent plugin class.

Joomla plugin classes follow standard naming conventions which you should
follow. By naming this plugin class "plgCiviCRMMyPlugin," I am stating that the
plugin resides in the plugin/civicrm/ folder, and the plugin file is named
"myplugin.php."

For more information about implementing hooks through plugins, see this [blog
article](http://civicrm.org/blogs/mcsmom/hooks-and-joomla)

Note the reference in the comments to a sample plugin which you can download
and modify.

Refine what you want to act upon
--------------------------------

When you create a hook, it will be called for all the types of entities. For
instance, a civicrm\_post is called after the creation or modification of any
object of any type (contact, tag, group, activity, etc.). But usually, you want
to launch an action only for a specific type of entity.

So a hook generally starts with a test on the type of entity or type of action.
For instance, if you want to act only when a new individual contact is created
or modified (does this match the code?), start your `civicrm_post` hook with:

```php
if ($objectName != "Individual" || $op != "edit") {   return; }
```

On the other hand, if you want to run multiple hooks within the same function,
you don't want to return from any single hook. Instead, you can nest the entire
code for each hook within your test:

```php
if ($objectName == "Individual" && $op == "edit") {   // Your hook }
```

Pitfalls of hooks
-----------------

Because you have little control over what CiviCRM passes to your hook function,
it is very helpful to look inside those objects (especially `$objectRef`) to
make sure you're getting what you expect. A good debugger is indispensable here.
See the Developer Tips & Tricks chapter at the end of this section for more
information on setting up a debugger for your development environment.

Examples of using hooks
-----------------------

Some example hooks follow.

In all of these examples, you'll put the code we provide into your
`myhooks.module` file if using Drupal, or the `civicrmHooks.php` file if using
Joomla!. Be sure to upload the file after each change to the appropriate
location on your server to see the new code take effect.

Additionally, if you are using Drupal and add a new hook to an existing module,
you will need to clear the cache for the hook to start operating. One way of
doing this is by visiting the page Admin > Build > Modules.

### Sending an email message when a contact in a particular group is edited

In order to have CiviCRM tell you when a contact is edited, define the
`civicrm_pre` hook. This lets you see the incoming edits as well as the values
of the existing record, because you may want to include that information in the
email.

If you are using Drupal, create a function named `myhooks_civicrm_pre`.
If using Joomla!, create a function named `joomla_civicrm_pre`. We'll
assume you're using Drupal for the rest of the example, so please adjust the
code accordingly if you're using Joomla! instead.

```php
<?php  function myhooks_civicrm_pre( $op, $objectName, $objectId, &$objectRef ) {

# configuration stuff
$theGroupId = 1;

# group id we want the contacts to be in
$emailRecipient = 'johndoe@example.org';

# person to e-mail
# Make sure we just saved an Individual contact and that it was edited

	if ($objectName == "Individual" && $op == "edit") {

	# Now see if it's in the particular group we're interested in

	require_once 'api/v2/GroupContact.php';
	$params = array('contact_id' => $objectId);
	$groups = civicrm_group_contact_get( $params );
	$found = false;
	foreach ($groups as $group) {
	  if ($group['group_id'] == $theGroupId) {
	  	$found = true;
	  }
	}

	# Exit now if contact wasn't in the group we wanted
	if (! $found) {
	 return;
	}

	# We found the contact in the group we wanted, send the e-mail

	$emailSubject = "Contact was edited";
	$emailBody = "Someone edited contactId $objectId\n";

	# Here's where you may want to iterate over the fields
	# and compare them so you can report on what has changed.
	mail( $emailRecipient, $emailSubject, $emailBody );
	}
}
```

### Validating a new contribution against custom business rules

If you have experience with other hook-based systems, you might think that the
`civicrm_pre` hook is the one to use for validations. But this is not the case
in CiviCRM because, even though the `civicrm_pre` hook is called before the
record is saved to the database, you cannot abort the action from this hook.

This is where validation hooks come in. When you return true from a validation
hook, CiviCRM saves the new or updated record. When you return an error object
instead, CiviCRM aborts the operation and reports your error to the user.

An example follows of using a validation hook to validate new contributions
against a business rule that says campaign contributions must have a source
associated with them. In this example, we'll assume you are using Joomla!, so if
you are using Drupal instead, be sure to change the function name accordingly.

```php
<?php  function joomla_civicrm_validate( $formName, &$fields, &$files, &$form ) {
  # configuration stuff
  $campaignContributionTypeId = 3;
  # adjust for your site if different
  $errors = array();

  # $formName will be set to the class name of the form that was posted

  if ($formName == 'CRM_Contribute_Form_Contribution') {

    require_once 'CRM/Utils/Array.php';
    $contributionTypeId = CRM_Utils_Array::value( 'contribution_type_id', $fields );

    if ($contributionTypeId == $campaignContributionTypeId) {
      # see if the source field is blank or not
      $source = CRM_Utils_Array::value( 'source', $fields );
      if (strlen( $source ) > 0) {
        # tell CiviCRM to proceed with saving the contribution
        return true;
      } else {
        # source is blank, bzzzzzzzzzzzt!
        # assign the error to a key corresponding to the field name
        $errors['source'] =  "Source must contain the campaign identifier for campaign contributions";
        return $errors;
      }
    } else {
      # not a campaign contribution, let it through
      return true;
    }
  }
}

```

### Automatically filling custom field values based on custom business logic

This example uses a hook to write some data back to CiviCRM. You can make a
custom field read-only and then set its value from a hook. This is very handy
for storing and displaying data that are derived from other attributes of a
record based on custom business logic.

For example, let's say you are storing employee records and you want to
auto-generate their network login account when new employees are added. By doing
it in your code, you can enforce a policy for login account names. For this
example, let's say the policy is first initial + last name. So if your name is
Jack Frost, your network login name would be jfrost.

Add a new read-only custom field to CiviCRM called "Network Login" and then
find its ID. You can find it either by:

-   Checking the `civicrm_custom_field` table in your CiviCRM database.
-   Editing a contact and check the name of the Network Login field.

The code must refer to the ID as `custom_id`. So if you find that
the id of the new field is `74`, refer to is as `custom_74` in your code.

Now that we have our Network Login field, let's see how to populate it
automatically with a hook. We'll switch back to the Drupal naming convention for
this example.

Note that we use the `civicrm_post` hook here because we need the new contact
record's ID in order to save a value to one of its custom fields. New records
don't have an ID until they have been saved in the database, so if we ran this
code in the `civicrm_pre hook`, it would fail.

```
<?php  function myhooks_civicrm_post( $op, $objectName, $objectId, &$objectRef ) {
  # configuration stuff
  $customId = 74;
  if ($objectName == 'Individual' && $op == 'create') {
    # generate the login
    $firstName = $objectRef->first_name;
    $lastName = $objectRef->last_name;
    $firstInitial = substr( $firstName, 0, 1 );
    $networkLogin = strtolower( $firstInitial . $lastName );
    # assign to the custom field
    $customParams = array("entityID" => $objectId,
     "custom_$customId" => $networkLogin);
    require_once 'CRM/Core/BAO/CustomValueTable.php';
    CRM_Core_BAO_CustomValueTable::setValues( $customParams );
  }
}
```

### Custom mail merge token

The CiviMail component lets you customize a bulk email message using mail merge
tokens. For instance, you can begin your message with, "Hi,
{recipient.first_name}!" and when John Doe receives it, he'll see, "Hi, John!"
whereas when Suzy Queue receives it, she'll see, "Hi, Suzy!"

Besides the built-in tokens, you can use a hook to create new custom tokens.
Let's make a new one that will show the largest contribution each recipient has
given in the past. We'll use Drupal syntax again for this one.

```php
# implement the tokens hook so we can add our new token to the list of tokens
# displayed to CiviMail users

function myhooks_civicrm_tokens( &$tokens ) {
  $tokens['contribution'] =
  array('contribution.largest' => 'Largest Contribution');
  /*  just array('contribution.largest'); in 3.1 or earlier */
}

# now we'll set the value of our custom token;
# it's better in general to use the API rather than SQL queries to retrieve data,
# but in this case the MAX() function makes it very efficient to get the largest
# contribution, so let's make an exception

function myhooks_civicrm_tokenValues( &$details, &$contactIDs ) {
  # prepare the contact ID(s) for use in a database query
  if ( is_array( $contactIDs ) ) {
    $contactIDString = implode( ',', array_values( $contactIDs ) );
    $single = false;
  } else {
    $contactIDString = "( $contactIDs )";
    $single = true;
  }
  # build the database query
  $query = "SELECT contact_id,
  max( total_amount ) as total_amount
  FROM civicrm_contribution
  WHERE contact_id IN ( $contactIDString )
  AND is_test = 0
  GROUP BY contact_id";

  # run the query
  $dao = CRM_Core_DAO::executeQuery( $query );
  while ( $dao->fetch( ) ) {
    if ( $single ) {
      $value =& $details;
    } else {
      if ( ! array_key_exists( $dao->contact_id, $details ) ) {
        $details[$dao->contact_id] = array( );
      }
      $value =& $details[$dao->contact_id];
    }
    # set the token's value
    $value['contribution.largest'] = $dao->total_amount;
  }
}
```

