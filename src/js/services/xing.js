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
        name: 'xing',
        faName: 's3uu-xing',
        title: {
            'de': 'Bei XING teilen',
            'en': 'Share on XING',
            'es': 'Compartir en XING',
            'fr': 'Partager sur XING',
            'it': 'Condividi su XING',
            'da': 'Del på XING',
            'nl': 'Delen op XING'
        },
        shareUrl: 'https://www.xing.com/social_plugins/share?url=' + url + shariff.getReferrerTrack()
    };
};
