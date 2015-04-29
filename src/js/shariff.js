'use strict';

var $ = require('jquery');
var url = require('url');
// needed for mobile mozilla fix
var window = require('browserify-window');

var Shariff = function(element, options) {
    var self = this;

    // the DOM element that will contain the buttons
    this.element = element;

    // Ensure elemnt is empty
    $(element).empty();

    this.options = $.extend({}, this.defaults, options, $(element).data());

    // available services. /!\ Browserify can't require dynamically by now.
    var availableServices = [
        require('./services/facebook'),
        require('./services/googleplus'),
        require('./services/twitter'),
        require('./services/whatsapp'),
        require('./services/mail'),
        require('./services/info'),
        require('./services/mailto'),
        require('./services/linkedin'),
        require('./services/xing'),
        require('./services/pinterest'),
        require('./services/reddit'),
        require('./services/stumbleupon'),
        require('./services/printer'),
        require('./services/flattr'),
    ];

    // filter available services to those that are enabled and initialize them
    this.services = $.map(this.options.services, function(serviceName) {
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
        this.getShares().then( $.proxy( this._updateCounts, this ) );
    }

};

Shariff.prototype = {

    // Defaults may be over either by passing "options" to constructor method
    // or by setting data attributes.
    defaults: {
        theme      : 'default',

        // URL to backend that requests social counts. null means "disabled"
        backendUrl : null,

        // Link to the "about" page
        infoUrl: 'http://ct.de/-2467514',

        // localisation: "de" or "en"
        lang: 'de',

        // horizontal/vertical
        orientation: 'horizontal',

        // big/small
        buttonsize: 'big',

        // a string to suffix current URL
        referrerTrack: null,

        // services to be enabled in the following order
        services   : ['twitter', 'facebook', 'googleplus', 'info'],

        title: function() {
            return $('title').text();
        },

        twitterVia: null,

        // build URI from rel="canonical" or document.location
        url: function() {
            var url = global.document.location.href;
            var canonical = $('link[rel=canonical]').attr('href') || this.getMeta('og:url') || '';

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
        return $(this.element);
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
        var metaContent = $('meta[name="' + name + '"],[property="' + name + '"]').attr('content');
        return metaContent || '';
    },

    getInfoUrl: function() {
        return this.options.infoUrl;
    },

    getURL: function() {
        return this.getOption('url');
    },

    getOption: function(name) {
        var option = this.options[name];
        return (typeof option === 'function') ? $.proxy(option, this)() : option;
    },

    getTitle: function() {
        return this.getOption('title');
    },

    getReferrerTrack: function() {
        return this.options.referrerTrack || '';
    },

    // set a default image for pinterest by using media=""
    getMedia: function() {
		return this.getOption('media');
    },

    // returns shareCounts of document
    getShares: function() {
        var baseUrl = url.parse(this.options.backendUrl, true);
        baseUrl.query.url = this.getURL();
        delete baseUrl.search;
        return $.getJSON(url.format(baseUrl));
    },

    // add value of shares for each service
    _updateCounts: function(data) {
        var self = this;
        $.each(data, function(key, value) {
            if(value >= 1000) {
                value = Math.round(value / 1000) + 'k';
            }
            $(self.element).find('.' + key + ' a').append('<span class="share_count">' + value);
        });
    },

    // add html for button-container
    _addButtonList: function() {
        var self = this;

        var $socialshareElement = this.$socialshareElement();

        var themeClass = 'theme-' + this.options.theme;
        var orientationClass = 'orientation-' + this.options.orientation;
        var serviceCountClass = 'col-' + this.options.services.length;
        var buttonsizeClass = 'buttonsize-' + this.options.buttonsize;

        var $buttonList = $('<ul>').addClass(themeClass).addClass(orientationClass).addClass(serviceCountClass).addClass(buttonsizeClass);

        // add html for service-links
        this.services.forEach(function(service) {
        // adding mobile-only option for whatsapp and fix mobile Mozilla problem by checking for window.document.ontouchstart as object
        if (!service.mobileonly || (typeof window.orientation !== 'undefined') || (typeof(window.document.ontouchstart) === 'object')) {
            var $li = $('<li class="shariff-button">').addClass(service.name);
            var $shareText = '<span class="share_text">' + self.getLocalized(service, 'shareText');

            var $shareLink = $('<a>')
              .attr('href', service.shareUrl)
              .append($shareText);

            if (typeof service.faName !== 'undefined') {
                $shareLink.prepend('<span class="s3uu ' +  service.faName + '">');
            }

            if (service.popup) {
                $shareLink.attr('rel', 'popup');
            } else if (service.blank) {
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

            var url = $(this).attr('href');
            var windowName = '_blank';
            var windowSizeX = '1000'; // was too small for some services
            var windowSizeY = '500';  // was too small for some services
            var windowSize = 'width=' + windowSizeX + ',height=' + windowSizeY;

            global.window.open(url, windowName, windowSize);

        });

        $socialshareElement.append($buttonList);
    }
};

module.exports = Shariff;

// export Shariff class to global (for non-Node users)
global.Shariff = Shariff;

// initialize .shariff elements
$('.shariff').each(function() {
    if (!this.hasOwnProperty('shariff')) {
        this.shariff = new Shariff(this);
    }
});
