<?php
namespace Mondoloz\GravityFormUTMTracker;

if (!defined('ABSPATH')) {
    exit;
}

class Assets
{

    private static $instance = null;

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_styles']);
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'mondoloz-utm-tracker-for-gravity-forms',
            MONDOLOZ_GF_UTM_TRACKER_URL . 'assets/js/mondoloz-gf-utm-tracker.js',
        ['jquery'],
            MONDOLOZ_GF_UTM_TRACKER_VERSION,
            true
        );
    }

    public function enqueue_admin_styles()
    {
        wp_enqueue_style(
            'mondoloz-utm-tracker-for-gravity-forms',
            MONDOLOZ_GF_UTM_TRACKER_URL . 'assets/css/mondoloz-gf-utm-tracker.css',
        [],
            MONDOLOZ_GF_UTM_TRACKER_VERSION
        );
    }
}
