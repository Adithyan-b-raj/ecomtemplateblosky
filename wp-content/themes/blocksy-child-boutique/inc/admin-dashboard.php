<?php
/**
 * Boutique Store Admin Dashboard & Settings Pages (Mobile-Optimized)
 *
 * Implements:
 * 1. Admin Menu "Boutique Settings"
 * 2. Mobile-responsive CSS injection for WordPress Admin
 * 3. Storefront & Scarcity Controls
 * 4. Fast Stock Matrix with Touch-friendly UI
 * 5. 1-Click Sample Catalog Seeder
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Register Admin Menu
 */
function blocksy_boutique_register_admin_menu() {
    add_menu_page(
        __('Boutique Settings', 'blocksy-child-boutique'),
        __('Boutique Hub', 'blocksy-child-boutique'),
        'manage_woocommerce',
        'boutique-hub',
        'blocksy_boutique_render_admin_page',
        'dashicons-store',
        56
    );

    add_submenu_page(
        'boutique-hub',
        __('Inventory Matrix', 'blocksy-child-boutique'),
        __('Stock Matrix', 'blocksy-child-boutique'),
        'manage_woocommerce',
        'boutique-stock-matrix',
        'blocksy_boutique_render_stock_matrix_page'
    );
}
add_action('admin_menu', 'blocksy_boutique_register_admin_menu');

/**
 * 2. Inject Mobile-Friendly CSS into WordPress Admin Pages
 */
