<?php
if (!defined('ABSPATH')) {
    exit;
}

// Variabili dinamiche ricevute dalla funzione di invio email
$order_id = isset($order_id) ? esc_html($order_id) : '0000000';
$customer_name = isset($customer_name) ? esc_html($customer_name) : 'Customer';
$customer_email = isset($customer_email) ? esc_html($customer_email) : 'example@email.com';
$order_total = isset($order_total) ? esc_html($order_total) : '0.00';
$order_items = isset($order_items) ? $order_items : []; // Array dei prodotti

?>

<!DOCTYPE html>
<html>
<head>
<title><?php esc_html_e('Order Confirmation', 'woocommerce-preorder-elementor'); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<style type="text/css">
body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
img { -ms-interpolation-mode: bicubic; }
table { border-collapse: collapse !important; }
body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }
a[x-apple-data-detectors] { color: inherit !important; text-decoration: none !important; font-size: inherit !important; font-family: inherit !important; font-weight: inherit !important; line-height: inherit !important; }
@media screen and (max-width: 480px) {
    .mobile-hide { display: none !important; }
    .mobile-center { text-align: center !important; }
}
</style>
</head>
<body style="margin: 0; padding: 0; background-color: #eeeeee;">
<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="background-color: #eeeeee;" bgcolor="#eeeeee">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;">
                <tr>
                    <td align="center" style="padding: 35px; background-color: #F44336;" bgcolor="#F44336">
                        <h1 style="color: #ffffff; font-family: Arial, sans-serif;">
                            <?php esc_html_e('Thank You For Your Order!', 'woocommerce-preorder-elementor'); ?>
                        </h1>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px; background-color: #ffffff;" bgcolor="#ffffff">
                        <h2 style="color: #333;"><?php echo esc_html($customer_name); ?></h2>
                        <p><?php esc_html_e('Here is your order summary:', 'woocommerce-preorder-elementor'); ?></p>
                        <table border="0" cellpadding="5" cellspacing="0" width="100%">
                            <tr>
                                <th align="left"><?php esc_html_e('Product', 'woocommerce-preorder-elementor'); ?></th>
                                <th align="left"><?php esc_html_e('Quantity', 'woocommerce-preorder-elementor'); ?></th>
                            </tr>
                            <?php foreach ($order_items as $item) : ?>
                                <tr>
                                    <td><?php echo esc_html($item['name']); ?></td>
                                    <td><?php echo esc_html($item['quantity']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <p style="font-weight: bold;"><?php esc_html_e('Total:', 'woocommerce-preorder-elementor'); ?> €<?php echo esc_html($order_total); ?></p>
                    </td>
                </tr>
                <tr>
                    <td align="center" style="padding: 20px; background-color: #eeeeee;">
                        <p><?php esc_html_e('If you have any questions, feel free to contact us.', 'woocommerce-preorder-elementor'); ?></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
