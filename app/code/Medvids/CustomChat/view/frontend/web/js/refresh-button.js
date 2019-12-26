define([
    'jquery',
    'customChatOpenBtn',
    'customChatChatBody'
], function ($) {
    'use strict';

    $.widget('MedvidsCustomChat.refreshButton', {

        /**
         * @private
         */
        _create: function () {
            $(this.element).on('click.medvids_customChat', $.proxy(this.bindingSwitcher, this));

            if ($(this.element).hasClass('floated')) {
                $(this.element).parent().show();
            }
        },

        /**
         * for testing - destroy handlers, call method _destroy
         * @private
         */
        _destroyEvent: function () {
            $('.custom-chat-open-btn').openButton('destroy');
            $('#custom-chat-body-wrapper').chatBody('destroy');

            $(this.element).removeClass('destroy-binding').addClass('establish-binding');
        },

        /**
         * for testing - reestablish handlers, call method _reestablish
         * @private
         */
        _establishEvent: function () {
            $('.custom-chat-open-btn').openButton();
            $('#custom-chat-body-wrapper').chatBody();

            $(this.element).removeClass('establish-binding').addClass('destroy-binding');
        },

        /**
         * event trigger handler
         */
        bindingSwitcher: function () {
            if ($(this.element).hasClass('destroy-binding')) {
                this._destroyEvent();
            } else {
                this._establishEvent();
            }
        }
    });

    return $.MedvidsCustomChat.refreshButton;
});
