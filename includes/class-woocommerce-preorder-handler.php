<?php

function woocommerce_preorder_handle_submission() {
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'preorder_nonce')) {
        wp_send_json_error(__('Security check failed!', 'woocommerce-preorder-elementor'));
        return;
    }

    $customer_name = sanitize_text_field($_POST['name'] ?? '');
    $customer_email = sanitize_email($_POST['email'] ?? '');
    $customer_phone = sanitize_text_field($_POST['phone']);
    $customer_notes = sanitize_text_field($_POST['notes'] ?? '');
    $order_total = sanitize_text_field($_POST['total'] ?? '');
    //$order_total = isset($_POST['total']) ? floatval(str_replace(',', '.', str_replace('.', '', $_POST['total']))) : 0;
    //$order_total = isset($_POST['total']) ? floatval(str_replace(',', '.', str_replace('.', '', $_POST['total']))) : 0;


    
    if (empty($customer_name) || empty($customer_email)|| empty($customer_phone) || empty($_POST['order_items'])) {
        wp_send_json_error(__('Missing order details.', 'woocommerce-preorder-elementor'));
        return;
    }

    // Recuperiamo i prodotti come array
    $order_items = [];
    foreach ($_POST['order_items'] as $item) {
        $order_items[] = [
            'name' => sanitize_text_field($item['name']),
            'quantity' => intval($item['quantity']),
        ];
    }

    // Invia l'email con i dati
    $email_sent = woocommerce_preorder_send_email($customer_name, $customer_email, $customer_phone, $customer_notes, $order_items, $order_total);

    if ($email_sent) {
        wp_send_json_success(__('Preorder successfully submitted!', 'woocommerce-preorder-elementor'));
    } else {
        wp_send_json_error(__('Failed to send email.', 'woocommerce-preorder-elementor'));
    }
}
add_action('wp_ajax_send_preorder', 'woocommerce_preorder_handle_submission');
add_action('wp_ajax_nopriv_send_preorder', 'woocommerce_preorder_handle_submission');

 