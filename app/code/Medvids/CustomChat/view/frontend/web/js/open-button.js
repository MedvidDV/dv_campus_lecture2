define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('MedvidsCustomeChat.openButton', {
        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click', $.proxy(this.openSidebar, this));
            $(document).on('medvids_customChat_closeChat.medvids_customChat', $.proxy(this.closeSidebar, this));

            if ($(this.element).hasClass('floated')) {
                $(this.element).parent().show();
            }
        },

        /**
         * Open Chat
         */
        openSidebar: function () {
            $(document).trigger('medvids_customChat_openChat.medvids_customChat');

            if ($(this.element).hasClass('floated')) {
                $(this.element).parent().fadeOut();
            }
        },

        /**
         * Close Chat
         */
        closeSidebar: function () {
            if ($(this.element).hasClass('floated')) {
                $(this.element).parent().fadeIn();
            }
        }
    });

    return $.MedvidsCustomeChat.openButton;
});
