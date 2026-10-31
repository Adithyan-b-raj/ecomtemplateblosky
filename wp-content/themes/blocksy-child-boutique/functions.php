<?php
/**
 * Blocksy Child Boutique Theme functions and definitions
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Enqueue parent and child stylesheets
 */
function blocksy_child_boutique_enqueue_styles() {
    wp_enqueue_style(
        'blocksy-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    wp_enqueue_style(
        'blocksy-child-boutique-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('blocksy-parent-style'),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'blocksy_child_boutique_enqueue_styles');

/**
 * Theme Setup: Add WooCommerce Support & Custom Image Galleries
 */
function blocksy_child_boutique_setup() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'blocksy_child_boutique_setup');

/**
 * Stock Urgency Bar Component for Single Product Page
 * Replicates the "Only X items left in stock" counter seen on boutique fashion sites
 */
function blocksy_boutique_stock_urgency_bar() {
    global $product;

    if (!$product || !$product->is_in_stock()) {
        return;
    }

    $stock_qty = $product->get_stock_quantity();

    // Show urgency bar for products with stock between 1 and 5
    if ($stock_qty && $stock_qty <= 5) {
        $max_threshold = 10;
        $progress_percentage = min(100, max(10, ($stock_qty / $max_threshold) * 100));
        ?>
        <div class="boutique-stock-urgency-wrapper" data-stock="<?php echo esc_attr($stock_qty); ?>">
            <div class="boutique-stock-urgency-label">
                <span>🔥 Only <strong><?php echo esc_html($stock_qty); ?></strong> left in stock</span>
                <span>Limited Boutique Piece</span>
            </div>
            <div class="boutique-stock-urgency-track">
                <div class="boutique-stock-urgency-progress" style="width: <?php echo esc_attr($progress_percentage); ?>%;"></div>
            </div>
        </div>
        <?php
    }
}
add_action('woocommerce_single_product_summary', 'blocksy_boutique_stock_urgency_bar', 15);
