define([// jscs:ignore internalError
    'jquery',
    'ko',
    'uiComponent',
    'autocloseAlert',
    //'Magento_Ui/js/modal/alert',
    'Magento_Customer/js/customer-data'
], function ($, ko, Component, alert, customerData) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Medvids_CustomChat/chat',
            chatActiveClass: ko.observable(''),
            messageCollection: customerData.get('customer-chatmessages'),
            customerMessages: ko.observableArray([]),
            messageText: ko.observable(''),
            getMessagesAction: '',
            submitMessageAction: ''
        },

        /** inheritdoc */
        initialize: function () {
            this._super();
            $(document).on('medvids_customChat_openChat.medvids_customChat', $.proxy(this.openChat, this));
        },

        /** close chat body */
        closeChat: function () {
            $(document).trigger('medvids_customChat_closeChat.medvids_customChat');
            this.chatActiveClass('');
        },

        /** Generate current time H:i format */
        getCurrentTime: function () {
            var time = new Date();

            return time.getHours() + ':' + (time.getMinutes() < 10 ? '0' : '') +
                time.getMinutes();
        },

        /** get initial 10 messages for user */
        getMessages: function () {
            if (this.messageCollection().messages.length) {
                this.customerMessages(this.messageCollection().messages);
            } else {
                this.customerMessages([{
                    'message': 'How may I help you?',
                    'authorType': 'admin',
                    'createdAt': this.getCurrentTime()
                }]);
            }

            this.scrollToLastMessage();
        },

        /** open chat body */
        openChat: function () {
            this.getMessages();
            this.chatActiveClass('active');
        },

        /** Scroll to the last message */
        scrollToLastMessage: function () {
            var id = '[data-repeat-index=' + (this.customerMessages().length - 1) + ']';

            $('.message' + id).get(0).scrollIntoView();
        },

        /** Submit message from message form, validation for empty string */
        submitMessage: function (form) {
            if (this.messageText().trim()) {
                this.submitMessageAjax(form);
            }
        },

        /** Ajax submit message after validation */
        submitMessageAjax: function (form) {
            var formData = new FormData(form);

            formData.append('form_key', $.mage.cookies.get('form_key'));

            $.ajax({
                url: this.submitMessageAction,
                data: formData,
                processData: false,
                contentType: false,
                isAjax: 1,
                type: 'post',
                dataType: 'json',
                context: this
            })
            .done(function (response) {
                alert({
                    modalClass: 'custom-chat-alert',
                    title: response.title,
                    content: response.message,
                    buttons: []
                });

                this.customerMessages.push(
                    {
                        'authorType': 'user',
                        'message': this.messageText(),
                        'createdAt': this.getCurrentTime()
                    }
                );
                this.messageText('');
            }).fail(function (error) {
                alert({
                    modalClass: 'custom-chat-alert',
                    title: error.title,
                    content: error.message,
                    buttons: []
                });
            }).always(function () {
                this.scrollToLastMessage();
            });
        }
    });
});
