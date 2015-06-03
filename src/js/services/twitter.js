/* globals window */

'use strict';

var url = require('url');
var $ = require('jquery');

// abbreviate at last blank before length and add "\u2026" (horizontal ellipsis)
var abbreviateText = function(text, length) {
    var abbreviated = $('<div/>').html(text).text();
    if (abbreviated.length <= length) {
        return text;
    }

    var lastWhitespaceIndex = abbreviated.substring(0, length - 1).lastIndexOf(' ');
    abbreviated = abbreviated.substring(0, lastWhitespaceIndex) + '\u2026';

    return abbreviated;
};

module.exports = function(shariff) {
    var shareUrl;
    // Fix für Seiten auf denen die Twitter-Skripte geladen sind -> verwende veralteten Share-Link
    if (window.twttr) { 
        shareUrl = url.parse('https://twitter.com/share', true);
    }
    else {
        shareUrl = url.parse('https://twitter.com/intent/tweet', true);
    }

    var title = shariff.getMeta('DC.title');
    var creator = shariff.getMeta('DC.creator');

    if (title.length > 0 && creator.length > 0) {
        title += ' - ' + creator;
    } else {
        title = shariff.getTitle();
    }

    // 120 is the max character count left after twitters automatic url shortening with t.co
    shareUrl.query.text = abbreviateText(title, 120);
    shareUrl.query.url = shariff.getURL();
    if (shariff.options.twitterVia !== null) {
        shareUrl.query.via = shariff.options.twitterVia;
    }
    delete shareUrl.search;

    return {
        popup: true,
        mobileonly: false,
        shareText: {
            'de': 'twittern',
            'en': 'tweet'
        },
        name: 'twitter',
        faName: 's3uu-twitter',
        title: {
            'bg': 'Сподели в Twitter',
            'da': 'Del på Twitter',
            'de': 'Bei Twitter teilen',
            'en': 'Share on Twitter',
            'es': 'Compartir en Twitter',
            'fi': 'Jaa Twitterissä',
            'fr': 'Partager sur Twitter',
            'hr': 'Podijelite na Twitteru',
            'hu': 'Megosztás Twitteren',
            'it': 'Condividi su Twitter',
            'ja': 'ツイッター上で共有',
            'ko': '트위터에서 공유하기',
            'nl': 'Delen op Twitter',
            'no': 'Del på Twitter',
            'pl': 'Udostępnij na Twitterze',
            'pt': 'Compartilhar no Twitter',
            'ro': 'Partajează pe Twitter',
            'ru': 'Поделиться на Twitter',
            'sk': 'Zdieľať na Twitteri',
            'sl': 'Deli na Twitterju',
            'sr': 'Podeli na Twitter-u',
            'sv': 'Dela på Twitter',
            'tr': 'Twitter\'da paylaş',
            'zh': '在Twitter上分享'
        },
        // shareUrl: 'https://twitter.com/intent/tweet?text='+ shariff.getShareText() + '&url=' + url
        shareUrl: url.format(shareUrl) + shariff.getReferrerTrack()
    };
};
