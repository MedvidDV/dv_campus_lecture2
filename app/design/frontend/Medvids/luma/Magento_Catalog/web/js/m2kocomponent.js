define([
    'jquery',
    'uiComponent',
    'ko',
    'Magento_Catalog/js/model/rgb-model'
], function ($, Component, ko, rgbModel) {
    'use strict';

    var self;

    return Component.extend(
        {
            myTimer: ko.observable(0),
            randomColor: ko.computed(function () {
                return 'rgb(' + rgbModel.red() + ', ' + rgbModel.green() + ', ' + rgbModel.blue() + ')';
            }),

            /**
             * Call the incrementTime function to run on intialize
             */
            initialize: function () {
                self = this;

                this._super();
                this.incrementTime();
                this.subscribeToTime();
            },

            /**
             * increment myTimer every second
             */
            incrementTime: function () {
                var t = 0;

                setInterval(function () {
                    t++;
                    self.myTimer(t);
                }, 1000);
            },

            /**
             * Subscription to value change in myTimer
             */
            subscribeToTime: function () {
                this.myTimer.subscribe(function () {
                    rgbModel.updateColor();
                });
            }
        }
    );
});
