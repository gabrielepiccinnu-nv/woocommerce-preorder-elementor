<?php
if (!defined('ABSPATH')) {
    exit;
}

function woocommerce_preorder_send_email($customer_name, $customer_email, $customer_notes, $order_items, $order_total) {
    $order_date = current_time('d/m/Y H:i'); // Data del preordine in formato locale
    $store_email = get_option('woocommerce_email_from_address'); // Email del negozio presa dalle impostazioni WooCommerce
    
    // Email cliente (con il template HTML)
    ob_start();
    $template_path = plugin_dir_path(dirname(__FILE__)) . 'email-template/new-preorder.php';
    if (file_exists($template_path)) {
        include $template_path;
    } else {
        error_log('WooCommerce Preorder: Il file del template email non è stato trovato in ' . $template_path);
        return false;
    }
    $message_customer = ob_get_clean();

    $subject_customer = sprintf(__('Your Preorder - %s', 'woocommerce-preorder-elementor'), $order_date);
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $mail_sent_customer = wp_mail($customer_email, $subject_customer, $message_customer, $headers);

    if (!$mail_sent_customer) {
        error_log('WooCommerce Preorder: Errore nell\'invio della mail al cliente ' . $customer_email);
    }

    // Email negozio (testuale con riepilogo)
    $subject_store = sprintf(__('New Preorder Received - %s', 'woocommerce-preorder-elementor'), $order_date);

    $message_store  = "Nuovo preordine ricevuto\n\n";
    $message_store .= "Cliente: $customer_name\n";
    $message_store .= "Email: $customer_email\n";
    $message_store .= "Data: $order_date\n";
    $message_store .= "\nDettagli ordine:\n";
    
    foreach ($order_items as $item) {
        $message_store .= "• " . esc_html($item['name']) . " x " . intval($item['quantity']) . "\n";
    }

    $message_store .= "\nTotale ordine: " . number_format($order_total, 2, ',', '.') . " €\n";
    $message_store .= "\nNote cliente:\n" . (!empty($customer_notes) ? esc_html($customer_notes) : "Nessuna");

    $headers_store = array('Content-Type: text/plain; charset=UTF-8');
    $mail_sent_store = wp_mail($store_email, $subject_store, $message_store, $headers_store);

    if (!$mail_sent_store) {
        error_log('WooCommerce Preorder: Errore nell\'invio della mail al negozio ' . $store_email);
    }

    return $mail_sent_customer && $mail_sent_store;
}