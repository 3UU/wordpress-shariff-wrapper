'use strict';

module.exports = function(shariff) {
    return {
        popup: false,
	mobileonly: false,
	blank: false,
        shareText: {
            'de': 'drucken',
            'en': 'print',
            'fr': 'imprimer',
            'es': 'imprimir',
            'it': 'imprimere',
            'da': 'dat trykke',
            'nl': 'drukken'
        },
        name: 'printer',
        faName: 's3uu-print',
        title: {
            'de': 'drucken',
            'en': 'print',
            'fr': 'imprimer',
            'es': 'imprimir',
            'it': 'imprimere',
            'da': 'dat trykke',
            'nl': 'drukken'
        },

        shareUrl: 'javascript:window.print()'
    };
};
