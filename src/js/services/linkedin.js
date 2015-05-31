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
            'de': 'mitteilen',
            'en': 'share',
            'es': 'compartir',
            'fi': 'Jaa',
            'fr': 'partager',
            'hr': 'podijelite',
            'hu': 'megosztás',
            'it': 'condividi',
            'ja': 'シェア',
            'ko': '공유하기',
            'nl': 'delen',
            'no': 'del',
            'pl': 'udostępnij',
            'pt': 'compartilhar',
            'ro': 'distribuiți',
            'ru': 'поделиться',
            'sk': 'zdieľať',
            'sl': 'deli',
            'sr': 'podeli',
            'sv': 'dela',
            'tr': 'paylaş',
            'zh': '分享'
        },
        name: 'linkedin',
        faName: 's3uu-linkedin',
        title: {
            'bg': 'Сподели в LinkedIn',
            'da': 'Del på LinkedIn',
            'de': 'Bei LinkedIn teilen',
            'en': 'Share on LinkedIn',
            'es': 'Compartir en LinkedIn',
            'fi': 'Jaa LinkedInissä',
            'fr': 'Partager sur LinkedIn',
            'hr': 'Podijelite na LinkedIn',
            'hu': 'Megosztás LinkedInen',
            'it': 'Condividi su LinkedIn',
            'ja': 'LinkedIn上で共有',
            'ko': 'LinkedIn에서 공유하기',
            'nl': 'Delen op LinkedIn',
            'no': 'Del på LinkedIn',
            'pl': 'Udostępnij przez LinkedIn',
            'pt': 'Compartilhar no LinkedIn',
            'ro': 'Partajează pe LinkedIn',
            'ru': 'Поделиться на LinkedIn',
            'sk': 'Zdieľať na LinkedIn',
            'sl': 'Deli na LinkedIn',
            'sr': 'Podeli na LinkedIn-u',
            'sv': 'Dela på LinkedIn',
            'tr': 'LinkedIn\'ta paylaş',
            'zh': '在LinkedIn上分享'
        },
        shareUrl: 'https://www.linkedin.com/shareArticle?mini=true&url=' + url + shariff.getReferrerTrack() + '&title=' + encodeURIComponent(title)
    };
};
