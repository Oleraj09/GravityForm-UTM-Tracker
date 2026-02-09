<?php
namespace GravityFormUTMTracker;

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
            'easy-utm-tracker-for-gravity-forms',
            GRAVITY_FORM_UTM_TRACKER_URL . 'assets/js/gf-utm-tracker.js',
        ['jquery'],
            GRAVITY_FORM_UTM_TRACKER_VERSION,
            true
        );
    }

    public function enqueue_admin_styles()
    {
        wp_enqueue_style(
            'gravity-form-utm-tracker',
            GRAVITY_FORM_UTM_TRACKER_URL . 'assets/css/gf-utm-tracker.css',
        [],
            GRAVITY_FORM_UTM_TRACKER_VERSION
        );
    }
}
