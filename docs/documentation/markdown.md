# Markdown Syntax

Markdown is a language used by many platforms to specify basic text formatting. This page describes its syntax, with a focus on features that work well within CiviCRM's documentation guide books.


## Platform differences

Many platforms which use markdown have expanded or modified the [original syntax](https://daringfireball.net/projects/markdown/syntax), which has created subtle differences among various platforms.

Platform-specific markdown references:

* [Mattermost markdown](https://docs.mattermost.com/help/messaging/formatting-text.html)
* [Stack Exchange markdown](http://stackoverflow.com/editing-help)
* [GitHub markdown](https://help.github.com/categories/writing-on-github/)
* CiviCRM's guide book markdown:
    * Books are built with MkDocs which parses markdown with [Python-Markdown](https://pythonhosted.org/Markdown/).
    * **But** the extra markdown features available depend heavily on the *theme* and *markdown extensions* used within the book.
    * [Material](http://squidfunk.github.io/mkdocs-material/) is the recommended theme to be used for CiviCRM guide books
    * [Extensions included within Python-Markdown](https://pythonhosted.org/Markdown/extensions/) can be enabled in the book's `mkdocs.yml` file without installing anything.
    * [PyMdown Extensions](http://facelessuser.github.io/pymdown-extensions/) is a 3rd party package that provides many additionally useful extensions.

The remainder of this page will focus on the markdown syntax which can be used within MkDocs books with the Material theme and common markdown extensions.


## CiviCRM markdown coding standards {:#standards}

To maintain some consistency and peace of mind for documentation content editors, we've [agreed](https://github.com/civicrm/civicrm-dev-docs/issues/43) to *recommend* the following syntax as markdown coding standards. These are not hard rules though.

### General coding standards {:#general-standards}

* **Line length:** write long lines (i.e. one line per paragraph) and set your text editor to view them with a "soft wrap".
* **Ordered lists:** use `1.` as delimiters.
* **Unordered lists:** use `*` to delimiters.
* **Headings:** use hashes like `## Heading 2`.

### Internal link standards {:#internal-url-standards}

!!! note
    These standards only apply to *internal* links and images (which should be internal anyway). There are no markdown standards for external links (which point outside of the current guide).

Valid examples:

1. `[Buildkit](../tools/buildkit.md)`
1. `[the API](../api/index.md)`
1. `[extension review process](../extensions/lifefycle.md#formal-review)`
1. `[section within this page](#that-section)`
1. `![awesome alt text](../../images/awesome-screenshot.png)`


Rules:

* Your link should point directly to the markdown file in the folder tree, using `..` and/or `filename.md` as appropriate.
    * When you are linking to a section within the current page, use only the fragment which corresponds to that heading (beginning with `#`, as in example 4 above.). (Also consider [specifying a custom heading ID](#custom-heading-ids) to prevent broken links if the heading is later renamed.)
* Append `.md` when linking to a page.
* If you are linking to a page which is named `index.md`, then include `index.md` in the path (even though your link will technically still work if you don't).
* If you're linking to a section within a page (other than the current one), then do it as shown in example 3 above (even though some some other variants will also work).
* Do not use syntax like `[Link Text][path]` which defines the path in a separate part of the document (even though it will technically work).

Reasons for these internal link standards:

* Following the rules above helps us avoid broken links.
* MkDocs will detect broken links when building books, but only if the links are absolute and end with `.md`.
* Using consistent syntax helps us to more easily find-and-replace links when moving pages.

## Basic inline formatting

| Code | Result | Extension required |
| :-- | :-- | :-- |
| `*italics*` | *italics* |  |
| `**bold**` | **bold** |  |
| `***bold and italic***` | ***bold and italic*** |  |
| `` `monospace` `` | `monospace` |  |
| `~~strikethrough~~` | ~~strikethrough~~ | [Tilde](http://facelessuser.github.io/pymdown-extensions/extensions/tilde/) |
| `==highlight==` | ==highlight== | [Mark](http://facelessuser.github.io/pymdown-extensions/extensions/mark/) |

Alternate syntax: Underscores for `_italics_` and `__bold__` also work on most
platforms.


## Internal hyperlinks

See the [internal link standards](#internal-url-standards) above for examples of internal hyperlinks.

!!! warning
    Several different syntax variants will produce functionally identical hyperlinks, but it's important you follow our [standards](#internal-url-standards) so that we can avoid broken links when re-organizing pages in the future.


## External hyperlinks

A basic external hyperlink in a sentence:

``` md
Try [CiviCRM](https://civicrm.org) for your database.
```

***Result:***

> Try [CiviCRM](https://civicrm.org) for your database.

### Named hyperlinks

If you're using a one external link in many places throughout a page, you can define the URL in one place as follows

``` md
See [#123] for more details.

My favorite issue is [#123].

[#123]: https://lab.civicrm.org/dev/core/issues/123
```

***Result:***

> See [#123] for more details.
>
> My favorite issue is [#123].
>
> [#123]: https://lab.civicrm.org/dev/core/issues/123

(The third line can be placed anywhere in the file.)

You can also use custom text for a named hyperlink, as shown below:

``` md
After learning [how to foo a bar][foobar], then you can party!

[foobar]: https://example.com/foobar
```

***Result:***

> After learning [how to foo a bar][foobar], then you can party!
>
> [foobar]: https://example.com/foobar

!!! caution "For external links only"
    Per our [standards](#internal-url-standards), named hyperlinks should only be used for *external* links

## Line breaks and whitespace

**Single line breaks** in markdown code are eliminated in display:

``` md
This text will all show up
on the
same
line.
```

***Result:***

>This text will all show up
>on the
>same
>line.

This makes it easy to avoid very long lines in markdown code. As a rule of
thumb, keep your markdown code free of lines longer than 80 characters
where possible.

**Double line breaks** create separate paragraphs:

``` md
This is
one paragraph.

This is a second.
```

***Result:***

>This is
>one paragraph.
>
>This is a second.

## Headings

``` md
# Heading 1

## Heading 2

### Heading 3

#### Heading 4
```

***Result:***

> # Heading 1
>
> ## Heading 2
>
> ### Heading 3
>
> #### Heading 4

The above syntax is [called](http://pandoc.org/MANUAL.html#headers)
"ATX style headers" in markdown terminology, and is the [preferred](#standards)
syntax within the CiviCRM community.
An alternate syntax called "setext style headers" works for h1 and h2 as
follows (but please avoid creating new content with this syntax).

``` md
Heading 1
=========

Heading 2
---------
```

***Result:***

> Heading 1
> =========
>
> Heading 2
> ---------

### Custom heading IDs

!!! tip "Extension required"
    To use custom heading IDs in MkDocs, insert the following code in `mkdocs.yml` to enable the [Attribute Lists](https://pythonhosted.org/Markdown/extensions/attr_list.html) extension:

    ``` yml
    markdown_extensions:
      - markdown.extensions.attr_list
    ```

Custom heading IDs allow you to link to specific sections in a page by appending the heading ID to the page URL. Most markdown platforms (e.g. MkDocs, GitHub) automatically set a heading ID for every heading and do so using the text of the heading itself. Sticking with the default is great is most cases, however sometimes you want to override it.

Setting a custom ID:

``` md
## How to foo a bar {:#foo}
```

***Result:***

> ## How to foo a bar {:#foo}

This is helpful when you think that readers are likely to frequently link
to this section in the future.

* Custom heading IDs will remain the same (thus preserving incoming links) even after the text of the heading is edited.
* Custom heading IDs create shorter URLs.

## Lists

### Unordered lists

``` md
Here is my paragraph (with a blank line after).

* My first item is here.
* My second item is here and a
  bit longer than the first.
* Then, a third.
```

***Result:***

> Here is my paragraph (with a blank line after).
>
> * My first item is here.
> * My second item is here and a
> bit longer than the first.
> * Then, a third.

!!! note
    If you don't **include a blank line before your first list item**, then the list will become part of the previous element (paragraph, heading, etc).

Alternate syntaxes:

* Unordered lists also recognize `-` and `+` as item delimiters.
* Markdown is somewhat flexible with the quantity and position of spaces when making lists.

### Ordered lists

``` md
1. Item
1. Item
1. Item
```

***Result:***

> 1. Item
> 1. Item
> 1. Item

Alternate syntaxes:

* Ordered lists items are automatically re-numbered sequentially upon display which means all items can begin with `1`, or they can be ordered sequentially in code.

### Nested lists

List sub-items must be indented 4 spaces:

``` md
1.  Item
    1.  Item
    1.  Item
1.  Item
    * Item
    * Item
    * Item
1. Item
```

***Result:***

> 1.  Item
>     1.  Item
>     1.  Item
> 1.  Item
>     * Item
>     * Item
>     * Item
> 1. Item

## Code

### Inline code

Use backticks to create inline monospace text:

``` md
Some `monospace text` amid a paragraph.
```

***Result:***

> Some `monospace text` amid a paragraph.

And if you need to put a backtick inside your monospace text, begin and end
with two backticks:

``` md
Some ``monospace text with `backticks` inside``, and all amid a paragraph.
```

***Result:***

> Some ``monospace text with `backticks` inside``, and all amid a paragraph.

### Code blocks

A block of **"fenced code"** with three or more backticks on their own line.

```` md
```
CODE
BLOCK
```
````

***Result:***

> ```
> CODE
> BLOCK
> ```

*Fenced code can use more than three backticks when necessary to represent code that contains 3 backticks (which is what you'd see in the source for this page).*

Alternate syntax: For fenced code, the tilde `~` character also works
in place of the backtick character but should be avoided for consistency.

A block of **"indented code"** with four spaces at the start of each line:

```` md
```
    CODE
    BLOCK
```
````

***Result:***

> ```
>     CODE
>     BLOCK
> ```

### Syntax highlighting for code
For code blocks, some platforms (e.g. GitHub) will guess the language of the code and automatically apply syntax highlighting to the display.

To force a particular type of syntax highlighting, use fenced code with a keyword (like `javascript` in this case) as follows:

```` md
```javascript
var cj = CRM.$ = jQuery;
```
````

***Result:***

> ```javascript
> var cj = CRM.$ = jQuery;
> ```

Available language keywords:
 *   Differ slightly by markdown platform
 *   Common language keywords that work on most platforms: `bash`, `css`, `docker`, `html`, `javascript`, `js`, `json`, `markdown`, `md`, `perl`, `php`, `python`, `ruby`, `scss`, `sh`, `smarty`, `sql`, `xhtml`, `xml`, `yaml`
 *   The Material theme for MkDocs will use the Pygments python library when possible, and in this case provide syntax highlighting for [over 300 languages](http://pygments.org/docs/lexers).

Syntax highlighting cannot be forced for indented code.

Syntax highlighting for inline code is possible with [InlineHilite](http://facelessuser.github.io/pymdown-extensions/extensions/inlinehilite/) but not recommended.

[Stack Exchange syntax highlighting](http://stackoverflow.com/editing-help#syntax-highlighting) is done differently.

### Code blocks within lists

#### Fenced code within lists

!!! tip "Extension required"
    To use fenced code within lists in MkDocs, install [PyMdown Extensions](http://facelessuser.github.io/pymdown-extensions) and then insert the following code in `mkdocs.yml` to enable the [Superfences](http://facelessuser.github.io/pymdown-extensions/extensions/superfences/) extension:

    ``` yml
    markdown_extensions:
      - pymdownx.superfences
    ```

Then insert fenced code into a list as follows:

```` md
*   First item
*   Look at this code:

    ```md
    code
    block
    ```

*   More list items
````

***Result:***

> * First item
> * Look at this code:
>
>     ```
>     code
>     block
>     ```
>
> *   More list items

#### Indented code within lists

You can use indented code within lists without needing any markdown extensions. Keep a blank line above and below the code and indent the code *4 spaces more than your list content*, like this:

``` md
*   First item
*   Look at this code:

        CODE BLOCK WITHIN
        TOP LEVEL LIST ITEM

*   More list items
*   A nested list is here:
    1.  Alpha
    1.  Beta, with some code

            CODE BLOCK WITHIN
            SUB-LIST ITEM

    1. Gamma

*   Fun, right?
```

***Result:***

> *   First item
> *   Look at this code:
>
>         CODE BLOCK WITHIN
>         TOP LEVEL LIST ITEM
> 
> *   More list items
> *   A nested list is here:
>     1.  Alpha
>     1.  Beta, with some code
>
>             CODE BLOCK WITHIN
>             SUB-LIST ITEM
> 
>     1. Gamma
>
> *   Fun, right?

## Admonitions

!!! tip "Extension required"
    To use admonitions in MkDocs, insert the following code in `mkdocs.yml` to enable the [Admonitions](https://pythonhosted.org/Markdown/extensions/admonition.html) extension:

    ``` yml
    markdown_extensions:
      - markdown.extensions.admonition
    ```

### Syntax {:#admonition-syntax}

Simple example:

``` md
!!! note
    Here is a note for you.
```

***Result:***

> !!! note
>     Here is a note for you.

Add a custom title (make sure to quote the title):

``` md
!!! danger "Don't try this at home!"
    Stand back. I'm about to try science!
```

***Result:***

> !!! danger "Don't try this at home!"
>     Stand back. I'm about to try science!

(You can also add an admonition *without* a title by passing an empty string `""` in place of the title.)

### Types {:#admonition-types}

The types of admonitions available for use in MkDocs depend on the theme being used. The Material theme [supports](http://squidfunk.github.io/mkdocs-material/extensions/admonition/#types) the following types:

!!! note
    I am a "note" admonition and look the same as "seealso".

!!! tip
    I am a "tip" admonition and look the same as "hint" and "important".

!!! warning
    I am a "warning" admonition and look the same as "attention" and "caution".

!!! danger
    I am a "danger" admonition and look the same as "error".

!!! summary
    I am a "summary" admonition and look the same as "tldr".

!!! success
    I am a "success" admonition and look the same as "check" and "done".

!!! failure
    I am a "failure" admonition and look the same as "fail" and "missing".

!!! bug
    I am a "bug" admonition.


## Images

Images function mostly the same as hyperlinks, but preceded by an exclamation
point and with alt text in place of the link text.

```
![Alt text](../img/CiviCRM.png)
```

***Result:***

> ![Alt text](../img/CiviCRM.png)

Note:

* The image files should be committed into git and stored in the `docs/img` directory within the project.
* The path to the image should follow our [internal link standards](#internal-url-standards)  

## Other markdown syntax

*   [Tables] (to be avoided when possible)
*   [Emojis] (great for Mattermost)
*   Blockquotes
    
    ``` md
        > This text is a blockquote, typically used
        >  to represent prose written by a person. It
        >  will be displayed slightly indented.
    ```
    
    ***Result:***
    
    > This text is a blockquote, typically used
    >  to represent prose written by a person. It
    >  will be displayed slightly indented.

[Emojis]: http://www.webpagefx.com/tools/emoji-cheat-sheet/
[Tables]: https://help.github.com/articles/organizing-information-with-tables



