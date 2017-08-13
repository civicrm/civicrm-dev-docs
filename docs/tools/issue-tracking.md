# Issue-tracking systems

As of 2017 CiviCRM uses three different system for tracking and managing issues (aka tickets). This page offers a brief summary of the systems and helps developers understand when and how to use them.


## Systems

### Jira {:#jira}

**[issues.civicrm.org](https://issues.civicrm.org/jira)**

Used as an issue-tracking system for: 

* **[CiviCRM core](https://issues.civicrm.org/jira/browse/CRM)**
* [CiviVolunteer](https://issues.civicrm.org/jira/browse/VOL)
* [CiviHR](https://issues.civicrm.org/jira/browse/HR)
* *...and a small number of [other projects](https://issues.civicrm.org/jira/secure/BrowseProjects.jspa?selectedCategory=all&selectedProjectType=all)*

### GitHub {:#github}

**[github.com/civicrm](https://github.com/civicrm)**

Used as an issue-tracking system for:

* [buildkit](https://github.com/civicrm/civicrm-buildkit/issues)
* [cv](https://github.com/civicrm/cv/issues)
* [civix](https://github.com/totten/civix)
* *...and many [other projects](https://github.com/civicrm)*

!!! note
    Some projects (e.g. CiviCRM core) have their repository hosted on GitHub but do *not* use the GitHub issue-tracking functionality. For these projects you will notice there is no "Issues" tab. 

 
### GitLab {:#gitlab}

**[lab.civicrm.org](http://lab.civicrm.org)**

Used as an issue-tracking system for:

* [civicrm.org website issues](https://lab.civicrm.org/marketing-team/civicrm-website)
* [infrastructure issues](https://lab.civicrm.org/infrastructure/ops/issues)
* *...and some [other projects](https://lab.civicrm.org/explore/projects)*

In 2017, CiviCRM began to use a private GitLab installation for *some* projects.


## Guidelines

* All changes to CiviCRM *Core*, however small, must receive a Jira issue. This helps us assemble the [release notes](https://github.com/civicrm/civicrm-core/tree/master/release-notes).

