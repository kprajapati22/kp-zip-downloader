=== KP Zip Downloader ===
Contributors: kprajapati22
Tags: plugins, themes, zip, download
Tested up to: 6.9
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

This plugin allows administrators to download installed plugins and themes as ZIP files directly from the WordPress dashboard.

== Description ==

KP Zip Downloader provides an easy way to download installed plugins and themes as ZIP files directly from your WordPress admin dashboard. This tool is particularly useful for developers, site administrators, or anyone needing to quickly access the source files for migration or backup purposes.

== Features ==

* Download any installed plugin as a ZIP file.
* Download any installed theme as a ZIP file.
* Fully integrated with the WordPress admin dashboard.
* Simple and intuitive user interface.
* Lightweight and efficient.

== Installation ==

= Installation from within WordPress =

1. Visit **Plugins > Add New**.
2. Search for **KP Zip Downloader**.
3. Install and activate the KP Zip Downloader
4. The plugin is now ready to use. Go to the Plugins or Themes page and you will see download zip option

= Manual installation =

1. Upload the entire `kp-plugins-and-themes-zip-downloader` folder to the `/wp-content/plugins/` directory.
2. Visit **Plugins**.
3. Activate the KP Zip Downloader plugin.
4. The plugin is now ready to use. Go to the Plugins or Themes page and you will see download zip option

== Screenshots ==

1. **Plugin Action Links: Download a plugin as a ZIP directly from the plugins page.**
2. **Theme Action Links: Download a theme as a ZIP directly from the themes page.**

== Frequently Asked Questions ==

= Can I use this plugin to download inactive plugins or themes? =
Yes, you can download both active and inactive plugins or themes.

= Does this plugin support multisite? =
This plugin is currently designed for single-site installations. Multisite support is not available at the moment.

= Is there any dependency on external libraries? =
No, the plugin uses the built-in WordPress functions and PHP libraries to create ZIP files.

== Changelog ==

= Version 1.0.3 - 2025-12-06 =
* Added plugin/theme name in the root of the zip.

= Version 1.0.2 - 2025-02-11 =
* Fixed download issue when only one theme is available.

= Version 1.0.1 - 2025-01-10 =
* Added pot file.

= Version 1.0 - 2025-01-08 =
* Initial release.
* Added the ability to download installed plugins as ZIP files.
* Added the ability to download installed themes as ZIP files.
* Added nonce verification for security.

== Upgrade Notice ==

= 1.0.0 =
Initial release. No upgrade required.

