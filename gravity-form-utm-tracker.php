<?php
/**
 * Plugin Name: Gravity Forms UTM Tracker
 * Plugin URI:  https://github.com/Oleraj09/GravityForm-UTM-Tracker
 * Description: Automatically captures UTM parameters (utm_source, utm_medium, utm_campaign, utm_term, utm_content) from URLs and populates corresponding Gravity Forms fields for advanced lead tracking and analytics.
 * Version:     1.0.0
 * Author:      Oleraj Hossin
 * Author URI:  https://olerajhossin.top
 * Text Domain: gf-utm-tracker
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.0
 * GF requires at least: 2.0
 */

if (!defined('ABSPATH'))
    exit;

require_once plugin_dir_path(__FILE__) . 'include/class-utm-tracker.php';

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style(
        'gravity-form-utm-tracker',
        plugin_dir_url(__FILE__) . 'assets/css/gf-utm-tracker.css',
        [],
        '1.0'
    );
});

add_action('plugins_loaded', function () {
    Gravity_Form_UTM_Tracker::get_instance();
});
