define([
    'jquery',
    'autocloseAlert',
    //'Magento_Ui/js/modal/alert',
    'jquery/ui',
    'mage/translate'
], function ($, alert) {
    'use strict';

    $.widget('MedvidsCustomChat.chatBody', {
        options: {
            chatMessageTextArea: '#custom_chat_user_message',
            closeBtn: '#custom-chat-close-btn',
            messageForm: '#custom-chat-message-form',
            messageHistory: '#custom-chat-chat-history > ul',
            greetingMsg: $.mage.__('How may I help you?')
        },

        /**
         * @private
         */
        _create: function () {
            $(this.options.closeBtn).on('click.medvids_customChat', $.proxy(this.closeChat, this));
            $(document).on('medvids_customChat_openChat.medvids_customChat', $.proxy(this.openChat, this));
            $(document).on('medvids_customChat_openChat.medvids_customChat', $.proxy(this.getCollection, this));
            $(document).on('medvids_customChat_destroyBinding.medvids_customChat', $.proxy(this._destroy, this));
            $(this.options.messageForm).submit(this.submitMessage.bind(this));
        },

        /**
         * @private
         */
        _destroy: function () {
            $(this.options.closeBtn).off('click.medvids_customChat');
            $(document).off('medvids_customChat_openChat.medvids_customChat');
            $(document).off('medvids_customChat_destroyBinding.medvids_customChat');
        },

        /**
         * add sent message to the message tree
         * @private
         */
        _appendMessage: function (message) {
            $(this.options.messageHistory).append(message);
        },

        /**
         * reset message field
         * @private
         */
        _resetForm: function (form) {
            form.get(0).reset();
        },

        /**
         * build a message, added current time
         */
        _generateMessage: function (message, user, time) {
            var messageWrapper = $('<li class="message-' + user + ' message"></li>'),
                messageBody = $('<span class="message-body"></span>').text(message),
                messageTime;

            time = time || '';

            if (time) {
                messageTime = $('<span class="message-time"></span>').text(time);
            } else {
                time = new Date();
                messageTime = $('<span class="message-time"></span>').text(
                    time.getHours() + ':' + (time.getMinutes() < 10 ? '0' : '') +
                    time.getMinutes()
                );
            }

            return messageWrapper.append(messageBody.append(messageTime));
        },

        /**
         * auto scroll to the last message
         */
        scrollToLastMessage: function () {
            $('.message:last-child', $(this.options.messageHistory)).get(0).scrollIntoView();
        },

        /**
         * add active class to show the chat body
         */
        openChat: function () {
            $(this.element).fadeIn().addClass('active');
        },

        /**
         * sends message to backend and generate message in the tree
         */
        submitMessage: function () {
            var message = $(this.options.chatMessageTextArea).val().trim();

            if (message) {
                this.ajaxSubmit(message);
            }
        },

        /**
         * ajax request
         */
        ajaxSubmit: function (message) {
            var formData = new FormData($(this.options.messageForm).get(0));

            formData.append('form_key', $.mage.cookies.get('form_key'));

            $.ajax({
                url: $(this.options.messageForm).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                dataType: 'json',
                context: this,

                /**
                 * success response
                 */
                success: function (response) {
                    alert({
                        modalClass: 'custom-chat-alert',
                        title: response.title,
                        content: response.message,
                        buttons: []
                    });
                    this._appendMessage(this._generateMessage(message, 'user'));
                    this.scrollToLastMessage();
                    this._resetForm($(this.options.messageForm));
                },

                /**
                 * failed response
                 */
                fail: function (error) {
                    alert({
                        modalClass: 'custom-chat-alert',
                        title: error.title,
                        content: error.message,
                        buttons: []
                    });
                }
            });
        },

        /**
         *
         */
        getCollection: function () {
            var self = this;

            $.ajax({
                url: 'send-message/collection/messages',
                dataType: 'json',
                type: 'get',
                context: this
            }).done(function (response) {
                if (response.messages.length) {
                    $.each(response.messages, function (index, item) {
                        self._appendMessage(
                            self._generateMessage(
                                item.message,
                                item.authorType,
                                item.createdAt
                            )
                        );
                    });
                } else {
                    self._appendMessage(self._generateMessage(self.options.greetingMsg, 'admin'));
                }
            }).fail(function () {
                this._appendMessage(this._generateMessage(
                    $.mage.__('History couldn\'t be loaded right now'),
                    'system'
                ));
                this._appendMessage(this._generateMessage(this.options.greetingMsg, 'admin'));
            }).always(function () {
                this.scrollToLastMessage();
            });
        },

        /**
         * generate custom event to show chat button; hide chat body
         */
        closeChat: function () {
            $(document).trigger('medvids_customChat_closeChat.medvids_customChat');
            $(this.element).removeClass('active').fadeOut();
            $(this.options.messageHistory).html('');
        }
    });

    return $.MedvidsCustomChat.chatBody;
});
