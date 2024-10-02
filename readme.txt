=== Pronamic Client ===
Contributors: pronamic, remcotolsma
Tags: pronamic
Requires at least: 3.0
Tested up to: 6.6
Stable tag: 2.0.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

WordPress plugin for Pronamic clients.

== Description ==

[Pronamic](https://www.pronamic.eu/) · [GitHub](https://github.com/pronamic/wp-pronamic-client)

The Pronamic Client plugin is a handy plugin for all WordPress users who use plugins or themes developed by Pronamic.


== Installation ==

Extract the zip file and just drop the contents in the wp-content/plugins/ directory of your WordPress installation and then activate the Plugin from Plugins page.


== Screenshots ==

1.	Checklist


== Changelog ==

<!-- Start changelog -->

### [2.0.3] - 2024-10-02

#### Commits

- Switch to `override_load_textdomain` filter instead of `plugin_locale` filter. ([60c621b](https://github.com/pronamic/wp-pronamic-client/commit/60c621b5590951ac9eaf9f55d07bc5ef7cfcf87f))
- Added `$admin_notices` property. ([2d152ba](https://github.com/pronamic/wp-pronamic-client/commit/2d152ba426b5918c7514dcc8d1cad3b7d32d0c22))
- Tested up to: 6.6 ([013ba16](https://github.com/pronamic/wp-pronamic-client/commit/013ba16186b560dd325ffaa25e14b1cd4f37d811))

#### Composer

- Changed `automattic/jetpack-autoloader` from `v3.0.7` to `v3.1.0`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v3.1.0

Full set of changes: [`2.0.2...2.0.3`][2.0.3]

[2.0.3]: https://github.com/pronamic/wp-pronamic-client/compare/v2.0.2...v2.0.3

### [2.0.2] - 2024-05-15

#### Commits

- Tested up to: 6.5. ([98df5b7](https://github.com/pronamic/wp-pronamic-client/commit/98df5b737910be38a0512c054747202001bc847c))
- Removed virus scanner, no longer used. ([3c44fe2](https://github.com/pronamic/wp-pronamic-client/commit/3c44fe2cdb273fd0cdde65b422e6e3ad7710f913))
- Added support for SQLite. ([b0f3968](https://github.com/pronamic/wp-pronamic-client/commit/b0f396867c4b761008e743bc207cd6a358d4e52e))
- Updated Adminder to full version with SQLite support. ([eddf9b8](https://github.com/pronamic/wp-pronamic-client/commit/eddf9b8ba43fade5c6dafb4b569aa89ea5544d4d))

#### Composer

- Changed `automattic/jetpack-autoloader` from `v2.12.0` to `v3.0.7`.
	Release notes: https://github.com/Automattic/jetpack-autoloader/releases/tag/v3.0.7
- Changed `pronamic/pronamic-mollie-user-agent` from `v1.0.0` to `v1.0.1`.
	Release notes: https://github.com/pronamic/pronamic-mollie-user-agent/releases/tag/v1.0.1
- Changed `pronamic/pronamic-wp-updater` from `v1.0.0` to `v1.0.2`.
	Release notes: https://github.com/pronamic/pronamic-wp-updater/releases/tag/v1.0.2

Full set of changes: [`2.0.1...2.0.2`][2.0.2]

[2.0.2]: https://github.com/pronamic/wp-pronamic-client/compare/v2.0.1...v2.0.2

### [2.0.1] - 2024-02-29

#### Commits

- Bump composer/composer from 2.2.22 to 2.2.23 ([456bc4d](https://github.com/pronamic/wp-pronamic-client/commit/456bc4d798b6b8fd5d24a2930eb777d310dfecab))

Full set of changes: [`2.0.0...2.0.1`][2.0.1]

[2.0.1]: https://github.com/pronamic/wp-pronamic-client/compare/v2.0.0...v2.0.1

### [2.0.0] - 2023-11-29

#### Commits

- Use `str_start_with`, WordPress has polyfill. ([035d940](https://github.com/pronamic/wp-pronamic-client/commit/035d94043247c2018a1913aa3a2aad4997390ea3))
- Added the new Mollie user agent library. ([666d48b](https://github.com/pronamic/wp-pronamic-client/commit/666d48bdd78d8a3852198a51623d725577051742))
- Use the new Pronamic updater library. ([32b5ad0](https://github.com/pronamic/wp-pronamic-client/commit/32b5ad0a3659a872caa5fa49facf2170699ceb4e))
- ncu ([86ea0b1](https://github.com/pronamic/wp-pronamic-client/commit/86ea0b166cb0e5dd8ee3fa155b2bf7d8ec737e41))
- Use Jetpack autoloader. ([962d77a](https://github.com/pronamic/wp-pronamic-client/commit/962d77af67855daa21669950a5e447fcf513994b))
- Fixed PHP warnings about dynamic properties. ([e85404d](https://github.com/pronamic/wp-pronamic-client/commit/e85404d60853ba83e5fa26a2a34b7ad6860c9481))

#### Composer

- Added `php` `>=8.0`.
- Added `automattic/jetpack-autoloader` `^2.12`.
- Added `pronamic/pronamic-mollie-user-agent` `^1.0`.
- Added `pronamic/pronamic-wp-updater` `^1.0`.

Full set of changes: [`1.9.8...2.0.0`][2.0.0]

[2.0.0]: https://github.com/pronamic/wp-pronamic-client/compare/v1.9.8...v2.0.0

### 1.9.8
- Update feed URLs.
- Also override on SEO tools page.

### 1.9.7
- Improved support with SiteGround Security plugin.
- Query Monitor plugin locale is forced to 'en_US'.

### 1.9.6
- Tested up to WordPress `6.0`.
- Improved the `readme.txt` file.

### 1.9.5
- Removed the 'Links' section from the `readme.txt` file.

### 1.9.4
- Also override `XMLHttpRequest.prototype.open` on SEO workouts screen.

### 1.9.3
- Check if URL startsWith with specific URL, instead of `URL( url ).host` for relative URLs support.

### 1.9.2
- Only override `XMLHttpRequest.prototype.open` on post edit screen.

### 1.9.1
- Fixed morphology feature not working error.

### 1.9.0
- Hide Gravity Forms license key details for all users except `pronamic`.

### 1.8.5
- Updated Adminer to 4.8.1 for MySQL and English only.
- Added dkimvalidator.com to email testing tools.
- Patched Bootstrap `3.3` to version `3.3.7` due to jQuery 3 support (https://github.com/twbs/bootstrap/releases/tag/v3.3.7).

### 1.8.4
- Fixed PHP warning `array_merge(): Expected parameter 2 to be an array, null given`.

### 1.8.3
- Updated Adminer to 4.7.8 for MySQL and English only.

### 1.8.2
- Patch "Easy SwipeBox" plugin.

### 1.8.1
- Patch Swipebox.

### 1.8.0
- Fixed Google Analytics script tag.
- Fixed headers of boxes on dashboard page.
- Updated email test page fields.
- Simplified authentication.

### 1.7.0
- The Pronamic plugins and themes updater now works more like WordPress core.
- IP addresses of hits sent to Google Analytics are anonymized by default.

### 1.6.1
- Clear the plugins and themes update cache after updating.

### 1.6.0
- Updated Adminer to version 4.7.7.
- Added email test from, subject, message and headers fields.

### 1.5.0
- Added new SVG admin menu icon.
- Block access to Akismet stats page
- Added Google Analytics feature.
- Added Google Tag Manager feature.

### 1.4.0
- Added email test page.
- Added setting for PHPMailer sender.
- Added scripts integration.
- Fixed error when network activated.
- Updated Adminer to version 4.7.5.
- Removed status page in favor of Site Health.

### 1.3.2
- Updated Adminer to version 4.7.0.
- Fixed `PHP Fatal error: Uncaught Error: Call to undefined function get_plugins()` errors.
- Disable Jetpack just in time messages for Pronamic user.

### 1.3.1
- Improved updater.
- Updated Adminer to version 4.3.1.

### 1.3.0
- Tweak - Changed admin h2 elements to h1 elements.
- Tweak - Changed admin h3 elements to h2 elements.
- Tweak - Added total size of autoload options on status page.
- Tweak - Added total size of transient options on status page.
- Tweak - Added the `time` and `current_time` to status page.
- Tweak - Updated Adminer from 4.2.1 to 4.2.4.

### 1.2.9
- Tweak - Improved status and checklist page.

### 1.2.8
- Tweak - Added ABSPATH to the admin status page.
- Tweak - Updated Adminer to version 4.2.1.

### 1.2.7
- Tweak - Updated Adminer to version 4.2.0.
- Tweak - Improved retrieving database (MySQL) version on status page.
- Tweak - Replace use of WPLANG constant with get_locale() function.
- Tweak - Removed wp_title() function check from the checklist.
- Tweak - Removed the "W3 Total Cache" plugin from the checklist.
- Tweak - Removed the "WP-Mail-SMTP" from the checklist.

### 1.2.6
- Tweak - Changed HappyWP RSS feed to the English Pronamic RSS feed.
- Test - Tested up to WordPress version 4.1.

### 1.2.5
- Tweak - Use the new Pronamic API endpoints (api.pronamic.eu).

### 1.2.4
- Tweak - Improved support for Adminer.

### 1.2.3
- Tweak - WordPress Coding Standards optimizations.
- Tweak - Improved security of Adminer, now only accessible from WordPress admin.

### 1.2.2
- Updated Adminer to version 4.1.0.
- Tested up to WordPress version 3.9.

### 1.2.1
- Feature - Added [Adminer](http://www.adminer.org/) to the Pronamic dashboard.
- Tweak - Improved the Pronamic dashboard layout.
- Tweak - Removed deprecated [screen_icon](http://codex.wordpress.org/Function_Reference/screen_icon) function calls.

### 1.2.0
- Added an WordPress admin status page with info about versions, plugins and more.

### 1.1.5
- Added support for Pronamic extension updates from http://www.happywp.com/.

### 1.1.4
- Improved check on plugin and themes update.

### 1.1.3
- Improved Fix PHP Warning: array_merge(): Argument #1 is not an array.
- Removed Pronamic admin footer text.

### 1.1.2
- Fix PHP Warning: array_merge(): Argument #1 is not an array.

### 1.1.1
- Fix Notice: Undefined property: stdClass::$response.

### 1.1.0
- Added support for Pronamic extension updates from http://wp.pronamic.eu/.

### 1.0.0
- Added custom capability for Pronamic clients.
- Removed the BackWPUp plugin from the recommended plugins list.
- Update Pronamic phone number.

### 0.2.1
- Added the "Regenerate Thumbnails" plugin to the checklist.
- Added the "Posts 2 Posts" plugin to the checklist.
- Added the "InfiniteWP Client" plugin to the checklist.
- Improved some checks on the checklist.

### 0.2
- Added bulk delete function to the virusscanner.
- Added check for wp_title('') in header.php.
- Improved some translations.

### 0.1.1
- Added the "Sucuri Scanner" plugin to the checklist.

### 0.1
- Initial release.

<!-- End changelog -->
