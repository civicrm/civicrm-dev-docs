# Customizing Built-in Screens

!!! caution "Use extensions instead"

    The methods on this page are of historical interest and may be helpful in understanding legacy code, but overriding templates and php files is strongly discouraged for any new customisations. These approaches need attention whenever you upgrade and will cause a range of problems from subtle to major if you forget about them.
    
    As CiviCRM evolves, the recommended ways of customizing it change.   The current preferred method is to Create an Extension to implement Hooks and use the API.  If you can't find a way to use existing hooks to accomplish what you want, discuss it on StackExchange or Chat.  It may be appropriate to add a new hook to core.
    
    (It is possible to override templates and php in an extension, but you face the same problems when it comes to upgrading - don't do it!)


You can create customized versions of the standard CiviCRM
forms and pages for your site. This gives you a lot of power to modify
the CiviCRM user interfaces without requiring advanced programming
skills - the templates use HTML plus a fairly simple scripting language.
It also means you won't lose your customizations when you upgrade
(although it is possible that changes in the core code could affect your
templates - and testing after upgrades is essential).

## First Time Configuration

-   Create a new directory under your webroot (or anywhere readable
    by www-data) where you will place your custom template files.
    -   EX: `/var/www/civicrm_custom`
    -   EX: `/var/www/media/civicrm/customtpl/`
-   Login to CiviCRM with administer CiviCRM permissions and navigate to
    **Administer CiviCRM » Global Settings » Directories** (in newer
    versions this is Administer » System settings » Directories)
-   Enter the full path to your custom templates directory in the
    **Custom Templates** field and save your changes.

## Create a Custom Screen

These steps are for any "built-in" screen (e.g. the **Contact Summary**
screen). Creating a custom **Profile** form or page are covered in the
next section.

-   Navigate to the screen you want to customize and use your browser to **View Source**.
-   Search for `.tpl` in the source. You will find a comment line which gives you the path and name of the template used for the screen you are viewing.
    
    ```html
    <!-- .tpl file invoked: CRM/Contact/Page/View/Summary.tpl. Call via form.tpl if we have a form in the page. -->
    ```

-   Locate this file on your server under your `civicrm/templates` path.
-   Make a copy of file in the equivalent path under your new custom templates directory.

    If your civicrm install files are in: `/var/www/drupal/sites/all/modules/civicrm`  ...and your custom templates directory is:  `/var/www/civicrm_custom`
    
    Create directory structure in your custom templates tree
    
    ```bash
    $ cd /var/www/civicrm_custom
    $ mkdir CRM
    $ cd CRM
    $ mkdir Contact
    $ cd Contact
    $ mkdir Page
    $ cd Page
    $ mkdir View
    $ cd View
    ```
    
    -OR-, do all of the above with:
    
    ```html
    $ mkdir -p CRM/Contact/Page/View/ ; cd CRM/Contact/Page/View/
    ```
   
    Copy standard template to your new tree

    ```bash
    $ cp /var/www/drupal/sites/all/modules/civicrm/templates/CRM/Contact/Page/View/Summary.tpl
    ```
    

-   Edit the file as needed and save.
-   Then clean-up the **compiled templates directory**, usually by deleting all the directories under your `templates_c` directory. You can also do the cleanup by enabling CiviCRM debugging and running the directory cleanup command.
    
    !!! note
        You do NOT need to delete the standard version of the template from your main CiviCRM codebase. CiviCRM will look for a customized version first, and use that one if found.)
        
