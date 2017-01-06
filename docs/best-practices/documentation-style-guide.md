# Documentation style guide

All CiviCRM guides *(like this Developer Guide)* are intended to provide
high-quality "finished" documentation about CiviCRM. This Style Guide page
documents the standards we wish to uphold to ensure all guides maintain this
high level of quality.

## Parts, chapters, sections

As with any text book or manual, we divide our guides into "parts", "chapters",
and "sections". In mkdocs, these blocks translate as follows:

-   "part" -- folder
-   "chapter" -- file (in markdown), also one web page with a given URL
-   "section" -- heading within the page

Keep the page hierarchy to this depth (i.e. do not put folders within other
folders).

Each chapter should start with a paragraph that explains what will be
covered in the chapter.

Don't use terms like "previous chapter", etc. because we may re-arrange
sections and we don't want or need to encourage people to read the book
from front to back.

## Tone and vocabulary

### Talking about technical stuff

We try and limit the content of the user and administrator guide to
things that you can do with the user interface of CiviCRM.

This means that we don't go into details steps about installation or
system administration tasks.  We do however let people know that there
are system administrator tasks out there (setting up an SSL certificate,
configuring CiviMail etc.) and point them in the right direction when
they want to know about those tasks.

### Referring to different users, contacts etc.

People who interact via the front end should be called end users /
contacts.

People who administer CiviCRM on a day to day basis can be called users
or, when you want to differentiate between end users and staff of an
organisation, you can say end users and site admins.

More techincal people are called hosting providers or system
administrators

We should avoid using the word CiviCRM core team.  Instead we can say
"CiviCRM" if we want to talk about something standard or official or
"the CiviCRM community" when we want to talk about the wider ecosystem
of users, developers, etc.

## Formatting conventions

### Describing the CiviCRM user interface

Menu selections, buttons, tabs (basically, things that the reader is
being told to click) should be in bold.

-   Navigate to **Administer > CiviEvent > Event Types** to review the
    default list of event types, shown in the following screenshot.
-   Modify event type labels by clicking **Edit** on any row.
-   Click **Add Event Type** to create a new category for your events.

Elements of the system and interface should be capitalized (e.g., the
Events component, the Template Title field).

It is also sometimes helpful for clarity when discussing concepts to use
capitalization to distinguish between a specific activity within Civi
and a generic activity (e.g., the Send Email activity versus sending an
email). However, sometimes it is too cumbersome or just plain weird to
capitalize every instance of a term even if it refers to a specific Civi
thing or technical definition (e.g., scheduled reminders, plain text).
Use your best judgment as to what serves the reader; trying to enforce
consistency in this arena will slow us down/drive us crazy.

Quotes should be avoided as much as possible; however, do use them when
they seem necessary for clarity (e.g., if you are talking about setting
or field labels that are long phrases).

You can divide the CiviCRM interface into CiviCRM admin/administration
pages and CiviCRM public/front-end pages.

### Referring to other sections and to other sources of information

An example of an internal reference as written in markdown is: Read more
about [defining memberships](../membership/defining-memberships).

Sample links to specific CiviCRM URL paths should use example.org as the
domain: [http://example.org/civicrm/mailing/queue&reset=1](http://example.org/civicrm/mailing/queue&reset=1)

As appropriate, use version-independent links when referencing wiki.
Version specific wiki page will have the version after CRMDOC, such
as [http://wiki.civicrm.org/confluence/display/CRMDOC43/CiviMail+Installation](http://wiki.civicrm.org/confluence/display/CRMDOC43/CiviMail+Installation).
Same link without the version number, such as
[http://wiki.civicrm.org/confluence/display/CRMDOC/CiviMail+Installation](http://wiki.civicrm.org/confluence/display/CRMDOC/CiviMail+Installation) will
always redirect to the most recent wiki version of the page.

### Headings

The first heading in a section should be Heading 1. All others should be
in H2 and H3, only where necessary.  If you find yourself wanting to use
H4, consider if it's truly necessary and whether the section should
instead be refactored.

Section titles and subheadings should be in sentence case (first word
capitalized), not headline case (each word capitalized). Chapter
headings (the big ones like Email, Contributions, etc) should be IN ALL
CAPS.

### Bullets and numbered lists

Bulleted lists should be used to convey short snippets of information.
Numbered lists should be used to describe specific steps in a process
that must be done in order.

Numbered lists should always be made up of full sentences (since they
are instructions) and thus should include terminal punctuation.

Bulleted lists may or may not need to be full sentences. Full sentence
bullets need terminal punctuation. Bullets that are not full sentences
should not have terminal punctuation. However, if any particular list
contains one or more full-sentence bullets, all bullets (even those that
are not full sentences) should have terminal punctuation.

Sections that are over 50% bullets look really bad and not like a book.
If you see a section which is 'all bullets', consider rewriting it
removing them.

### Images

Images should be in png format and a maximum of 690px wide. Please use
descriptive names for images.

Alternative Text  (ALT Tags) should be included for every image.

## The Best Practices/Workflows/Tasks/Use Cases section

*Audience*: Someone who is new to CiviCRM, potentially new to the
nonprofit sector, and not able to code their way out of a problem (i.e.,
they likely have no coding skills at all).

*Purpose*: To provide users with a quick introduction to CiviCRM's
features from the perspective of job responsibilities, so potential
clients can get a feel for how CiviCRM will help them fulfill their
roles. And someone new to the nonprofit sector can get a better sense of
how exactly, CiviCRM can help them grow and sustain relationships. Step
by step guides to common processes will also hopefully motivate people
that they can learn this new, complicated system.

*Section Focus*: Process required to complete one task. Tasks can
include set up event registration, enter an offline donation, send a
newsletter, etc. If each section can be read independent of any other,
then readers can find exactly what they want based on how much time they
have and what they need to learn for their job. Sections can probably
satisfy these goals in 200 words or less.

*Series*: Each task or section can be part of a series on fundraising,
event planning, membership management, etc.

*Expertise Level*: We probably want to start with beginning tasks.
Advanced tasks that require extra coding, complicated combinations of
modules, or burdensome data entry procedures for more personal
acknowledgment letters (I'm thinking of the soft credit field) might be
able to wait.

*Relationship to CiviCRM User and Administrator Guide*: Process oriented
sections end with a heading "Where to Learn More" and links to sections
in the User and Administrator Guide that explain more thoroughly how to
implement in CiviCRM the process just described.

*Example*: In case anyone feels that having them in printable PDF form
would make it easy to share and impress potential decision makers that
this is right product for their organization. [Quick Start Guide to
Fundraising - Offline
Donations.pdf](/confluence/download/attachments/65307021/Quick%20Start%20Guide%20to%20Fundraising%20-%20Offline%20Donations.pdf?version=1&modificationDate=1333845371000&api=v2)

## Spelling and punctuation

Both U.K. and U.S. English spellings are acceptable; we actually welcome
inconsistency around this. CiviCRM is an international project, so it's
mixing it up is a benefit.

Double quotes are preferred over single quotes.

### Frequently used terms

-   "Autoresponder" *(not auto-responder)*
-   "Deduplicate", "dedupe" *(not "de-duplicate" or "de-dupe")*
-   "Dropdown", "dropdown menu" *(not "drop-down")*
-   "Meetup" (noun)
-   "Set up" (verb), "set-up" (noun)
-   "Unsubscribe" *(not "un-subscribe")*

