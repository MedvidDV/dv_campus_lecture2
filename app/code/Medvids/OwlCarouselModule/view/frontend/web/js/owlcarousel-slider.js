define([
    'jquery',
    'owlcarousel'
], function ($) {
    'use strict';

    return function (config) {
        var options = {
            loop: config.loop || true,
            autoOpen: config.autoOpen || true,
            autoplay: config.autoplay || true,
            nav: config.nav || true,
            dots: config.dots || false,
            items: config.items || 1,
            autoplayHoverPause: config.autoplayHoverPause || true,
            animateIn: config.animateIn || 'fadeIn',
            animateOut: config.animateOut || 'fadeOut',
            autoplaySpeed: config.autoplaySpeed || 50
        };

        $('.owl-carousel').owlCarousel(options);
    };

});
