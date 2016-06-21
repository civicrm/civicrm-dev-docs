# Deprecated developer instructions

These instructions are for historical reference only, but may be of use if the newer processes don't suit your working environment.

## Deprecated: Manual checkout from Github

Steps:

* Perform a standard CiviCRM install from tarball
* Use the "gitify" command to replace the codebase with the latest code from git.

Obtain the existing CiviCRM directory (such as `/var/www/drupal/sites/all/modules/civicrm` or `/home/myuser/src/civicrm`), then run the `gitify` command. You will need to adapt the command arguments, but a typical case would be:

    cd /tmp
    wget https://github.com/civicrm/civicrm-core/raw/master/bin/gitify
    bash gitify Drupal git://github.com/civicrm /var/www/drupal/sites/all/modules/civicrm --hooks

If you develop for multi-CMS, then you might have one copy of CiviCRM (e.g. `/home/myuser/src/civicrm`) shared by each CMS. You can use `gitify` to setup this directory, and then use symlinks to share among CMSs:

    ## Create ~/src/civicrm
    mkdir -p ~/src/civicrm
    wget https://github.com/civicrm/civicrm-core/raw/master/bin/gitify
    bash gitify all git://github.com/civicrm ~/src/civicrm --l10n --hooks

    ## Replace an old symlink with new symlink
    rm /var/www/drupal7/sites/all/modules/civicrm
    ln -s ~/src/civicrm /var/www/drupal7/sites/all/modules/civicrm

## References

* [Wiki: GitHub For CiviCRM](https://wiki.civicrm.org/confluence/display/CRMDOC43/GitHub+for+CiviCRM)
