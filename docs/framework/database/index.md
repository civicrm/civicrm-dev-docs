# CiviCRM Database Information

CiviCRM has a complex Database structure which this chapter will examine in more depth. CiviCRM for various reasons has the Schema structure of its database written out in XML files store in `xml/templates/schema` These files are used to define all Core CiviCRM tables that are installed when CiviCRM is installed. CiviCRM maintains a number of tools and scripts such as `GenCode`, `bin/setup.sh`, `bin/regen.sh`, the purpose of these tools is to update any current database with the lastest information stored in the XML files, Re-create the installation .mysql files that live in `sql/civicrm*.mysql` which are not committed to the Git Repo but found in the downloadable package. Also They update the "DAO" Files which contain a reference of what files and what relationships are there in the database.

## Tools

Documentation on tools to interact with the CiviCRM Database still to come.
