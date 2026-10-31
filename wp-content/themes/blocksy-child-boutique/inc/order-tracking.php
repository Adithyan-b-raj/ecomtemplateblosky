<?php
/**
 * Boutique Self-Service Order Tracker & Timeline
 *
 * Implements:
 * Shortcode [boutique_order_tracker] allowing customers to look up order status
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

function blocksy_boutique_order_tracker_shortcode() {
    ob_start();

    $order_id = isset($_REQUEST['order_id']) ? sanitize_text_field(wp_unslash($_REQUEST['order_id'])) : '';
    $order_email = isset($_REQUEST['order_email']) ? sanitize_email(wp_unslash($_REQUEST['order_email'])) : '';
    $searched = !empty($order_id);
    $found_order = null;
    $error_msg = '';

    if ($searched) {
        $order = wc_get_order($order_id);
        if ($order) {
            // Verify billing email or if admin
            if (empty($order_email) || strtolower($order->get_billing_email()) === strtolower($order_email)) {
                $found_order = $order;
            } else {
                $error_msg = 'Email does not match this Order ID. Please check and try again.';
            }
        } else {
            $error_msg = 'Order #' . esc_html($order_id) . ' could not be found. Please check your confirmation SMS or email.';
        }
    }
    ?>
    <div class="boutique-tracking-card">
        <div class="boutique-tracking-header">
            <h2 style="font-size: 20px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Track Your Boutique Order</h2>
            <p style="font-size: 13px; color: #666;">Enter your Order ID (from confirmation email/SMS) to see live fulfillment status.</p>
        </div>

        <form method="GET" class="boutique-tracking-form" style="display: flex; gap: 10px; flex-direction: column; margin-bottom: 24px;">
            <div>
                <label style="font-size: 12px; font-weight: 500; display: block; margin-bottom: 4px;">Order ID *</label>
                <input type="text" name="order_id" required value="<?php echo esc_attr($order_id); ?>" placeholder="e.g. 5432" style="width: 100%; padding: 10px 14px; border: 1px solid #EAE3DC; border-radius: 6px;">
            </div>
            <div>
                <label style="font-size: 12px; font-weight: 500; display: block; margin-bottom: 4px;">Billing Email (optional for verification)</label>
                <input type="email" name="order_email" value="<?php echo esc_attr($order_email); ?>" placeholder="yourname@gmail.com" style="width: 100%; padding: 10px 14px; border: 1px solid #EAE3DC; border-radius: 6px;">
            </div>
            <button type="submit" style="background: #1C1917; color: #FFF; border: none; padding: 12px; border-radius: 6px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; cursor: pointer; margin-top: 8px;">Check Status</button>
        </form>

        <?php if (!empty($error_msg)) : ?>
            <div style="padding: 12px; background: #FFEBEE; border-left: 4px solid #D32F2F; color: #C62828; font-size: 13px; border-radius: 4px; margin-bottom: 20px;">
                <?php echo esc_html($error_msg); ?>
            </div>
        <?php endif; ?>

        <?php if ($found_order) : 
            $status = $found_order->get_status();
            $step = 1;
            if ($status === 'processing') $step = 2;
            if ($status === 'completed') $step = 4;
            $order_date = $found_order->get_date_created();
            ?>
            <div class="boutique-tracking-result" style="border-top: 1px solid #EAE3DC; padding-top: 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <div>
                        <strong style="font-size: 15px;">Order #<?php echo esc_html($found_order->get_order_number()); ?></strong>
                        <div style="font-size: 12px; color: #666;">Placed on <?php echo esc_html($order_date ? $order_date->date_i18n('d M, Y') : ''); ?></div>
                    </div>
                    <span style="padding: 4px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; text-transform: uppercase; background: #F7EEE9; color: #B76E58;">
                        <?php echo esc_html(wc_get_order_status_name($status)); ?>
                    </span>
                </div>

                <!-- Interactive Timeline -->
                <div class="boutique-tracking-steps">
                    <div class="boutique-tracking-step <?php echo ($step >= 1) ? 'completed' : ''; ?>">
                        <div class="boutique-tracking-dot">✓</div>
                        <div class="boutique-tracking-title">Placed</div>
                    </div>
                    <div class="boutique-tracking-step <?php echo ($step >= 2) ? (($step > 2) ? 'completed' : 'active') : ''; ?>">
                        <div class="boutique-tracking-dot">2</div>
                        <div class="boutique-tracking-title">Packaging</div>
                    </div>
                    <div class="boutique-tracking-step <?php echo ($step >= 3) ? (($step > 3) ? 'completed' : 'active') : ''; ?>">
                        <div class="boutique-tracking-dot">3</div>
                        <div class="boutique-tracking-title">Dispatched</div>
                    </div>
                    <div class="boutique-tracking-step <?php echo ($step >= 4) ? 'completed' : ''; ?>">
                        <div class="boutique-tracking-dot">4</div>
                        <div class="boutique-tracking-title">Delivered</div>
                    </div>
                </div>

                <div style="background: #FAF6F2; padding: 14px; border-radius: 6px; font-size: 13px; line-height: 1.5; color: #444;">
                    📦 <strong>Estimated Delivery:</strong> Within 4–6 business days.<br>
                    📍 <strong>Delivering to:</strong> <?php echo esc_html($found_order->get_shipping_city() ?: $found_order->get_billing_city()); ?>, <?php echo esc_html($found_order->get_shipping_postcode() ?: $found_order->get_billing_postcode()); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('boutique_order_tracker', 'blocksy_boutique_order_tracker_shortcode');
