<?php
/**
 * Plugin Name: Gravity Form UTM Tracker
 * Description: Automatically captures UTM parameters and injects them into Gravity Forms fields.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH'))
    exit;

// Include classes
require_once plugin_dir_path(__FILE__) . 'include/class-utm-tracker.php';

// Enqueue CSS
add_action('wp_enqueue_scripts', function () {
    wp_enqueue_style(
        'gravity-form-utm-tracker',
        plugin_dir_url(__FILE__) . 'assets/css/gf-utm-tracker.css',
        [],
        '1.0'
    );
});

// Initialize plugin
add_action('plugins_loaded', function () {
    Gravity_Form_UTM_Tracker::get_instance();
});
