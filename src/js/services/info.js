'use strict';

module.exports = function(shariff) {
    return {
        popup: false,
        blank: true,
        mobileonly: false,
        shareText: 'Info',
        name: 'info',
        faName: 's3uu-info',
        title: {
            'de': 'weitere Informationen',
            'en': 'more information',
            'es': 'más informaciones',
            'fr': 'plus d\'informations',
            'it': 'maggiori informazioni',
            'da': 'flere oplysninger',
            'nl': 'verdere informatie'
        },
        shareUrl: shariff.getInfoUrl()
    };
};
