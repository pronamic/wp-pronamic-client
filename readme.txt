=== Pronamic Client ===
Contributors: pronamic, remcotolsma 
Tags: pronamic, client, update, plugin, theme, extension, plugins, themes, extensions
Requires at least: 3.0
Tested up to: 4.2.2
Stable tag: 1.3.0

WordPress plugin for Pronamic clients.

== Description ==

The Pronamic CLient plugin is a handy plugin for all WordPress users who use 
plugins or themes developed by Pronamic. It contains an checklist, virus scanner
and an Pronamic extensions overview list.


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your 
WordPress installation and then activate the Plugin from Plugins page.


== Screenshots ==

1.	Checklist
2.	Virus Scanner


== Changelog ==

= 1.3.0 =
*	Tweak - Changed admin h2 elements to h1 elements.
*	Tweak - Changed admin h3 elements to h2 elements.
*	Tweak - Added total size of autoload options on status page.
*	Tweak - Added total size of transient options on status page.
*	Tweak - Added the `time` and `current_time` to status page.
*	Tweak - Updated Adminer from 4.2.1 to 4.2.4.

= 1.2.9 =
*	Tweak - Improved status and checklist page.

= 1.2.8 =
*	Tweak - Added ABSPATH to the admin status page.
*	Tweak - Updated Adminer to version 4.2.1.

= 1.2.7 =
*	Tweak - Updated Adminer to version 4.2.0.
*	Tweak - Improved retrieving database (MySQL) version on status page.
*	Tweak - Replace use of WPLANG constant with get_locale() function.
*	Tweak - Removed wp_title() function check from the checklist.
*	Tweak - Removed the "W3 Total Cache" plugin from the checklist.
*	Tweak - Removed the "WP-Mail-SMTP" from the checklist.

= 1.2.6 =
*	Tweak - Changed HappyWP RSS feed to the English Pronamic RSS feed.
*	Test - Tested up to WordPress version 4.1.

= 1.2.5 =
*	Tweak - Use the new Pronamic API endpoints (api.pronamic.eu).

= 1.2.4 =
*	Tweak - Improved support for Adminer.

= 1.2.3 =
*	Tweak - WordPress Coding Standards optimizations.
*	Tweak - Improved security of Adminer, now only accessible from WordPress admin.

= 1.2.2 =
*	Updated Adminer to version 4.1.0.
*	Tested up to WordPress version 3.9.

= 1.2.1 =
*	Feature - Added [Adminer](http://www.adminer.org/) to the Pronamic dashboard.
*	Tweak - Improved the Pronamic dashboard layout.
*	Tweak - Removed deprecated [screen_icon](http://codex.wordpress.org/Function_Reference/screen_icon) function calls.

= 1.2.0 =
*	Added an WordPress admin status page with info about versions, plugins and more.

= 1.1.5 =
*	Added support for Pronamic extension updates from http://www.happywp.com/.

= 1.1.4 =
*	Improved check on plugin and themes update.

= 1.1.3 =
*	Improved Fix PHP Warning: array_merge(): Argument #1 is not an array.
*	Removed Pronamic admin footer text.

= 1.1.2 =
*	Fix PHP Warning: array_merge(): Argument #1 is not an array.

= 1.1.1 =
*	Fix Notice: Undefined property: stdClass::$response.

= 1.1.0 =
*	Added support for Pronamic extension updates from http://wp.pronamic.eu/.

= 1.0.0 =
*	Added custom capability for Pronamic clients.
*	Removed the BackWPUp plugin from the recommended plugins list.
*	Update Pronamic phone number.

= 0.2.1 =
*	Added the "Regenerate Thumbnails" plugin to the checklist.
*	Added the "Posts 2 Posts" plugin to the checklist.
*	Added the "InfiniteWP Client" plugin to the checklist.
*	Improved some checks on the checklist.

= 0.2 =
*	Added bulk delete function to the virusscanner.
*	Added check for wp_title('') in header.php.
*	Improved some translations.
 
= 0.1.1 =
*	Added the "Sucuri Scanner" plugin to the checklist.

= 0.1 =
*	Initial release.


== Links ==

*	[Pronamic](http://pronamic.eu/)
*	[Remco Tolsma](http://remcotolsma.nl/)
*	[Markdown's Syntax Documentation][markdown syntax]
*	[Special Characters for HTML  & XHTML](http://www.mistywindow.com/reference/html-characters.php)
*	[Adminer](http://www.adminer.org/)

[markdown syntax]: http://daringfireball.net/projects/markdown/syntax
            "Markdown is what the parser uses to process much of the readme file"
