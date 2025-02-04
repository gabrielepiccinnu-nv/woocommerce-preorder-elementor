<?php
if (!defined('ABSPATH')) {
    exit;
}

class WooCommerce_Preorder_Loader {
    public static function init() {
        add_action('elementor/widgets/register', [__CLASS__, 'register_widgets']);
    }

    public static function register_widgets($widgets_manager) {
        require_once plugin_dir_path(__FILE__) . 'class-woocommerce-preorder-widget.php';
        $widgets_manager->register(new \WooCommerce_Preorder_Widget());
    }
}
