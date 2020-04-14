Civilint is a thin wrapper which calls jshint and PHP_CodeSniffer (with the coder ruleset).

Code-style tests ensure a consistent layout across all of the codebase, and they also identify some unsafe or confusing coding patterns. While working on a patch, you should run civilint to determine if the pending changes comply with style guides. 

Note that civilint may be invoked a few different ways:

```bash
# (no arguments) – Check style of any uncommitted changes.
civilint

# Check style of a specific file (or list of files).
civilint some/file.php

# Check your last commit
git diff --name-only HEAD~1 | civilint -

# Check for changes in your branch (compared to master)
git diff --name-only master | civilint -
```

See also:

- [CiviCRM Coding Standards](../standards/php.md)
- [CiviCRM Javascript Standards](../standards/javascript.md)
- [Drupal Coding Standards](https://www.drupal.org/docs/develop/standards/coding-standards)
- [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)
- [coder](https://github.com/civicrm/coder)
- [jshint](http://jshint.com/)
