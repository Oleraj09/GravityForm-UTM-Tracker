<?php
if (!defined('ABSPATH')) exit;

class Gravity_Form_UTM_Tracker {

    private static $instance = null;
    private $utm_keys = ["utm_id","utm_source","utm_medium","utm_campaign","utm_term","utm_content"];

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        foreach ($this->utm_keys as $key) {
            add_filter("gform_field_value_{$key}", [$this, 'get_utm_value']);
        }

        add_filter('gform_pre_render', [$this, 'add_hidden_utm_fields']);
        add_filter('gform_pre_validation', [$this, 'add_hidden_utm_fields']);
        add_filter('gform_pre_submission_filter', [$this, 'add_hidden_utm_fields']);
        add_filter('gform_admin_pre_render', [$this, 'add_hidden_utm_fields']);

        add_filter('gform_pre_submission_filter', [$this, 'populate_hidden_utm_fields']);

        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function get_utm_value($value) {
        return '';
    }

    public function add_hidden_utm_fields($form) {
        foreach ($this->utm_keys as $key) {
            $exists = false;
            foreach ($form['fields'] as $field) {
                if (isset($field->inputName) && $field->inputName === $key) {
                    $exists = true;
                    break;
                }
            }
            if ($exists) continue;

            $field = new GF_Field_Hidden();
            $field->label = strtoupper(str_replace('_',' ',$key));
            $field->inputName = $key;
            $field->cssClass = $key . ' gf-column';
            $field->id = 1000 + count($form['fields']);
            $field->visibility = 'hidden';
            $field->allowsPrepopulate = true;
            $field->isRequired = false;

            $form['fields'][] = $field;
        }
        return $form;
    }

    public function populate_hidden_utm_fields($form) {
        foreach ($this->utm_keys as $key) {
            foreach ($form['fields'] as $field) {
                if (isset($field->inputName) && $field->inputName === $key) {
                    if (isset($_POST[$key]) && !empty($_POST[$key])) {
                        $_POST["input_{$field->id}"] = sanitize_text_field($_POST[$key]);
                    }
                }
            }
        }
        return $form;
    }

    public function enqueue_scripts() {
        $plugin_url = plugin_dir_url(__FILE__);
        wp_enqueue_script(
            'gf-utm-tracker',
            $plugin_url . '../assets/js/gf-utm-tracker.js',
            ['jquery'],
            '1.0',
            true
        );
    }
}
