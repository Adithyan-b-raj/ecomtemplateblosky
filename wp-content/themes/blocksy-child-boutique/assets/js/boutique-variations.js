/**
 * Dynamic Variation Stock Urgency & Swatch Handler
 * Replicates Kaka by Eliza's dynamic stock counter behavior
 */
(function ($) {
  'use strict';

  $(document).ready(function () {
    const $form = $('form.variations_form');
    if (!$form.length) return;

    const $urgencyWrapper = $('.boutique-stock-urgency-wrapper');
    const $label = $('.boutique-stock-urgency-label span:first-child');
    const $progress = $('.boutique-stock-urgency-progress');

    // When a specific variation is selected
    $form.on('found_variation', function (event, variation) {
      if (!variation) return;

      const isInstock = variation.is_in_stock;
      const maxQty = variation.max_qty; // stock quantity

      if (!isInstock || maxQty === 0) {
        $urgencyWrapper.show();
        $label.html('⚠️ <strong>Sold Out</strong> in this size');
        $progress.css({
          width: '0%',
          background: '#9E9E9E'
        });
      } else if (maxQty && maxQty <= 5) {
        // Limited stock: trigger scarcity bar
        $urgencyWrapper.show();
        $label.html('🔥 Only <strong>' + maxQty + '</strong> left in stock');
        const pct = Math.min(100, Math.max(15, (maxQty / 10) * 100));
        $progress.css({
          width: pct + '%',
          background: 'linear-gradient(90deg, #B76E58 0%, #D88972 100%)'
        });
      } else {
        // Abundant stock
        $urgencyWrapper.show();
        $label.html('✅ <strong>In Stock</strong> & Ready to Dispatch');
        $progress.css({
          width: '100%',
          background: '#2E7D32'
        });
      }
    });

    // When variations are reset / cleared
    $form.on('reset_data', function () {
      const defaultStock = $urgencyWrapper.data('default-stock');
      if (defaultStock && defaultStock <= 5) {
        $label.html('🔥 Only <strong>' + defaultStock + '</strong> left in stock');
        const pct = Math.min(100, Math.max(15, (defaultStock / 10) * 100));
        $progress.css('width', pct + '%');
      } else {
        $label.html('✨ Select your size for real-time stock');
        $progress.css('width', '100%');
      }
    });

    // Custom Swatch Pills Interaction
    $(document).on('click', '.boutique-swatch-pill:not(.disabled)', function () {
      const $this = $(this);
      const val = $this.data('value');
      const attrName = $this.closest('.boutique-swatch-list').data('attribute');

      $this.siblings().removeClass('selected');
      $this.addClass('selected');

      const $select = $('select[name="' + attrName + '"]');
      if ($select.length) {
        $select.val(val).trigger('change');
      }
    });
  });
})(jQuery);
