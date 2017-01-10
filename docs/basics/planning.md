# Planning your project

Before you start on any code to extend CiviCRM, it is really important
to discuss your ideas with the community. Here are a few of the reasons
why this is a good idea:

-   It may have been done already
-   You'll get suggestions and advice on suitable ways to approach the
    problem
-   Other people will know what you are doing, and be able to contact
    you if they want to collaborate

A typical pre-development workflow will start with a discussion on the
[forum](http://forum.civicrm.org/index.php/board,20.0.html) about what
you want to do. Here you'll receive feedback from other members of the
community and core team about your ideas. You might be lucky and find
out that there is a already a way to do what you want using the user
interface (and that no coding is necessary). Or it might be that someone
has done something similar already and all that is required is a few
tweaks to their code.

If and when you have confirmed that some coding is required, it is good
practice, even for relatively small projects, to write

-   a requirements document which describes in detail what you want the
    finished code to do
-   a specification that outlines how you are going to meet these
    requirements with CiviCRM

The requirements are typically written to be understandable to end
users, and the specification can be thought of as a translation of those
requirements into the language of CiviCRM. Both requirements and
specification should go on the
[wiki](http://wiki.civicrm.org/confluence/display/CRM/CiviCRM+Wiki).

Once you've written the requirements and specification document, you
should go about soliciting feedback.  Get feedback on the requirements
from your end users and feedback on the requirements and the
specification from anyone that is willing to comment. To encourage more
discussion, you can write a post on CiviCRM's blog, tweet it out with
the \#civicrm hashtag, tell similar CiviCRM users and organisations and
so on.  The more feedback you can get the better.

If you get stuck writing your requirements and specification, or would
like to get more background, have a look at some requirements and
specifications written by others - there are plenty on the wiki.

## Make it happen

If you need or would like extra resources for your code improvement, you
should consider a 'make it happen' (MIH for short).

Make it happen is a crowd-sourcing initiative for CiviCRM,
which incidentally, is built using CiviCRM.  Around 15 MIH's were
selected for the 4.0 release, and more Make it Happens are likely to be
selected for future releases.  MIH work is carried out by the core team
or trusted consultants.  You can see a list of current MIH online at
[http://civicrm.org/mih](http://civicrm.org/mih).  If you think your
project would make a good MIH, discuss it with the core team.


# Recommendations



-   Use Git and Github for revision control.  The official CiviCRM
    repositories are housed on Github.  If you use Github you will find
    it easy to access the latest source-code, to submit pull requests
    for any patches you create and to collaborate with many other
    CiviCRM developers who also use Github.  See [Contributing to
    CiviCRM using
    GitHub](/confluence/display/CRMDOC/Contributing+to+CiviCRM+using+GitHub)
    for more details.\
     \
-   Whenever possible package the feature you are developing as a native
    extension created using Civix, the CiviCRM extension builder.  A
    native extension is easy to install on all you own sites and easy to
    share with the CiviCRM community.  Civix is a command-line tool
    which generates the boilerplate code required for some common
    development tasks. Instructions for installing Civix can be found at
    [https://github.com/totten/civix/](https://github.com/totten/civix/).\
     \
-   Use the CiviCRM API to access and manage CiviCRM data in any patch,
    native extension, CMS module, or external program that you
    develop.   The API will function as expected with every new release
    and backwards compatibility of the API is maintained for several
    versions of CiviCRM.  See [Using the
    API](/confluence/display/CRMDOC/Using+the+API).\
     \
-   Follow the CiviCRM [Coding
    Standards](/confluence/display/CRMDOC/Coding+Standards). If everyone
    follows the coding standards then all development work will be
    easier as the structure will be more uniform.
