# Access Control in CiviCRM

## Introduction

CiviCRM has a system of Access Control Lists (ACLs) which allow administrators to customise what information groups of their users are able to see. ACLs work alongside the system of permissions set out by CiviCRM which is integrated in the Content Management System's Permissions structure.

## Context

Access Control is used to control access to CiviCRM data and functionality. This is done through Access Control Lists (ACL's). An ACL consists of:

1. A Role that has permission to do this operation ('Administrator', 'Team Leader'),
2. An Operation (e.g. 'View' or 'Edit'), and
3. A set of Data that the operation can be performed on (e.g. a group of contacts)

Example: there may be a role called "Team Leaders" that can "Edit" all the contacts within the "Active Volunteers Group"

## Within Code

Much of the ACL control process happens within the `CRM/ACL/Api.php` file and `CRM/ACL/BAO/Acl.php` file. These files demonstrate how the ACL is used to add clauses to the WHERE statement of queries that are generated within CiviCRM. Many of these functions will be called from within the relevant CMS system files in `CRM/Utils/System/xxx.php` where xxx is the UF name of your CMS. e.g. Drupal 7 is Drupal, Drupal 8 is Drupal8 etc. These functions are usually checked at run time and are very low level.

## Extending ACLs

There are a few ACL hooks that allow developers in their extension to extend the implementation of various ACLs for their own purposes.

 - [`hook_civicrm_aclGroup`](../hooks/hook_civicrm_aclGroup.md) This hook alters what entities (e.g. CiviCRM Groups, CiviCRM Events) an end user is able to see.

 - [`hook_civicrm_aclWhereClause`](../hooks/hook_civicrm_aclWhereClause.md) This hook adds extra SQL statements when the ACL contact cache table is to be filled up. Depending on how frequently your ACL cache is cleared this may become taxing on your database.

 - [`hook_civicrm_selectWhereClause`](../hooks/hook_civicrm_selectWhereClause.md) This hook was introduced in 4.7 and allows you to add specific restrictions or remove restrictions when querying specific entities. This is different to `hook_civicrm_aclWhereClause` because that only deals with contacts and limiting of contacts and also `hook_civicrm_selectWhereClause` is run every time a select query for that entity is run.

It should be noted that especially with `hook_civicrm_selectWhereClause` there is little CiviCRM Core test coverage on these items so it is always very important that administrators test their own ACLs when testing any upgrade to CiviCRM.
