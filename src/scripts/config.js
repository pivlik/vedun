/* jshint ignore:start */
// Ключ для карт
var googleMapKey = 'AIzaSyB1k18NIGKRnRJxpUY5eGadjW286_-CkFQ'; // *.kelnik.pro

require.config({
    baseUrl: '/scripts/lib',

    paths: {
        app       : '../app',
        tpl       : '../tpl',
        apartments: '../apartments',

        cookie          : 'js-cookie/src/js.cookie',
        jquery          : 'jquery/dist/jquery.min',
        fastclick       : 'fastclick/lib/fastclick',
        modernizr       : 'modernizr/modernizr',
        handlebars      : 'handlebars/handlebars.runtime.min',
        polyfiller      : 'webshim/js-webshim/dev/polyfiller',
        fotorama        : 'fotorama/fotorama',
        'slick-carousel': 'slick-carousel/slick/slick.min',
        'masked-inputs' : 'jquery.maskedinput/dist/jquery.maskedinput.min',
        'magnific-popup': 'magnific-popup/dist/jquery.magnific-popup.min',
        select          : 'jquery-selectric/public/jquery.selectric.min',
        rangeSlider     : 'ion.rangeSlider/js/ion.rangeSlider.min',

        'google-maps'  : 'async!https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=geometry&key=' + googleMapKey,
        infobox        : 'google-infobox/google-infobox',
        async          : 'requirejs-plugins/src/async',
        markerClusterer: 'js-marker-clusterer/src/markerclusterer_compiled',

        videojs     : 'videojs/dist/video-js/video',
        sammy       : 'sammy/lib/min/sammy-latest.min',
        scaleraphael: 'scaleraphael/scaleraphael',
        eve         : 'eve-adobe/eve.min',
        raphael     : 'raphael/raphael-min',
        autoNumeric : 'autoNumeric/autoNumeric-min'
    },
    shim: {
        fastclick: {
            exports: 'FastClick'
        },
        modernizr: {
            exports: 'Modernizr'
        },
        handlebars: {
            exports: 'Handlebars'
        },
        'magnific-popup': {
            exports: '$.magnificPopup',
            deps   : ['jquery']
        },
        infobox: {
            exports: 'InfoBox',
            deps   : ['app/google-map']
        },
        markerClusterer: {
            exports: 'MarkerClusterer'
        },
        rangeSlider: {
            deps: ['jquery']
        },
        autoNumeric: {
            exports: '$.fn.autoNumeric',
            deps   : ['jquery']
        },
        videojs: {
            exports: 'videojs'
        },
        scaleraphael: {
            exports: 'ScaleRaphael',
            deps   : ['raphael']
        },
        eve: {
            exports: 'eve'
        },
        raphael: {
            exports: 'Raphael',
            deps   : ['eve']
        }
    },
    /* Launch app.js after config */
    deps: ['app']
});
