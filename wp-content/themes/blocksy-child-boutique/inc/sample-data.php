<?php
/**
 * Boutique Sample Data Seed Utility
 *
 * Provides a 1-click admin action or WP-CLI command to seed sample boutique categories & variable products
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

function blocksy_boutique_seed_categories() {
    $categories = array(
        'All',
        'Kurthis',
        'Peplum Tops',
        'Dresses',
        'Co-ord set',
        'Short Kurti',
        'In Store',
        'Clearance Sale'
    );

    $cat_ids = array();
    foreach ($categories as $cat) {
        $term = term_exists($cat, 'product_cat');
        if (!$term) {
            $created = wp_insert_term($cat, 'product_cat');
            if (!is_wp_error($created)) {
                $cat_ids[$cat] = $created['term_id'];
            }
        } else {
            $cat_ids[$cat] = is_array($term) ? $term['term_id'] : $term;
        }
    }
    return $cat_ids;
}

/**
 * Register Global 'Size' Attribute if not already present
 */
function blocksy_boutique_setup_size_attribute() {
    global $wpdb;

    $attribute_name = 'size';
    $attribute_label = 'Size';

    $attribute_id = wc_attribute_taxonomy_id_by_name($attribute_name);
    if (!$attribute_id) {
        $attribute_id = wc_create_attribute(array(
            'name'         => $attribute_label,
            'slug'         => $attribute_name,
            'type'         => 'select',
            'order_by'     => 'menu_order',
            'has_archives' => true,
        ));
    }

    // Register taxonomy terms: XS, S, M, L, XL, XXL
    $sizes = array('XS', 'S', 'M', 'L', 'XL', 'XXL');
    $taxonomy = 'pa_' . $attribute_name;

    if (taxonomy_exists($taxonomy)) {
        foreach ($sizes as $size) {
            if (!term_exists($size, $taxonomy)) {
                wp_insert_term($size, $taxonomy);
            }
        }
    }

    return $attribute_id;
}
add_action('admin_init', 'blocksy_boutique_setup_size_attribute');
