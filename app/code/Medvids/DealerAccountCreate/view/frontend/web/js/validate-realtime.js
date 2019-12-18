define([
    'jquery',
    'jquery/ui',
    'mage/validation'
], function ($) {
    'use strict';

    $.widget('medvids.validateRealtime', {
        /**
         * Widget initialization
         * @private
         */
        _create: function () {
            this._bind();
        },

        /**
         * Event binding, will monitor change, keyup and paste events.
         * @private
         */
        _bind: function () {
            this._on(this.element, {
                'change': this.validateField,
                'keyup': this.validateField,
                'paste': this.validateField,
                'focusout': this.touchDetect
            });
        },

        /**
         * validate current input
         */
        validateField: function () {
            if (this.element.hasClass('touched')) {
                $.validator.validateSingleElement(this.element, {});
            }
        },

        /**
         * prevent validation before user enters data the first time
         */
        touchDetect: function () {
            this.element.addClass('touched');
            this.validateField();
        }
    });

    return $.medvids.validateRealtime;
});
