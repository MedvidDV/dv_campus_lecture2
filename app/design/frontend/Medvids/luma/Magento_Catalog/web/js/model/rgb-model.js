define(
    ['ko'],
    function (ko) {
        'use strict';

        var red = ko.observable(0),
            green = ko.observable(0),
            blue = ko.observable(0);

        /**
         * Generate random number 0-255 for rgb color
         */
        function getRandomNumber() {
            return Math.floor(Math.random() * 255 + 1);
        }

        /**
         * Generate random rgb color
         */
        function updateColor() {
            red(getRandomNumber());
            green(getRandomNumber());
            blue(getRandomNumber());
        }

        return {
            updateColor: updateColor,
            red: red,
            green: green,
            blue: blue
        };
    }
);
