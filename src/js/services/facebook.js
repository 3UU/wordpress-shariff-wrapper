'use strict';

module.exports = function(shariff) {
    var url = encodeURIComponent(shariff.getURL());
    return {
        popup: true,
	noblank: false,
	mobileonly: false,
        shareText: {
            'de': 'teilen',
            'fr': 'partager',
            'en': 'share',
            'es': 'compartir'
        },
        name: 'facebook',
        faName: 's3uu-facebook',
        title: {
            'de': 'Bei Facebook teilen',
            'en': 'Share on Facebook',
            'fr': 'Partager sur Facebook',
            'es': 'Compartir en Facebook'
        },
        shareUrl: 'https://www.facebook.com/sharer/sharer.php?u=' + url + shariff.getReferrerTrack()
    };
};
