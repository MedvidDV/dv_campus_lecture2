define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('MedvidsCustomChat.openButton', {
        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.medvids_customChat', $.proxy(this.openChat, this));
            $(document).on('medvids_customChat_closeChat.medvids_customChat', $.proxy(this.closeChat, this));
            $(document).on('medvids_customChat_destroyBinding.medvids_customChat', $.proxy(this.destroy, this));

            if ($(this.element).hasClass('floated')) {
                $(this.element).parent().show();
            }
        },

        /**
         * @private
         */
        _destroy: function () {
            $(this.element).off('click.medvids_customChat');
            $(document).off('medvids_customChat_closeChat.medvids_customChat');
            $(document).off('medvids_customChat_destroyBinding.medvids_customChat');
        },

        /**
         * Open Chat
         */
        openChat: function () {
            $(document).trigger('medvids_customChat_openChat.medvids_customChat');

            if ($(this.element).hasClass('floated')) {
                $(this.element).parent().fadeOut();
            }
        },

        /**
         * Close Chat
         */
        closeChat: function () {
            if ($(this.element).hasClass('floated')) {
                $(this.element).parent().fadeIn();
            }
        }
    });

    return $.MedvidsCustomChat.openButton;
});
