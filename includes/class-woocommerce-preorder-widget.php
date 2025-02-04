<?php
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class WooCommerce_Preorder_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'woocommerce_preorder';
    }

    public function get_title()
    {
        return __('WooCommerce Preorder', 'woocommerce-preorder');
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
?>
        <div class="woocommerce-preorder-container">
            <span style="display:block; font-size: 1.6rem; margin-bottom: 3rem">Effettua un preordine</span>
            <form id="preorder-form">
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
                                <div class="elementor-column elementor-col-100 elementor-field-group">

                                    <div class="elementor-column elementor-col-40 ">
                                        <span><?php the_title(); ?></span>
                                    </div>
                                    <div class="elementor-column elementor-col-30">
                                        <span>€ <span class="price"><?php echo $product->get_price(); ?></span></span>
                                    </div>
                                    <div class="elementor-column elementor-col-30">
                                        <input type="number" class="form-control quantity" data-price="<?php echo $product->get_price(); ?>" min="0" value="0">
                                    </div>

                                </div>
                        <?php endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<p>Nessun prodotto disponibile.</p>';
                        endif;
                        ?>
                    </div>
                </div>

                <h4>Totale ordine: € <span id="total">0.00</span></h4>

                <label>Nome</label>
                <input type="text" class="form-control" id="name" required>

                <label>Email</label>
                <input type="email" class="form-control" id="email" required>

                <div class="elementor-widget-container">
                    <div class="elementor-button-wrapper">
                        <button type="submit" class="elementor-button elementor-button-link elementor-size-sm">
                            <span class="elementor-button-content-wrapper">
                                <span class="elementor-button-text">Invia preordine</span>
                            </span>
                    </button>
                    </div>
                </div>

            </form>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const quantities = document.querySelectorAll(".quantity");
                const totalSpan = document.getElementById("total");

                function updateTotal() {
                    let total = 0;
                    quantities.forEach(input => {
                        let price = parseFloat(input.dataset.price);
                        let quantity = parseInt(input.value) || 0;
                        total += price * quantity;
                    });
                    totalSpan.textContent = total.toFixed(2);
                }

                quantities.forEach(input => {
                    input.addEventListener("input", updateTotal);
                });

                document.getElementById("preorder-form").addEventListener("submit", function(event) {
                    event.preventDefault();

                    let name = document.getElementById("name").value;
                    let email = document.getElementById("email").value;
                    let orderSummary = "";
                    quantities.forEach(input => {
                        let quantity = parseInt(input.value) || 0;
                        if (quantity > 0) {
                            let productName = input.parentElement.previousElementSibling.previousElementSibling.textContent.trim();
                            orderSummary += productName + " x" + quantity + "\n";
                        }
                    });

                    let message = `Nuovo Preordine:\n\nNome: ${name}\nEmail: ${email}\n\nProdotti:\n${orderSummary}\nTotale Stimato: €${totalSpan.textContent}`;

                    fetch('<?php echo admin_url("admin-ajax.php"); ?>', {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `action=send_preorder&message=${encodeURIComponent(message)}`
                    }).then(response => response.text()).then(data => {
                        console.log("Preordine inviato con successo!");
                    }).catch(error => console.error(error));
                });
            });
        </script>
<?php
    }
}
