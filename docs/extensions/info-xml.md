# info.xml

Every CiviCRM [extension](/extensions/index.md) must have an `info.xml` file within it to provide information about the extension. This page is a reference for the schema of the `info.xml` file.

Typically, you'll begin by running [civix generate:module](/extensions/civix.md#generate-module) and `civix` will create a basic `info.xml` file for you. Use this page to learn how to customize that file.

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

### `<billingMode>` {:#billingMode}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<civix>` {:#civix}

* Containing element: [`<extension>`](#extension)
* Description: Used to store settings which [civix](/extensions/civix.md) reads and writes 
* Contains: elements

Elements acceptable within `<civix>`

| Element | Acceptable instances | 
| -- | -- |
| [`<namespace>`](#namespace) | 1 |

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
    * If you want your extension to be available for [automated distribution](/extensions/publish.md#automated-distribution), it must be marked as `stable`.
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

### `<extension>` {:#extension}

* Containing element: None. This is the root element of `info.xml`.
* Description: Describe one extension
* Contains: elements

Attributes acceptable for `<extension>`

| Attribute | Description |
| -- | -- |
| `key` | The unique name of the extension, e.g. `org.example.myextension`. It should match the name of directory this extension resides in. Read more about [choosing a name](/extensions/index.md#extension-names). |
| `type` | One of `module`, `search`, `payment`, `report`. |

Elements acceptable within `<extension>`

| Element | Acceptable instances | Acceptable<br>when |
| -- | -- | -- |
| [`<civix>`](#civix) | 0 or 1 |  |
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
| [`<typeInfo>`](#typeInfo) | 0 or 1 |  |
| [`<urls>`](#urls) | 1 |  |
| [`<version>`](#version) | 1 |  |

### `<file>` {:#file}

* Containing element: [`<extension>`](#extension)
* Description: The name of the file to invoke when the extension is executed (not including `.php` file extension) This file must be present in the root of the extension `.zip` file / in base directory of the extension
* Contains: text

### `<isRecur>` {:#isRecur}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
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
* Description: The PHP namespace that [civix](/extensions/civix.md) uses when generating code 
* Contains: text
* Example: `CRM/Volunteer`

### `<passwordLabel>` {:#passwordLabel}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<paymentType>` {:#paymentType}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<releaseDate>` {:#releaseDate}

* Containing element: [`<extension>`](#extension)
* Description: The release date of the current version (use `YYYY-MM-DD` format)
* Contains: text

### `<reportUrl>` {:#reportUrl}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<signatureLabel>` {:#signatureLabel}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<subjectLabel>` {:#subjectLabel}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<typeInfo>` {:#typeInfo}

* Containing element: [`<extension>`](#extension)
* Description: This is a container tag for other tags which store information that is only relevant to extensions of particular types (e.g. payment processor extensions)
* Contains: elements

Elements acceptable within `<typeInfo>`

| Element | Acceptable instances | Acceptable<br>when |
| -- | -- | -- |
| [`<reportUrl>`](#reportUrl) | 0 or 1 | `<extension type="report">` |
| [`<userNameLabel>`](#userNameLabel) | 0 or 1 | `<extension type="payment">` |
| [`<passwordLabel>`](#passwordLabel) | 0 or 1 | `<extension type="payment">` |
| [`<signatureLabel>`](#signatureLabel) | 0 or 1 | `<extension type="payment">` |
| [`<subjectLabel>`](#subjectLabel) | 0 or 1 | `<extension type="payment">` |
| [`<urlSiteDefault>`](#urlSiteDefault) | 0 or 1 | `<extension type="payment">` |
| [`<urlApiDefault>`](#urlApiDefault) | 0 or 1 | `<extension type="payment">` |
| [`<urlRecurDefault>`](#urlRecurDefault) | 0 or 1 | `<extension type="payment">` |
| [`<urlSiteTestDefault>`](#urlSiteTestDefault) | 0 or 1 | `<extension type="payment">` |
| [`<urlApiTestDefault>`](#urlApiTestDefault) | 0 or 1 | `<extension type="payment">` |
| [`<urlRecurTestDefault>`](#urlRecurTestDefault) | 0 or 1 | `<extension type="payment">` |
| [`<urlButtonDefault>`](#urlButtonDefault) | 0 or 1 | `<extension type="payment">` |
| [`<urlButtonTestDefault>`](#urlButtonTestDefault) | 0 or 1 | `<extension type="payment">` |
| [`<billingMode>`](#billingMode) | 0 or 1 | `<extension type="payment">` |
| [`<isRecur>`](#isRecur) | 0 or 1 | `<extension type="payment">` |
| [`<paymentType>`](#paymentType) | 0 or 1 | `<extension type="payment">` |

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


### `<urlSiteDefault>` {:#urlSiteDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<urlApiDefault>` {:#urlApiDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<urlRecurDefault>` {:#urlRecurDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<urlSiteTestDefault>` {:#urlSiteTestDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<urlApiTestDefault>` {:#urlApiTestDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<urlRecurTestDefault>` {:#urlRecurTestDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<urlButtonDefault>` {:#urlButtonDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<urlButtonTestDefault>` {:#urlButtonTestDefault}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<ver>` {:#ver}

* Containing element: [`<compatibility>`](#compatibility)
* Description: a version of CiviCRM with which this extension is compatible
* Contains: text
* Example: `4.7`

!!! note
    It is not currently possible to specify compatibility with point releases. If your extension is compatible with CiviCRM 4.7.21 but *not* 4.7.20, then you will need to clearly specify this in the [comments](#comments).
    
### `<userNameLabel>` {:#userNameLabel}

* Containing element: [`<typeInfo>`](#typeInfo)
* Description: *not yet documented*
* Contains: text

### `<version>` {:#version}

* Containing element: [`<extension>`](#extension)
* Description: The current version of this extension (used for upgrade detection and used to sort available releases for automated distribution)
* Contains: text

Valid version formats include: `1`, `1.1`, `1.2.3.4`, `1.2-3`, `1.2.alpha2` , `1.2.rc2`, `2012-01-01-1`, `2012-01-01`, `r456 `, `r5000`

