// add to avoid warnings of jshint on the implementation of WP own jQuery
/*global jQuery:false */
'use strict';

// do not needed on WP
//var $ = require('jquery');
var $jq3uu = jQuery.noConflict();
var window = require('browserify-window');

var _Shariff = function(element, options) {
    var self = this;

    // the DOM element that will contain the buttons
    this.element = element;

    this.options = $jq3uu.extend({}, this.defaults, options, $jq3uu(element).data());

    // available services. /!\ Browserify can't require dynamically by now.
    var availableServices = [
        require('./services/facebook'),
        require('./services/googleplus'),
        require('./services/twitter'),
        require('./services/whatsapp'),
        require('./services/mail'),
        require('./services/mailto'),
        require('./services/info'),
        require('./services/linkedin'),
	require('./services/xing'),
        require('./services/pinterest'),
	require('./services/reddit'),
	require('./services/stumbleupon'),
        require('./services/printer'),
        require('./services/flattr')
    ];

    // filter available services to those that are enabled and initialize them
    this.services = $jq3uu.map(this.options.services, function(serviceName) {
        var service;
        availableServices.forEach(function(availableService) {
            availableService = availableService(self);
            if (availableService.name === serviceName) {
                service = availableService;
                return null;
            }
        });
        return service;
    });

    this._addButtonList();

    if (this.options.backendUrl !== null) {
        this.getShares().then( $jq3uu.proxy( this._updateCounts, this ) );
    }

};

_Shariff.prototype = {

    // Defaults may be over either by passing "options" to constructor method
    // or by setting data attributes.
    defaults: {
        theme      : 'color',

        // URL to backend that requests social counts. null means "disabled"
        backendUrl : null,

        // Link to the "about" page
        infoUrl: 'http://ct.de/-2467514',

        // localisation: "de" or "en"
        lang: 'de',

        // horizontal/vertical
        orientation: 'horizontal',


        // a string to suffix current URL
        referrerTrack: null,

        // services to be enabled in the following order
        services   : ['twitter', 'facebook', 'googleplus', 'info'],

        title: function() {
            return $jq3uu('title').text();
        },

        twitterVia: null,

        // build URI from rel="canonical" or document.location
        url: function() {
            var url = global.document.location.href;
            var canonical = $jq3uu('link[rel=canonical]').attr('href') || this.getMeta('og:url') || '';

            if (canonical.length > 0) {
                if (canonical.indexOf('http') < 0) {
                    canonical = global.document.location.protocol + '//' + global.document.location.host + canonical;
                }
                url = canonical;
            }

            return url;
        }
    },

    $socialshareElement: function() {
        return $jq3uu(this.element);
    },

    getLocalized: function(data, key) {
        if (typeof data[key] === 'object') {
            return data[key][this.options.lang];
        } else if (typeof data[key] === 'string') {
            return data[key];
        }
        return undefined;
    },

    // returns content of <meta name="" content=""> tags or '' if empty/non existant
    getMeta: function(name) {
        var metaContent = $jq3uu('meta[name="' + name + '"],[property="' + name + '"]').attr('content');
        return metaContent || '';
    },

    getInfoUrl: function() {
        return this.options.infoUrl;
    },
	
	getImageUrl: function() {
            // look if media is set
            if (this.options.media === undefined ) {
            // look if image is also not set
            if (this.options.image === undefined ) {
            // return the URL for Pinterest
            return encodeURIComponent(this.getURL());
            }else{return this.options.image; }
          } else { return this.options.media; }
	},

    getURL: function() {
        var url = this.options.url;
        return ( typeof url === 'function' ) ? $jq3uu.proxy(url, this)() : url;
    },

    getService: function() {
        var service = this.options.service;
        return ( typeof service === 'function' ) ? $jq3uu.proxy(service, this)() : service;
    },

    getTTL: function() {
        var ttl = this.options.ttl;
        return ( typeof ttl === 'function' ) ? $jq3uu.proxy(ttl, this)() : ttl;
    },
	
    getTemp: function() {
        var temp = this.options.temp;
        return ( typeof temp === 'function' ) ? $jq3uu.proxy(temp, this)() : temp;
    },

    getTitle: function() {
        return this.options.title;
    },
	
    getReferrerTrack: function() {
        return this.options.referrerTrack || '';
    },

    // returns shareCounts of document
    getShares: function() {
        return $jq3uu.getJSON(this.options.backendUrl + '?url=' + encodeURIComponent(this.getURL()) + '&temp=' + encodeURIComponent(this.getTemp()) + '&ttl=' + encodeURIComponent(this.getTTL()) + '&service=' + encodeURIComponent(this.getService()));
    },

    // add value of shares for each service
    _updateCounts: function(data) {
        var self = this;
        $jq3uu.each(data, function(key, value) {
            if(value >= 1000) {
                value = Math.round(value / 1000) + 'k';
            }
            $jq3uu(self.element).find('.' + key + ' a').append('<span class="share_count">' + value);
        });
    },

    // add html for button-container
    _addButtonList: function() {
        var self = this;

        var $socialshareElement = this.$socialshareElement();

        var themeClass = 'theme-' + this.options.theme;
        var orientationClass = 'orientation-' + this.options.orientation;

        var $buttonList = $jq3uu('<ul>').addClass(themeClass).addClass(orientationClass);
		
        // add html for service-links
        this.services.forEach(function(service) {
			if (!service.mobileonly || (typeof window.orientation !== 'undefined') || (typeof(window.document.ontouchstart) === 'object')) {
          	  	var $li = $jq3uu('<li class="shariff-button">').addClass(service.name);
         		var $shareText = '<span class="share_text">' + self.getLocalized(service, 'shareText');

          	  	var $shareLink = $jq3uu('<a>')
          			.attr('href', service.shareUrl)
 					.append($shareText);

         		if (typeof service.faName !== 'undefined') {
         		   $shareLink.prepend('<span class="s3uu ' +  service.faName + '">');
            	}

				if (service.popup) {
                	$shareLink.attr('rel', 'popup');
				} else {
                	$shareLink.attr('target', '_blank');
            	}
				$shareLink.attr('title', self.getLocalized(service, 'title'));

                                $li.append($shareLink);

                                $buttonList.append($li);
			}
        });

        // event delegation
        $buttonList.on('click', '[rel="popup"]', function(e) {
            e.preventDefault();

            var url = $jq3uu(this).attr('href');
            var windowName = $jq3uu(this).attr('title');
            var windowSizeX = '1000';
            var windowSizeY = '500';
            var windowSize = 'width=' + windowSizeX + ',height=' + windowSizeY;

            global.window.open(url, windowName, windowSize);

        });

        $socialshareElement.append($buttonList);
    }
};

module.exports = _Shariff;

// initialize .shariff elements
$jq3uu('.shariff').each(function() {
    if (!this.hasOwnProperty('shariff')) {
        this.shariff = new _Shariff(this);
    }
});
