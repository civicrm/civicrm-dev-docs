# Publishing Extensions

Publishing an extension is an easy way to:

-   recruit collaborators for a project
-   increase your user base and, along with it, the potential for:
    -   contributed bug fixes
    -   new use cases
    -   feature requests and project funding
-   bring positive attention to your organization
-   share a useful feature or set of configurations with worthy nonprofit and
    community organizations

While you could simply publish your extension to a web-based code repository like GitHub, publishing through the [CiviCRM Extensions Directory](http://civicrm.org/extensions) makes it easy for others to find and download it. Moreover, CiviCRM-native extensions which undergo a formal review can even be distributed in-application to CiviCRM sites running version 4.2 or greater.

The following instructions assume you will be publishing a CiviCRM-native extension (i.e., a CMS-agnostic extension). Instructions for CMS-specific extensions are similar; differences are noted in [Notes for CMS-specific extensions](#notes-for-cms-specific-extensions).

## Publishing a CiviCRM extension

CiviCRM's publishing process automates a number of tasks related to maintaining your extensions. Just provide some basic information about the extension, and the rest is taken care of for you! Subsequent releases will automatically be detected, published, and submitted for translation.

### Prerequisites:

-   The extension code is published in a public GitHub repository.
-   The extension manifest (`info.xml`) is in the root of the repository.
-   The extension manifest is [valid](info-xml.md#ExtensionReference-Tagsininfo.xml).
-   The name of the extension repository (e.g., *https://github.com/civicrm/org.civicrm.legcase.git*) matches the extension's fully qualified name (.e.g, *org.civicrm.legcase*) or its short name as specified by the `file` tag in `info.xml` (e.g., *legcase*).
-   Each release of the extension is "tagged" in the git repository with a ["PHP-standardized" version number string](http://php.net/manual/en/function.version-compare.php). Version number strings may optionally be prefixed with a "v".
    -   Valid tag names: "v1.2.3", "1.2.3", "v1.2-beta3", "1.2-beta3"
    -   Invalid (ignored) tag names: "stable", "1.2-prerelease"

### Publishing an extension:

-   [Register](https://civicrm.org/user/register) for an account on civicrm.org if you do not already have one.
-   [Login](https://civicrm.org/user) to civicrm.org and [create a new extension node](http://civicrm.org/node/add/extension). If you see an "Access Denied" message, you'll need to email [info@civicrm.org](mailto:info@civicrm.org) with your user id and request permission to publish extensions.
-   Fill out all required fields. Also provide the "Git URL" for the extension.
-   Complete the steps in "Publishing an extension release" below.
-   Within a day, you will receive an email notifying you that the extension was published on civicrm.org or that a problem with the extension manifest (`info.xml`) prevented publication.

### Publishing an extension release:

-   Update the extension manifest and push the changes to your GitHub repository. At minimum you'll need to increment the version number.
-   Create a git "tag" which matches the version in the manifest and push it, e.g.:

        git tag -a v1.2.0
        git push origin v1.2.0

-   Visit your extension node (created in "Publishing an extension" step above) and click "Add CiviCRM-native release" at bottom of page.
-   Within a day, you will receive an email notifying you that the release was published on civicrm.org or that a problem with the extension manifest prevented publication.

### Notes for CMS-specific extensions

CMS-specific extensions are developed for a single CMS / CiviCRM environment (e.g. Drupal 7 + CiviCRM, Joomla 2.5 + CiviCRM, etc.), typically using the extension framework of the CMS in question (for example, webform_civicrm is packaged as a Drupal module which invokes Drupal hooks).

These extensions can be published on the CiviCRM.org extensions directory, but CiviCRM does not provide in-application distribution for them. We recommend publishing them to the CiviCRM directory and to the relevant CMS extension directory (drupal.org, etc.) to take advantage of the distribution system provided by the CMS.

To publish a CMS-specific extension, follow the steps outlined above for [publishing an extension](#publishing-an-extension). (Note that the [prerequisites](#prerequisites) do not apply, that the extension manifest will be named and formatted according to the conventions of the CMS and not CiviCRM's `info.xml`, and that you may choose not to supply a "Git URL.") On the resulting release node, you will find a link "Add Extension Release." On this screen, you will provide release information as well as a link from which the extension may be downloaded.

If you develop new version(s) of your extension, you can submit additional releases at any time.

## Automated distribution

The best way to reap the benefits of publishing your extension is to make it as easy as possible for others to install it. With just a few clicks, CiviCRM site administrators can view and install CiviCRM extension releases which meet certain criteria. To be eligible for automated distribution: 

-   The extension must be published in the Extensions Directory.
-   One of the extension's maintainers must [request an extension review](https://lab.civicrm.org/extensions/extension-review-requests/issues/new?issue[title]=Request%20review%20for%20[FIXME_COM.FIXME_VENDOR.FIXME_NAME]&issue[description]=Extension%20is%20listed%20in%20the%20directory%20at%20this%20URL:%20https://civicrm.org/extensions/FIXME).
-   The extension manifest must flag the release as "stable."
-   The extension manifest must flag the release as compatible with CiviCRM version 4.2 or greater.
-   The release must be CMS-agnostic, and it must install without errors or notices from the Manage Extensions page of a site running a stable release of CiviCRM. Errors installing in any of the supported CMSes are grounds for holding an extension back from automated distribution.
-   The extension must provide the promised functionality. Serious bugs and errors found by a CiviCRM community extension moderator exploring the functionality of the extension are grounds for holding an extension back from automated distribution.

It is strongly recommended that you [write unit tests](https://github.com/civicrm/org.civicrm.testapalooza) for the extension and include them in the extension's repository. For an example, see the extension [org.civicrm.exampletests](https://github.com/totten/org.civicrm.exampletests).

Once an extension release meets these criteria, the extension will be approved for automated distribution by a CiviCRM community extension moderator. 

## Translation

When an extension is approved for automatic distribution, it will automatically be added to Transifex under the [civicrm_extensions](https://www.transifex.com/civicrm/civicrm_extensions/) project so that any strings in the interface (if there are any) may be translated.

This synchronisation task is run nightly. It also generates the translation files, which may be downloaded with the [l10nupdate](https://github.com/cividesk/com.cividesk.l10n.update) extension or by guessing the URL using the form:

    https://download.civicrm.org/civicrm-l10n-extensions/mo/i18nexample/fr_FR/i18nexample.mo
