define([
    'jquery',
    'Magento_Ui/js/modal/modal'
],  ($, modal) => {
    'use strict';

    let config = {
        type: 'popup',
        responsive: true,
        innerScroll: true,
        title: $.mage.__('Dealer Registration'),
        buttons: []
    };

    $('#dealerRegisterModal').empty();

    modal(config, $('#dealerRegisterModal'));
    $('#dealerRegister').on('click', function () {
        $('#dealerRegisterModal').modal('openModal');
    });
});