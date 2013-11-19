=== Pronamic Client ===
Contributors: pronamic, remcotolsma 
Tags: pronamic, client, update, plugin, theme, extension, plugins, themes, extensions
Requires at least: 3.0
Tested up to: 3.7.1
Stable tag: 1.1.1

WordPress plugin for Pronamic clients.

== Description ==

The Pronamic CLient plugin is a handy plugin for all WordPress users who use 
plugins or themes developed by Pronamic. It contains an checklist, virus scanner
and an Pronamic extensions overview list.


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your 
WordPress installation and then activate the Plugin from Plugins page.


== Developers ==

*	php ~/wp/svn/i18n-tools/makepot.php wp-plugin ~/wp/git/pronamic-client ~/wp/git/pronamic-client/languages/pronamic_client.pot


== Screenshots ==

1.	Checklist
2.	Virus Scanner


== Changelog ==

= 1.1.2 =
*	Fix PHP Warning: array_merge(): Argument #1 is not an array.

= 1.1.1 =
*	Fix Notice: Undefined property: stdClass::$response.

= 1.1.0 =
*	Added support for Pronamic extension updates from http://wp.pronamic.eu/.

= 1.0.0 =
*	Added custom capability for Pronamic clients
*	Removed the BackWPUp plugin from the recommended plugins list
*	Update Pronamic phone number

= 0.2.1 =
*	Added the "Regenerate Thumbnails" plugin to the checklist
*	Added the "Posts 2 Posts" plugin to the checklist
*	Added the "InfiniteWP Client" plugin to the checklist
*	Improved some checks on the checklist

= 0.2 =
*	Added bulk delete function to the virusscanner
*	Added check for wp_title('') in header.php
*	Improved some translations
 
= 0.1.1 =
*	Added the "Sucuri Scanner" plugin to the checklist

= 0.1 =
*	Initial release


== Links ==

*	[Pronamic](http://pronamic.eu/)
*	[Remco Tolsma](http://remcotolsma.nl/)
*	[Markdown's Syntax Documentation][markdown syntax]
*	[Special Characters for HTML  & XHTML](http://www.mistywindow.com/reference/html-characters.php)

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"