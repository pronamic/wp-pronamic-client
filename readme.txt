=== Pronamic Client ===
Contributors: pronamic, remcotolsma
Tags: pronamic
Requires at least: 3.0
Tested up to: 6.5
Stable tag: 2.0.2

WordPress plugin for Pronamic clients.

== Description ==

The Pronamic Client plugin is a handy plugin for all WordPress users who use plugins or themes developed by Pronamic.


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.


== Screenshots ==

1.	Checklist


== Changelog ==

= 1.9.8 =
*	Update feed URLs.
*	Also override on SEO tools page.

= 1.9.7 =
*	Improved support with SiteGround Security plugin.
*	Query Monitor plugin locale is forced to 'en_US'.

= 1.9.6 =
*	Tested up to WordPress `6.0`.
*	Improved the `readme.txt` file.

= 1.9.5 =
*   Removed the 'Links' section from the `readme.txt` file.

= 1.9.4 =
*   Also override `XMLHttpRequest.prototype.open` on SEO workouts screen.

= 1.9.3 =
*   Check if URL startsWith with specific URL, instead of `URL( url ).host` for relative URLs support.

= 1.9.2 =
*   Only override `XMLHttpRequest.prototype.open` on post edit screen.

= 1.9.1 =
*   Fixed morphology feature not working error.

= 1.9.0 =
*   Hide Gravity Forms license key details for all users except `pronamic`.

= 1.8.5 =
*   Updated Adminer to 4.8.1 for MySQL and English only.
*   Added dkimvalidator.com to email testing tools.
*   Patched Bootstrap `3.3` to version `3.3.7` due to jQuery 3 support (https://github.com/twbs/bootstrap/releases/tag/v3.3.7).

= 1.8.4 =
*	Fixed PHP warning `array_merge(): Expected parameter 2 to be an array, null given`.

= 1.8.3 =
*	Updated Adminer to 4.7.8 for MySQL and English only.

= 1.8.2 =
*	Patch "Easy SwipeBox" plugin.

= 1.8.1 =
*	Patch Swipebox.

= 1.8.0 =
*	Fixed Google Analytics script tag.
*	Fixed headers of boxes on dashboard page.
*	Updated email test page fields.
*	Simplified authentication.

= 1.7.0 =
*	The Pronamic plugins and themes updater now works more like WordPress core.
*	IP addresses of hits sent to Google Analytics are anonymized by default.

= 1.6.1 =
*	Clear the plugins and themes update cache after updating.

= 1.6.0 =
*	Updated Adminer to version 4.7.7.
*	Added email test from, subject, message and headers fields.

= 1.5.0 =
*	Added new SVG admin menu icon.
*	Block access to Akismet stats page
*	Added Google Analytics feature.
*	Added Google Tag Manager feature.

= 1.4.0 =
*	Added email test page.
*	Added setting for PHPMailer sender.
*	Added scripts integration.
*	Fixed error when network activated.
*	Updated Adminer to version 4.7.5.
*	Removed status page in favor of Site Health.

= 1.3.2 =
*	Updated Adminer to version 4.7.0.
*	Fixed `PHP Fatal error: Uncaught Error: Call to undefined function get_plugins()` errors.
*	Disable Jetpack just in time messages for Pronamic user.

= 1.3.1 =
*	Improved updater.
*	Updated Adminer to version 4.3.1.

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
