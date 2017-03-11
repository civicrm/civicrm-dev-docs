## Using hooks with Drupal

The Drupal documentation has good information about 
[hooks in general](https://www.drupal.org/docs/7/creating-custom-modules/understanding-the-hook-system-for-drupal-modules)
and [configuration to enable hooks for your module](https://www.drupal.org/docs/7/creating-custom-modules/telling-drupal-about-your-module)

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

Additionally, if you are using Drupal and add a new hook to an existing module,
you will need to clear the cache for the hook to start operating. One way of
doing this is by visiting the page Admin > Build > Modules.

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