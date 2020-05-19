# PHP Coding Standards

CiviCRM uses the [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards) as a basis for the coding standards used in civicrm-core code and in civicrm-drupal code.
The standards are version-independent and all new code should follow the current standards regardless of version. 

## Brief example

```php
/**
 * The example class demonstrates Drupal/Civi code convention.
 */
class CRM_Coding_Example implements CRM_Coding_ExampleInterface {

  /**
   * Increase the size of a file exponentially.
   *
   * @param string $file
   *   The full file path. (Ex: '/tmp/myfile.txt')
   * @param int $power
   *   The number of times to double the size.
   * @return bool
   *   Whether the operation succeeded.
   */
  public function expandFile($file, $power) {
    $keyValuePairs = array(
      'first' => 1,
      'second' => 2,
    );

    if ($power < 4) {
      echo "You got it, boss.\n";
    }
    elseif ($power < 10) {
      echo "Whoa, that's gonna be a big file!\n";
    }
    else {
      echo "Whoa, that's gonna be a really big file!\n";
    }

    for ($i = 0; $i < $power; $i++) {
      $oldContent = file_get_contents($file);
      if (file_put_contents($file, $oldContent . $oldContent) === FALSE) {
        return FALSE;
      }
    }

    return TRUE;
  }

}
```

For more details, see [the full series of example snippets](https://www.drupal.org/docs/develop/standards/coding-standards) from `drupal.org`.

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

**Rationale for Change**

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

**Rationale for Change**

Changing these can be quite difficult and can break interfaces consumed by downstream. For more discussion of `CRM_` and `Civi\`, see [The Codebase](../framework/filesystem.md).

## Localization

Any string that will be displayed to the user should be wrapped in `ts()` to translate the string:

```php
$string = ts("Hello, world!");
```

Translation strings can also include placeholders for variables:

```php
$string = ts("Membership for %1 has been updated. The membership End Date is %2.", array(
  1 => $userDisplayName,
  2 => $endDate,
));
```

For more information on translation, see [Translation for Developers](../translation/index.md).

## Scope

The CiviCRM Coding Standard for PHP Code and Inline Documentation applies to all PHP code in the CiviCRM code base, except files under the following directories:

1. `packages/`
1. `packages.orig/`
1. `tools/`
1. `joomla/`
1. `WordPress/`