-   You should see your modified version when you reload the
    CiviCRM screen.
    -   If you need help with the templating language, check out the [Smarty Documentation](http://www.smarty.net/)

## Create a Custom Screen for Contact SubTypes

You can have customized view/edit screens for contact subtypes in CiviCRM. This allows you to have different views for your specific contact subtypes. 


If your civicrm install files are in:
`/var/www/drupal/sites/all/modules/civicrm`
...and your custom templates directory is:
`/var/www/civicrm_custom`

Create a directory "SubType" to place your custom contact subtypes

```bash
$ mkdir SubType
``` 

The new directory structure is:

* `/var/www/civicrm_custom/CRM/Contact/Form/Edit/SubType/`
* `/var/www/civicrm_custom/CRM/Contact/Page/View/SubType/`

To override edit screen: put `subTypeName.tpl` file in the `templates/CRM/Contact/Form/Edit/SubType/` directory.

To override view screen(contact summary): put `subTypeName.tpl` file in the `templates/CRM/Contact/Page/View/SubType/` directory.

For Example if you want to create custom templates for Contact SubTypes "Student" and "Sponsor". Copy standard template to your new tree as:

For Contact SubType "Student"

* Edit Screen

    ```bash
    $ cp /var/www/drupal/sites/all/modules/civicrm/templates/CRM/Contact/Form/Contact.tpl
               /var/www/drupal/sites/all/modules/civicrm/civicrm_custom/CRM/Contact/Form/Edit/SubType/Student.tpl
    ```

* View Screen

    ```bash
    $ cp /var/www/drupal/sites/all/modules/civicrm/templates/CRM/Contact/Page/View/Summary.tpl
               /var/www/drupal/sites/all/modules/civicrm/civicrm_custom/CRM/Contact/Page/View/SubType/Student.tpl
    ```

For Contact SubType "Sponsor"

* Edit Screen

    ```bash
    $ cp /var/www/drupal/sites/all/modules/civicrm/templates/CRM/Contact/Form/Contact.tpl
               /var/www/drupal/sites/all/modules/civicrm/civicrm_custom/CRM/Contact/Form/Edit/SubType/Sponsor.tpl
    ```

* View Screen

    ```bash
    $ cp /var/www/drupal/sites/all/modules/civicrm/templates/CRM/Contact/Page/View/Summary.tpl
               /var/www/drupal/sites/all/modules/civicrm/civicrm_custom/CRM/Contact/Page/View/SubType/Sponsor.tpl
    ```

Edit the file as needed and save.

Then clean-up the **compiled templates directory**, usually by
deleting all the directories under your templates_c directory. You
can also do the cleanup by [enabling CiviCRM debugging and running
the directory cleanup command](#){.unresolved}. (NOTE: You do NOT
need to delete the standard version of the template from your main
CiviCRM codebase. CiviCRM will look for a customized version first,
and use that one if found.)

You should see your modified version when you reload the CiviCRM screen.
    
If you need help with the templating language, check out the [Smarty Documentation](http://www.smarty.net/)

## Custom Profile / Contribution / Event Registration Screens

The process for customizing Profiles / Contribution / Event Registration
is the same as above EXCEPT that you have the flexibility to create
different screen "versions" for each of your configured Profile /
Contribution / Event Registration Pages. The structure for contribution
and event registration pages is similar to that of profile explained
here. You do this by creating an extra directory layer in your custom
directory tree that references the Profile's **ID**.


!!! tip
    If you want a custom version of the **Profile View** screen for a Profile whose ID is 2...and your basic install and custom directory setup are the same as shown above - then your custom template copy should be saved to:

        /var/www/civicrm_custom/CRM/Profile/Page/2/View.tpl


!!! tip
    If you want a custom version of the Profile Create/Edit screen for a Profile whose ID is 2...and your basic install and custom directory setup are the same as shown above - then your custom template copy should be saved to:

        /var/www/civicrm_custom/CRM/Profile/Form/2/Edit.tpl


Profile ID's are listed in the Administer CiviCRM » CiviCRM Profiles table (ID column).


!!! tip
    If you want a custom version of the **Register** screen for an **Event** whose ID is 2...and your basic install and custom directory setup are the same as shown above - then your custom template copy should be saved to:

        /var/www/civicrm_custom/CRM/Event/Form/Registration/2/Register.tpl

Customizing `MembershipBlock.tpl`.

If you are making customizations for specific contribution forms based on the ID, you will need to also customize the `Main.tpl` file to call your custom `MembershipBlock.tpl` file.

In `Main.tpl` in your custom template directory find:

```smarty
{include file="CRM/Contribute/Form/Contribution/MembershipBlock.tpl" context="makeContribution"}
```

and change it to:

```smarty
{include file="CRM/Contribute/Form/Contribution/1/MembershipBlock.tpl" context="makeContribution"}
```

insuring that you are using the correct path for your custom form id.

### Referencing Individual Fields in Profile Pages

Profile view and edit pages consist of several template files working
together. The View.tpl (detail view pages) and `Edit.tpl` (edit pages)
each reference a corresponding Dynamic.tpl file, which cycles through
the fields found in the profile and displays them in a table. The layout
is very basic -- one column is used for the field label, the other
column for the field value or form field. Often, when customizing
profile pages, you may want to reference specific fields and layout them
out in a customized display. To do so you would work with the
`View.tpl`/`Edit.tpl` files and insert smarty tokens for the profile fields
array. The profile fields array is structured as follows:

-   `$profileFields_ProfileID => Field Name => label/value`

So you would insert the following token into the template file to
reference the First Name label and field value in Profile 3:

-   `{$profileFields_3.first_name.label}: {$profileFields_3.first_name.value}`

Custom fields in your profile are referenced using the custom ID value,
e.g. `{$profileFields_3.custom_38.value}`.

To customize fields displayed within Drupal profiles, edit Dynamic.tpl
and use `$profileFields => Field Name => label/value` (e.g.
`{$profileFields.first_name.value}` - without the profile ID) to
reference individual profile fields.

!!! tip
    With debugging turned on, use the Smarty Debug url string to view the Smarty variables available for inclusion on any given page. Add `&smartyDebug=1` at the end of your page url.


## Changing Show/Hide Default on Collapsible Fieldsets

In the words of Dave Greenberg:

> This is a bit tricky because the
collapse vs. expand states are controlled via showBlocks and hideBlocks
arrays that are passed into a jscript function by the PHP code.

So I will show you how to do this using an example.

If you want to make a fieldset that is by default collapsible or hidden
on a CiviCRM Contact View screen, you would need to follow all the above
instructions to first get the correct custom template and put it in the
proper location. Let's assume you want to edit the
`CRM/Contact/Page/View/Tabbed.tpl` screen, which is the default contact
view. You would copy that into your custom template location. Then you
will need to determine the name of the fieldset that you want to change
the default state of. Let's say I want to collapse the "Communications
Preferences" fieldset by default. Look in the file for that section, so
in this case I find the line that has the following:

```html
<a href="#"
    onclick="hide('commPrefs_show'); show('commPrefs'); return false;">
  <img
      src="{$config->resourceBase}i/TreePlus.gif" 
      class="action-icon"
      alt="{ts}open section{/ts}"/>
</a>
<label>{ts}Communications Preferences{/ts}</label>
```

We can see that there's a javascript onclick call which references
`'commPrefs_show'` and `'commPrefs'`. So since this fieldset is by default
showing up, we need to force the javascript to reverse it. So next you
need to scroll to the bottom of this file and look for the following
javascript call:

```smarty
{literal}
 <script type="text/javascript">

   init_blocks = function( ) {
{/literal}
      var showBlocks = new Array({$showBlocks});
      var hideBlocks = new Array({$hideBlocks});
{literal}
      on_load_init_blocks( showBlocks, hideBlocks );
  }

  dojo.addOnLoad( init_blocks );
 </script>
{/literal}
```

The way we will fix this is to reverse the hide/show states of
Communication Preferences. We will add the following two lines after
`on_load_init_blocks(showBlocks, hideBlocks)`;

```javascript
hide('commPrefs');
show('commPrefs_show');
```

And that will fix it. Our final javascript call will look like this:

```smarty
{literal}
 <script type="text/javascript">

   init_blocks = function( ) {
{/literal}
      var showBlocks = new Array({$showBlocks});
      var hideBlocks = new Array({$hideBlocks});
{literal}
      on_load_init_blocks( showBlocks, hideBlocks );
      // Added next 2 lines to reverse the show and hide states for commPrefs blocks
      hide('commPrefs');
      show('commPrefs_show');
  }

  dojo.addOnLoad( init_blocks );
 </script>
{/literal}
```

So for any other fieldset, you can find the show/hide names by looking
for the fieldset section in the code as I demonstrated here. Just look
at the `<a>` tag for the onclick function.

## Using jQuery to hide contribution amounts, event fees, or membership options

It's pretty easy to do, but the key is identifying the unique 'id' of
the radio button of the item you wish to hide.

Use [Firebug](http://getfirebug.com) to click on the
radio button and copy and paste the id number. The ids are cryptic and
will look something like this example: `CIVICRM_QFID_369_2`

Then use some jQuery. You can put this jQuery in one of three places.
1. a unfiltered block that is visible on the page in question (Drupal only)
2. in your site's theme files
3. in a custom .tpl in a Custom Template Directory in CiviCRM as described on this page

Here is an example of the jQuery code.

!!! note
    there is no need to use `drupal_add_js()` or any other function.
    This is just pure jQuery code. Copy and paste, filling in the id of your
    field where indicated

This jQuery does 3 things: Hides the radio button itself, hides the
label, and hides the `<br>` tag immediately after the label. So
easy.

```smarty
{literal}
<script>
  cj("#YOUR_CIVICRM_QFID_999_9").hide();
  cj("label[for='YOUR_CIVICRM_QFID_999_9']").next('br').remove();
  cj("label[for='YOUR_CIVICRM_QFID_999_9']").hide();
</script>
{/literal}
```


If you are using option #3 above you can use this jQuery in conjunction
with `$user->roles;` array to hide certain options for certain roles
only.

## Move the placement of hook form elements

When defining CiviCRM fields in a Drupal module (such as [Civievent
Discount](http://drupal.org/project/civievent_discount)),
CiviCRM normally will place the fields at the top of the form. If you
want the placement changed, follow these steps.

1.  Set the path for custom templates in CiviCRM (see above).
2.  Make a custom version of `CRM/Form/body.tpl` at
    `custom_civicrm_templates/CRM/Form/body.tpl` (or wherever you place
    the custom templates).
3.  Cut everything between the

    ```smarty
    {if $beginHookFormElements}
    ```
        
    and

    ```smarty
    {/if}
    ```

1.  Create a new custom template at `CRM/common` called
    `hookFormElements.tpl`
2.  Insert the `$beginHookFormElements` text from `body.tpl`
3.  Create custom version of `CRM/Contribute/Form/Contribution/Main.tpl`
    (for memberships) and `CRM/Event/Form/Registration/Register.tpl`
    (for events).
4.  Place this snippet:

    ```smarty
    {include file="CRM/common/hookFormElements.tpl"}
    ```

    wherever you want it to appear in the template. Include wherever
    `body.tpl` is also referenced.

5.  Refresh the template files. Turn debugging on (**Administer >
    Configure > Global Settings > Debugging**) and then put this at
    end of URL: `&directoryCleanup=1`, press enter to refresh page.
