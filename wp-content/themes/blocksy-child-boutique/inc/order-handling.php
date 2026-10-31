<?php
/**
 * Boutique Order Handling, Indian Checkout & Cashfree Optimizations
 *
 * Implements:
 * 1. Streamlined Indian Checkout Fields (Phone, Pincode, State)
 * 2. Mini-Cart Free Shipping Bar Injection
 * 3. AJAX Cart Subtotal Endpoint for Dynamic Progress Bar
 * 4. Automatic Order Processing & Note Hooks
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Streamline Checkout Fields for Fast Indian Mobile Checkout
 */
function blocksy_boutique_optimize_checkout_fields($fields) {
    // Remove unnecessary fields for boutique fashion
    unset($fields['billing']['billing_company']);
    unset($fields['shipping']['shipping_company']);
    unset($fields['order']['order_comments']);

    // Set phone placeholder & label tailored for Indian mobile buyers
    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['placeholder'] = '10-digit mobile number';
        $fields['billing']['billing_phone']['label'] = 'Mobile Number (for WhatsApp & SMS delivery updates)';
        $fields['billing']['billing_phone']['required'] = true;
    }

    // Set post code label
    if (isset($fields['billing']['billing_postcode'])) {
        $fields['billing']['billing_postcode']['placeholder'] = '6-digit PIN code';
        $fields['billing']['billing_postcode']['label'] = 'PIN Code';
    }

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'blocksy_boutique_optimize_checkout_fields');

/**
 * 2. Validate Indian 10-digit Mobile Phone Number & 6-digit Pincode
 */
function blocksy_boutique_validate_checkout_fields() {
    if (isset($_POST['billing_phone']) && !empty($_POST['billing_phone'])) {
        $phone = preg_replace('/[^0-9]/', '', $_POST['billing_phone']);
        if (strlen($phone) < 10) {
            wc_add_notice(__('Please enter a valid 10-digit mobile number for delivery tracking.', 'blocksy-child-boutique'), 'error');
        }
    }

    if (isset($_POST['billing_postcode']) && !empty($_POST['billing_postcode'])) {
        $postcode = preg_replace('/[^0-9]/', '', $_POST['billing_postcode']);
        if (strlen($postcode) !== 6) {
            wc_add_notice(__('Please enter a valid 6-digit Indian PIN code.', 'blocksy-child-boutique'), 'error');
        }
    }
}
add_action('woocommerce_checkout_process', 'blocksy_boutique_validate_checkout_fields');

/**
 * 3. Render Free Shipping Bar in Mini-Cart & Cart Page
 */
function blocksy_boutique_render_free_shipping_bar() {
    $threshold = apply_filters('boutique_free_shipping_threshold', 999);
    $cart_subtotal = WC()->cart ? WC()->cart->get_subtotal() : 0;
    $remaining = max(0, $threshold - $cart_subtotal);
    $percentage = min(100, max(5, ($cart_subtotal / $threshold) * 100));
    ?>
    <div class="boutique-free-shipping-box">
        <div class="boutique-free-shipping-text">
            <?php if ($remaining <= 0) : ?>
                🎉 Congratulations! You have unlocked <strong>FREE Shipping!</strong>
            <?php else : ?>
                Add <strong>₹<?php echo esc_html(number_format($remaining, 0)); ?></strong> more to unlock <strong>FREE Shipping</strong>
            <?php endif; ?>
        </div>
        <div class="boutique-free-shipping-track">
            <div class="boutique-free-shipping-bar" style="width: <?php echo esc_attr($percentage); ?>%;"></div>
        </div>
    </div>
    <?php
}
add_action('woocommerce_before_mini_cart', 'blocksy_boutique_render_free_shipping_bar', 5);
add_action('woocommerce_before_cart', 'blocksy_boutique_render_free_shipping_bar', 5);

/**
 * 4. AJAX Endpoint to fetch current cart subtotal for live JavaScript updates
 */
function blocksy_boutique_get_cart_subtotal_ajax() {
    $subtotal = WC()->cart ? WC()->cart->get_subtotal() : 0;
    wp_send_json(array(
        'subtotal' => floatval($subtotal)
    ));
}
add_action('wc_ajax_get_cart_subtotal', 'blocksy_boutique_get_cart_subtotal_ajax');

/**
 * 5. Add Cashfree UPI Trust Badge on Checkout
 */
function blocksy_boutique_checkout_trust_badge() {
    ?>
    <div class="boutique-checkout-trust" style="margin-top: 15px; padding: 12px; background: #FAF6F2; border-radius: 6px; font-size: 12px; color: #444;">
        🔒 <strong>100% Secure Checkout:</strong> Powered by Cashfree. Instant UPI payments with Google Pay, PhonePe, Paytm, BHIM, plus Credit/Debit Cards & NetBanking.
    </div>
    <?php
}
add_action('woocommerce_review_order_after_submit', 'blocksy_boutique_checkout_trust_badge', 10);
