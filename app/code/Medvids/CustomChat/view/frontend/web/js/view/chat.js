define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Customer/js/customer-data',
    'Medvids_CustomChat/js/action/submit-message'
], function ($, ko, Component, customerData, submitMessage) {
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
            var payload = {
                'author_type': form['author_type'].value,
                'hideit': form.hideit.value,
                'author_message': form['author_message'].value,
                'form_key': $.mage.cookies.get('form_key'),
                isAjax: 1
            };

            submitMessage(payload, this.submitMessageAction)
            .done(function () {
                this.customerMessages.push(
                    {
                        'authorType': 'customer',
                        'message': this.messageText(),
                        'createdAt': this.getCurrentTime()
                    }
                );
                this.messageText('');
            }.bind(this))
            .always(function () {
                this.scrollToLastMessage();
            }.bind(this));
        }
    });
});
