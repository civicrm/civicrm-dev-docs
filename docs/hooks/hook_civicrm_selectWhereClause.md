# hook_civicrm_selectWhereClause

## Summary

This hook is called when executing a SELECT query.

## Notes

The hook is called
once for each entity in the query, allowing you to add (or remove)
restrictions specific to that entity. Note that this hook will only be
invoked for API calls if check_permissions is set to 1. It will be
bypassed for API calls that do not set this parameter.

This hook is new in 4.7 and coverage is limited. The Case entity is
fully covered by this hook; selecting cases via api, ui, or searches
will all invoke this hook. Most other entities are covered when being
selected via api but not in the UI or searches.

This hook is part of a general permissions refactoring which is not yet
complete.

The Contact entity is fully covered
by [hook_civicrm_aclWhereClause](hook_civicrm_aclWhereClause.md)
and that is the recommended hook for limiting access to contacts. For
other entities, we need to increase coverage of this hook by using the
api internally instead of directly executing sql, and by standardizing
searches to use these permissions.

## Definition

    hook_civicrm_selectWhereClause($entity, &$clauses)

## Parameters

-   string $entity - name of entity being selected - follows api naming
    conventions (Contact, EntityTag, etc.)
-   array $clauses - (reference) array of clauses keyed by field\
     Uses the format array('field_name' => array('operator
    condition'))

## Returns

-   void

## Example

    function example_civicrm_selectWhereClause($entity, &$clauses) {
      // Restrict access to cases by type
      if ($entity == 'Case') {
        $clauses['case_type_id'][] = 'IN (1,2,3)';
      }
    }

If your condition depends on joining onto another table, use a subquery,
like so:

    function example_civicrm_selectWhereClause($entity, &$clauses) {
      // Restrict access to emails by contact type
      if ($entity == 'Email') {
        $clauses['contact_id'][] = "IN (SELECT id FROM civicrm_contact WHERE contact_type = 'Individual')";
      }
    }
