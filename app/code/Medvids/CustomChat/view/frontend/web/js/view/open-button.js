define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data'
], function ($, ko, Component, customerData) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Medvids_CustomChat/open_button',
            buttonActiveClass: ko.observable('active')
        },

        /** inheritdoc */
        initialize: function () {
            this._super();
            $(document).on('medvids_customChat_closeChat.medvids_customChat', $.proxy(this.showButton, this));
        },

        /** hide button on chat opening */
        openChat: function () {
            $(document).trigger('medvids_customChat_openChat.medvids_customChat');
            this.buttonActiveClass('');
        },

        /** show button on chat closing */
        showButton: function () {
            if (customerData.get('customer-chatmessages')) {
                this.buttonActiveClass('active');
            }
        }
    });
});
