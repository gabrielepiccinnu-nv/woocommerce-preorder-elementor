<?php
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
//use Elementor\Controls_Manager;

class WooCommerce_Preorder_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'woocommerce_preorder';
    }

    public function get_title()
    {
        return __('WooCommerce Preorder', 'woocommerce-preorder-elementor');
    }

    public function get_icon()
    {
        return 'eicon-cart';
    }

    public function get_categories()
    {
        return ['woocommerce-elements'];
    }

    protected function render()
    {
        $recaptcha_site_key = get_option('woocommerce_preorder_recaptcha_site_key'); // Imposta la chiave in WP Admin
        $nonce = wp_create_nonce('preorder_nonce'); // Protezione XSS/CSRF
?>
        <div class="woocommerce-preorder-container">
            <span style="display:block; font-size: 1.6rem; margin-bottom: 3rem">
                <?php esc_html_e('Place a Preorder', 'woocommerce-preorder-elementor'); ?>
            </span>

            <form id="preorder-form">
                <input type="hidden" id="preorder-nonce" value="<?php echo esc_attr($nonce); ?>">
                <input type="hidden" id="recaptcha-response" name="recaptcha-response">

                <div class="elementor-section">
                    <div class="elementor-row">
                        <?php
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => -1
                        );

                        $products = new WP_Query($args);
                        if ($products->have_posts()) :
                            while ($products->have_posts()) : $products->the_post();
                                global $product;
                        ?>
                                <div class="elementor-column elementor-col-100 elementor-field-group" style="margin-bottom: 15px; padding: 10px; border-bottom: 1px solid #ddd;">

                                    <div class="elementor-column elementor-col-40">
                                        <span><?php echo esc_html(get_the_title()); ?></span>
                                    </div>
                                    <div class="elementor-column elementor-col-30">
                                        <span>€ <span class="price"><?php echo esc_html($product->get_price()); ?></span></span>
                                    </div>
                                    <div class="elementor-column elementor-col-30">
                                        <input type="number" class="form-control quantity" data-price="<?php echo esc_attr($product->get_price()); ?>" min="0" value="0">
                                    </div>

                                </div>
                        <?php endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p>' . esc_html__('No products available.', 'woocommerce-preorder-elementor') . '</p>';
                        endif;
                        ?>
                    </div>
                </div>

                <h4><?php esc_html_e('Order total:', 'woocommerce-preorder-elementor'); ?> € <span id="total">0.00</span></h4>

                <label><?php esc_html_e('Name', 'woocommerce-preorder-elementor'); ?></label>
                <input type="text" class="form-control" id="name" required>

                <label><?php esc_html_e('Email', 'woocommerce-preorder-elementor'); ?></label>
                <input type="email" class="form-control" id="email" required>
                <input type="hidden" id="recaptcha-response" name="recaptcha-response">
                <input type="hidden" id="preorder-total" name="total" value="0">

                <div class="elementor-widget-container">
                    <div class="elementor-button-wrapper">
 
                        <button type="submit" class="elementor-button elementor-button-link elementor-size-sm">
                            <span class="elementor-button-content-wrapper">
                                <span class="elementor-button-text"><?php esc_html_e('Submit order', 'woocommerce-preorder-elementor'); ?></span>
                            </span>
                        </button>
                    </div>
                </div>

            </form>
        </div>

       
<?php
    }
}