function blocksy_boutique_admin_responsive_styles($hook) {
    if (strpos($hook, 'boutique') === false) {
        return;
    }
    ?>
    <style>
        .boutique-admin-wrap {
            max-width: 1000px;
            margin: 20px auto 40px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .boutique-card {
            background: #FFF;
            border: 1px solid #CCD0D4;
            border-radius: 6px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }
        .boutique-card h2 {
            font-size: 16px;
            border-bottom: 1px solid #EEE;
            padding-bottom: 10px;
            margin-top: 0;
            color: #161616;
        }
        .boutique-stock-matrix-table {
            width: 100%;
            border-collapse: collapse;
            background: #FFF;
        }
        .boutique-stock-matrix-table th {
            background: #FAF6F2;
            padding: 10px 14px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }
        .boutique-stock-matrix-table td {
            padding: 12px 14px;
            border-bottom: 1px solid #F0F0F1;
        }
        @media (max-width: 782px) {
            .boutique-admin-wrap {
                margin: 10px;
            }
            .boutique-card {
                padding: 16px;
            }
            .boutique-stock-matrix-table th, 
            .boutique-stock-matrix-table td {
                padding: 8px 10px;
                font-size: 12px;
            }
            .form-table th, .form-table td {
                padding: 10px 0 !important;
                display: block;
                width: 100% !important;
            }
        }
    </style>
    <?php
}
add_action('admin_head', 'blocksy_boutique_admin_responsive_styles');

/**
 * 3. Register Settings
 */
function blocksy_boutique_register_settings() {
    register_setting('boutique_settings_group', 'boutique_announcement_text');
    register_setting('boutique_settings_group', 'boutique_free_shipping_threshold');
    register_setting('boutique_settings_group', 'boutique_stock_urgency_limit');
    register_setting('boutique_settings_group', 'boutique_support_whatsapp_number');
    register_setting('boutique_settings_group', 'boutique_whatsapp_webhook_url');
    register_setting('boutique_settings_group', 'boutique_dispatch_lead_days');
    register_setting('boutique_settings_group', 'boutique_delivery_days');
}
add_action('admin_init', 'blocksy_boutique_register_settings');

/**
 * 4. Render Main Admin Hub Page
 */
function blocksy_boutique_render_admin_page() {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }

    if (isset($_POST['boutique_action']) && $_POST['boutique_action'] === 'seed_catalog' && check_admin_referer('boutique_seed_action')) {
        blocksy_boutique_seed_categories();
        blocksy_boutique_setup_size_attribute();
        echo '<div class="notice notice-success is-dismissible"><p><strong>Success!</strong> Boutique categories and size taxonomies seeded.</p></div>';
    }

    $announcement = get_option('boutique_announcement_text', 'All India Free Shipping | 4 to 6 Days Delivery | Handcrafted in Kerala');
    $free_shipping = get_option('boutique_free_shipping_threshold', '999');
    $urgency_limit = get_option('boutique_stock_urgency_limit', '5');
    $whatsapp = get_option('boutique_support_whatsapp_number', '919876543210');
    $webhook = get_option('boutique_whatsapp_webhook_url', '');
    $dispatch_days = get_option('boutique_dispatch_lead_days', '2-3');
    $delivery_days = get_option('boutique_delivery_days', '4-6');
    ?>
    <div class="wrap boutique-admin-wrap">
        <h1 style="display: flex; align-items: center; gap: 10px; font-size: 24px; font-weight: 700; margin-bottom: 20px;">
            <span>🛍️</span> Boutique Store Control Center
        </h1>

        <div class="boutique-card">
            <form method="post" action="options.php">
                <?php settings_fields('boutique_settings_group'); ?>
                <?php do_settings_sections('boutique_settings_group'); ?>

                <h2>1. Storefront & Urgency Controls</h2>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="boutique_announcement_text">Announcement Bar Text</label></th>
                        <td>
                            <input name="boutique_announcement_text" type="text" id="boutique_announcement_text" value="<?php echo esc_attr($announcement); ?>" class="regular-text" style="width: 100%;">
                            <p class="description">Displays at the very top of all pages.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="boutique_free_shipping_threshold">Free Shipping Threshold (₹)</label></th>
                        <td>
                            <input name="boutique_free_shipping_threshold" type="number" id="boutique_free_shipping_threshold" value="<?php echo esc_attr($free_shipping); ?>" class="small-text">
                            <p class="description">Mini-cart calculates: "Add ₹X more to get FREE Delivery".</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="boutique_stock_urgency_limit">Stock Urgency Bar Limit</label></th>
                        <td>
                            <input name="boutique_stock_urgency_limit" type="number" id="boutique_stock_urgency_limit" value="<?php echo esc_attr($urgency_limit); ?>" class="small-text">
                            <p class="description">Shows the "🔥 Only X left in stock!" bar when inventory is equal to or below this number.</p>
                        </td>
                    </tr>
                </table>

                <h2 style="margin-top: 30px;">2. WhatsApp & Customer Notifications</h2>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="boutique_support_whatsapp_number">Support WhatsApp Number</label></th>
                        <td>
                            <input name="boutique_support_whatsapp_number" type="text" id="boutique_support_whatsapp_number" value="<?php echo esc_attr($whatsapp); ?>" class="regular-text" placeholder="919876543210">
                            <p class="description">Floating chat button on the website links directly to this number (include country code 91).</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="boutique_whatsapp_webhook_url">WhatsApp Webhook URL</label></th>
                        <td>
                            <input name="boutique_whatsapp_webhook_url" type="url" id="boutique_whatsapp_webhook_url" value="<?php echo esc_attr($webhook); ?>" class="regular-text" style="width: 100%;" placeholder="https://api.interakt.ai/v1/webhook...">
                            <p class="description">Dispatched automatically on paid order status (AiSensy, Interakt, Wati, or Zapier).</p>
                        </td>
                    </tr>
                </table>

                <h2 style="margin-top: 30px;">3. Shipping & Fulfillment Timelines</h2>
                <table class="form-table" role="presentation">
                    <tr>
                        <th scope="row"><label for="boutique_dispatch_lead_days">Packaging Lead Time (Days)</label></th>
                        <td>
                            <input name="boutique_dispatch_lead_days" type="text" id="boutique_dispatch_lead_days" value="<?php echo esc_attr($dispatch_days); ?>" class="small-text">
                            <p class="description">Days required to handcraft and pack items (Default: 2-3).</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="boutique_delivery_days">Estimated Delivery Days</label></th>
                        <td>
                            <input name="boutique_delivery_days" type="text" id="boutique_delivery_days" value="<?php echo esc_attr($delivery_days); ?>" class="small-text">
                            <p class="description">Total transit time shown on product pages and Order Tracker (Default: 4-6).</p>
                        </td>
                    </tr>
                </table>

                <?php submit_button(__('Save Boutique Settings', 'blocksy-child-boutique'), 'primary'); ?>
            </form>
        </div>

        <div style="background: #FAF6F2; border: 1px solid #EAE3DC; border-radius: 6px; padding: 20px 24px;">
            <h3 style="margin-top: 0; font-size: 15px;">⚡ Setup Helper: Seed Boutique Categories & Sizes</h3>
            <p style="font-size: 13px; color: #555;">Automatically creates categories (Kurthis, Peplum Tops, Dresses, Co-ord Set, In Store) and registers standard size taxonomies (XS, S, M, L, XL, XXL) in one click.</p>
            <form method="post">
                <?php wp_nonce_field('boutique_seed_action'); ?>
                <input type="hidden" name="boutique_action" value="seed_catalog">
                <button type="submit" class="button button-secondary">Seed Boutique Categories & Sizes</button>
            </form>
        </div>
    </div>
    <?php
}

