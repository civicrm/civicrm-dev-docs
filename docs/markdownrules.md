# Markdown Syntax

Learning [Markdown](https://en.wikipedia.org/wiki/Markdown)
language is useful for:

*   Writing CiviCRM [extensions](extend) -- In order for your extension to be
    compliant, you must provide extension documentation, written in markdown.
*   Writing other *.md* files that display on [GitHub]
*   Contributing to CiviCRM [documentation](documentation)
*   Chatting on [Mattermost](http://chat.civicrm.org)
*   Q&A on [Stack Exchange](http://civicrm.stackexchange.com)

[GitHub]: https://github.com

Markdown language is mostly consistent across these platforms, but some
discrepancies do exist. The `mkdocs` specific guide for markdown, as used in
this book is
[here](http://www.mkdocs.org/user-guide/writing-your-docs).


## CiviCRM markdown code standards {:#standards}

To maintain some consistency and peace of mind for documentation content editors, we've [agreed](https://github.com/civicrm/civicrm-dev-docs/issues/43) to *recommend* the following syntax as markdown code standards. These are not hard rules though.

* **Line length:** write long lines (i.e. one line per paragraph) and set your text editor to view them with a "soft wrap".
* **Ordered lists:** use `1.` as delimiters.
* **Unordered lists:** use `*` to delimiters.
* **Headings:** use hashes like `## Heading 2`.


## Basics

*   `*italics*`
*   `**bold**`
*   `***bold and italic***`
*   `~~strikethrough~~` *(GitHub/Mattermost/StackExchange)*
*   `<del>strikethrough</del>` *(mkdocs)*

Alternate syntax: Underscores for `_italics_` and `__bold__` also work on most
platforms.


## Hyperlinks

*   A basic hyperlink (in a sentence)

        Try [CiviCRM](https://civicrm.org) for your database.

*   An internal hyperlink on mkdocs. The `.md` is optional.
    *Make sure to use an absolute path and precede the path with a slash,
    as shown below.*

        [extensions](/extensions/basics)
        [extensions](/extensions/basics.md)

*   With long URLs, the following syntax is better.

        See [this issue][CRM-19799] for more details.

        [CRM-19799]: https://issues.civicrm.org/jira/browse/CRM-19799

    -   The second line can be placed anywhere in the file.
    -   Optionally, if the link ID ("CRM-19799" in this case) is omitted, you
        can use the link text ("this issue") to reference the link in the
        second line.


## Line breaks and whitespace

**Single line breaks** in markdown code are eliminated in display:

```md
This text will all show up
on the
same
line.
```

This makes it easy to avoid very long lines in markdown code. As a rule of
thumb, keep your markdown code free of lines longer than 80 characters
where possible.

**Double line breaks** create separate paragraphs:

```md
This is
one paragraph.

This is a second.
```


## Headings

```md
# Heading 1

## Heading 2

### Heading 3

#### Heading 4
```

The above syntax is [called](http://pandoc.org/MANUAL.html#headers)
"ATX style headers" in markdown terminology, and is the [preferred](#standards)
syntax within the CiviCRM community.
An alternate syntax called "setext style headers" works for h1 and h2 as
follows (but please avoid creating new content with this syntax).

```md
Heading 1
=========

Heading 2
---------
```

### Heading IDs

Heading IDs allow you to link to specific sections in a page by appending
the heading ID to the page URL. Most markdown platforms (e.g. MkDocs, GitHub)
automatically set a heading ID for every heading and do so using the text of
the heading itself. Sticking with the default is great is most cases, however
sometimes you want to override it. Some markdown platforms (e.g. MkDocs)
allow you to set *custom* heading IDs to override the automatically chosen
value.

Setting a custom ID:

```md
## How to foo a bar {:#foo}
```

This is helpful when you think that readers are likely to frequently link
to this section in the future.

* Custom heading IDs will remain the same (thus preserving incoming links) even
after the text of the heading is edited.
* Custom heading IDs create shorter URLs.

Custom heading IDs only work in MkDocs when the following code is used to enable
the [Attribute Lists](https://pythonhosted.org/Markdown/extensions/attr_list.html)
extension:

```yml
markdown_extensions:
  - markdown.extensions.attr_list
```

## Lists

### Unordered lists

```md
*   My first item is here.
*   My second item is here and a
    bit longer than the first.
*   Then, a third.
```

Alternate syntax:

*   Unordered lists also recognize `-` and `+` as item delimiters.
*   Markdown is somewhat flexible with the quantity and position of spaces when
    making lists, but using 3 spaces after the dash means that sub-lists look
    nicer in code.


### Ordered lists

```md
1.  Item
1.  Item
1.  Item
```

Alternate syntax:

*   Ordered lists items are automatically re-numbered sequentially upon display
    which means all items can begin with `1`, or they can be ordered
    sequentially in code.


### Nested lists

List items must be indented 4 spaces:

```md
1.  Item
    1.  Item
    1.  Item
1.  Item
    * Item
    * Item
    * Item
1. Item
```


## Code

### Inline code

Use backticks to create inline monospace text:

```md
Some `monospace text` amid a paragraph.
```

And if you need to put a backtick inside your monospace text, begin and end
with two backticks:

```md
Some ``monospace text with `backticks` inside``, and all amid a paragraph.
```


### Code blocks

A block of **"fenced code"** with three or more backticks on their own line.

````md
```
CODE
BLOCK
```
````

*Fenced code can use more backticks when necessary to represent code with
3 backticks (which is what you'd see in the source for this page).*

Alternate syntax: For fenced code, the tilde `~` character also works
in place of the backtick character but should be avoided for consistency.


A block of **"indented code"** with four spaces at the start of each line:

```md
    CODE
    BLOCK
```


### Syntax highlighting for code

*   For code blocks, most platforms (e.g. mkdocs, GitHub) will guess guess the
    language of the code and automatically apply syntax highlighting to the
    display.
*   To force a particular type of syntax highlighting, use fenced code with a
    keyword (like `javascript` in this case) as follows:

        ```javascript
        var cj = CRM.$ = jQuery;
        ```

*   Available language keywords for forced syntax highlighting differ slightly
    by markdown platform, but here are some common ones:
    `as`, `atom`, `bas`, `bash`, `boot`, `c`, `c++`, `cake`, `cc`, `cjsx`,
    `cl2`, `clj`, `cljc`, `cljs`, `cljsc`, `cljs.hl`, `cljx`, `clojure`,
    `coffee`, `_coffee`, `coffeescript`, `cpp`, `cs`, `csharp`, `cson`, `css`,
    `d`, `dart`, `delphi`, `dfm`, `di`, `diff`, `django`, `docker`,
    `dockerfile`, `dpr`, `erl`, `erlang`, `f90`, `f95`, `freepascal`, `fs`,
    `fsharp`, `gcode`, `gemspec`, `go`, `groovy`, `gyp`, `h`, `h++`,
    `handlebars`, `haskell`, `haxe`, `hbs`, `hic`, `hpp`, `hs`, `html`,
    `html.handlebars`, `html.hbs`, `hx`, `iced`, `irb`, `java`, `javascript`,
    `jinja`, `jl`, `js`, `json`, `jsp`, `jsx`, `julia`, `kotlin`, `kt`, `ktm`,
    `kts`, `lazarus`, `less`, `lfm`, `lisp`, `lpr`, `lua`, `m`, `mak`,
    `makefile`, `markdown`, `matlab`, `md`, `mk`, `mkd`, `mkdown`, `ml`, `mm`,
    `nc`, `objc`, `obj-c`, `objectivec`, `ocaml`, `osascript`, `pas`, `pascal`,
    `perl`, `php`, `pl`, `plist`, `podspec`, `powershell`, `pp`, `ps`, `ps1`,
    `puppet`, `py`, `python`, `r`, `rb`, `rs`, `rss`, `ruby`, `rust`, `scala`,
    `scheme`, `scm`, `scpt`, `scss`, `sh`, `sld`, `smalltalk`, `sql`, `st`,
    `swift`, `tex`, `thor`, `v`, `vb`, `vbnet`, `vbs`, `vbscript`, `veo`,
    `xhtml`, `xml`, `xsl`, `yaml`, `zsh`
*   Syntax highlighting cannot be forced for indented code.
*   Syntax highlighting is not available for inline code.
*   [Stack Exchange syntax highlighting][stack exchange syntax highlighting] is
    done differently.

[stack exchange syntax highlighting]: http://stackoverflow.com/editing-help#syntax-highlighting

### Code blocks within lists

You can use **indented code within lists** by keeping a blank line
above/below and indenting *4 spaces more than your list content*, like this:

```md
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

**Fenced code within lists** is shown below. It works on GitHub but **not
mkdocs**:

````md
*   First item
*   Look at this code:

    ```md
    code
    block
    ```

*   More list items
````

## Admonitions

### Types

!!! note
    I am a "note" admonition.

!!! tip
    I am a "tip" admonition.

!!! warning
    I am a "warning" admonition.

!!! danger
    I am a "danger" admonition.

Other types

*   "hint", "important" (visually identical to "tip")
*   "attention", "caution" (visually identical to "warning")
*   "error" (visually identical to "danger")

### Syntax

Simple example:

```md
!!! note
    This feature is only available as of CiviCRM 4.5.
```

Add a custom title (make sure to quote the title):

```md
!!! danger "Don't try this at home!"
    Stand back. I'm about to try science!
```

### Enabling

Admonitions only work in MkDocs when the following code is used to enable
the [Admonition extension](https://pythonhosted.org/Markdown/extensions/admonition.html):

```yml
markdown_extensions:
  - markdown.extensions.admonition
```

## Images

Images function mostly the same as hyperlinks, but preceded by an exclamation
point and with alt text in place of the link text.

```md
![Alt text](image.png)
```

or

```md
![Alt text][id]

[id]: image.png
```


## Other markdown syntax

*   [Tables] (to be avoided when possible)
*   [Emojis] (great for Mattermost)
*   Blockquotes

        > This text is a blockquote, typically used
        > to represent prose written by a person. It
        > will be displayed slightly indented.

[Emojis]: http://www.webpagefx.com/tools/emoji-cheat-sheet/
[Tables]: https://help.github.com/articles/organizing-information-with-tables

## External references

*   [Mattermost markdown](https://docs.mattermost.com/help/messaging/formatting-text.html)
*   [Stack Exchange markdown](http://stackoverflow.com/editing-help)
*   [GitHub markdown](https://help.github.com/categories/writing-on-github/)
*   [Official markdown reference](https://daringfireball.net/projects/markdown/syntax)
    (though somewhat difficult to read)


