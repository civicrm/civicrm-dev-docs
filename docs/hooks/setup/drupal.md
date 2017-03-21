The Drupal documentation has great information about 
[hooks in general][drupal-hooks], 
[configuration to enable hooks for your module][hooks-config], 
and [this guide][hooks-intro] on starting out with hooks.

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

## Inspecting Hooks

The documentation about hooks can be somewhat abstract, and it sometimes
helps to see interactively how the hooks run.

-   If you use Drupal, then you can inspect some hooks by installing
    these two Drupal modules:
    -   [devel](http://drupal.org/project/devel)
    -   [civicrm\_developer](https://github.com/eileenmcnaughton/civicrm_developer)

[drupal-hooks]: https://www.drupal.org/docs/7/creating-custom-modules/understanding-the-hook-system-for-drupal-modules
[hooks-config]: https://www.drupal.org/docs/7/creating-custom-modules/telling-drupal-about-your-module
[hooks-intro]: https://www.drupal.org/docs/7/creating-custom-modules/writing-comments-and-implementing-your-first-hook