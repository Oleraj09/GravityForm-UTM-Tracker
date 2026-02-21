<?php
/**
 * Plugin Name: MZ UTM Tracker for Gravity Form
 * Plugin URI:  https://github.com/oleraj09/GravityForm-UTM-Tracker
 * Description: Automatically captures UTM parameters (utm_source, utm_medium, utm_campaign, utm_term, utm_content) from URLs and populates corresponding Gravity Forms fields.
 * Version:     1.1.2
 * Author:      Oleraj Hossin
 * Author URI:  https://olerajhossin.top
 * Text Domain: mondoloz-utm-tracker-for-gravity-forms
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.0
 * GF requires at least: 2.0
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MONDOLOZ_GF_UTM_TRACKER_VERSION', '1.1.2');
define('MONDOLOZ_GF_UTM_TRACKER_PATH', plugin_dir_path(__FILE__));
define('MONDOLOZ_GF_UTM_TRACKER_URL', plugin_dir_url(__FILE__));

require_once MONDOLOZ_GF_UTM_TRACKER_PATH . 'includes/class-core.php';
require_once MONDOLOZ_GF_UTM_TRACKER_PATH . 'includes/class-assets.php';

add_action('plugins_loaded', function () {
    if (class_exists('GFForms')) {
        \Mondoloz\GravityFormUTMTracker\Core::get_instance();
        \Mondoloz\GravityFormUTMTracker\Assets::get_instance();
    }
});
