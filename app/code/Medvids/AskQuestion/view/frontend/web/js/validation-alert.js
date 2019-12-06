define([
    'jquery',
    'jquery/ui',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($) {
    'use strict';

    $.widget('medvids.validationAlert', $.mage.alert, {
        options: {
            modalClass: 'error',
            title: $.mage.__('Form has not been submitted'),
            content: $.mage.__('Please, check the form data and try again')
        },

        /**
         * Generate modal confirmation
         */
        openModal: function () {
            var element = this._super();

            $('<div></div>').html(this.options.content).appendTo(element);
        }
    });

    return $.medvids.validationAlert;
});
