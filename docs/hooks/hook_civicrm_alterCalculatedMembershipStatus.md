# hook_civicrm_alterCalculatedMembershipStatus

## Summary

This hook is called when calculating the membership status.

## Notes

Examples of when this hook is called include:

  * on a form
  * in the cron job that rolls over statuses

## Definition

    hook_civicrm_alterCalculatedMembershipStatus(&$membershipStatus, $arguments, $membership)

## Parameters

   * $membershipStatus the calculated membership status array
   * $arguments arguments used in the calculation
   * $membership the membership array from the calling function

## Added

4.5., 4.4.10

## Notes

Although the membership array is passed through to the hooks no work has
gone into ensuring the consistency of the data in the membership array
so far - it was added to the parameters on the basis that it was easy to
think of a use case (e.g requiring approval) but not tested against a
current use case

## Examples

Extend Grace period to one year for membership types 14, 15 & 16

    /**

     * Implementation of hook_civicrm_alterCalculatedMembershipStatus

     * Set membership status according to membership type

     * @param array $membershipStatus the calculated membership status array

     * @param array $arguments arguments used in the calculation

     * @param array $membership the membership array from the calling function

     */

    function membershipstatus_civicrm_alterCalculatedMembershipStatus(&$membershipStatus, $arguments, $membership) {

      //currently we are hardcoding a rule for membership type ids 14, 15, & 16

      if(empty($arguments['membership_type_id']) || !in_array($arguments['membership_type_id'], array(14, 15, 16))) {

        return;

      }

      $statusDate = strtotime($arguments['status_date']);

      $endDate = strtotime($arguments['end_date']);

      $graceEndDate = strtotime('+ 12 months', $endDate);

      if($statusDate > $endDate && $statusDate <= $graceEndDate) {

        $membershipStatus['name'] = 'Grace';

        $membershipStatus['id'] = 8;

      }