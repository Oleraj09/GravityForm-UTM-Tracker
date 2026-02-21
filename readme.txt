=== MZ UTM Tracker for Gravity Form ===
Contributors: mondoloz
Tags: gravity forms, utm, tracking, analytics, marketing
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Automatically captures UTM parameters from URLs and populates corresponding Gravity Forms fields for advanced lead tracking.

== Description ==

**MZ UTM Tracker for Gravity Form** allows you to easily track your marketing campaigns by capturing UTM parameters from the URL and saving them into your Gravity Forms submissions.

The plugin automatically detects the following UTM parameters:
*   `utm_source`
*   `utm_medium`
*   `utm_campaign`
*   `utm_term`
*   `utm_content`
*   `utm_id`

### Key Features
*   **Automatic Capture**: Detects UTM parameters in the URL and stores them in the user's browser (Local Storage).
*   **Form Population**: Automatically populates hidden fields in your Gravity Forms with the captured data.
*   **Field Creation**: Automatically adds hidden fields to your forms if they don't exist (optional but recommended to add them manually to map them correctly).
*   **Cross-Page Tracking**: Because data is stored in Local Storage, the user can navigate your site before submitting the form, and the UTMs will still be captured.
*   **Clean URLs**: Optionally cleans up the URL after capturing the data.

== Installation ==

1.  Upload the plugin files to the `/wp-content/plugins/mondoloz-utm-tracker-for-gravity-forms` directory, or install the plugin through the WordPress plugins screen directly.
2.  Activate the plugin through the 'Plugins' screen in WordPress.
3.  That's it! The plugin works automatically. Just ensure your Gravity Forms have fields with the "Input Name" set to `utm_source`, `utm_medium`, etc., or let the plugin add them as hidden fields.

== Frequently Asked Questions ==

= Does this work with AJAX forms? =
Yes, it works with both AJAX and standard form submissions.

= Does this work with caching? =
Yes, since the population happens via JavaScript, it is compatible with most caching plugins.

== Changelog ==

= 1.1.2 =
*   Renamed plugin to "MZ UTM Tracker for Gravity Form" to comply with WordPress repository guidelines.
*   Updated text domain and internal structure.

= 1.1.1 =
*   Fixed security warnings.
*   Fixed issue with UTM formatting (space encoding).
*   Renamed plugin to avoid collisions.

= 1.1.0 =
*   Refactored codebase for better performance and standards.
*   Fixed issue where Local Storage was not cleared on non-AJAX redirects.
*   Added `utm_id` support.

= 1.0.0 =
*   Initial release.

