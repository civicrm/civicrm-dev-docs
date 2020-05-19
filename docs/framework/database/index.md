# CiviCRM Database Information

CiviCRM has a complex Database structure which this chapter will examine in more depth. CiviCRM for various reasons has the Schema structure of its database written out in XML files store in `xml/templates/schema` These files are used to define all Core CiviCRM tables that are installed when CiviCRM is installed. CiviCRM maintains a number of tools and scripts such as `GenCode`, `bin/setup.sh`, `bin/regen.sh`, the purpose of these tools is to update any current database with the lastest information stored in the XML files, Re-create the installation .mysql files that live in `sql/civicrm*.mysql` which are not committed to the Git Repo but found in the downloadable package. Also They update the "DAO" Files which contain a reference of what files and what relationships are there in the database.

## Tools

More documentation on tools to interact with the CiviCRM Database still to come.

### bin/regen.sh

If you are making a change that requires changes to the core schema, then in addition to any steps described at [XML Schema definition](schema-definition.md) you may need to also update the sql/civicrm_generated.mysql file which is used when installing CiviCRM with the option to include sample data.

If the change doesn't touch on other parts of the data or schema and doesn't affect any generated foreign key ids for example, you might be able to get away with just editing the file directly, which will avoid having to commit a lot of otherwise unrelated changes because the data in the file is semi-randomly generated. But if it is more complex or affects other parts of the file you need to use bin/regen.sh to regenerate it.

#### Setup

If you are using buildkit or have used setup.sh you may already be set up for it and may not need to do these steps.

  1. While you can run this on an existing install that has the latest master code, you may either want to set up a separate install or make a backup of your database since it will get wiped. **Note: if you have your CMS and CiviCRM in the same database the CMS will get wiped too**.
  1. Copy bin/setup.conf.txt to bin/setup.conf
  1. Edit bin/setup.conf as needed.
  1. If using Drupal, you will need drush installed and in your PATH.
  1. (Optional) If you don't have /path/to/civicrm/node_modules/karma/bin in your PATH, add it. It's optional because it will still work, but you'll avoid it trying to reinstall karma. It's not used here anyway.

#### Running

  1. Log out of your install if you are logged in.
  1. Run bin/regen.sh from within your /path/to/civicrm folder.

It's a little difficult to verify the results by comparing to the previous file because of the deliberate randomness in the data, but you can compare the overall structure. You can also log back in to your install and check it out.

## Useful coding structures

### Only full group by

When writing direct Database queries sometimes they can cause issues due to the sql mode `ONLY_FULL_GROUP_BY` which aims to ensure that all columns in the group by / order by are in the select query and aggregated properly. If a query is particularly problematic that SQL mode can be temporarily disabled by putting a call in to `CRM_Core_DAO::disableFullGroupByMode()` before the DB query function then `CRM_Core_DAO::reenableFullGroupByMode()` immediately after. It is recommended that the original query is fixed rather than deploying this workaround however the workaround maybe the only real solution.
