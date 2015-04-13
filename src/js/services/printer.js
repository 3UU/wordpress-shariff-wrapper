'use strict';

module.exports = function(shariff) {
    return {
        popup: false,
		noblank: true,
		mobileonly: false,
        shareText: {
            'de': 'drucken',
            'en': 'print',
            'fr': 'imprimer',
            'es': 'imprimir'
        },
        name: 'printer',
        faName: 's3uu-print',
        title: {
            'de': 'Drucken',
            'en': 'Printer',
            'fr': 'Imprimeur',
            'es': 'Impresora'
        },

        shareUrl: 'javascript:window.print()'
    };
};
