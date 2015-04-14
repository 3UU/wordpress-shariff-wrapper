'use strict';

module.exports = function(shariff) {
    var url = encodeURIComponent(shariff.getURL());
    
    var title = shariff.getMeta('DC.title');
    var creator = shariff.getMeta('DC.creator');
    
    if (title.length > 0 && creator.length > 0) {
        title += ' - ' + creator;
    } 
    else {
        title = shariff.getTitle();
    }
    
    return {
        popup: false,
        mobileonly: false,
        shareText: 'mail',
        name: 'mailto',
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
        shareUrl: 'mailto:?body=' + url + shariff.getReferrerTrack() + '&subject=' + encodeURIComponent(title)
    };
    };
};
