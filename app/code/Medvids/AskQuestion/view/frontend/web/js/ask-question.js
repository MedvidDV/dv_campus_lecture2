define([
    'jquery',
    'validationAlert',
    'Magento_Ui/js/modal/alert',
    'jquery/ui',
    'mage/translate'
], function ($, validationAlert, alert) {
    'use strict';

    $.widget('medvids.askQuestion', {
        options: {
            cookieName: 'question_sent',
            cookieDuration: 120,
            cookieExp: 0
        },

        /**
         * @private
         */
        _create: function () {
            $(this.element).submit(this.submitForm.bind(this));
        },

        /**
         * Submit a form to the server
         */
        submitForm: function () {
            if (!this.validateForm()) {
                validationAlert();

                return;
            }

            if ($.mage.cookies.get(this.options.cookieName)) {
                console.log(document.cookie);
                alert({
                    title: $.mage.__('Limit reached'),
                    content: $.mage.__('I\'m sorry, but your limit for question is reached.' +
                        'You can check back in 2 minutes')
                });

                return;
            }

            this.ajaxSubmit();
        },

        /**
         * Submit request via AJAX. Add form key to the post data
         */

        ajaxSubmit: function () {
            var formData = new FormData($(this.element).get(0)),
                self = this;

            formData.append('form_key', $.mage.cookies.get('form_key'));

            $.ajax({
                url: $(this.element).attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                type: 'post',
                dataType: 'json',
                context: this,

                /** Add spinner */
                beforeSend: function () {
                    $('body').trigger('processStart');
                },

                /** Remove spinner, set cookie*/
                success: function (response) {
                    $('body').trigger('processStop');
                    alert({
                        title: $.mage.__(response.status),
                        content: $.mage.__(response.message)
                    });
                    $(self.element)[0].reset();

                    //this.options.cookieExp = new Date().getTime() + this.options.cookieDuration * 1000;

                    $.mage.cookies.set(
                        this.options.cookieName,
                        'question_form',
                        {
                            lifetime: this.options.cookieDuration
                        }
                    );
                },

                /** Remove spinner */
                fail: function (error) {
                    $('body').trigger('processStop');
                    alert({
                        title: $.mage.__(error.status),
                        content: $.mage.__(error.message)
                    });
                }
            });
        },

        /**
         * Form Validation
         * @returns {jQuery|bool}
         */
        validateForm: function () {
            return $(this.element).validation().valid();
        }
    });

    return $.medvids.askQuestion;
});
