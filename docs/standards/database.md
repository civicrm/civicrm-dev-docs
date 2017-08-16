# Database layer

We are looking to agree some standards along the lines of the sample
below. The analysis that follows in is order to best define what they
should be

 We have agreed on the following standard that we will work towards



1\) Every BAO will have a create function. This will be called by the API
& form layer

2\) the create function will take a single params array

3\) Depending on the params passed in the create function will perform
any additional actions like creating activities

4\) The create function will call hooks.

5\) We are moving away from the $ids array being included..

6\) The add function if it exists will be internal to the BAO layer

7\) If any additional actions are to be done when deleting the BAO there
should be function del which takes the entity id as the only required
parameter

8\) The delete action will take any additional tasks like deleting
additional object (generally done by code)

9\) The delete action will take an array including \['id'\]

10\) The api will call the del action & fall back onto delete. It is
recommended the form layer call the api

BAO_xx-&gt;DAO_xx-&gt;CRM_DAO_Core-&gt;DB_DataObject-&gt;DB_DataObject_Overload-

Running some queries to find what methods are mostly used

ack-grep "function XXX\[ |\(\]" CRM -l -1 | ack-grep 'BAO' | wc -l

That's crude but gives an overview of the most common names

I still don't understand the diffrence between create and add, and why
some BAO have only one of the other and some have the two eg.
CRM/Core/BAO/Website.php

## Compliance

<div class="table-wrap">

+--------------------+--------------------+--------------------+--------------------+
|                    | Create             | Delete             | Recommended Action |
+====================+====================+====================+====================+
| Tag                | -   No create      | del($id) exists & | -   new create     |
|                    |     function       | returns True /     |     function which |
|                    |     exists         | FALSE              |     calls add      |
|                    | -   Add calls      |                    | -   move hooks to  |
|                    |     hooks          |                    |     create         |
|                    | -   Add enforces   |                    | -   Don't call     |
|                    |     'name' field   |                    |     dataexists if  |
|                    |     even when id   |                    |     id exists      |
|                    |     provided       |                    | -   ? come up with |
|                    | -   Field Check is |                    |     some std       |
|                    |     done in a      |                    |     variant of     |
|                    |     sepate         |                    |     dataexists     |
|                    |     function       |                    |                    |
|                    |     called         |                    |                    |
|                    |     dataexists     |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
| Activity           | -   Create exists  | No Del function    | -   Add Del /      |
|                    |     & meets        |                    |     delete         |
|                    |     API requiremen |                    |     function that  |
|                    | ts.                |                    |     wraps          |
|                    | -   Uses           |                    |     deleteactivity |
|                    |     dataexists     |                    |                    |
|                    |     function       |                    |                    |
|                    | -   ad hoc         |                    |                    |
|                    |     handling in    |                    |                    |
|                    |     create of      |                    |                    |
|                    |     datefields,    |                    |                    |
|                    |     empty id       |                    |                    |
|                    |     field, unique  |                    |                    |
|                    |     fields,        |                    |                    |
|                    |     setting        |                    |                    |
|                    |     defauls        |                    |                    |
|                    | -   API create     |                    |                    |
|                    |     function       |                    |                    |
|                    |     heavily crufty |                    |                    |
|                    |     & hard needs   |                    |                    |
|                    |     further        |                    |                    |
|                    |     rationalisatio |                    |                    |
|                    | n                  |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
| Address            | -   Create & Add   | No Del function    | ?add del           |
|                    |     both exist     |                    | function?-not      |
|                    | -   hooks called   |                    | resolved per above |
|                    |     in add         |                    |                    |
|                    | -   Create is      |                    | -   Second params  |
|                    |     actually a     |                    |     on fixaddress  |
|                    |     multiple       |                    |     to be optional |
|                    |     create         |                    |     & have a       |
|                    |     function -     |                    |     default        |
|                    |     expects        |                    | -   Create should  |
|                    |     address as a   |                    |     comply with    |
|                    |     key of params  |                    |     standard to be |
|                    | -   Add has a      |                    |     single or we   |
|                    |     second         |                    |     should change  |
|                    |     compulsory     |                    |     std to accom   |
|                    |     param          |                    |     multiple       |
|                    |     'fixaddress'   |                    | -   API should     |
|                    |     not quite      |                    |     call create    |
|                    |     clear what it  |                    |     function       |
|                    |     means          |                    |                    |
|                    | -   Dataexists     |                    |                    |
|                    |     function in    |                    |                    |
|                    |     place - not    |                    |                    |
|                    |     quite clear    |                    |                    |
|                    |     what it's      |                    |                    |
|                    |     doing as it    |                    |                    |
|                    |     has a long if  |                    |                    |
|                    |     else section   |                    |                    |
|                    |     around the     |                    |                    |
|                    |     keys           |                    |                    |
|                    | -   API is calling |                    |                    |
|                    |     add not create |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
| Campaign           | -   Std type       | -   Std del exists | 1.  Add hooks to   |
|                    |     create exists  | -   hooks not      |     create &       |
|                    | -   takes normal   |     called         |     delete?        |
|                    |     params         | -   api uses basic |                    |
|                    | -   Hooks not      |     delete         |                    |
|                    |     called         |                    |                    |
|                    | -   API uses basic |                    |                    |
|                    |     create         |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
| Case               | -   Create         | -   deleteCase     | -   create del     |
|                    |     function       |     exists rather  |     function?      |
|                    |     exists         |     than del       | -    need to       |
|                    | -   takes normal   | -   calls hooks    |     consider del   |
|                    |     params         | -   takes more     |     standard       |
|                    | -   calls hooks    |     than one       |     taking an      |
|                    | -   API function   |     parameter      |     array as one   |
|                    |     fairly         |                    |     param is not   |
|                    |     non-standard & |                    |     always enough  |
|                    |     complex        |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
| Membership Status  |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+
|                    |                    |                    |                    |
+--------------------+--------------------+--------------------+--------------------+

