# PHP Coding Standards

CiviCRM uses the [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards) as a basis for the coding standards used in civicrm-core code and in civicrm-drupal code.
The standards are version-independant and all new code should follow the current standards regardless of version. 

## Deviations from the Drupal Coding Standards

There are two deviations from the Drupal Coding standards that apply in CiviCRM.

<table><thead><td>Drupal Standard</td><td>CiviCRM Stanard</td><td>Rational for Change</td></thead>
<tbody>
<tr>
<td>"Functions and variables should be named using lowercase, and words should be separated with an underscore."</td>
<td>For existing code/files/functions, err on the side of preserving compatibility.

For new procedural code (eg api/v3/*.php), use lowercase and underscores.

For new object-oriented code:

1. For DAO properties, use underscores. (These correspond to the DB schema - which uses underscores.)
2. For everything else, use camelCase.
<a href="http://forum.civicrm.org/index.php/topic,35519.0.html">See Forum Discussion</a></td>
<td>The codebase includes many examples of both lowerCamelCase and under_scores for function and variable names. Changing these can be quite difficult and can break interfaces consumed by downstream.</td>
</tr>
<tr>
<td>Classes and interfaces in Drupal take one of two forms:
<ul>
  <li>(Common in Drupal 7) Place classes in the root/global namespace and use UpperCamel names (e.g. "FooBarWhiz")</li>
  </li>(Common in Drupal 8) Place classes in the "Drupal\" namespace using PHP 5.3 conventions (e.g. "Drupal\Foo\BarWhiz")</li>
</ul>
</td>
<td>Classes and interfaces in Civi take one of two forms:
<ul>
  <li>For the "CRM_" namespace, follow the PEAR convention (using underscores for pseudo-namespaces; e.g. "CRM_Foo_BarWhiz")</li>
  <li>For the "Civi\" namespace, follow the PHP 5.3 convention (using backslashes for namespaces; e.g. "Civi\Foo\BarWhiz")</li>
</ul>
</td>
<td>Changing these can be quite difficult and can break interfaces consumed by downstream. For more discussion of CRM_ and Civi\, see <a href="https://wiki.civicrm.org/confluence/display/CRMDOC/The+codebase">The Codebase</a>
</td>
</tr>
</tbody>
</table>

## Scope

The CiviCRM Coding Standard for PHP Code and Inline Documentation applies to all PHP code in the CiviCRM code base, except files under the following directories:
1. packages/
2. packages.orig/
3. tools/
4. joomla/
5. WordPress/

## Tools

If a developer creates a test site with buildkit they can use the CiviLint tool to check the code standards. See [Civilint Documentation](https://github.com/seamuslee001/civicrm-dev-docs/blob/coding_standards/docs/tools/civilint.md)

You can also Set up your IDE to lint your code as well. See instructions on [setting up your IDE](https://wiki.civicrm.org/confluence/display/CRMDOC/IDE+Settings+to+Meet+Coding+Standards)
