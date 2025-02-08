<?php
if (!defined('ABSPATH')) {
    exit;
}

class WooCommerce_Preorder_Settings {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Aggiunge una voce di menu in WooCommerce -> Preorder
     */
    public function add_settings_page() {
        add_submenu_page(
            'woocommerce',
            __('Preorder Settings', 'woocommerce-preorder-elementor'),
            __('Preorder Settings', 'woocommerce-preorder-elementor'),
            'manage_options',
            'woocommerce-preorder-settings',
            array($this, 'settings_page_content')
        );
    }

    /**
     * Registra le impostazioni per salvare la chiave reCAPTCHA
     */
    public function register_settings() {
        register_setting('woocommerce_preorder_settings_group', 'woocommerce_preorder_recaptcha_site_key');
        register_setting('woocommerce_preorder_settings_group', 'woocommerce_preorder_recaptcha_secret_key');

        add_settings_section(
            'woocommerce_preorder_settings_section',
            __('reCAPTCHA Settings', 'woocommerce-preorder-elementor'),
            null,
            'woocommerce-preorder-settings'
        );

        add_settings_field(
            'woocommerce_preorder_recaptcha_site_key',
            __('reCAPTCHA Site Key', 'woocommerce-preorder-elementor'),
            array($this, 'recaptcha_site_key_field'),
            'woocommerce-preorder-settings',
            'woocommerce_preorder_settings_section'
        );

        add_settings_field(
            'woocommerce_preorder_recaptcha_secret_key',
            __('reCAPTCHA Secret Key', 'woocommerce-preorder-elementor'),
            array($this, 'recaptcha_secret_key_field'),
            'woocommerce-preorder-settings',
            'woocommerce_preorder_settings_section'
        );
    }

    /**
     * Campo per la Site Key di reCAPTCHA
     */
    public function recaptcha_site_key_field() {
        $value = get_option('woocommerce_preorder_recaptcha_site_key', '');
        echo '<input type="text" name="woocommerce_preorder_recaptcha_site_key" value="' . esc_attr($value) . '" class="regular-text">';
    }

    /**
     * Campo per la Secret Key di reCAPTCHA
     */
    public function recaptcha_secret_key_field() {
        $value = get_option('woocommerce_preorder_recaptcha_secret_key', '');
        echo '<input type="text" name="woocommerce_preorder_recaptcha_secret_key" value="' . esc_attr($value) . '" class="regular-text">';
    }

    /**
     * Contenuto della pagina impostazioni
     */
    public function settings_page_content() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('WooCommerce Preorder Settings', 'woocommerce-preorder-elementor'); ?></h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('woocommerce_preorder_settings_group');
                do_settings_sections('woocommerce-preorder-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}

new WooCommerce_Preorder_Settings();
