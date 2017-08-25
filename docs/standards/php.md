# PHP Coding Standards

CiviCRM uses the [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards) as a basis for the coding standards used in civicrm-core code and in civicrm-drupal code.
The standards are version-independent and all new code should follow the current standards regardless of version. 

## Deviations from the Drupal Coding Standards {:#vs-drupal}

There are two deviations from the Drupal Coding standards that apply in CiviCRM.

### Functions and variable names

**Drupal Standard**

> Functions and variables should be named using lowercase, and words should be separated with an underscore.

**CiviCRM Standard**

For existing code/files/functions, err on the side of preserving compatibility.

For new procedural code (eg `api/v3/*.php`), use lowercase and underscores.
 
For new object-oriented code:

1. For DAO properties, use underscores. (These correspond to the DB schema - which uses underscores.)
2. For everything else, use camelCase. [See Forum Discussion](http://forum.civicrm.org/index.php/topic,35519.0.html)

**Rational for Change**

The codebase includes many examples of both "lowerCamelCase" and "snake_case" for function and variable names. Changing these can be quite difficult and can break interfaces consumed by downstream.


### Classes and interfaces

**Drupal Standard**

> Classes and interfaces in Drupal take one of two forms:
> 
> * (Common in Drupal 7) Place classes in the root/global namespace and use "UpperCamel" names (e.g. `FooBarWhiz`)
> * (Common in Drupal 8) Place classes in the "Drupal\" namespace using PHP 5.3 conventions (e.g. `Drupal\Foo\BarWhiz`)


**CiviCRM Standard**

Classes and interfaces in Civi take one of two forms:

* For the `CRM_` namespace, follow the PEAR convention (using underscores for pseudo-namespaces; e.g. `CRM_Foo_BarWhiz`).
* For the `Civi\` namespace, follow the PHP 5.3 convention (using backslashes for namespaces; e.g. `Civi\Foo\BarWhiz`).

**Rational for Change**

Changing these can be quite difficult and can break interfaces consumed by downstream. For more discussion of `CRM_` and `Civi\`, see [The Codebase](/framework/filesystem.md).


## Scope

The CiviCRM Coding Standard for PHP Code and Inline Documentation applies to all PHP code in the CiviCRM code base, except files under the following directories:

1. `packages/`
1. `packages.orig/`
1. `tools/`
1. `joomla/`
1. `WordPress/`
