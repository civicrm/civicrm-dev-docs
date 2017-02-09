# hook_civicrm_permission

## Description

This hook is called to allow custom permissions to be defined.

Available starting in 4.3, with permission descriptions supported
starting in 4.6.  Version 4.6.0 [may cause
trouble](https://issues.civicrm.org/jira/browse/CRM-16230), even without
descriptions.

## Definition

    hook_civicrm_permission(&$permissions)

## \
 Parameters {#hook_civicrm_permission-Parameters}

-   $permissions: reference to an associative array of custom
    permissions that are implemented by extensions, modules and other
    custom code. This will be an empty array unless another
    implementation of hook_civicrm_permission adds items to it. Items
    may be added in one of two formats.

    -   Simple associative array in the format "permission string =>
        label".  Compatible with CiviCRM 4.3+.

            $prefix = ts('CiviCRM Grant Program') . ': '; // name of extension or module
            $permissions['edit grant programs'] = $prefix . ts('edit grant programs');
            $permissions['delete in Grant Program'] = $prefix . ts('delete grant program');

        \
         \

    -   A multidimensional array in the format "permission string =>
        array(label, description)".  Compatible with CiviCRM 4.6+.  The
        first array item is the label for the permission.  If a second
        array item is present, it will appear as a description beneath
        the permission.

            $prefix = ts('CiviCRM Grant Program') . ': '; // name of extension or module
            $permissions['edit grant programs'] = array(
              $prefix . ts('edit grant programs'),                     // label
              ts('Create or edit grant programs and their criteria'),  // description
            );
            $permissions['delete in Grant Program'] = array(
              $prefix . ts('delete grant program'),                    // if no description, just give an array with the label
            );

## Returns

-   null

## Conventions and Tips

In the examples, note the convention of using "delete in ComponentName"
as the delete permission name.

For edit permissions, it is conventional to use the plural, such as
"edit your items"; it is normal to use the singular for create
permissions, such as "create new item".

Like in Drupal 6's hook_perm, there is no automatic namespacing for
permissions, so one should adopt unique permission names.

Extension permissions do not currently work properly in Joomla (see
[CRM-12059](https://issues.civicrm.org/jira/browse/CRM-12059)).  CiviCRM
will recognize the permission, but you will have no way to grant it to
users.

## Example

The following is an excerpt from [the CiviCRM Monitoring for
Nagios](https://github.com/aghstrategies/com.aghstrategies.civimonitor/blob/bc1993fd07e2c730847e5fda6bf3958d41a51341/civimonitor.php#L132)
extension, including a check for the CiviCRM version in order to ensure
backwards compatibility while providing a description to versions that
support it.

    function civimonitor_civicrm_permission(&$permissions) {
      $version = CRM_Utils_System::version();
      if (version_compare($version, '4.6.1') >= 0) {
        $permissions += array(
          'access CiviMonitor' => array(
            ts('Access CiviMonitor', array('domain' => 'com.aghstrategies.civimonitor')),
            ts('Grants the necessary API permissions for a monitoring user without Administer CiviCRM', array('domain' => 'com.aghstrategies.civimonitor')),
          ),
        );
      }
      else {
        $permissions += array(
          'access CiviMonitor' => ts('Access CiviMonitor', array('domain' => 'com.aghstrategies.civimonitor')),
        );
      }
    }

See
[http://issues.civicrm.org/jira/browse/CRM-11946](http://issues.civicrm.org/jira/browse/CRM-11946)