'use strict';

module.exports = function(shariff) {
    var url = encodeURIComponent(shariff.getURL());

    var title = shariff.getMeta('DC.title');
    var creator = shariff.getMeta('DC.creator');

    if (title.length > 0 && creator.length > 0) {
        title += ' - ' + creator;
    } else {
        title = shariff.getTitle();
    }
    
    return {
        popup: true,
        mobileonly: false,
        shareText: {
            'de': 'pinnen',
            'en': 'pin it',
        },
        name: 'pinterest',
        faName: 's3uu-pinterest',
        title: {
            'de': 'Bei Pinterest pinnen',
            'en': 'Pin it on Pinterest',
            'es': 'Compartir en Pinterest',
            'fr': 'Partager sur Pinterest',
            'it': 'Condividi su Pinterest',
            'da': 'Del på Pinterest',
            'nl': 'Delen op Pinterest'
        },
        shareUrl: 'https://www.pinterest.com/pin/create/button/?url=' + url + shariff.getReferrerTrack() + '&media=' + shariff.getMedia() + '&description=' + encodeURIComponent(title)
    };
};
