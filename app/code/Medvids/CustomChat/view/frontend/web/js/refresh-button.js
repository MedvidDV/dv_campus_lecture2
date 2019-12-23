define([
    'jquery'
], function ($) {
    'use strict';

    $.widget('MedvidsCustomeChat.refreshButton', {

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
            $(document).trigger('medvids_customChat_destroyBinding.medvids_customChat');
            $(this.element).removeClass('destroy-binding').addClass('establish-binding');
        },

        /**
         * for testing - reestablish handlers, call method _reestablish
         * @private
         */
        _establishEvent: function () {
            $(document).trigger('medvids_customChat_establishBinding.medvids_customChat');
            $(this.element).removeClass('establish-binding').addClass('destroy-binding');
        },

        /**
         * event trigger handler
         */
        bindingSwitcher: function () {
            if ($(this.element).hasClass('destroy-binding')) {
                this._destroyEvent();

                return;
            }

            if ($(this.element).hasClass('establish-binding')) {
                this._establishEvent();

                return;
            }
        }
    });

    return $.MedvidsCustomeChat.refreshButton;
});
