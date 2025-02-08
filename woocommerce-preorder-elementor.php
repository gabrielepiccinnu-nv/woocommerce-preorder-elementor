<?php
/**
 * Plugin Name: WooCommerce Preorder (for Elementor)
 * Plugin URI:  https://www.networkvision.it/
 * Description: Modulo di preordine per WooCommerce con supporto Elementor
 * Version: 1.0
 * Author: Network Vision
 * Author URI: https://www.networkvision.it/
 * License: GPL v2 or later
 * Text Domain: woocommerce-preorder-elementor
 */

if (!defined('ABSPATH')) {
    exit; 
}

require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-preorder-loader.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-preorder-handler.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-preorder-email.php';
//require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-preorder-widget.php';

if (is_admin()) {
    require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-preorder-settings.php';
}



add_action('plugins_loaded', 'woocommerce_preorder_init');

function woocommerce_preorder_load_textdomain() {
    load_plugin_textdomain(
        'woocommerce-preorder-elementor',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages/'
    );
}
add_action('plugins_loaded', 'woocommerce_preorder_load_textdomain');



function woocommerce_preorder_init() {
    if (did_action('elementor/loaded')) {
        \WooCommerce_Preorder_Loader::init();
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning"><p><strong>WooCommerce Preorder</strong> richiede Elementor attivo per funzionare.</p></div>';
        });
    }
}

function woocommerce_preorder_enqueue_scripts() {
    wp_enqueue_script(
        'woocommerce-preorder-js',
        plugin_dir_url(__FILE__) . 'js/woocommerce-preorder-elementor.js',
        array('jquery'),
        '1.0.0',
        true
    );

    $recaptcha_site_key = get_option('woocommerce_preorder_recaptcha_site_key');
    wp_localize_script('woocommerce-preorder-js', 'woocommercePreorderData', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('preorder_nonce'),
        'locale' => get_locale(),
        'recaptcha_site_key' => $recaptcha_site_key, // Passa la Site Key
        'error_no_products' => __('Please select at least one product.', 'woocommerce-preorder-elementor'),
        'error_missing_fields' => __('Please fill in all required fields.', 'woocommerce-preorder-elementor'),
        'success_message' => __('Preorder successfully submitted!', 'woocommerce-preorder-elementor'),
        'error_message' => __('Error submitting preorder.', 'woocommerce-preorder-elementor'),
    ));
}
add_action('wp_enqueue_scripts', 'woocommerce_preorder_enqueue_scripts');


function woocommerce_preorder_enqueue_recaptcha() {
    $recaptcha_site_key = get_option('woocommerce_preorder_recaptcha_site_key');
    
    if ($recaptcha_site_key) {
        // Carica lo script reCAPTCHA di Google
        wp_enqueue_script(
            'google-recaptcha',
            'https://www.google.com/recaptcha/api.js?render=' . esc_js($recaptcha_site_key),
            array(),
            null,
            true
        );
    } else {
        error_log('WooCommerce Preorder: La Site Key reCAPTCHA non è configurata.');
    }
}
add_action('wp_enqueue_scripts', 'woocommerce_preorder_enqueue_recaptcha');
