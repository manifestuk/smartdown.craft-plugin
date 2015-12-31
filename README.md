# SmartDown for Craft #
SmartDown for Craft is a Twig Filter which brings the unbridled joy of [Markdown Extra][md_extra] and [Smartypants][smartypants] to [Craft][craft].

[craft]:http://buildwithcraft.com
[md_extra]:http://michelf.ca/projects/php-markdown/
[smartypants]:http://michelf.ca/projects/php-smartypants/

Craft already supports standard Markdown, but sadly standard Markdown doesn't support lots of useful things such as footnotes, fenced code blocks, and tables. It also does nothing to spruce up your typography, leaving your site with an embarrassment of straight quotes, and faux ellipses.

SmartDown plugs both of these gaps, turning your website into a typographic dreamboat.

## Installation ##
1. [Download][download] and unzip SmartDown.
2. Copy the `src/smartdown` folder to your `craft/plugins/` directory.
3. Navigate to the "[Craft Admin &rarr; Settings &rarr; Plugins][plugins]" page, and activate SmartDown.

[download]: https://github.com/experience/smartdown.craft-plugin/archive/master.zip
[plugins]: http://docs.buildwithcraft.com/cp/settings/plugins.html

## Basic usage ##
Use the SmartDown filter in exactly the same way as any other Twig filter.

    {{ myVariable | smartdown }}

This will parse your content with both Markdown Extra, and Smartypants, turning this:

    "Outside of a dog, a book is a man's best friend. Inside a dog it's too dark to read..."

Into this:

> "Outside of a dog, a book is a man's best friend. Inside a dog it's too dark to read..."

## Filter parameters ##

### markup ###
The `markup` filter parameter controls whether the text will be parsed using MarkdownExtra. The default value is `true`.

Example usage:

    {{ content | smartdown(markup=false) }}

### typography ###
The `typography` filter parameter controls whether the text will be parsed using SmartyPants. The default value is `true`.

Example usage:

    {{ content | smartdown(typography=false) }}

## Deprecated filter parameters ##
As of version 1.0.0, the following parameters have been officially deprecated:

- `markdown`: use `markup` instead.
- `smartypants`: use `typography` instead.
