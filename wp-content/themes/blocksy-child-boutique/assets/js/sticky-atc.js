/**
 * Mobile Sticky Add to Cart Bar
 * Appears when scrolling past the main product add-to-cart button on mobile screens
 */
(function ($) {
  'use strict';

  $(document).ready(function () {
    const $mainBtn = $('form.cart .single_add_to_cart_button');
    const $stickyBar = $('.boutique-sticky-atc');

    if (!$mainBtn.length || !$stickyBar.length) return;

    $(window).on('scroll', function () {
      if ($(window).width() > 768) {
        $stickyBar.removeClass('visible');
        return;
      }

      const btnOffset = $mainBtn.offset().top + $mainBtn.outerHeight();
      const scrollPos = $(window).scrollTop();

      if (scrollPos > btnOffset) {
        $stickyBar.addClass('visible');
      } else {
        $stickyBar.removeClass('visible');
      }
    });

    // Clicking the sticky button submits the primary cart form
    $('.boutique-sticky-atc-btn').on('click', function (e) {
      e.preventDefault();
      if ($mainBtn.is(':disabled') || $mainBtn.hasClass('disabled')) {
        // Scroll user back to selection if attributes not chosen
        $('html, body').animate({
          scrollTop: $('form.cart').offset().top - 80
        }, 400);
      } else {
        $mainBtn.trigger('click');
      }
    });
  });
})(jQuery);
