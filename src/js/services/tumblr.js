'use strict';

module.exports = function(shariff) {

	var url = encodeURIComponent(shariff.getURL());

	var title = shariff.getMeta('DC.title');
	var creator = shariff.getMeta('DC.creator');
	
	if (title.length > 0 && creator.length > 0) {
		title += ' - ' + creator;
	} 
	else {
		title = shariff.getTitle();
	}

	var url2 = shariff.getURL();
	var urlParts = url2.replace('http://','').replace('https://','').replace('www.','').split(/[/?#]/);
	var domain = urlParts[0];

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
		name: 'tumblr',
		faName: 's3uu-tumblr',
		title: {
            'bg': 'Сподели във Tumblr',
            'da': 'Del på Tumblr',
            'de': 'Bei Tumblr teilen',
            'en': 'Share on Tumblr',
            'es': 'Compartir en Tumblr',
            'fi': 'Jaa Tumblrissa',
            'fr': 'Partager sur Tumblr',
            'hr': 'Podijelite na Tumblru',
            'hu': 'Megosztás Tumblron',
            'it': 'Condividi su Tumblr',
            'ja': 'フェイスブック上で共有',
            'ko': '페이스북에서 공유하기',
            'nl': 'Delen op Tumblr',
            'no': 'Del på Tumblr',
            'pl': 'Udostępnij na Tumblru',
            'pt': 'Compartilhar no Tumblr',
            'ro': 'Partajează pe Tumblr',
            'ru': 'Поделиться на Tumblr',
            'sk': 'Zdieľať na Tumblru',
            'sl': 'Deli na Tumblru',
            'sr': 'Podeli na Tumblr-u',
            'sv': 'Dela på Tumblr',
            'tr': 'Tumblr\'ta paylaş',
            'zh': '在Tumblr上分享',
		},
		shareUrl: 'https://www.tumblr.com/widgets/share/tool?posttype=link&canonicalUrl=' + url + shariff.getReferrerTrack() + '&tags=' + domain
	};
};
