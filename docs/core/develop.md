# Developing CiviCRM

## Repositories

CiviCRM is divided into a few repositories. This allows developers to work
with different components, allows different teams to manage each component,
and will allow all the pieces to be remixed using different tools (civibuild,
shell scripts, Drush & Drush make, or composer). The repositories are:

-   [civicrm-core](https://github.com/civicrm/civicrm-core/) -
    Core application which can be embedded in different systems
    (Drupal, Joomla, etc).
-   [civicrm-drupal](https://github.com/civicrm/civicrm-drupal/) -
    Drupal integration modules, with branches for each CiviCRM release &
    Drupal major version (e.g. 7.x-4.6, 7.x-4.7, 6.x-4.4, 6.x-4.6).
-   [civicrm-joomla](https://github.com/civicrm/civicrm-joomla/) -
    Joomla integration modules.
-   [civicrm-wordpress](https://github.com/civicrm/civicrm-wordpress/) -
    WordPress integration modules.
-   [civicrm-backdrop](https://github.com/civicrm/civicrm-backdrop/) -
    Backdrop integration module.
-   [civicrm-packages](https://github.com/civicrm/civicrm-packages/) -
    External dependencies required by CiviCRM.
-   [civicrm-l10n](https://github.com/civicrm/civicrm-l10n/) -
    Localization data.

## Obtaining a development build of CiviCRM

The recommended method is to use
[CiviCRM Buildkit](https://github.com/civicrm/civicrm-buildkit/) to build a
CiviCRM codebase to develop with.
