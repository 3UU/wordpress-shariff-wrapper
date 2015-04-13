'use strict';

module.exports = function(shariff) {
    return {
        popup: false,
	noblank: false,
	mobileonly: false,
        shareText: 'mail',
        name: 'mail',
        faName: 's3uu-envelope',
        title: {
            'de': 'Per E-Mail versenden',
            'en': 'Send by email',
            'fr': 'Envoyer par e-mail',
            'es': 'Enviar por email'
        },
        shareUrl: 'mailto:?body=' + encodeURIComponent(shariff.getURL() + shariff.getReferrerTrack()) + '&subject=' + shariff.getTitle()
    };
};
