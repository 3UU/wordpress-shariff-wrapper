'use strict';

module.exports = function(shariff) {
    // var url = shariff.getOption('mailUrl');
    
    return {
        popup: false,
        mobileonly: false,
        // blank: url.indexOf('http') === 0,
        blank: false,
        shareText: 'mail',
        name: 'mail',
        faName: 's3uu-envelope',
        title: {
            'de': 'Per E-Mail versenden',
            'en': 'Send by email',
            'es': 'Enviar por email',
            'fr': 'Envoyer par courriel',
            'it': 'Inviare via email',
            'da': 'Sende via e-mail',
            'nl': 'Sturen via e-mail '
        },
        // shareUrl: shariff.getOption('mailUrl');
        shareUrl: shariff.getReferrerTrack() + '?view=mail'
    };
};
