define([
    'jquery',
    'autocloseAlert'
], function ($, alert) {
    'use strict';

    return function (payload, url) {
        return $.ajax({
            url: url,
            data: payload,
            type: 'post',
            dataType: 'json',
            context: this,

            /** @inheritdoc */
            success: function (response) {
                alert({
                    modalClass: 'custom-chat-alert',
                    title: response.title,
                    content: response.message,
                    buttons: []
                });
            },

            /** @inheritdoc */
            error: function (error) {
                alert({
                    modalClass: 'custom-chat-alert',
                    title: error.title,
                    content: error.message,
                    buttons: []
                });
            }
        });
    };
});
