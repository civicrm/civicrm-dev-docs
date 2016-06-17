Overview
========

In order for your CiviCRM extension to be compliant, you must provide a minimal extension documentation, written in [Markdown](https://guides.github.com/features/mastering-markdown/) language.

The allowed structure is the following:

* Headings (eg. [Heading 1](#heading-1), [Heading 2](#heading-2), [Heading 3](#heading-3))

* [Styling Texts](#styling-text)

* [Blockquotes](#blockquotes)

* [Unordered Lists](#unordered-lists)

* [Ordered Lists](#ordered lists)

* [Nested Lists](#nested-lists)

* Inline code block (see [example](#code-inline))

* [Links](#links)

* Table creation - Avoid creating tables! If you still want to use them, avoid complex columns/rows

Examples
========

Heading 1
---------
````javascript
This is a heading 1
===================
````

Heading 2
---------
````javascript
This is a heading 2
-------------------
````

Heading 3
---------

````javascript
### This is a heading 3

````

Trivial Headings
----------------

Standard Markdown headings are treated as navigational elements and added to
the navbar. However, some headings indicate trivial distinctions among
subsections and should not be highlighted in the navigation. Demarcate these
using raw HTML.

```html
<h3>Local Heading</h3>
```


Styling Text
------------

Both bold and italic can use either a * or an _ around the text for styling. This allows you to combine both bold and italic if needed.


````
*This text will be italic*
**This text will be bold**
````

Blockquotes
-----------

You can indicate blockquotes with a >.

````
In the words of Abraham Lincoln:

> Pardon my french
````

Lists
-----

Unordered lists
---------------

You can make an unordered list by preceding list items with either a * or a -.

````
* Item
* Item
* Item

- Item
- Item
- Item
````

Ordered lists
-------------

You can make an ordered list by preceding list items with a number.

````
1. Item 1
2. Item 2
3. Item 3
````


Nested lists
------------

You can create nested lists by indenting list items by two spaces.

````
1. Item 1
  1. A corollary to the above item.
  2. Yet another point to consider.
2. Item 2
  * A corollary that does not need to be ordered.
    * This is indented four spaces, because it's two spaces further than the item above.
    * You might want to consider making a new list.
3. Item 3
````



Inline code block
-----------------

### Inline formats

Use single backticks (`) to format text in a special monospace format. Everything within the backticks appear as-is, with no other special formatting.

Here's an idea: why don't we take `SuperiorProject` and turn it into `**Reasonable**Project`.

### Multiple lines

You can use prepend and append triple backticks (```) to format text as its own distinct block.

Check out this neat program I wrote:

```
x = 0
x = 2 + 2
what is x
```

Links
-----

You can create an inline link by wrapping link text in brackets ( [ ] ), and then wrapping the link in parentheses ( ( ) ). 

````
This is a [demo link](https://www.google.com)
````