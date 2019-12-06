var config = {
    map: {
        '*': {
            askQuestion: 'Medvids_AskQuestion/js/ask-question',
            validationAlert: 'Medvids_AskQuestion/js/validation-alert'
        }
    },
    config: {
        mixins: {
            'mage/validation': {
                'Medvids_AskQuestion/js/validation-mixin': true
            }
        }
    }
};
