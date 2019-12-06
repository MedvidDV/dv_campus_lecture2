define([
    'jquery',
    'jquery/ui',
    'jquery/validate',
    'mage/translate'
], function ($) {
    'use strict';

    return function () {
        $.validator.addMethod(
            'validate-phoneUA',
            function (phoneNumber, element) {
                phoneNumber = phoneNumber.replace(/\s+/g, '');

                return this.optional(element) || phoneNumber.length > 9 &&
                phoneNumber.match(/^(\+?38)?((\(0\d{2}\)|0\d{2})-?\d{3})-?\d{2}-?\d{2}$/);
            },
            $.mage.__('Please specify a valid phone number')
        );
    };
});
