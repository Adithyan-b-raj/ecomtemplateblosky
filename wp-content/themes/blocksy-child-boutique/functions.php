<?php
/**
 * Blocksy Child Boutique Theme functions and definitions
 * Inspired by Kaka by Eliza - Tailored for Indian Fashion Boutiques
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

define('BLOCKSY_CHILD_BOUTIQUE_VERSION', '1.0.0');
define('BLOCKSY_CHILD_BOUTIQUE_DIR', get_stylesheet_directory());
define('BLOCKSY_CHILD_BOUTIQUE_URI', get_stylesheet_directory_uri());

/**
 * 1. Enqueue Styles and Scripts
 */
function blocksy_child_boutique_scripts() {
    // Parent theme style
    wp_enqueue_style(
        'blocksy-parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Boutique Core Styles
    wp_enqueue_style(
        'blocksy-boutique-core',
        BLOCKSY_CHILD_BOUTIQUE_URI . '/assets/css/boutique-core.css',
        array('blocksy-parent-style'),
        BLOCKSY_CHILD_BOUTIQUE_VERSION
    );

    // Child theme style header
    wp_enqueue_style(
        'blocksy-child-boutique-style',
        get_stylesheet_uri(),
        array('blocksy-boutique-core'),
        BLOCKSY_CHILD_BOUTIQUE_VERSION
    );

    // Variation Stock Urgency Handler
    if (is_product()) {
        wp_enqueue_script(
            'blocksy-boutique-variations',
            BLOCKSY_CHILD_BOUTIQUE_URI . '/assets/js/boutique-variations.js',
            array('jquery'),
            BLOCKSY_CHILD_BOUTIQUE_VERSION,
            true
        );

        wp_enqueue_script(
            'blocksy-boutique-sticky-atc',
            BLOCKSY_CHILD_BOUTIQUE_URI . '/assets/js/sticky-atc.js',
            array('jquery'),
            BLOCKSY_CHILD_BOUTIQUE_VERSION,
            true
        );
    }

    // Free Shipping Calculator in Mini-Cart
    wp_enqueue_script(
        'blocksy-boutique-free-shipping',
        BLOCKSY_CHILD_BOUTIQUE_URI . '/assets/js/free-shipping-progress.js',
        array('jquery'),
        BLOCKSY_CHILD_BOUTIQUE_VERSION,
        true
    );

    // Localize cart subtotal & threshold
    $cart_subtotal = (function_exists('WC') && WC()->cart) ? WC()->cart->get_subtotal() : 0;
    wp_localize_script('blocksy-boutique-free-shipping', 'boutiqueSettings', array(
        'threshold' => apply_filters('boutique_free_shipping_threshold', 999),
        'subtotal'  => floatval($cart_subtotal),
        'ajax_url'  => admin_url('admin-ajax.php')
    ));
}
add_action('wp_enqueue_scripts', 'blocksy_child_boutique_scripts');

/**
 * 2. Theme Setup
 */
function blocksy_child_boutique_setup() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'blocksy_child_boutique_setup');

/**
 * 3. Include Core Modules
 */
require_once BLOCKSY_CHILD_BOUTIQUE_DIR . '/inc/stock-inventory.php';
require_once BLOCKSY_CHILD_BOUTIQUE_DIR . '/inc/order-handling.php';
require_once BLOCKSY_CHILD_BOUTIQUE_DIR . '/inc/whatsapp-notifications.php';
require_once BLOCKSY_CHILD_BOUTIQUE_DIR . '/inc/order-tracking.php';
require_once BLOCKSY_CHILD_BOUTIQUE_DIR . '/inc/sample-data.php';
require_once BLOCKSY_CHILD_BOUTIQUE_DIR . '/block-patterns/patterns.php';
