'use strict';

module.exports = function(shariff) {

    var url = encodeURIComponent(shariff.getURL());
    var title = shariff.getMeta('DC.title');
    var creator = shariff.getMeta('DC.creator');
    var flattruser = '';
    var lang = '';

    if (title.length > 0 && creator.length > 0) {
        title += ' - ' + creator;
    } 
    else {
        title = shariff.getTitle();
    }

    if (shariff.options.flattruser !== null) {
        flattruser = shariff.options.flattruser;
    }

    if (shariff.options.lang === 'de' || shariff.options.lang === 'fr' || shariff.options.lang === 'es') { 
        lang = shariff.options.lang + '_' + shariff.options.lang.toUpperCase(); 
    }
    else {
        lang = 'en_US'; 
    }

    return {
        popup: true,
		noblank: false,
		mobileonly: false,
        shareText: 'flattr',
        name: 'flattr',
        faName: 's3uu-flattr',
        title: {
            'de': 'Beitrag flattrn!',
            'en': 'Flattr this!',
            'fr': 'Flattré!',
            'es': 'Flattr!'
        },
        shareUrl: 'https://flattr.com/submit/auto?url=' + url + shariff.getReferrerTrack() + '&title='+ encodeURIComponent(title) + '&description=&language=' + lang + '&tags=&category=text&user_id=' + flattruser
    };
};