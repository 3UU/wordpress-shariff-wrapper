'use strict';

module.exports = function(shariff) {

	var patreonid = '';

    if (shariff.options.patreonid !== null) {
        patreonid = shariff.options.patreonid;
    }

	return {
		popup: true,
		mobileonly: false,
		shareText: 'patreon',
		name: 'patreon',
		faName: 's3uu-patreon',
		title: {
            'de': 'Werde ein patron!',
            'en': 'Become a patron!',
            'es': 'Conviértete en un patron!',
            'fr': 'Devenez un patron!',
		},
		shareUrl: 'https://www.patreon.com/' + patreonid
	};
};
