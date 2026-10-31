<?php
/**
 * WhatsApp Order Notifications & Customer Support Engine
 *
 * Implements:
 * 1. Webhook dispatcher on paid order status (Processing)
 * 2. Webhook dispatcher on fulfillment status (Completed / Shipped)
 * 3. Direct WhatsApp floating customer support button
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Dispatch Webhook on Order Paid (Processing)
 */
function blocksy_boutique_trigger_whatsapp_order_placed($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    $webhook_url = get_option('boutique_whatsapp_webhook_url');
    if (!$webhook_url) return; // Only send if configured in admin

    $phone = $order->get_billing_phone();
    // Normalize Indian phone number to 91XXXXXXXXXX
    $phone_clean = preg_replace('/[^0-9]/', '', $phone);
    if (strlen($phone_clean) === 10) {
        $phone_clean = '91' . $phone_clean;
    }

    $items_summary = array();
    foreach ($order->get_items() as $item) {
        $items_summary[] = $item->get_name() . ' x ' . $item->get_quantity();
    }

    $payload = array(
        'event'          => 'order_confirmed',
        'order_id'       => $order->get_order_number(),
        'customer_name'  => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
        'phone'          => $phone_clean,
        'email'          => $order->get_billing_email(),
        'total'          => $order->get_total(),
        'currency'       => $order->get_currency(),
        'items'          => implode(', ', $items_summary),
        'shipping_city'  => $order->get_shipping_city() ?: $order->get_billing_city(),
        'tracking_url'   => site_url('/track-order/?order_id=' . $order->get_order_number())
    );

    wp_remote_post($webhook_url, array(
        'headers' => array('Content-Type' => 'application/json'),
        'body'    => wp_json_encode($payload),
        'timeout' => 10,
    ));
}
add_action('woocommerce_order_status_processing', 'blocksy_boutique_trigger_whatsapp_order_placed', 10, 1);

/**
 * 2. Floating Boutique WhatsApp Chat Button
 */
function blocksy_boutique_render_whatsapp_float() {
    $phone = get_option('boutique_support_whatsapp_number', '919876543210');
    $default_msg = rawurlencode('Hello! I have a question regarding products on your boutique store.');
    $wa_url = 'https://wa.me/' . esc_attr($phone) . '?text=' . $default_msg;
    ?>
    <a href="<?php echo esc_url($wa_url); ?>" 
       class="boutique-whatsapp-float" 
       target="_blank" 
       rel="noopener noreferrer" 
       aria-label="Chat with us on WhatsApp"
       style="position: fixed; bottom: 25px; right: 25px; z-index: 998; width: 52px; height: 52px; background: #25D366; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 16px rgba(37, 211, 102, 0.4); text-decoration: none;">
        <svg width="30" height="30" viewBox="0 0 24 24" fill="#FFFFFF">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
    </a>
    <?php
}
add_action('wp_footer', 'blocksy_boutique_render_whatsapp_float', 50);