/**
 * 5. Render Quick Stock Matrix Page (Boutique Inventory Manager)
 */
function blocksy_boutique_render_stock_matrix_page() {
    if (!current_user_can('manage_woocommerce')) {
        return;
    }

    if (isset($_POST['save_stock_matrix']) && check_admin_referer('boutique_stock_matrix_action')) {
        if (!empty($_POST['variation_stock']) && is_array($_POST['variation_stock'])) {
            foreach ($_POST['variation_stock'] as $var_id => $qty) {
                $var_id = intval($var_id);
                $qty = intval($qty);
                wc_update_product_stock($var_id, $qty);
            }
            echo '<div class="notice notice-success is-dismissible"><p><strong>Inventory Updated!</strong> Real-time stock counts have been saved.</p></div>';
        }
    }

    $args = array(
        'status' => 'publish',
        'type'   => 'variable',
        'limit'  => 50,
    );
    $products = wc_get_products($args);
    ?>
    <div class="wrap boutique-admin-wrap">
        <h1 style="font-size: 24px; font-weight: 700; margin-bottom: 8px;">📦 Boutique Quick Stock Matrix</h1>
        <p style="color: #666; margin-bottom: 20px;">Quickly view and adjust inventory quantities across all sizes without opening individual product editors.</p>

        <form method="post">
            <?php wp_nonce_field('boutique_stock_matrix_action'); ?>
            <input type="hidden" name="save_stock_matrix" value="1">

            <div style="overflow-x: auto; background: #FFF; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); border: 1px solid #CCD0D4;">
                <table class="boutique-stock-matrix-table">
                    <thead>
                        <tr>
                            <th style="min-width: 200px;">Product Name</th>
                            <th style="min-width: 300px;">Size Variations & Current Stock</th>
                            <th style="min-width: 140px;">POS Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)) : ?>
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 24px; color: #888;">
                                    No variable products found. Use <strong>Boutique Hub</strong> to seed categories or create your first variable kurthi/dress!
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($products as $prod) : 
                                $variations = $prod->get_available_variations();
                                ?>
                                <tr>
                                    <td>
                                        <strong><?php echo esc_html($prod->get_name()); ?></strong>
                                        <div style="font-size: 11px; color: #888;">ID: #<?php echo esc_html($prod->get_id()); ?> | SKU: <?php echo esc_html($prod->get_sku() ?: 'N/A'); ?></div>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                                            <?php foreach ($variations as $var) : 
                                                $var_obj = wc_get_product($var['variation_id']);
                                                $size_label = implode(', ', $var['attributes']);
                                                $curr_stock = $var_obj ? $var_obj->get_stock_quantity() : 0;
                                                $is_low = ($curr_stock <= 2 && $curr_stock > 0);
                                                $is_out = ($curr_stock <= 0);
                                                ?>
                                                <div style="display: flex; align-items: center; gap: 6px; background: #F8F9FA; padding: 4px 8px; border-radius: 4px; border: 1px solid #E2E4E7;">
                                                    <span style="font-size: 11px; font-weight: 700; text-transform: uppercase;"><?php echo esc_html($size_label); ?>:</span>
                                                    <input type="number" 
                                                           name="variation_stock[<?php echo esc_attr($var['variation_id']); ?>]" 
                                                           value="<?php echo esc_attr($curr_stock); ?>" 
                                                           min="0" 
                                                           style="width: 50px; height: 28px; text-align: center; font-weight: 700; <?php echo $is_out ? 'border-color:#D32F2F;color:#D32F2F;' : ($is_low ? 'border-color:#f76b6a;color:#f76b6a;' : ''); ?>">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #2E7D32; font-weight: 600; font-size: 11px;">● Synced via WCPOS</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($products)) : ?>
                <div style="margin-top: 20px;">
                    <button type="submit" class="button button-primary button-large" style="background:#161616;border-color:#161616;">Save All Stock Changes</button>
                </div>
            <?php endif; ?>
        </form>
    </div>
    <?php
}
