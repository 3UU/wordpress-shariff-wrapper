'use strict';

module.exports = function(shariff) {

    var paypalbuttonid = '';

    if (shariff.options.paypalbuttonid !== null) {
        paypalbuttonid = shariff.options.paypalbuttonid;
    }

    return {
        popup: true,
		noblank: false,
		mobileonly: false,
        shareText: {
            'de': 'spenden',
            'en': 'donate',
            'fr': 'faire un don',
            'es': 'donar'
        },
        name: 'paypal',
        faName: 's3uu-paypal',
        title: {
            'de': 'Spenden mit PayPal',
            'en': 'Donate with PayPal',
            'fr': 'Faire un don via PayPal',
            'es': 'Donar via PayPal'
        },
        shareUrl: 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=' + paypalbuttonid
    };
};