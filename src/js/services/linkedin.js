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
            'de': 'teilen',
            'en': 'share',
            'es': 'compartir',
            'fr': 'partager',
            'it': 'condividi',
            'da': 'del',
            'nl': 'delen'
        },
        name: 'linkedin',
        faName: 's3uu-linkedin',
        title: {
            'de': 'Bei LinkedIn teilen',
            'en': 'Share on LinkedIn',
            'es': 'Compartir en LinkedIn',
            'fr': 'Partager sur LinkedIn',
            'it': 'Condividi su LinkedIn',
            'da': 'Del på LinkedIn',
            'nl': 'Delen op LinkedIn'
        },
        shareUrl: 'https://www.linkedin.com/shareArticle?mini=true&url=' + url + shariff.getReferrerTrack() + '&title=' + encodeURIComponent(title)
    };
};
