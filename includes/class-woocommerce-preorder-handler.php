<?php
if (!defined('ABSPATH')) {
    exit;
}

function woocommerce_preorder_send_email() {
    if (!isset($_POST['message'])) {
        wp_send_json_error('Messaggio mancante');
    }

    $to = get_option('admin_email'); 
    $subject = "Nuovo Preordine dal Sito";
    $message = sanitize_textarea_field($_POST['message']);

    wp_mail($to, $subject, $message);

    wp_send_json_success('Preordine inviato!');
}
add_action('wp_ajax_send_preorder', 'woocommerce_preorder_send_email');
add_action('wp_ajax_nopriv_send_preorder', 'woocommerce_preorder_send_email');
