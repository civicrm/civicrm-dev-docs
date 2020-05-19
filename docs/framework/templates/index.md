# Page Templates

!!! warning
    The content in this page was written at some unknown time prior to 2013. It needs updating and may contain inaccuracies. 

CiviCRM uses page template files to display all the pages. This makes it
possible for a person to customize any CiviCRM screen to suit a special
requirement or preference. The HTML for CiviCRM pages is not embedded in
PHP code, instead a template engine (Smarty) sends the correct page to
the web browser.

You should be familiar with HTML and CSS syntax to be comfortable
editing page templates.  Some page templates additionally make use of
JavaScript and an Ajax utility, JQuery.

## Changing page templates is the wrong choice when ...

1.  it is possible to make the needed changes by updating the
    CSS styles. For example, if a requirement is to hide or move some
    information or form fields on a screen, a CSS style for that HTML
    element can be changed to display: none, or position: absolute
    within the CSS file.
2.  there is a CiviCRM hook available that can control the page. For
    example, there is a hook that can modify the information on the
    Contact Summary screen
3.  there is no process in place to update the page templates after an
    upgrade to a new version of CiviCRM. Page templates are stored in a
    separate folder and are not touched during an upgrade, However new
    versions of CiviCRM often change which placeholder elements are
    available in various templates. Proper source control procedures are
    needed to simplify upgrades to new versions.

## Smarty templates introduction

CiviCRM uses a page template engine called Smarty.  This documentation
is focused on how Smarty is used within the CiviCRM environment.  Every
Smarty element is enclosed between braces like these: `{}`. All the
other text is going to be displayed directly as HTML in the rendered
page.

Each page template is stored in a file with the extension `.tpl`. The
PHP code assigns variables for content that needs to be displayed, and
then lets the template engine take care of presenting it.

The Smarty template engine always does this process :

1.  Load the contents of a `.tpl` file.
2.  Scan the `.tpl` file for placeholder elements.
3.  Replace each placeholder element with the corresponding
    variable value.
4.  Send the resulting HTML to the web browser.

These are the most commonly used Smarty template elements:

-   `{$Name}`: To display the value of a variable named "Name"
-   `{$row.Name}`: To display the value of the attribute Name in the
    object Row
-   `{foreach from=$rows item=row}...{/foreach}`: To loop though all the
    items of the Rows array
-   `{literal} JavaScript code{/literal}` to indicate to Smarty the `{}`
    aren't smarty elements but JavaScript code, enclose JavaScript
    between `{literal}`
-   `{ldelim}` and `{rdelim}` are alternative
    ways to generate `{` and `}`.  This is often useful if you have a simple
    JavaScript code that needs a lot of values from Smarty
    variables 
-   `{include file="CRM/path/to/template.tpl" param1=xxx}`: includes the
    result of the `template.tpl`. Some included files expect to have
    extra param (e.g., `param1`).

Please read the Smarty documentation for more information.

!!! tip
    To see what variables have been assigned to the template, enable debug (Administer > Configure > Global Settings > Debugging) and on any URL, add `&smartyDebug=1`. It opens a new browser window listing all the variables and values.
 
CiviCRM introduces some extra features to Smarty:

-   `{ts}Any text{/ts}`: It will display the translated text (if you don't
    use US English)
-   ``{crmURL p='civicrm/contact/view' q="reset=1&cid=`$row.source_contact_id`"}``.
    Generates the proper CiviCRM URL that works both on Joomla!
    and Drupal.
-   `{crmAPI}` Allows retrieval and display of extra data that is not
    assigned to the template already. Read about the CiviCRM API  for
    more information.

## How to find and modify the templates?

