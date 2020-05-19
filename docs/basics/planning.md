# Planning your project

## Community input

Before you start on any code to extend CiviCRM, it is really important to discuss your ideas with the community. Here are a few of the reasons why this is a good idea:

-   It may have been done already.
-   You'll get suggestions and advice on suitable ways to approach the problem.
-   Other people will know what you are doing, and be able to contact you if they want to collaborate.

A typical pre-development workflow will start with a discussion on [Mattermost](https://chat.civicrm.org/) (in the Developer channel) about what you want to do. Here you'll receive feedback from other members of the community and core team about your ideas. You might be lucky and find out that there is a already a way to do what you want using the user interface (and that no coding is necessary). Or it might be that someone has done something similar already and all that is required is a few tweaks to their code.

## Requirements and specifications

If and when you have confirmed that some coding is required, it is good practice, even for relatively small projects, to write:

-   a requirements document which describes in detail what you want the finished code to do, and
-   a specification that outlines how you are going to meet these requirements with CiviCRM.

The requirements are typically written to be understandable to end users, and the specification can be thought of as a translation of those requirements into the language of CiviCRM. 

Store your requirements and specifications in a public location such as:

* a markdown file in your project's repository,
* a google doc, or 
* the [CiviCRM wiki](http://wiki.civicrm.org/confluence/display/CRM/CiviCRM+Wiki).

Once you've written the requirements and specification document, you should go about soliciting feedback.  Get feedback on the requirements from your end users and feedback on the requirements and the specification from anyone that is willing to comment. To encourage more discussion, you can write a post on CiviCRM's [blog](https://civicrm.org/blog/), tweet it out with the [#civicrm](https://twitter.com/hashtag/civicrm) hashtag, tell similar CiviCRM users and organisations and so on. The more feedback you can get the better.

!!!tip
    If you get stuck writing your requirements and specification, or would like to get more background, have a look at some [existing requirements and specifications](https://wiki.civicrm.org/confluence/display/CRM/Requirements+and+specifications) from CiviCRM developers.

## Bugs

Before starting work on a bug, your first step should be to check the [issue tracking systems](../tools/issue-tracking.md) for any open issues before creating one yourself or working on a pull request.

In order to reproduce the bug you can reproduce the issue in the appropriate [CiviCRM Sandbox](https://civicrm.org/sandboxes). Enabling debugging can help to get more details.

Make sure to check [the contribution guidelines](../core/contributing.md) for more information on how to create a pull request.

## Recommendations

**Open a free [GitHub](https://github.com/) account** for version control. The official CiviCRM [repositories](https://github.com/civicrm) are housed in `git` repositories on GitHub.  If you use GitHub you will find it easy to access the latest source-code, to submit pull requests for any patches you create and to collaborate with many other CiviCRM developers who also use GitHub.

**Install the [buildkit](https://github.com/civicrm/civicrm-buildkit)**, ideally as a [vagrant virtual-machine](https://github.com/civicrm/civicrm-buildkit-vagrant) or using one of available `docker` images ([progressivetech](https://github.com/progressivetech/docker-civicrm-buildkit) or [EricBSchulz](https://github.com/ErichBSchulz/dcbk)). The buildkit is not an absolute requirement but it is definitely the fastest path to a good development experience!

**From the outset, [automate testing](../testing/index.md)**. In the current climate of rapid evolution of not just CiviCRM, but also it's myriad of dependencies, automated testing of PHP code with `phpunit` and javascript with tools like `karma` and `jasmine` is essential. Start all your work by considering how you will provide automated testing for it. Starting with the buildkit will make this much simpler for you to set up. Getting started with unit-testing may seem daunting and onerous when you start, but you will soon come to love the freedom it gives you. If you are unsure how to proceed with testing ask the [community](community.md).

**Create a native [extension](../extensions/index.md)**. If you have new functionality to add to CiviCRM, it probably belongs in an extension.  "Native" extensions will install into all CiviCRM sites regardless of the  underlying CMS used (Drupal, WordPress, Joomla or Backdrop), making it easy to share your extension with the CiviCRM community.

**Use the [API](../api/index.md) and [hooks](../hooks/index.md)** to access and manage CiviCRM data in any patch, native extension, CMS module, or external program that you develop. The API will function as expected with every new release and backwards compatibility of the API is maintained for several versions of CiviCRM. 

**Avoid [hacking the core](../core/hacking.md)** of CiviCRM unless you understand the implications.

**Follow the [Coding Standards](../standards/index.md)** for uniform structure that will make everyone's development work easier.

## Make it happen

If you need or would like extra resources for your code improvement, you might consider a [Make It Happen](https://civicrm.org/make-it-happen) (aka MIH) campaign, which is a crowd-sourcing initiative for CiviCRM. The MIH work is carried out by the core team or trusted consultants, and has helped build many amazing new features into CiviCRM in recent years.

