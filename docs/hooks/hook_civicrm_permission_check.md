# hook_civicrm_permission_check

## Summary

This hook is called to dynamically alter permissions based on
conditions or external criteria.

## Notes

See
[https://issues.civicrm.org/jira/browse/CRM-19256](https://issues.civicrm.org/jira/browse/CRM-19256)
for some use cases.

Available in ~~4.6.21 / 4.7.11~~ (check JIRA issue to see fix version)
and above.

## Definition

    hook_civicrm_permission_check($permission, &$granted)

## Parameters {:#hook_civicrm_permission_check-Parameters}

-   $permission: a string representing the name of an atomic
    permission, ie. 'access deleted contacts'

-   $granted: a boolean reflecting whether this permission is currently
    granted. Change this value to alter the permission.

## Returns

-   null

## Limitations

-   This hook is implemented for forms (eg Add, Edit) but not yet for
    reports and viewing of objects like Activities on the Contact
    Summary page as of 4.7.17

## Examples

The following is an excerpt from [the CiviCRM Multisite
extension](https://github.com/eileenmcnaughton/org.civicrm.multisite)
extension. If the extension is enabled but the current domain does not
enforce multisite ACLs then the 'view all contacts' permission is
determined based on the value of the 'view all contacts in domain'
permission.

    function multisite_civicrm_permission_check($permission, &$granted) {
      $isEnabled = civicrm_api('setting', 'getvalue', array(
          'version' => 3,
          'name' => 'is_enabled',
          'group' => 'Multi Site Preferences')
      );
      if ($isEnabled == 0) {
        // Multisite ACLs are not enabled, so 'view all contacts in domain' cascades to 'view all contacts'
        // and the same is true for 'edit all contacts' - cf. CRM-19256
        if ($permission == 'view all contacts' && CRM_Core_Permission::check('view all contacts in domain')) {
          $granted = TRUE;
        } elseif ($permission == 'edit all contacts' && CRM_Core_Permission::check('edit all contacts in domain')) {
          $granted = TRUE;
        }
      }
    }

The following example modifies permission access for the event
participant listing page. The participant listing page is generally
exposed on the front of the site, and has a permission attached to it.
But that permission is universal – if a user is granted access via that
permission, they can view participant listings for all events that are
configured with that feature. In the example below, we disable that
permission by default, and when that page is accessed, determine if the
logged in user should be granted access based on other criteria. For the
sake of simplicity the purpose of the helper functions are explained but
not detailed in full.

    function eventmgmt_civicrm_permission_check($permission, &$granted) {
      if (_eventmgmt_isParticipantListing()) {
        if ($permission == 'view event participants' &&
          _eventmgmt_isEventMgr()
        ) {
          $granted = true;
        }
      }
    }

    function _eventmgmt_isParticipantListing() {
      //determine if the page being accessed is a participant listing page
    }

    function _eventmgmt_isEventMgr() {
      //determine if the logged in user should be granted access to this particular event's participant listing page (i.e. they are an "event manager")
    }
