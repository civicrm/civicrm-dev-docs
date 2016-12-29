# Writing Documentation 

[CiviCRM.org/documentation](https://civicrm.org/documentation) has a nice high-level list of all active documentation. 

## Guides in mkdocs 

We are using [mkdocs](http://www.mkdocs.org) to produce guides, and currently have the following two: 

* [User Guide](https://docs.civicrm.org/user/en/stable/)
* [Developer Guide](https://docs.civicrm.org/dev/en/master/) *(which you are reading now!)* 

The content for each of these guides is written in markdown, stored in text files, and hosted on GitHub. 

### How to edit 

1.  Obtain the source files for the guide you want to edit
    1. Find the repository on GitHub (e.g. here is [the repo for this guide](https://github.com/civicrm/civicrm-dev-docs))
    1. Fork and clone locally. (For more help with Git and GitHub see [Git](git))
1.  Install mkdocs on your machine.
    1.  For Ubuntu 
        
            sudo apt-get install python-pip python-wheel
            sudo pip install mkdocs

    1.  For other platforms, follow instructions on [mkdocs.org](http://www.mkdocs.org)

1. Launch a local copy of the guide 
    1. Run: 

            cd civicrm-dev-docs
            mkdocs serve
    
        * If you get `[Errno 98] Address already in use` then try something like 
        
        `mkdocs serve -a localhost:8001` 
    
    1. Go to `http://localhost:8000` to view
    
1. Edit, commit, push, submit pull request 
   

### Formatting 

See [Markdown](markdownrules) for formatting syntax within mkdocs. 


## Documentation in the wiki 

The [CiviCRM wiki](https://wiki.civicrm.org/confluence/display/CRMDOC/CiviCRM+Documentation) has lots of great info but is slowly falling out of use in favor of mkdocs. 


