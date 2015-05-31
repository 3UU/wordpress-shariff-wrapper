'use strict';

module.exports = function(shariff) {
    var url = encodeURIComponent(shariff.getURL());
    return {
        popup: true,
        mobileonly: false,
        shareText: {
            'bg': 'cподеляне',
            'da': 'del',
            'de': 'teilen',
            'en': 'share',
            'es': 'compartir',
            'fi': 'Jaa',
            'fr': 'partager',
            'hr': 'podijelite',
            'hu': 'megosztás',
            'it': 'condividi',
            'ja': '共有',
            'ko': '공유하기',
            'nl': 'delen',
            'no': 'del',
            'pl': 'udostępnij',
            'pt': 'compartilhar',
            'ro': 'partajează',
            'ru': 'поделиться',
            'sk': 'zdieľať',
            'sl': 'deli',
            'sr': 'podeli',
            'sv': 'dela',
            'tr': 'paylaş',
            'zh': '分享',
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
