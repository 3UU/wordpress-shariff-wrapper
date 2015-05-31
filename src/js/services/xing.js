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
