/**
 * Dynamic Variation Stock Urgency & Swatch Handler
 * Replicates Kaka by Eliza's dynamic stock counter behavior
 */
(function ($) {
  'use strict';

  $(document).ready(function () {
    const $form = $('form.variations_form');
    if (!$form.length) return;

    const $urgencyWrapper = $('.custom-stock-bar, .boutique-stock-urgency-wrapper');
    const $label = $urgencyWrapper.find('span');
    const $progress = $urgencyWrapper.find('.progress-fill, .boutique-stock-urgency-progress');

    // When a specific variation is selected
    $form.on('found_variation', function (event, variation) {
      if (!variation) return;

      const isInstock = variation.is_in_stock;
      const maxQty = variation.max_qty; // stock quantity

      if (!isInstock || maxQty === 0) {
        $urgencyWrapper.show();
        $label.html('⚠️ <b>Sold Out</b> in this size');
        $progress.css({
          width: '0%',
          background: '#9E9E9E'
        });
      } else if (maxQty && maxQty <= 5) {
        // Limited stock: trigger scarcity bar with exact #f76b6a color
        $urgencyWrapper.show();
        $label.html('Only <b>' + maxQty + '</b> left in stock!');
        const pct = Math.min(100, Math.max(15, (maxQty * 20)));
        $progress.css({
          width: pct + '%',
          background: '#f76b6a'
        });
      } else {
        // Abundant stock
        $urgencyWrapper.show();
        $label.html('✅ <b>In Stock</b> & Ready to Dispatch');
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
        $label.html('Only <b>' + defaultStock + '</b> left in stock!');
        const pct = Math.min(100, Math.max(15, (defaultStock * 20)));
        $progress.css({ width: pct + '%', background: '#f76b6a' });
      } else {
        $label.html('✨ <b>Real-time Stock:</b> Select your size below');
        $progress.css({ width: '100%', background: '#f76b6a' });
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
