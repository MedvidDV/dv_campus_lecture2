define([
    'jquery',
    'underscore',
    'jquery/ui',
    'Magento_Ui/js/modal/alert',
    'mage/translate'
], function ($, _) {
    'use strict';

    $.widget('medvids.autoclosedAlert', $.mage.alert, {
        options: {
            modalClass: 'error',
            modalLifeTime: '' | 4000,
            content: '',
            actions: {

                /**
                 * Callback always - called on all actions.
                 */
                always: function () {}
            }
        },

        /**
         * Add timeout for autoclosing to the methods
         * @private
         */
        _create: function () {
            this.setAutoClose();
            this._super();
        },

        /**
         * Add timeout for autoclosing
         */
        setAutoClose: function () {
            var that = this;

            setTimeout(function () {
                that.closeModal();
            }, this.options.modalLifeTime);
        },

        /**
         * Add content to the message
         * @returns {*}
         */
        openModal: function () {
            var element = this._super();

            $('<div></div>').html(this.options.content).appendTo(element);

            return this._super();
        },

        /**
         *
         * @returns Alert.js CloseModal func
         */
        closeModal: function () {
            this._super();
            //this.element.bind('autoclosedalertclosed', _.bind(this._remove, this));
            this._remove();
            this._destroyOverlay();

            return this._super();
        }
    });

    return $.medvids.autoclosedAlert;
});
