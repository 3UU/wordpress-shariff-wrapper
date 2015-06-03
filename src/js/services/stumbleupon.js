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
