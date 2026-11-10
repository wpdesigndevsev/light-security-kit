=== Light Security Kit ===
Contributors: webpressdesing
Tags: security, xml-rpc, hide version, disable file editor, cleanup
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight kit that disables XML-RPC, hides the WordPress version, blocks the file editor and applies simple security and performance tweaks.

== Description ==

Light Security Kit bundles several small but effective measures into a single, lightweight plugin. No bloat, no complex settings, no database tables. Just check the boxes you need and save.

It includes the following tools:

1. **Disable XML-RPC** — Blocks `xmlrpc.php` to prevent brute force attacks.
2. **Hide WordPress version** — Removes the `generator` meta tag from the source code and RSS feeds.
3. **Disable file editor** — Prevents editing PHP files of themes and plugins from the dashboard.
4. **Clean up the `<head>`** — Removes unnecessary links (wlwmanifest, shortlink, REST, adjacent posts) to save resources.
5. **Block REST API for visitors** — Prevents unauthenticated visitors from using the REST API. Disabled by default because it may affect the block editor.
6. **Block user enumeration** — Prevents scanning usernames with `?author=1` and removes the users endpoints from the REST API.
7. **Disable emojis** — Removes WordPress emoji scripts and styles.

All tools are controlled from **Settings > Light Security**. Each one can be enabled or disabled independently.

== Installation ==

1. Upload the `light-security-kit` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings > Light Security** to configure the options.

== Frequently Asked Questions ==

= Will this break my site? =

No, if you use WordPress normally. Only enable the REST API block if you do not need unauthenticated access to the API.

= Can I enable only one of the options? =

Yes. Each checkbox is independent.

= Does the plugin modify files on my server? =

No. Everything is done through WordPress filters and constants. It does not touch `.htaccess`, theme files or other plugins.

= Is it translation-ready? =

Yes. The plugin uses the `light-security-kit` text domain and includes a Spanish translation. You can add your own `.po` and `.mo` files in the `languages/` folder.

== Screenshots ==

1. Settings page with all the available options.

== Changelog ==

= 1.2.0 =
* Added translation support (text domain, Domain Path, Spanish translation).
* Renamed functions with the `lsk_` prefix.
* All visible strings are now translatable.
* Fixed `$_GET['author']` sanitization for Plugin Check.

= 1.1.0 =
* Added settings page with checkboxes.
* Added option to hide the WordPress version.
* Added option to disable the file editor.

= 1.0.0 =
* Initial release. Disables XML-RPC and removes the RSD link from the head.
