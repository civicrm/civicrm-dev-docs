# Review/Release Process

Releases are developed on a monthly cycle, with all but urgent bug and security fixes appearing in versions that are released the first Wednesday of each month.  These iterate the second part of the version number.  For example, 5.23.0 was released on Wednesday, March 4, 2020, 5.24.0 was released on Wednesday, April 1, 2020, and 5.25.0 was released on Wednesday, May 6, 2020.

There is no active plan to iterate the first part of the version number.  The 4.7.x series generally followed the same monthly pattern as above except that the third part of the version number was iterated each month.  CiviCRM 5.0.0 followed 4.7.31 in April 2018, and the change was solely for numbering purposes: there is no substantial difference between 5.0.0 and 4.7.31 except for routine changes similar to those introduced every month.  The difference from 4.7.31 to 5.0.0 is comparable to that between 4.7.30 and 4.7.31 or that between 5.0.0 and 5.1.0.

During the month, regressions and security vulnerabilities may be fixed in releases that iterate the third part of the version number.  Generally speaking, security changes will only be released on the first or third Wednesday of the month, and they will be announced through [the CiviCRM website and a security announcement email list](https://civicrm.org/security).  Other point releases may appear at any time.

## Timing

Monthly versions require the participation of a variety of people around the world.  While there is no firm time window, and new versions have been released at all times of day, you can typically expect monthly versions to be releases late in the day in the US/Pacific time zone, which may be early Thursday in many parts of the world.

## Branches

The main branch, termed `master`, is where pull requests are typically merged.  These changes will appear in the version released up to 2 months in the future.

Each monthly release is based upon a branch of the codebase that is number for the first two parts of the upcoming release number.  This is branched off of `master` soon after the prior monthly release and is often termed the "Release Candidate" even though no static, numbered release candidates are generally produced.

After the monthly release, the numbered branch is retained, and any changes that will appear in point releases for that version will be merged there.

Pull requests should typically be made against `master` unless they clearly should appear in the release candidate or a point release.  Situations like this usually involve regressions that newly appear in the release candidate or a very recent version.  In these cases, it's not uncommon for reviewers or release managers to request two or three pull requests in tandem: one for `master`, one for the release candidate, and potentially one for the most recent release.

## Example release cycle

This system is best illustrated by the 5.24.x release cycle.  The `5.24` branch was cut from `master` soon after the 5.23.0 release on March 4, 2020.  A number of late changes, including urgent fixes and intra-release-candidate regressions, were merged during March.  On April 1, 2020, the first Wednesday of the month, 5.24.0 was released based upon the branch as it stood that day.

Soon thereafter, the `5.25` branch was cut from `master`.

Over the next couple of weeks, a number of urgent bug fixes were merged to `5.24`, and point releases 5.24.1 and 5.24.2 were released on the 4th and 9th of the month, respectively.  Security changes were merged to the branch and released on April 15, 2020, the third Wednesday of the month, as 5.24.3.  Further urgent bug fixes were merged to the branch and released as 5.24.4, 5.24.5, and 5.24.6 later in the month.

All of these changes were also merged to `master` and `5.25`.  On May 6, 2020, the first Wednesday of the next month, 5.25.0 was released based upon the `5.25` branch.  No further changes have been merged to `5.24`, and any bugs appearing in the 5.24.x series are to be addressed in later branches and versions.  Meanwhile, the `5.26` branch was cut from `master`, denoting the start of the cycle for the release to appear in June.

## Release impact

Despite having a similar numbering pattern, CiviCRM *does not* use Semantic Versioning.  Each monthly version may contain any number of bug fixes, new features, deprecations, database schema changes, and API changes.  Each may require site administrators to reconfigure features, manually change things prior to or after upgrading, or address problems in past upgrades.

The release notes for each monthly version contain a synopsis to highlight these considerations.  The following items are noted for each version:

- **Fix security vulnerabilities?**  A new monthly release will not typically fix security vulnerabilities.  This is as a courtesy to site administrators so that they won't have the pressure to upgrade for security reasons while potentially facing other changed due to the upgrade.  A security release will more commonly contain only the security-specific changes.
- **Change the database schema?**  This highlights releases that add or remove database tables, fields, indexes or triggers.  The upgrade process will manage these changes, but site administrators with unusual circumstances or direct-to-database integrations will want to take note of these changes.
- **Alter the API?** 	The API is intended to be a relatively stable way to integrate other systems or build customizations.  A change to the API, whether through adding or deprecating entities or methods, altering the output of a method, or changing permissions for a method, will often need more attention than other changes to the software.
- **Require attention to configuration options?**  Changes in the CiviCRM code will often require site administrators to revisit configuration settings that would otherwise be left alone.  One common example is system workflow message templates: when the standard template for a receipt is changed, any site administrator who has edited their site's copy of the template will need to merge their site-specific changes with the changes between versions.  Another is when new permissions are added: a site administrator may need to check that the appropriate users have permission to do a task that may have previously required a different permission.
- **Fix problems installing or upgrading to a previous version?**  Site administrators encountering a problem installing or upgrading may use a variety of workarounds.  A version that resolves these problems may require the workarounds to be undone.  This is also a useful flag for those who encounter problems and revert the upgrade: they can know that their problems may have been addressed.
- **Introduce features?**  Most monthly versions introduce new features, which are defined as improvements that go beyond making an existing feature behave the way it purports to.
- **Fix bugs?**  Practically all monthly versions and point releases fix bugs.

## Getting your changes documented accurately

Clear documentation multiplies the impact of any improvement in code.  If your change is a new feature someone might want to use, you should explain that it's there and how to use it.  If you're fixing a bug, you should make it easy for others experiencing it to know that it is fixed in the new version.  If your changes require a site administrator's attention just to continue using CiviCRM as they had before, it's imperative that you catch their attention.

At the very least, be sure that the intent and impact of your changes are clear for the editors of the release notes.  If you need the attention of site administrators as they upgrade, you should write an upgrade message and/or system check.  Finally, the user, system administrator, and developer documentation allow you to explain in-depth how to make use of your changes.

### Descriptions for release notes

The release notes for each monthly version are built upon a list of all commits since the previous monthly version.  The editors of the release notes will never be able to follow all changes as they are developed, deliberated, and merged.  Instead, they need to be able to understand the effect of each pull request within a minute or two and convey that in the release notes.

In a long ticket, it's sometimes hard to distinguish the initial proposal from what ultimately happened.  For the editors, and for their readers who follow the link to read the details, you can't assume they have followed anything that went on.  Conversations sometimes split between GitLab and a pull request (sometimes a pull request that gets closed), and sometimes key things are resolved on email or Mattermost conversations, or in person at a sprint.

An accurate title goes a long way.  If a pull request fixes a bug, and the title describes the bug concisely, that is completely sufficient.  If the title is "Changes to CiviContribute Component Settings not saved", and in fact the bug is that submitting that form does not save the changes, and the fix makes it so that submitting the form saves the changes, that makes for a perfect description.

If the pull request can't be completely described in the title, it's best to add a modifier indicating what details to look for.  "Changes to CiviContribute Component Settings not saved for some users" or "Changes to certain fields in CiviContribute Component Settings not saved" both point to the sort of detail that the editors--and anyone skimming the release notes--would want to read more about.

The second key thing is to link all related issues and pull requests.  If there's a GitLab issue that the pull request addresses, link to it.  If the pull request follows on an earlier, closed one where there was a lot of discussion, link to it.  If there are corresponding pull requests for other branches, link to them.  The first two provide context for describing the issue or solution.  The latter helps the editors be sure that the change is indeed new in that version or already introduced in an earlier point release.

Finally, especially for larger issues, review the pull request and related issue, if there is one, to make sure it's clear what has actually changed.  You can edit the pull request or issue description to add a couple sentences, or if you lack permission to do that, you can add a final comment.  If the issue is only partially resolved or just has groundwork laid, make it clear what has been done and what has yet to come.  You might also consider whether it's appropriate to have a massive issue that will just be partially complete for the foreseeable future: maybe it's better to split it into several.

### Upgrade messages and system checks

If a site administrator might need to do something following the upgrade, add a post-upgrade message.  (These are in the version-specific file in the `CRM/Upgrade/Incremental/php` folder.)  You may also add a pre-upgrade message, but know that by the time a site administrator sees it, the codebase has already been swapped out.  It's not realistic to expect them to revert at that point, so the effect is similar to the post-upgrade messages.

The person applying upgrades will not necessarily be in a position to make configuration changes.  Whether they lack the organizational authority or simply lack the context, the person reading a post-upgrade message may not understand something you're describing or may not feel confident doing it.  This is separate from something simply being technically confusing: the IT director might run the upgrade but defer to the fundraising officer for managing edits to the online contribution receipt.

Of course, some other site administrators may simply blow past the pre- and post-upgrade messages, and there's no way to go back and view them later.

There are two strategies for addressing these issues.  The first is to make the information easy to find and share.  Add documentation in the user or administrator guide, a helper extension like Doctor When, or simply in a clear explanation on the issue or pull request.  Make the post-upgrade message something straightforward for the person running the upgrade to share with their colleagues.

The second is to assume that many site administrators will ignore the message.  Instead, think through the consequences and make sure that the issue is highlighted later through help text, form validation, or a system check.

System checks are a good way to flag problems on a site.  When an administrator logs in for the first time in a day or visits the system status page, problems will be visible.  You can write a system check that ensures that things are configured properly, and if they are not, administrator users will be reminded.

### User, administrator, or developer documentation

When you add a new feature or resolve a bug in a way that requires some thought on the part of site administrators, writing documentation in the User Guide, System Administrator Guide, or this Developer Guide will help people understand and use it properly.

It's common to open a pull request in a documentation repository and point out that it is dependent upon unmerged changes in the code repository.  Add links in both directions, and reviewers can know to merge them together.
