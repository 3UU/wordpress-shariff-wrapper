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
        name: 'stumbleupon',
        faName: 's3uu-stumbleupon',
        title: {
            'de': 'Bei StumbleUpon teilen',
            'en': 'Share on StumbleUpon',
            'es': 'Compartir en StumbleUpon',
            'fr': 'Partager sur StumbleUpon',
            'it': 'Condividi su StumbleUpon',
            'da': 'Del på StumbleUpon',
            'nl': 'Delen op StumbleUpon'
        },
        shareUrl: 'https://www.stumbleupon.com/submit?url=' + url + shariff.getReferrerTrack()
    };
};