All the templates are under the folder `templates/CRM` in your CiviCRM
installation. Finding which template is used on a given page can be
difficult, but the easiest way to find out the answer is to 
[enable debugging](../../tools/debugging.md#changing-settings-in-the-ui) then 
view the source of the page from a web browser and search for `.tpl`.
For example, for the Contact Summary page, use the web browser to open the
Contact Summary page, then click "View Source" in the browser.  You
should find an HTML comment such as:

```html
<!-- .tpl file invoked: CRM/Contact/Page/View/Summary.tpl.   Call via form.tpl if we have a form in the page. -->
```

You can then view the file at
`templates/CRM/Contact/Page/View/Summary.tpl` to see how the HTML is
generated. If you want to modify the layout; for instance to reorder the
content, do **not** modify directly these files, as all the
modifications will be lost on the next upgrade of CiviCRM. The proper
way is to create a new folder outside of your CiviCRM folder, then
navigate to **Administer -> Configure -> Global settings ->
Directories** in the navigation menu, and set the complete path of the
folder that is going to contain your custom templates in the field
*Custom Templates*.

## Scenario: The Contact Summary screen needs to be changed

If you want to alter the Contact Summary page template for Acme
organization, perform these steps:

1.  Create the folder
    `/var/www/civi.custom/acme/templates/CRM/Contact/Page/View`
2.  Update the Custom Template field in the Global Settings directories
    to `/var/www/civi.custom/acme/templates.` You can use any directory.
    We found it easier to put the custom templates under
    `/var/www/civi.custom/yourorganisation`.
3.  copy `templates/Contact/Page/View/Summary.tpl` from your CiviCRM
    install to `/var/www/civi.custom/acme`

!!! tip
    Say you want to modify the template for a specific profile form, or a specific event. Instead of copying the Form template to its default place (`templates/CRM/Profile/Form/Edit.tpl`), you can create a subfolder with the ID of the profile and put the template file you want to change in the subfolder (`templates/CRM/Profile/Form/42/Edit.tpl` to modify only the form for ProfileID 42).

You might want to modify a template that isn't directly the page you
load, but added later via Ajax. For instance, perhaps you want to change
all the tabs beside the Content Summary (Activities, Groups, etc.). The
easiest way to do this is to install a development oriented plug-in to
your web browser. If using Mozilla Firefox, the Firebug plug-in is
indispensable.  Open the Firebug console (or equivalent in your
browser)  and click the tab. You will see what URL has been loaded for
the tab (e.g., for the notes tab:
`http://example.org/civicrm/contact/view/note?reset=1&snippet=1&cid=1`).
Open it in a new window or new tab of the web browser, and view the
source. It also contains a comment identifying the template used (`CRM/Contact/Page/View/Note.tpl`).

Keep in mind that when you modify a template, you might have a template
that doesn't work properly anymore after an upgrade of CiviCRM, because
the layout has changed or the name of variables assigned to the template
was modified. In our experience, the easiest is to use a source code
management system (SCM) to keep track  of the changes you have made.
Before doing any modification of the template you copied, add it to your
SCM, and obviously also commit the template after having modified it.
That way, you can easily generate a patch of your changes, and see how
to apply them to the latest version of the template.

## Semantically meaningful HTML attributes

To make it as easy as possible for you to style any element in the page
(e.g. put a yellow background on all the contacts of the subtype
"members"), or add Ajax (clicking on the status of the activity changes
it to complete), we strive to have a consistent and coherent schema for
class names and ids for the generated HTML. This makes it easier to
isolate the elements you want to alter from a custom style or from
JavaScript:

-   There is a class `crm-entityName` defining the type of the entity
    bubbled up as high as possible in the DOM. For instance, each line
    on a list of activity has `<tr class="crm-activity ...">`
-   There is an id `crm-entityName_entityID` allowing to find the id
    of the entity bubbled up. e.g., on a list of contacts, the
    contact number 42  has a `<tr id="crm-contact_42" ...>`
-   Each field or column contains a class identifying it, e.g.,
    `"crm-activity-subject"`
-   Each field or column that contains a value with a fixed set of
    possible values (e.g., a Status, a Role, a Contact Type) contains a
    class identifying it. It doesn't contain the human readable version
    (that can be changed), but the id or a name that can't be modified
    by the end-user; such as `class="crm-activity-status-id_42"`.
    This is on the top of the class identifying the field name, so the
    complete HTML is `<td class="crm-activity-status
    crm-activity-status-id_42">Hitchhiked</td>`.

At the time of the writing, some of the templates don't follow these
conventions. Please update them and submit a bug tracking issue with a
patch if you need to use a template that isn't yet complying. For more
information about submitting a bug or issue, read about the CiviCRM
community.

## Displaying more content and adding Ajax features

If your modifications go further than "simple" modifications of the
layout, but need to display more content than the one assigned to the
template by default, or to add Ajax functionality, use the CiviCRM API.
Please read more information about using the CiviCRM API from Ajax to
pursue this approach.

In most cases, using the CiviCRM APIs should be simple and only takes a
few extra lines of modifications.

## Appending jQuery or other code to a template

You can also append jQuery functions, Smarty code, HTML (really
anything) to any template without having to create a customized copy of
the entire file. All you need to do is put your "extra stuff" in a new
file and save it as `template_to_append_to.extra.tpl`.

**EXAMPLE**: You want to add some jQuery to hide a few of the fields in
the Contact Edit form - Contact.tpl.

1. Configure your Custom Templates directory
2. Create the directory structure in your custom templates directory (`CRM/Contact/Form`)
3. Write your jQuery script and save it in a file called `Contact.extra.tpl`

Voila - your jQuery script will be automatically appended to the
standard Contact.tpl template when that form is loaded.

One heads up … if your file contains Javascript (as it probably will),
you will need to start your "extra" custom file with the Smarty
`{literal}` tag and the `<script>` tag. Then use
`{/literal}{$variable}{literal}` if you need to use any Smarty code or
variables. Finally, end the file with `</script>{/literal}`.

For customizations where you just need to add a script, this approach is
preferable to creating a custom copy of the entire template as it should
minimize the need for review and merge of changes during upgrades.

## Some useful variables and examples

On each page template, you have extra Smarty variables populated by
CiviCRM.

* `{$config}` Contains a lot of useful information about your
environment (including the URL, if it's Drupal or Joomla!, etc.)
* `{$session}` Contains information about the user.

If you want to modify the template only for a logged-in user but leave
it identical for anonymous users, do the following:

```smarty
{if $session->get('userID') > 0} Insert your modifications here {/if}
```

## Modify templates without using jQuery/javascript

You can insert php code directly into smarty templates, using the `{php}`
tag. Note that this is deprecated as of Smarty 3.

```smarty
{php}
//sets the page title
CRM_Utils_System::setTitle('Thank You', 'Thank You');

//gets the variable 'form' from smarty and puts it in your php scope so you can play with it
$form = $this->get_template_vars('form');

//assigns $form back to smarty so you can use it in your template file, outside the {php} scope
$this->assign('form',$form); 
{/php}
```

You can also do various modifications on elements on the page using
smarty-native or Civi-specific functions:

```smarty
{* Prefill the input element with the user's email and assign the whole thing to $fieldWithValue. *}
{assign var="fieldWithValue" value=$form.email_confirm.html|crmInsert:value:$email}

{* Make the field wider *}
{$fieldWithValue|crmInsert:size:40}

{* Put the result of the include in $modifiedButtons instead of rendering it *}
{include file="CRM/common/formButtons.tpl" location="bottom" assign="modifiedButtons"}

{* Now change the text of one of the buttons from 'Opt Out' to 'Unsubscribe' *}
{$modifiedButtons|replace:'value="Opt Out"':'value="Unsubscribe"'}
```

You can find more such functions in the smarty documentation. And you
can find other useful Civi-specific functions
[here](https://github.com/civicrm/civicrm-core/tree/4.6/CRM/Core/Smarty/plugins).

One additional tool you can use is the Word Replacements tool in Civi's
backend. It's available under **Administer > Customize Data and Screens > Word Replacements**.
