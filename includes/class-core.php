<?php
namespace GravityFormUTMTracker;

if (!defined('ABSPATH')) {
    exit;
}

class Core
{

    private static $instance = null;
    private $utm_keys = ["utm_id", "utm_source", "utm_medium", "utm_campaign", "utm_term", "utm_content"];

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->init_hooks();
    }

    private function init_hooks()
    {
        foreach ($this->utm_keys as $key) {
            add_filter("gform_field_value_{$key}", [$this, 'get_utm_value']);
        }

        add_filter('gform_pre_render', [$this, 'add_hidden_utm_fields']);
        add_filter('gform_pre_validation', [$this, 'add_hidden_utm_fields']);
        add_filter('gform_pre_submission_filter', [$this, 'add_hidden_utm_fields']);
        add_filter('gform_admin_pre_render', [$this, 'add_hidden_utm_fields']);

        add_filter('gform_pre_submission_filter', [$this, 'populate_hidden_utm_fields']);

        // Fix for redirect confirmation
        add_filter('gform_confirmation', [$this, 'append_reset_param_to_redirect'], 10, 4);
    }

    public function get_utm_value($value)
    {
        return '';
    }

    public function add_hidden_utm_fields($form)
    {
        if (is_array($form) && isset($form['fields'])) {
            foreach ($this->utm_keys as $key) {
                $exists = false;
                foreach ($form['fields'] as $field) {
                    if (isset($field->inputName) && $field->inputName === $key) {
                        $exists = true;
                        break;
                    }
                }
                if ($exists)
                    continue;

                $field = new \GF_Field_Hidden();
                $field->label = strtoupper(str_replace('_', ' ', $key));
                $field->inputName = $key;
                $field->cssClass = $key . ' gf-column';
                // Avoid ID conflicts by using a high number base or letting GF handle it if possible, 
                // but for now keeping logic similar to original but safer? 
                // Original used 1000 + count. Let's stick to that but maybe check if ID exists?
                // Actually, existing logic is fine for now, but let's make it robust against non-array fields
                $field->id = 1000 + count($form['fields']);
                while ($this->field_id_exists($form, $field->id)) {
                    $field->id++;
                }

                $field->visibility = 'hidden';
                $field->allowsPrepopulate = true;
                $field->isRequired = false;

                $form['fields'][] = $field;
            }
        }
        return $form;
    }

    private function field_id_exists($form, $id)
    {
        foreach ($form['fields'] as $field) {
            if ($field->id == $id)
                return true;
        }
        return false;
    }

    public function populate_hidden_utm_fields($form)
    {
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

    /**
     * Appends utm_reset=1 to the redirect URL if the confirmation type is redirect.
     * Also sets a cookie as a fallback.
     */
    public function append_reset_param_to_redirect($confirmation, $form, $entry, $ajax)
    {
        // Debug logging
        // error_log('GF UTM Tracker: Confirmation Filter triggered. Type: ' . (isset($confirmation['type']) ? $confirmation['type'] : 'unknown'));

        if (isset($confirmation['redirect']) && !empty($confirmation['redirect'])) {
            $url = $confirmation['redirect'];
            $url = add_query_arg('utm_reset', '1', $url);
            $confirmation['redirect'] = $url;

            // Set cookie as fallback (valid for 1 hour)
            if (!headers_sent()) {
                setcookie('gf_utm_reset', '1', time() + 3600, '/');
            }
        }
        return $confirmation;
    }
}
