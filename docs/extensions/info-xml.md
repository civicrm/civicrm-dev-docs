# info.xml

Every CiviCRM [extension](index.md) must have an `info.xml` file within it to provide information about the extension. This page is a reference for the schema of the `info.xml` file.

Typically, you'll begin by running [civix generate:module](civix.md#generate-module) and `civix` will create a basic `info.xml` file for you. Use this page to learn how to customize that file.

## Example

Here is an example of a full `info.xml` file from [CiviVolunteer](https://github.com/civicrm/org.civicrm.volunteer/blob/master/info.xml).

```xml
<?xml version="1.0"?>
<extension key="org.civicrm.volunteer" type="module">
  <file>volunteer</file>
  <name>CiviVolunteer</name>
  <description>The CiviVolunteer extension provides tools for signing up, managing, and tracking volunteers.</description>
  <license>AGPL-3.0</license>
  <maintainer>
    <author>Ginkgo Street Labs; CiviCRM, LLC; and the CiviCRM community</author>
    <email>inquire@ginkgostreet.com</email>
  </maintainer>
  <releaseDate>2016-12-06</releaseDate>
  <version>4.6-2.2.1</version>
  <develStage>stable</develStage>
  <compatibility>
    <ver>4.6</ver>
    <ver>4.7</ver>
  </compatibility>
  <comments>
    Developed by Ginkgo Street Labs and CiviCRM, LLC with contributions from the community. Special thanks to Friends of Georgia State Parks &amp; Historic Sites for funding the initial release, and to The Manhattan Neighborhood Network for funding the 1.4 release.
  </comments>
  <civix>
    <namespace>CRM/Volunteer</namespace>
  </civix>
  <urls>
    <url desc="Documentation">https://docs.civicrm.org/volunteer/en/latest</url>
  </urls>
</extension>
```

## Changelog

| CiviCRM Version | Description |
| -- | -- |
| 5.24.0 | [`<tags>`](#tags) Tags introduced as part of schema |
| 5.0.0 | [`<ver>`](#ver) tags now imply forward compatibility when the version specified is 4.7 or higher. (e.g. an extension declaring `<ver>5.1</ver>` is displayed on 5.2, 5.3 etc. but *not* on 5.0.) Because 4.7.x and 5.x are substantively the same series, `<ver>4.7</ver>` implies forward compatiblity with 5.x. If you want to specify multiple version compatibility for both lower than 4.7 and higher, then you need to specify multiple `<ver>` tags for all of the lower versions and at least one of 4.7 or higher, e.g. `<ver>4.5</ver> <ver>4.6</ver> <ver>4.7</ver>`.|
| 4.7.27 | Added [`<requires>`](#requires) and [`<ext>`](#ext) | 
| 4.5 | [`<develStage>`](#develStage) is not always required; when using civicrm.org's automated release management, this value is inferred from the version; for manual or private releases, the field should still be defined.
| 4.2 | Most extensions should be packaged as generic *module* rather than type-specific extensions.
| 4.2 | [`<downloadUrl>`](#downloadUrl) is optional for ordinary development; when using civicrm.org to distribute extensions, the [`<downloadUrl>`](#downloadUrl) will be specified when announcing the release on the website
| 4.2 | [`<downloadUrl>`](#downloadUrl) can point to auto-generated zipballs on Github.com
| 4.2 | Introduce stable support for generic modules.
| 4.1 | Introduce experimental extension-type for generic modules.
| 3.3 | Introduce extension-types for payment-processors, report-templates, and custom-searches.

## Elements

Here we describe all the elements acceptable within the XML file. They are presented in alphabetical order, but note that **the root element must be [`<extension>`](#extension)**, so you might wish to [begin reading about `<extension>` first](#extension).

### `<author>` {:#author}

* Containing element: [`<maintainer>`](#maintainer)
* Description: Name of the person and/or organisation maintaining the extension
* Contains: text

### `<civix>` {:#civix}

* Containing element: [`<extension>`](#extension)
* Description: Used to store settings which [civix](civix.md) reads and writes 
* Contains: elements

Elements acceptable within `<civix>`

| Element | Acceptable instances | 
| -- | -- |
| [`<namespace>`](#namespace) | 1 |

### `<classloader>` {:#classloader}

* Containing element: [`<extension>`](#extension)
* Description: Specifies the namespace and file-path of classes to add to the classloader.
* Contains: elements

Elements acceptable within `<classloader>`

| Element | Acceptable instances | 
| -- | -- |
| [`<psr4>`](#psr4) | 1+ |

For example, if you want to write PHP classes in the `Civi` namespace and autoload those classes within your extension, you'll need to use the following XML:

```xml
<classloader>
  <psr4 prefix="Civi\" path="Civi"/>
</classloader>
```

### `<comments>` {:#comments}

* Containing element: [`<extension>`](#extension)
* Description: A long description of the extension that will display to the user before installation.
* Contains: text

### `<compatibility>` {:#compatibility}

* Containing element: [`<extension>`](#extension)
* Description: specifies the versions of CiviCRM with which this extension is compatible
* Contains: elements

Elements acceptable within `<compatibility>`

| Element | Acceptable instances | 
| -- | -- |
| [`<ver>`](#ver) | 1+ |

### `<description>` {:#description}

* Containing element: [`<extension>`](#extension)
* Description: A brief (one sentence) human-readable description of what the extension does
* Contains: text

### `<develStage>` {:#develStage}

* Containing element: [`<extension>`](#extension)
* Description: development stage
* Contains: text
* Acceptable values: `stable`, `beta`, `alpha`
* Notes: 
    * If you want your extension to be available for [automated distribution](publish.md#automated-distribution), it must be marked as `stable`.
    * If you use civicrm.org's automated release management (based on git tags), the `<develStage>` value will be determined automatically by searching for "alpha" or "beta" in the version.

### `<downloadUrl>` {:#downloadUrl}

* Containing element: [`<extension>`](#extension)
* Description: The url to the zip file with your extension.
* Contains: text

!!! failure "Deprecated"
    `<downloadUrl>` was deprecated in CiviCRM 4.2

### `<email>` {:#email}

* Containing element: [`<maintainer>`](#maintainer)
* Description: The maintainer's email address
* Contains: text

### `<ext>` {:#ext}

* Containing element: [`<requires>`](#requires)
* Description: Specifies the unique name of one extension on which this extension is dependent.
* Notes:
    * It is not currently possible to specify *versions* in these dependencies. 
    * See [`<requires>`](#requires) for more details about extension dependencies.
* Contains: text
* Example: `org.civicrm.shoreditch`
* Added in: CiviCRM 4.7.27

### `<extension>` {:#extension}

* Containing element: None. This is the root element of `info.xml`.
* Description: Describe one extension
* Contains: elements

Attributes acceptable for `<extension>`

| Attribute | Description |
| -- | -- |
| `key` | The unique name of the extension, e.g. `org.example.myextension`. It should match the name of directory this extension resides in. Read more about [choosing a name](index.md#extension-names). |
| `type` | One of `module`, `search`, `payment`, `report`. |

Elements acceptable within `<extension>`

| Element | Acceptable instances | Acceptable<br>when |
| -- | -- | -- |
| [`<civix>`](#civix) | 0 or 1 |  |
| [`<classloader>`](#classloader) | 0 or 1 |  |
| [`<compatibility>`](#compatibility) | 1 |  |
| [`<comments>`](#comments) | 0 or 1 |  |
| [`<description>`](#description) | 1 |  |
| [`<develStage>`](#develStage) | 0 or 1 |  |
| ~~[`<downloadUrl>`](#downloadUrl)~~ | 0 |  |
| [`<file>`](#file) | 1 |  |
| [`<label>`](#label) | 0 or 1 | `<extention type="search">` |
| [`<license>`](#license) | 1 |  |
| [`<maintainer>`](#maintainer) | 1 |  |
| [`<name>`](#name)| 1 |  |
| [`<releaseDate>`](#releaseDate) | 1 |  |
| [`<requires>`](#requires) | 0 or 1 |  |
| [`<urls>`](#urls) | 1 |  |
| [`<version>`](#version) | 1 |  |

!!! tip "Legacy extensions: Payment, Report, Search"
      Historically, CiviCRM v3.x-4.1 strictly categorized extensions as "Payment", "Report", or "Search" -- which required additional XML tags. These have been phased out during the 4.x cycle, and they are no longer documented here. For archival documentation, see [CRMDOC46: Extension Reference](https://wiki.civicrm.org/confluence/display/CRMDOC46/Extension+Reference).

### `<file>` {:#file}

* Containing element: [`<extension>`](#extension)
* Description: The name of the file to invoke when the extension is executed (not including `.php` file extension) This file must be present in the root of the extension `.zip` file / in base directory of the extension
* Contains: text

### `<label>` {:#label}

* Containing element: [`<extension>`](#extension)
* Description: For search extensions, this element is used as the menu item text on Custom Searches list
* Contains: text

### `<license>` {:#license}

* Containing element: [`<extension>`](#extension)
* Description: The name of the license under which your extension is offered.
* Contains: text
* Example: `AGPL-3.0`

!!! tip
    For more information on what license to choose, see [CiviCRM licensing page](http://civicrm.org/licensing). For technical details on how to identify a license, see [SPDX License List](http://www.spdx.org/licenses/)

### `<maintainer>` {:#extension-maintainer}

* Containing element: [`<extension>`](#extension)
* Description: Used to store information about the person (or organization maintaining the extension)
* Contains: elements

Elements acceptable within `<maintainer>`

| Element | Acceptable instances |
| -- | -- |
| [`<author>`](#author) | 1 |
| [`<email>`](#email) | 1 |

### `<name>` {:#name}

* Containing element: [`<extension>`](#extension)
* Description: Human-readable name of the extension. It can contain spaces
* Contains: text

### `<namespace>` {:#namespace}

* Containing element: [`<civix>`](#civix)
* Description: The PHP namespace that [civix](civix.md) uses when generating code 
* Contains: text
* Example: `CRM/Volunteer`

### `<psr4>` {:#psr4}

* Containing element: [`<classloader>`](#classloader)
* Description: Specifies the namespace and file-path of classes to add to the classloader in PSR-4 Standard.
* Contains: nothing &mdash; should be empty

Attributes acceptable for `<psr4>`

| Attribute | Contains |  Purpose |
| -- | -- | -- |
| `prefix` | text | Namespace of classes to add |
| `path` | text | Directory path to classes to add |


### `<releaseDate>` {:#releaseDate}

* Containing element: [`<extension>`](#extension)
* Description: The release date of the current version (use `YYYY-MM-DD` format)
* Contains: text

### `<requires>` {:#requires}

* Containing element: [`<extension>`](#extension)
* Description: Used to to specify other extensions on which this extension is dependent.
* Contains: elements
* Example:

    ```xml
    <extension key="org.civicrm.foo" type="module">
      <requires>
        <ext>org.civicrm.bar</ext>
      </requires>
    </extension>
    ```
    
* Notes:
    * For example if `org.civicrm.foo` requires `org.civicrm.bar`, then CiviCRM core will not enable `org.civicrm.foo` unless it can enable `org.civicrm.bar` _first_.
    * Also, if `org.civicrm.bar` depends on other extensions, the process will continue recursively, always by enabling the dependencies first. 
    * **CiviCRM core does not _download_ the dependencies** &mdash; it only *enables* them. The *user* must first download the dependencies. 
    * Currently, `<requires>` is only for managing dependencies on other *extensions* (not other libraries).
* Added in: CiviCRM 4.7.27

Elements acceptable within `<requires>`

| Element | Acceptable instances | 
| -- | -- |
| [`<ext>`](#ext) | 1+ |

### `<tags>` {:#tags}

* Containing element: [`<extension>`](#extension)
* Description: Freeform tags allow extensions to be organized into categories or selected as subgroups. Tags MUST contain only letters, numbers, dashes,and colons. Tags are case-sensitive. Tags MUST follow the structure `<prefix>:<some-tag-name>`. Tags SHOULD be documented in the table below. (*If you need to use a new/unknown tag, then please submit a [documentation update](../documentation/index.md) with a description.*)
* Contains: elements
* Example:

    ```xml
    <extension key="org.civicrm.foo" type="module">
      <tags>
        <tag>comp:CiviContribute</tag>
        <tag>topic:payment-processor</tag>
        <tag>mgmt:hidden</tag>
      </tags>
    </extension>
    ```

The `topic:*` tags relate to a general topic/business area/problem area. Topics must use "snake-case" (lowercase/hyphenated).

| Tag name | Description |
|--|--|
| `topic:reporting` | Provides reporting functionality |
| `topic:search` | Provides search functionality |
| `topic:email` | Provides email functionality |

The `comp:*` tags relate to specific implementations (components/extensions/modules). Components may use capitalization that matches the original component.  Where applicable, tags SHOULD match counterparts in the [Gitlab labels](https://lab.civicrm.org/dev/core/-/labels).

| Tag name | Description |
|--|--|
| `comp:CiviCampaign` | Improves or alters the CiviCase component |
| `comp:CiviCase` | Improves or alters the CiviCase component |
| `comp:CiviContribute` | Improves or alters the CiviContribute component |
| `comp:CiviEvent` | Improves or alters the CiviEvent component |
| `comp:CiviGrant` | Improves or alters the CiviGrant component |
| `comp:CiviMail` | Improves or alters the CiviMail component |
| `comp:CiviReport` | Improves or alters the CiviReport component |

The `mgmt:*` tags relate to the management of extensions. Management tags must use "snake-case" (lowercase/hyphenated).

| Tag name | Description |
|--|--|
| `mgmt:hidden` | The extension is not displayed in the administrative UI, but it can be managed via CLI and API. (Implemented in v5.24+.) |
| `mgmt:mandatory` | The extension must always be enabled. (*Implementation pending*) |
| `mgmt:autoinstall` | The extension is installed by default on  new sites. (*Implementation pending*) |

### `<url>` {:#url}

* Containing element: [`<urls>`](#urls)
* Description: Represents one URL which is associated with this extension. For example, the URL of the extension's documentation.
* Contains: text

Attributes acceptable for `<url>`

| Attribute | Contains |  Purpose |
| -- | -- | -- |
| `desc` | text | A short (usually one word) description of the URL. This will display next to the URL in the CiviCRM UI before users install the extension. For example "Documentation" and "Support" are common values. |

### `<urls>` {:#urls}

* Containing element: [`<extension>`](#extension)
* Description: Stores all the urls that you think are appropriate and necessary for users to find out more about what your extension does, additional documentation, etc.
* Contains: elements

Elements acceptable within `<urls>`

| Element | Acceptable instances |
| -- | -- |
| [`<url>`](#url) | 1+ |

### `<ver>` {:#ver}

* Containing element: [`<compatibility>`](#compatibility)
* Description: a version of CiviCRM with which this extension is compatible; expressed as two digits
* Contains: text
* Example: `4.7`

!!! note "Point releases"
    It is not currently possible to specify compatibility with point releases. If your extension is compatible with CiviCRM 4.7.21 but *not* 4.7.20, then you will need to clearly specify this in the [comments](#comments).

!!! note "Forward compatibility (4.7/5.x)"
    For CiviCRM 3.x and 4.x, `<ver>` tags must explicitly list all compatible versions.

    For CiviCRM 4.7.x and 5.x, `<ver>` tags imply forward compatibility.

    Because 4.7.x and 5.x are substantively the same series, `<ver>4.7</ver>` implies forward compatiblity with 5.x.

### `<version>` {:#version}

* Containing element: [`<extension>`](#extension)
* Description: The current version of this extension (used for upgrade detection and used to sort available releases for automated distribution)
* Contains: text

Valid version formats include: `1`, `1.1`, `1.2.3.4`, `1.2-3`, `1.2.alpha2` , `1.2.rc2`, `2012-01-01-1`, `2012-01-01`, `r456 `, `r5000`
