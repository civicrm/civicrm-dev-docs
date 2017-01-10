# Extension files

The [civix](./civix) command line tool will generate the following structure
for you:

-   ***info.xml*** is a manifest that describes your extension – the
    name, license, version number, etc. You should edit most information
    in this file.
-   ***myextension.php*** stores source code for all your hooks. It
    includes a few default hook implementations which will make
    development easier. You can add and remove hooks as you wish. (Note:
    This file name is different in each module – it is based the
    module's *[short-name](./basics/#extension-names)*.)
-   ***myextension.civix.php*** contains auto-generated helper
    functions. These deal with common problems like registering your
    module in the template include-path. `civix` may automatically
    overwrite this file, so generally do not edit it.

In addition, it creates some empty directories. These directories are
reminiscent of the directory structure in CiviCRM core:

-   ***CRM/Myextension/*** stores PHP class files. Classes in this
    folder should be prefixed with "CRM\_Myextension\_"
-   ***templates/*** stores Smarty templates
-   ***xml/*** stores XML configuration files (such as URL routes)
-   ***build/*** stores exportable .zip files
