'use strict';

module.exports = function(shariff) {

    var paypalmeid = '';

    if (shariff.options.paypalmeid !== null) {
        paypalmeid = shariff.options.paypalmeid;
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
        name: 'paypalme',
        faName: 's3uu-paypal',
        title: {
            'de': 'Spenden mit PayPal',
            'en': 'Donate with PayPal',
            'fr': 'Faire un don via PayPal',
            'es': 'Donar via PayPal'
        },
        shareUrl: 'https://www.paypal.me/' + paypalmeid
    };
};
