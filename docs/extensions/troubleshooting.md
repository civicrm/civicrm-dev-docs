# Troubleshooting

If you are struggling, the best thing to do is reach out to the [CiviCRM community](../basics/community.md).

If you cannot find the answer in this guide or by searching in the [CiviCRM StackExchange site](http://civicrm.stackexchange.com/) then please [ask](http://civicrm.stackexchange.com/questions/ask). Asking questions on StackExchange not only helps you but may well help others who follow you.

That said, this is a small list of some of the commoner problems extension writers encounter.

## Extension not doing anything

<!-- TODO: arguably this list should be removed altogether?? -->

Q: I've created the files and edited them but I don't see the expected changes.

A: Did you install and enable your extension? (<site\>/civicrm/admin/extensions?reset=1)

## Civix error messages

Q: I get Error: "Cannot instantiate API client -- please set connection options in parameters.yml"

A: You might have missed the step about setting 'civicrm\_api3\_conf\_path' ([https://github.com/totten/civix/](https://github.com/totten/civix/)), or it didn't get set properly for some reason.

Q: I've tried to generate a page/report/search/upgrader/etc with civix but it's not working.

A: For all of the various types, you must first run [generate:module](civix.md#generate-module), and then \`cd\` into the folder (e.g. com.example.myextension) before running one of the other \`generate:\` commands.

## Out-of-date templates

Many of the generators in `civix` rely on helpers and stubs defined in `<mymodule>.php` or `<mymodule>.civix.php`. If you
run `civix generate:*` on an older extension and have trouble with the generated code, then review [UPGRADE.md](https://github.com/totten/civix/blob/master/UPGRADE.md)
for (a) general upgrade procedures and (b) a list of specific changes that could require manual attention.
