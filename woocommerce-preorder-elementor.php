<?php
/**
 * Plugin Name: WooCommerce Preorder (for Elementor)
 * Plugin URI:  https://www.gabrielepiccinnu.com/
 * Description: Modulo di preordine per WooCommerce con supporto Elementor
 * Version: 1.0
 * Author: Gabriele Piccinnu
 * Author URI: https://www.gabrielepiccinnu.com/
 * License: GPL v2 or later
 * Text Domain: woocommerce-preorder-elementor
 */

if (!defined('ABSPATH')) {
    exit; 
}

require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-preorder-loader.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-preorder-handler.php';

add_action('plugins_loaded', 'woocommerce_preorder_init');

function woocommerce_preorder_init() {
    if (did_action('elementor/loaded')) {
        \WooCommerce_Preorder_Loader::init();
    } else {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-warning"><p><strong>WooCommerce Preorder</strong> richiede Elementor attivo per funzionare.</p></div>';
        });
    }
}
