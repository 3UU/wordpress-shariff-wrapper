'use strict';

module.exports = function(shariff) {

    var rssfeed = '';

    if (shariff.options.rssfeed !== null) {
        rssfeed = shariff.options.rssfeed;
    }

    return {
        popup: true,
		noblank: false,
		mobileonly: false,
        shareText: {
            'de': 'rss-feed',
            'en': 'rss feed',
        },
        name: 'rss',
        faName: 's3uu-rss',
        title: {
            'de': 'rss-feed',
            'en': 'rss feed',
        },
        shareUrl: rssfeed
    };
};