</div>



# 141 DAO, 114 "direct" BAO, 125 BAO

ack-grep "_DAO.*extends CRM_Core_DAO" CRM -l | wc -l

ack-grep "_BAO.*extends.*_DAO" CRM -l | wc -l

ack-grep "_BAO.*extends" CRM -l | wc -l

They are few DAOs without BAO. It will simplify the code and make it
easier if all the DAO are extended with a BAO

<span>(12:50:29) </span><span style="color: rgb(54,104,242);">eileen:
</span>CRM_Grant_BAO_Grant::del() seens to be a pattern that would be
pretty generic\
<span>(12:50:30) </span><span style="color: rgb(165,48,80);">DerekL:
</span>$return = array($apiresult\['id'\]);\
<span>(12:52:08) </span><span style="color: rgb(54,104,242);">eileen:
</span>DerekL - you need to add id indexing @ this line (equivalent)
_civicrm_api3_object_to_array($bao, $values\[ $bao-&gt;id\]);\
<span>(12:52:22) </span><span style="color: rgb(54,104,242);">eileen:
</span>ie. the \[$bao-&gt;id\]\
<span>(12:55:30) </span><span style="color: rgb(32,74,135);">xavier_d:
</span>eileen, I don't think it needs to do the -&gt;find first, I'd
rather delete and handle 0 deleted as an error (and eventually then do
-&gt;find to get a more detailed error message)\
<span>(12:55:56) </span><span style="color: rgb(32,74,135);">xavier_d:
</span>and while we are on code review, instead of always return false
I'd return if it's been deleted or not\
<span>(12:56:44) </span><span style="color: rgb(175,127,0);">eileen:
</span>xavier_d - yep - both good points\
<span>(12:56:46) </span><span style="color: rgb(32,74,135);">xavier_d:
</span>and not 100% sure about the Utils_Recent (ie. if you use it for
an import/batch update, that's going to flood your recent items)\
<span>(12:57:09) </span><span style="color: rgb(32,74,135);">xavier_d:
</span>Might be an option?\
<span>(12:57:23) </span><span style="color: rgb(32,74,135);">xavier_d:
</span>eg del ($id, $options)\
<span>(12:57:37) </span><span style="color: rgb(54,104,242);">eileen:
</span>Yes, imports do flood your recent - although it's kinda useful
actually\
<span>(12:57:51) </span><span style="color: rgb(32,74,135);">xavier_d:
</span>and $option is an array where we could expend and put more stuff
in if needed\
<span>(12:58:15) </span><span style="color: rgb(32,74,135);">xavier_d:
</span>ok, your final cut, don't care that much either way





## 58 retrieve

-   CRM/Price/BAO/LineItem.php
-   CRM/Price/BAO/Set.php
-   CRM/Price/BAO/FieldValue.php
-   CRM/Price/BAO/Field.php
-   CRM/Mailing/BAO/Component.php
-   CRM/Pledge/BAO/PledgePayment.php
-   CRM/Pledge/BAO/PledgeBlock.php
-   CRM/Pledge/BAO/Pledge.php
-   CRM/Report/BAO/Instance.php
-   CRM/Contribute/BAO/Premium.php
-   CRM/Contribute/BAO/ManagePremiums.php
-   CRM/Contribute/BAO/ContributionType.php
-   CRM/Contribute/BAO/Contribution.php
-   CRM/Event/BAO/Event.php
-   CRM/Event/BAO/ParticipantStatusType.php
-   CRM/Event/Cart/BAO/Cart.php
-   CRM/Case/BAO/Case.php
-   CRM/Member/BAO/MembershipStatus.php
-   CRM/Member/BAO/Membership.php
-   CRM/Member/BAO/MembershipType.php
-   CRM/Contact/BAO/RelationshipType.php
-   CRM/Contact/BAO/ContactType.php
-   CRM/Contact/BAO/Group.php
-   CRM/Contact/BAO/Contact/Utils.php
-   CRM/Contact/BAO/SavedSearch.php
-   CRM/Campaign/BAO/Survey.php
-   CRM/Campaign/BAO/Campaign.php
-   CRM/Activity/BAO/Activity.php
-   CRM/Core/BAO/LocationType.php
-   CRM/Core/BAO/Navigation.php
-   CRM/Core/BAO/OptionValue.php
-   CRM/Core/BAO/Job.php
-   CRM/Core/BAO/LabelFormat.php
-   CRM/Core/BAO/ActionSchedule.php
-   CRM/Core/BAO/CustomGroup.php
-   CRM/Core/BAO/PdfFormat.php
-   CRM/Core/BAO/MailSettings.php
-   CRM/Core/BAO/PaymentProcessor.php
-   CRM/Core/BAO/Persistent.php
-   CRM/Core/BAO/PaymentProcessorType.php
-   CRM/Core/BAO/Mapping.php
-   CRM/Core/BAO/Domain.php
-   CRM/Core/BAO/MessageTemplates.php
-   CRM/Core/BAO/OptionGroup.php
-   CRM/Core/BAO/ConfigSetting.php
-   CRM/Core/BAO/CustomOption.php
-   CRM/Core/BAO/UFGroup.php
-   CRM/Core/BAO/PrevNextCache.php
-   CRM/Core/BAO/UFField.php
-   CRM/Core/BAO/Tag.php
-   CRM/Core/BAO/CustomField.php
-   CRM/Core/BAO/PreferencesDate.php
-   CRM/Core/BAO/PaperSize.php
-   CRM/ACL/BAO/Cache.php
-   CRM/ACL/BAO/EntityRole.php
-   CRM/ACL/BAO/ACL.php
-   CRM/Friend/BAO/Friend.php
-   CRM/Grant/BAO/Grant.php



## del 39

-   CRM/Price/BAO/FieldValue.php
-   CRM/Mailing/BAO/Mailing.php
-   CRM/Pledge/BAO/PledgePayment.php
-   CRM/Contribute/BAO/Premium.php
-   CRM/Contribute/BAO/ManagePremiums.php
-   CRM/Contribute/BAO/ContributionType.php
-   CRM/Event/BAO/Event.php
-   CRM/Member/BAO/MembershipLog.php
-   CRM/Member/BAO/MembershipStatus.php
-   CRM/Member/BAO/MembershipType.php
-   CRM/Contact/BAO/RelationshipType.php
-   CRM/Contact/BAO/ContactType.php
-   CRM/Contact/BAO/Relationship.php
-   CRM/Campaign/BAO/Survey.php
-   CRM/Campaign/BAO/Campaign.php
-   CRM/Core/BAO/LocationType.php
-   CRM/Core/BAO/OptionValue.php
-   CRM/Core/BAO/Job.php
-   CRM/Core/BAO/LabelFormat.php
-   CRM/Core/BAO/ActionSchedule.php
-   CRM/Core/BAO/PdfFormat.php
-   CRM/Core/BAO/Discount.php
-   CRM/Core/BAO/PaymentProcessor.php
-   CRM/Core/BAO/PaymentProcessorType.php
-   CRM/Core/BAO/Mapping.php
-   CRM/Core/BAO/MessageTemplates.php
-   CRM/Core/BAO/OptionGroup.php
-   CRM/Core/BAO/CustomOption.php
-   CRM/Core/BAO/UFGroup.php
-   CRM/Core/BAO/Website.php
-   CRM/Core/BAO/Note.php
-   CRM/Core/BAO/UFField.php
-   CRM/Core/BAO/Tag.php
-   CRM/Core/BAO/PreferencesDate.php
-   CRM/Core/BAO/PaperSize.php
-   CRM/Core/BAO/EntityTag.php
-   CRM/ACL/BAO/EntityRole.php
-   CRM/ACL/BAO/ACL.php
-   CRM/Grant/BAO/Grant.php



## delete 6

-   CRM/PCP/BAO/PCP.php
-   CRM/Report/BAO/Instance.php
-   CRM/Event/Cart/BAO/EventInCart.php
-   CRM/Contact/BAO/GroupOrganization.php
-   CRM/Activity/BAO/Activity.php
-   CRM/Core/BAO/File.php

##  create 36

-   CRM/Price/BAO/LineItem.php
-   CRM/Price/BAO/Set.php
-   CRM/Price/BAO/FieldValue.php
-   CRM/Price/BAO/Field.php
-   CRM/Mailing/BAO/Mailing.php
-   CRM/Pledge/BAO/PledgePayment.php
-   CRM/Event/BAO/Event.php
-   CRM/Event/Cart/BAO/MerParticipant.php
-   CRM/Event/Cart/BAO/EventInCart.php
-   CRM/Event/Cart/BAO/Cart.php
-   CRM/Contact/BAO/GroupContact.php
-   CRM/Contact/BAO/Group.php
-   CRM/Contact/BAO/Relationship.php
-   CRM/Campaign/BAO/Survey.php
-   CRM/Campaign/BAO/Campaign.php
-   CRM/Activity/BAO/Activity.php
-   CRM/Activity/BAO/ActivityAssignment.php
-   CRM/Activity/BAO/ActivityTarget.php
-   CRM/Project/BAO/TaskStatus.php
-   CRM/Core/BAO/Block.php
-   CRM/Core/BAO/CustomGroup.php
-   CRM/Core/BAO/Address.php
-   CRM/Core/BAO/Domain.php
-   CRM/Core/BAO/Batch.php
-   CRM/Core/BAO/Website.php
-   CRM/Core/BAO/CustomField.php
-   CRM/Core/BAO/CMSUser.php
-   CRM/Core/BAO/ActionLog.php
-   CRM/Core/BAO/CustomValueTable.php
-   CRM/Core/BAO/EntityTag.php
-   CRM/Core/BAO/Location.php
-   CRM/Core/BAO/FinancialTrxn.php
-   CRM/ACL/BAO/EntityRole.php
-   CRM/ACL/BAO/ACL.php
-   CRM/Friend/BAO/Friend.php
-   CRM/Grant/BAO/Grant.php

## add 48





-   CRM/PCP/BAO/PCP.php
-   CRM/Mailing/BAO/Mailing.php
-   CRM/Mailing/BAO/Component.php
-   CRM/Pledge/BAO/PledgePayment.php
-   CRM/Pledge/BAO/PledgeBlock.php
-   CRM/Pledge/BAO/Pledge.php
-   CRM/Contribute/BAO/ManagePremiums.php
-   CRM/Contribute/BAO/ContributionType.php
-   CRM/Contribute/BAO/Contribution.php
-   CRM/Contribute/BAO/ContributionRecur.php
-   CRM/Event/BAO/Event.php
-   CRM/Event/BAO/ParticipantStatusType.php
-   CRM/Event/Cart/BAO/Cart.php
-   CRM/Case/BAO/Case.php
-   CRM/Member/BAO/MembershipLog.php
-   CRM/Member/BAO/MembershipStatus.php
-   CRM/Member/BAO/MembershipType.php
-   CRM/Contact/BAO/GroupNesting.php
-   CRM/Contact/BAO/GroupContactCache.php
-   CRM/Contact/BAO/RelationshipType.php
-   CRM/Contact/BAO/ContactType.php
-   CRM/Contact/BAO/GroupOrganization.php
-   CRM/Contact/BAO/GroupContact.php
-   CRM/Contact/BAO/Relationship.php
-   CRM/Contact/BAO/Contact.php
-   CRM/Core/BAO/Navigation.php
-   CRM/Core/BAO/OptionValue.php
-   CRM/Core/BAO/ActionSchedule.php
-   CRM/Core/BAO/MailSettings.php
-   CRM/Core/BAO/Discount.php
-   CRM/Core/BAO/Email.php
-   CRM/Core/BAO/Log.php
-   CRM/Core/BAO/Persistent.php
-   CRM/Core/BAO/Address.php
-   CRM/Core/BAO/Mapping.php
-   CRM/Core/BAO/IM.php
-   CRM/Core/BAO/MessageTemplates.php
-   CRM/Core/BAO/OptionGroup.php
-   CRM/Core/BAO/ConfigSetting.php
-   CRM/Core/BAO/UFGroup.php
-   CRM/Core/BAO/Website.php
-   CRM/Core/BAO/UFField.php
-   CRM/Core/BAO/Tag.php
-   CRM/Core/BAO/OpenID.php
-   CRM/Core/BAO/EntityTag.php
-   CRM/Core/BAO/Phone.php
-   CRM/Friend/BAO/Friend.php
-   CRM/Grant/BAO/Grant.php

ack-grep "function add\[ |\(\]" CRM

The signature is either like

CRM/Friend/BAO/Friend.php\
63:    static function add( &$params )

CRM/Core/BAO/UFGroup.php\
1151:    static function add( &$params, &$ids )

 or (single exeption)

CRM/Core/BAO/Address.php\
143:    static function add( &$params, $fixAddress )

CRM/Contact/BAO/Relationship.php\
191:    static function add ( &$params, &$ids, $contactId )

CRM/Contact/BAO/GroupNesting.php\
252:    static function add( $parentID, $childID ) {

CRM/PCP/BAO/PCP.php\
66:    static function add( &$params, $pcpBlock = true )
