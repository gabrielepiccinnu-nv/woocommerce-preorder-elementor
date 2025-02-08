<?php
if (!defined('ABSPATH')) {
    exit;
}

function woocommerce_preorder_send_email($customer_name, $customer_email, $order_items, $order_total) {
    ob_start();
    $template_path = plugin_dir_path(__FILE__) . 'email-template/new-preorder.php';

    if (file_exists($template_path)) {
        include $template_path; // Usa il template per creare il contenuto dell'email
    } else {
        error_log('WooCommerce Preorder: Il file del template email non è stato trovato in ' . $template_path);
        return false;
    }

    $message = ob_get_clean();

    $to = $customer_email;
    $subject = __('Your Preorder Confirmation', 'woocommerce-preorder-elementor');
    $headers = array('Content-Type: text/html; charset=UTF-8');

    $mail_sent = wp_mail($to, $subject, $message, $headers);

    if (!$mail_sent) {
        error_log('WooCommerce Preorder: Errore nell\'invio della mail a ' . $to);
        return false;
    }

    return true;
}
