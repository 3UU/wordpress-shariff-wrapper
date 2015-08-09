'use strict';

module.exports = function(shariff) {

    var bitcoinaddress = '';
    var bitcoinurl = '';

    if (shariff.options.bitcoinaddress !== null) {
        bitcoinaddress = shariff.options.bitcoinaddress;
    }

    if (shariff.options.bitcoinurl !== null) {
        bitcoinurl = shariff.options.bitcoinurl;
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
        name: 'bitcoin',
        faName: 's3uu-bitcoin',
        title: {
            'de': 'Spenden mit Bitcoin',
            'en': 'Donate with Bitcoin',
            'fr': 'Faire un don via Bitcoin',
            'es': 'Donar via Bitcoin'
        },
        shareUrl: bitcoinurl + 'bitcoin.php?bitcoinaddress=' + bitcoinaddress
    };
};