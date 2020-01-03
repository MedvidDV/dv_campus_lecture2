define([
    'underscore',
    'Magento_Ui/js/grid/columns/select'
], function (_, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            customClasses: {
                pending: 'question-pending',
                answered: 'question-answered'
            },
            //bodyTmpl: 'Medvids_AskQuestion/grid/cells/text'
        },

        /**
         * @param {Object} row
         * @returns {*|String}
         */
        getCustomClass: function (row) {
            console.log(row);

            return this.customClasses[row.status] || '';
        }
    });
});
