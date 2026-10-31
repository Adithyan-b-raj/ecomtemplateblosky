<?php
/**
 * Boutique Stock Handling & Inventory Engine
 *
 * Implements:
 * 1. Urgency stock progress bar
 * 2. Mobile sticky add-to-cart markup
 * 3. Product card badges (Low stock, In Store, Sold Out)
 * 4. Catalog in-stock filtering (?in-stock=1)
 * 5. WCPOS / REST API compatibility hooks
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * 1. Single Product Stock Urgency Progress Bar
 */
function blocksy_boutique_render_stock_urgency() {
    global $product;

    if (!$product) {
        return;
    }

    $is_variable = $product->is_type('variable');
    $stock_qty = $product->get_stock_quantity();
    $in_stock = $product->is_in_stock();

    // If out of stock completely
    if (!$in_stock) {
        ?>
        <div class="boutique-stock-urgency-wrapper" data-stock="0">
            <div class="boutique-stock-urgency-label">
                <span>⚠️ <strong>Sold Out</strong> — Join waitlist</span>
                <span class="boutique-stock-urgency-tag">Exclusive Batch</span>
            </div>
            <div class="boutique-stock-urgency-track">
                <div class="boutique-stock-urgency-progress" style="width: 0%; background: #9E9E9E;"></div>
            </div>
        </div>
        <?php
        return;
    }

    // For variable products, output container that JS will update on swatch click
    if ($is_variable) {
        ?>
        <div class="boutique-stock-urgency-wrapper" data-default-stock="<?php echo esc_attr($stock_qty ?: 10); ?>">
            <div class="boutique-stock-urgency-label">
                <span>✨ <strong>Real-time Stock:</strong> Select your size below</span>
                <span class="boutique-stock-urgency-tag">Handcrafted Boutique</span>
            </div>
            <div class="boutique-stock-urgency-track">
                <div class="boutique-stock-urgency-progress" style="width: 100%;"></div>
            </div>
        </div>
        <?php
        return;
    }

    // For simple products with limited stock
    if ($stock_qty && $stock_qty <= 5) {
        $pct = min(100, max(15, ($stock_qty / 10) * 100));
        ?>
        <div class="boutique-stock-urgency-wrapper" data-stock="<?php echo esc_attr($stock_qty); ?>">
            <div class="boutique-stock-urgency-label">
                <span>🔥 Only <strong><?php echo esc_html($stock_qty); ?></strong> left in stock</span>
                <span class="boutique-stock-urgency-tag">Limited Batch</span>
            </div>
            <div class="boutique-stock-urgency-track">
                <div class="boutique-stock-urgency-progress" style="width: <?php echo esc_attr($pct); ?>%;"></div>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'blocksy_boutique_render_stock_urgency', 15);

/**
 * 2. Mobile Sticky Add to Cart Bar Markup
 */
function blocksy_boutique_render_sticky_atc() {
    global $product;

    if (!$product || !$product->is_in_stock()) {
        return;
    }
    ?>
    <div class="boutique-sticky-atc">
        <div class="boutique-sticky-atc-info">
            <span class="boutique-sticky-atc-title"><?php echo esc_html($product->get_name()); ?></span>
            <span class="boutique-sticky-atc-price"><?php echo wp_kses_post($product->get_price_html()); ?></span>
        </div>
        <button type="button" class="boutique-sticky-atc-btn">Add to Bag</button>
    </div>
    <?php
}
add_action('woocommerce_after_single_product', 'blocksy_boutique_render_sticky_atc', 30);

/**
 * 3. Product Loop Badges: "In Store", "Only 1 Left", "Sold Out"
 */
function blocksy_boutique_product_loop_badges() {
    global $product;

    if (!$product) {
        return;
    }

    // Sold out badge
    if (!$product->is_in_stock()) {
        echo '<span class="boutique-badge out-of-stock">Sold Out</span>';
        return;
    }

    // In Store category badge
    if (has_term('in-store', 'product_cat', $product->get_id())) {
        echo '<span class="boutique-badge in-store">In Store Only</span>';
        return;
    }

    // Scarcity badge for 1-2 remaining units
    $stock = $product->get_stock_quantity();
    if ($stock && $stock <= 2) {
        echo '<span class="boutique-badge low-stock">Only ' . esc_html($stock) . ' Left</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'blocksy_boutique_product_loop_badges', 9);

/**
 * 4. Catalog In-Stock Filter (?in-stock=1)
 * Matches Kaka by Eliza's shop filter behavior
 */
function blocksy_boutique_handle_instock_filter($query) {
    if (!is_admin() && $query->is_main_query() && (is_shop() || is_product_category())) {
        if (isset($_GET['in-stock']) && $_GET['in-stock'] === '1') {
            $meta_query = $query->get('meta_query') ?: array();
            $meta_query[] = array(
                'key'     => '_stock_status',
                'value'   => 'instock',
                'compare' => '='
            );
            $query->set('meta_query', $meta_query);
        }
    }
}
add_action('pre_get_posts', 'blocksy_boutique_handle_instock_filter');

/**
 * 5. WCPOS / In-Store POS Synchronization Hooks
 * Exposes barcode/SKU and instant stock updates via REST API
 */
function blocksy_boutique_register_pos_fields() {
    register_rest_field('product', 'pos_barcode', array(
        'get_callback'    => function($object) {
            return get_post_meta($object['id'], '_sku', true);
        },
        'update_callback' => null,
        'schema'          => null,
    ));
}
add_action('rest_api_init', 'blocksy_boutique_register_pos_fields');
