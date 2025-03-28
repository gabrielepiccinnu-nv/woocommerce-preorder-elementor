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
            <span style="display: block;font-size: 1.7rem;margin-bottom: 3rem;text-align: center;font-weight: 500;color: #ac905e;">
                <?php esc_html_e('Place a Preorder', 'woocommerce-preorder-elementor'); ?>
            </span>

            <form id="preorder-form">
                <input type="hidden" id="preorder-nonce" value="<?php echo esc_attr($nonce); ?>">
                <input type="hidden" id="recaptcha-response" name="recaptcha-response">

                <div class="elementor-section" style="margin-bottom: 4rem">
                    <div class="elementor-row">
                        <?php
                        $args = array(
                            'post_type'      => 'product',
                            'posts_per_page' => -1,
                            'orderby'        => 'title',
                            'order'          => 'ASC',
                            'meta_query' => array(
                                array(
                                    'key' => '_stock_status',
                                    'value' => 'instock',
                                    'compare' => '=',
                                ),
                            ),
                        );

                        $products = new WP_Query($args);
                        if ($products->have_posts()) :
                            while ($products->have_posts()) : $products->the_post();
                                global $product;
                        ?>
                                <div class="elementor-column elementor-col-100 elementor-field-group" style="margin-bottom: 15px; border-bottom: 1px solid #b6ab8d;">

                                    <div class="elementor-column elementor-sm-60 elementor-col-60">
                                        <span><?php echo esc_html(get_the_title()); ?></span>
                                    </div>
                                    <div class="elementor-column elementor-sm-20 elementor-col-20">
                                        <span>€ <span class="price"><?php echo esc_html($product->get_price()); ?></span></span>
                                    </div>
                                    <div class="elementor-column elementor-sm-20 elementor-col-20">
                                        <input type="number" class="form-control quantity" data-price="<?php echo esc_attr($product->get_price()); ?>" min="0" max="10" value="0">
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

                <h4 style="margin-bottom: 5rem;"><?php esc_html_e('Order total:', 'woocommerce-preorder-elementor'); ?> € <span id="total">0.00</span></h4>
                <label><?php esc_html_e('Name', 'woocommerce-preorder-elementor'); ?></label>
                <input type="text" class="form-control" id="name" required>

                <label><?php esc_html_e('Email', 'woocommerce-preorder-elementor'); ?></label>
                <input type="email" class="form-control" id="email" required>

                <label><?php esc_html_e('Phone', 'woocommerce-preorder-elementor'); ?></label>
                <input type="tel" class="form-control" id="phone" pattern="[\d\s\+\-\(\)]{6,}" required>

                <label><?php esc_html_e('Additional notes', 'woocommerce-preorder-elementor'); ?></label>
                <textarea class="form-control" rows="3" id="notes"></textarea>

                <input type="hidden" id="recaptcha-response" name="recaptcha-response">
                <input type="hidden" id="preorder-total" name="total" value="0">


                <div class="elementor-column elementor-col-100 elementor-field-group elementor-field-type-checkbox">
                    <div class="elementor-field-subgroup">
                        <label class="elementor-field-label">
                            <input type="checkbox" id="privacy" required style="margin-right: 5px;">
                            <?php esc_html_e('I accept the Privacy Policy and consent to data processing under EU Regulation 2016/679.', 'woocommerce-preorder-elementor'); ?>
                            <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" target="_blank" rel="noopener noreferrer">
                                <?php esc_html_e('Privacy Policy', 'woocommerce-preorder-elementor'); ?>
                            </a>
                        </label>
                    </div>
                </div>



                <div class="elementor-widget-container">
                    <div class="elementor-button-wrapper" style="display: flex;justify-content: center;">

                        <button type="submit" class="elementor-button preorder-button">
                            <span class="elementor-button-content-wrapper">
                                <span class="elementor-button-text"><?php esc_html_e('Submit order', 'woocommerce-preorder-elementor'); ?></span>
                            </span>
                        </button>

                    </div>
                </div>

                <div class="elementor-widget-container">

                
                <p style="font-size: 0.8rem; color: #777; margin-top: 1rem;">
    <?php echo sprintf(
        /* translators: %1$s and %2$s are links to Google Privacy Policy and Terms */
        esc_html__('This site is protected by reCAPTCHA and the Google %1$sPrivacy Policy%2$s and %3$sTerms of Service%4$s apply.', 'woocommerce-preorder-elementor'),
        '<a href="https://policies.google.com/privacy" target="_blank" rel="noopener noreferrer">',
        '</a>',
        '<a href="https://policies.google.com/terms" target="_blank" rel="noopener noreferrer">',
        '</a>'
    ); ?>
</p>


                </div>

                <style>

.grecaptcha-badge { 
    visibility: hidden !important;
}

                    .woocommerce-preorder-container {
                        max-width: 568px;
                        background: #f9f8f4;
                        border: 1px solid #b6ab8d;
                        border-radius: 10px;
                        padding: 1rem;
                        margin-bottom: 3rem;
                    }

                    @media screen and (min-width:768px) {
                        .woocommerce-preorder-container {
                            padding: 3rem;
                        }
                    }

                    #preorder-form .form-control {
                        padding: 1rem;
                        margin-bottom: 1rem;
                    }

                    #preorder-form label {
                        text-transform: uppercase;
                        font-size: .875rem;
                        margin-bottom: .5rem;
                    }

                    #preorder-form .elementor-field-type-checkbox label {
                        text-transform: none;
                        line-height: 1.64;
                    }

                    #preorder-form span .price {
                        color: #333;
                        font-weight: 400;
                    }

                    .elementor-button.preorder-button {
                        margin-top: 2rem;
                        background-color: #b98d58;
                        border-color: #b98d58;
                        color: #fff;
                        font-size: 16px;

                        padding: 12px 24px;

                        text-transform: uppercase;
                        transition: all 0.3s ease-in-out;
                    }

                    .elementor-button.preorder-button:hover {
                        background-color: #b98d58;
                        /* Cambia il colore al passaggio del mouse */
                        border-color: #b98d58;
                        /* Cambia il colore al passaggio del mouse */
                        color: #fff;

                    }

                    .woocommerce-preorder-success {
                        font-weight: 500;
                        font-size: 1.2rem;
                    }

                    .elementor-button.button-disabled {
                        background-color: #ccc !important;
                        border-color: #ccc !important;
                        color: #666 !important;
                        cursor: not-allowed !important;
                        opacity: 0.7;
                    }
                </style>

            </form>

            <div id="preorder-success-message" style="display: none;" class="woocommerce-preorder-success"></div>

        </div>


<?php
    }
}
