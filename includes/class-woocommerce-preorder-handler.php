<?php
function woocommerce_preorder_handle_submission() {
    // Recupera il payload JSON
    $json_input = file_get_contents("php://input");
    $decoded_input = json_decode($json_input, true);

    if (!isset($decoded_input['nonce']) || !wp_verify_nonce($decoded_input['nonce'], 'preorder_nonce')) {
        wp_send_json_error(__('Security check failed!', 'woocommerce-preorder-elementor'));
        return;
    }

    $customer_name = sanitize_text_field($decoded_input['name']);
    $customer_email = sanitize_email($decoded_input['email']);
    $order_total = sanitize_text_field($decoded_input['total']);
    $order_items = $decoded_input['order_items'];  // Rimosso stripslashes e json_decode perché è già un array

    if (empty($customer_name) || empty($customer_email) || empty($order_items)) {
        wp_send_json_error(__('Missing order details.', 'woocommerce-preorder-elementor'));
        return;
    }

    // Verifica reCAPTCHA
    if (!woocommerce_preorder_verify_recaptcha($decoded_input['recaptcha'])) {
        wp_send_json_error(__('reCAPTCHA verification failed.', 'woocommerce-preorder-elementor'));
        return;
    }

    // Invia l'email
    woocommerce_preorder_send_email($customer_name, $customer_email, $order_items, $order_total);

    wp_send_json_success(__('Preorder successfully submitted!', 'woocommerce-preorder-elementor'));
}
add_action('wp_ajax_send_preorder', 'woocommerce_preorder_handle_submission');
add_action('wp_ajax_nopriv_send_preorder', 'woocommerce_preorder_handle_submission');

function woocommerce_preorder_verify_recaptcha($recaptcha_response) {
    $secret_key = get_option('woocommerce_preorder_recaptcha_secret_key');

    if (!$secret_key) {
        return false;
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
        'body' => array(
            'secret'   => $secret_key,
            'response' => $recaptcha_response,
            'remoteip' => $_SERVER['REMOTE_ADDR'],
        ),
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $response_body = wp_remote_retrieve_body($response);
    $result = json_decode($response_body, true);

    return isset($result['success']) && $result['success'] === true;
}
