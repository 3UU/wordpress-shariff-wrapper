'use strict';

module.exports = function(shariff) {
    var url = encodeURIComponent(shariff.getURL());
    return {
        popup: true,
        mobileonly: false,
        shareText: {
            'de': 'teilen',
            'en': 'share',
            'es': 'compartir',
            'fr': 'partager',
            'it': 'condividi',
            'da': 'del',
            'nl': 'delen'
        },
        name: 'reddit',
        faName: 's3uu-reddit',
        title: {
            'de': 'Bei Reddit teilen',
            'en': 'Share on Reddit',
            'es': 'Compartir en Reddit',
            'fr': 'Partager sur Reddit',
            'it': 'Condividi su Reddit',
            'da': 'Del på Reddit',
            'nl': 'Delen op Reddit'
        },
        shareUrl: 'https://www.reddit.com/submit?url=' + url + shariff.getReferrerTrack()
    };
};
