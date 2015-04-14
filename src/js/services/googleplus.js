'use strict';

module.exports = function(shariff) {
    var url = encodeURIComponent(shariff.getURL());
    return {
        popup: true,
        mobileonly: false,
        shareText: '+1',
        name: 'googleplus',
        faName: 's3uu-google-plus',
        title: {
            'de': 'Bei Google+ teilen',
            'en': 'Share on Google+',
            'es': 'Compartir en Google+',
            'fr': 'Partager sur Goolge+',
            'it': 'Condividi su Google+',
            'da': 'Del på Google+',
            'nl': 'Delen op Google+'
        },
        shareUrl: 'https://plus.google.com/share?url=' + url + shariff.getReferrerTrack()
    };
};
