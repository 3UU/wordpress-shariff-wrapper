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
        name: 'facebook',
        faName: 's3uu-facebook',
        title: {
            'de': 'Bei Facebook teilen',
            'en': 'Share on Facebook',
            'es': 'Compartir en Facebook',
            'fr': 'Partager sur Facebook',
            'it': 'Condividi su Facebook',
            'da': 'Del på Facebook',
            'nl': 'Delen op Facebook'
        },
        shareUrl: 'https://www.facebook.com/sharer/sharer.php?u=' + url + shariff.getReferrerTrack()
    };
};
