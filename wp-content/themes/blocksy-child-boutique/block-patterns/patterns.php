<?php
/**
 * Boutique Gutenberg Block Patterns
 *
 * Provides ready-to-insert sections matching Kaka by Eliza:
 * 1. Boutique Hero Banner with Warm Sand Background & CTA
 * 2. Category Navigation Pills (Kurthis, Dresses, Peplum Tops, In Store)
 * 3. Feature Highlights (Handcrafted, All India Shipping, 4-6 Days Delivery)
 *
 * @package BlocksyChildBoutique
 */

if (!defined('ABSPATH')) {
    exit;
}

function blocksy_boutique_register_block_patterns() {
    if (!function_exists('register_block_pattern')) {
        return;
    }

    // Register Pattern Category
    register_block_pattern_category('boutique-fashion', array(
        'label' => __('Boutique Fashion (Kaka Style)', 'blocksy-child-boutique')
    ));

    // 1. Boutique Hero Section
    register_block_pattern('blocksy-child-boutique/hero-banner', array(
        'title'       => __('Boutique Hero Banner', 'blocksy-child-boutique'),
        'description' => __('Warm minimalist boutique hero with call to action', 'blocksy-child-boutique'),
        'categories'  => array('boutique-fashion'),
        'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px","left":"20px","right":"20px"}},"color":{"background":"#FAF6F2"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group has-background" style="background-color:#FAF6F2;padding-top:60px;padding-right:20px;padding-bottom:60px;padding-left:20px">
    <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"letterSpacing":"1px","textTransform":"uppercase","fontSize":"36px"}},"textColor":"black"} -->
    <h1 class="wp-block-heading has-text-align-center has-black-color has-text-color" style="font-size:36px;letter-spacing:1px;text-transform:uppercase">Handcrafted Elegance</h1>
    <!-- /wp:heading -->
    <!-- wp:paragraph {"textAlign":"center","style":{"typography":{"fontSize":"16px"}},"textColor":"gray"} -->
    <p class="has-text-align-center has-gray-color has-text-color" style="font-size:16px">Discover pure cotton kurthis, chic peplum tops, and limited boutique dresses made with love.</p>
    <!-- /wp:paragraph -->
    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"25px"}}}} -->
    <div class="wp-block-buttons" style="margin-top:25px">
        <!-- wp:button {"style":{"color":{"background":"#1C1917","text":"#FFFFFF"},"border":{"radius":"4px"}}} -->
        <div class="wp-block-button"><a class="wp-block-button__link has-text-color has-background" href="/shop/" style="border-radius:4px;background-color:#1C1917;color:#FFFFFF">Explore Collection</a></div>
        <!-- /wp:button -->
    </div>
    <!-- /wp:buttons -->
</div>
<!-- /wp:group -->'
    ));

    // 2. Category Navigation Pills
    register_block_pattern('blocksy-child-boutique/category-pills', array(
        'title'       => __('Boutique Category Pills', 'blocksy-child-boutique'),
        'description' => __('Quick circular or pill filter links for boutique categories', 'blocksy-child-boutique'),
        'categories'  => array('boutique-fashion'),
        'content'     => '<!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","bottom":"20px"}}},"layout":{"type":"flex","justifyContent":"center","flexWrap":"wrap"}} -->
<div class="wp-block-group" style="padding-top:20px;padding-bottom:20px">
    <div class="boutique-swatch-list" style="display:flex;gap:10px;flex-wrap:wrap;justify-content:center;">
        <a href="/shop/" class="boutique-swatch-pill selected" style="text-decoration:none;">All</a>
        <a href="/product-category/kurthis/" class="boutique-swatch-pill" style="text-decoration:none;">Kurthis</a>
        <a href="/product-category/peplum-tops/" class="boutique-swatch-pill" style="text-decoration:none;">Peplum Tops</a>
        <a href="/product-category/dresses/" class="boutique-swatch-pill" style="text-decoration:none;">Dresses</a>
        <a href="/product-category/co-ord-set/" class="boutique-swatch-pill" style="text-decoration:none;">Co-ord Set</a>
        <a href="/product-category/in-store/" class="boutique-swatch-pill" style="text-decoration:none;background:#263238;color:#FFF;">In Store</a>
        <a href="/product-category/clearance-sale/" class="boutique-swatch-pill" style="text-decoration:none;border-color:#B76E58;color:#B76E58;">Sale</a>
    </div>
</div>
<!-- /wp:group -->'
    ));
}
add_action('init', 'blocksy_boutique_register_block_patterns');
