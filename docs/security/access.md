# Access Control in CiviCRM

## Introduction

CiviCRM has a system of ACLs which are done in the effort to allow end users to customise which groups of their users are able to see what contacts within the organisation. The Access Control Lists (ACL) also allows Administrators of sites. ACLs work alongside the system of permissions set out by CiviCRM which is intergrated in the Content Management System's Permissions strtucure. 

## Context

Access Control is used to control access to CiviCRM data and functionality. This is done through Access Control Lists (ACL's). An ACL consists of:

1. A Role that has permission to do this operation ('Administrator', 'Team Leader'),
2. An Operation (e.g. 'View' or 'Edit'), and
3. A set of Data that the operation can be performed on (e.g. a group of contacts)

Example there maybe a role called "Team Leaders" that can "Edit" all the contacts within the "Active Volunteers Group"

## Within Code

Much of the ACL control process happens within the `CRM/ACL/Api.php` file and `CRM/ACL/BAO/Acl.php` file. These files demonstrates how the ACL is used to add cluases to the WHERE statement of queries that are generated within CiviCRM. Many of these functions will be called from within the relevant CMS system files in `CRM/Utils/System/xxx.php` where xxx is the UF name of your CMS. e.g. Drupal 7 is Drupal, Drupal 8 is Drupal8 etc. These functions are usually checked at run time and are very low level down. 

## Extending ACLs

There are a few ACL hooks that allow developers in their extension to extend the implementation of various ACLs for their own purposes. 
 - `hook_civicrm_aclGroup` This hook aims to alter what entities e.g. CiviCRM Groups, CiviCRM Events etc that an end user is able to see [See hook documentation](/hooks/hook_civicrm_aclGroup.md) for more information on the hook. 
 - `hook_civicrm_aclWhereClause`, The purpose of this hook is as it is mentioned adds on extra SQL statements when the ACL contact cache table is to be filled up. Depending on how frequently your ACL cache is cleared this may become taxining on your database see also [hook documentation](/hooks/hook_civicrm_aclWhereClause.md). 
 - `hook_civicrm_selectWherClause` This hook is very new only introduced within 4.7. THe purpose of this is to allow you to add specific restrictions or remove restrictions when querying specific entities. This is differnt to `hook_civicrm_aclWhereClause` because that only deals with contacts and limiting of contacts and also `hook_civicrm_selectWhereClause` is run every time a select query for that entity is run see also [hook documentation](/hooks/hook_civicrm_selectWhereClause.md). 

It should be noted that especially with `hook_civicrm_selectWhereClause` there is not a whole lot of CiviCRM Core test coverage on these items so its always very important that end users test their own ACLs when testing any upgrade to CiviCRM.
