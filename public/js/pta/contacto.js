
/*
Map Settings

	Find the Latitude and Longitude of your address:
		- http://universimmedia.pagesperso-orange.fr/geo/loc.htm
		- http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/

*/
var latitude  = oficina.latitude,
    longitude = oficina.longitude,
    title     = "Agencia " + oficina.ciudad,
    address   = oficina.direccion;


// Map Markers
var mapMarkers = [{
	latitude: latitude,
	longitude: longitude,
	html: "<strong>"+title+"</strong><br>"+address+"<br><br><a href='#' onclick='mapCenterAt({latitude: latitude, longitude: longitude, zoom: 19}, event)'>[+] Acercar</a>",
	icon: {
		image: pinImg,
		iconsize: [26, 46],
		iconanchor: [12, 46]
	}
}];



// Map Extended Settings
var mapSettings = {
	controls: {
		draggable: (($.browser.mobile) ? false : true),
		panControl: true,
		zoomControl: true,
		mapTypeControl: true,
		scaleControl: true,
		streetViewControl: true,
		overviewMapControl: true
	},
	scrollwheel: false,
	markers: mapMarkers,
	latitude: latitude,
	longitude: longitude,
	zoom: 16
};

var map = $("#googlemaps").gMap(mapSettings),
	mapRef = $("#googlemaps").data('gMap.reference');

// Map Center At
var mapCenterAt = function(options, e) {
	e.preventDefault();
	$("#googlemaps").gMap("centerAt", options);
}

// Create an array of styles.
var mapColor = "#0088cc";

var styles = [
    {
        stylers: [{
            hue: mapColor
        }]
    }, {
	    featureType: "road",
        elementType: "geometry",
        stylers: [{
	        lightness: 0
            }, {
	            visibility: "simplified"
            }]
    }, {
        featureType: "road",
        elementType: "labels",
        stylers: [{
	        visibility: "on"
        }]
    }
];

var styledMap = new google.maps.StyledMapType(styles, {
name: "Styled Map"
});

mapRef.mapTypes.set('map_style', styledMap);
mapRef.setMapTypeId('map_style');


/*
 Name: 			View - Contact
 Written by: 	Okler Themes - (http://www.okler.net)
 Version: 		4.6.0
 */

(function($) {

    'use strict';

    /*
     Contact Form: Basic
     */
    
    /*
     Contact Form: Advanced
     */
    $('#contactFormAdvanced').validate({
        onkeyup: false,
        onclick: false,
        onfocusout: false,
        rules: {
            'captcha': {
                captcha: true
            },
            'checkboxes[]': {
                required: true
            },
            'radios': {
                required: true
            }
        },
        errorPlacement: function(error, element) {
            if (element.attr('type') == 'radio' || element.attr('type') == 'checkbox') {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

}).apply(this, [jQuery]);