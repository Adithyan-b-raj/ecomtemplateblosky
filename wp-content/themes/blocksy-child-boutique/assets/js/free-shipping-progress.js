/**
 * Free Shipping Progress Calculator for Slide-Out Mini-Cart Drawer
 */
(function ($) {
  'use strict';

  function updateFreeShipping(cartTotal) {
    const threshold = window.boutiqueShippingThreshold || 999; // Default ₹999 free shipping
    const $container = $('.boutique-free-shipping-box');
    const $text = $('.boutique-free-shipping-text');
    const $bar = $('.boutique-free-shipping-bar');

    if (!$container.length) return;

    if (cartTotal >= threshold) {
      $text.html('🎉 Congratulations! You have unlocked <strong>FREE Shipping!</strong>');
      $bar.css('width', '100%');
    } else {
      const remaining = threshold - cartTotal;
      const pct = Math.min(100, Math.max(5, (cartTotal / threshold) * 100));
      $text.html('Add <strong>₹' + remaining.toFixed(0) + '</strong> more to unlock <strong>FREE Shipping</strong>');
      $bar.css('width', pct + '%');
    }
  }

  $(document).ready(function () {
    // Initial check
    if (window.boutiqueCartSubtotal !== undefined) {
      updateFreeShipping(window.boutiqueCartSubtotal);
    }

    // On WooCommerce AJAX fragments refresh
    $(document.body).on('wc_fragments_refreshed added_to_cart removed_from_cart', function () {
      $.ajax({
        url: window.boutiqueAjaxUrl || '/?wc-ajax=get_cart_subtotal',
        type: 'GET',
        dataType: 'json',
        success: function (res) {
          if (res && res.subtotal !== undefined) {
            updateFreeShipping(parseFloat(res.subtotal));
          }
        }
      });
    });
  });
})(jQuery);
