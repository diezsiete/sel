// import $ from 'jquery';

import './theme/theme';
import './theme/animate';
import './theme/animate-init';

import { Map } from './map';

document.addEventListener("DOMContentLoaded", function() {
    let mapElement = document.getElementById('googlemaps');

    const latitude = Number.parseFloat(mapElement.dataset.latitude);
    const longitude =  Number.parseFloat(mapElement.dataset.longitude);
    const title = mapElement.dataset.title;
    const address = mapElement.dataset.address;
    const pinImg = mapElement.dataset.pinImg;

    console.log(latitude);
    console.log(longitude);
    const mapSettings = {
        /*controls: {
            //draggable: ((!$.browser.mobile)),
            panControl: true,
            zoomControl: true,
            mapTypeControl: true,
            scaleControl: true,
            streetViewControl: true,
            overviewMapControl: true
        },
        scrollwheel: false,
        markers: [{
            lat: latitude,
            lng: longitude,
            html: "<strong>"+title+"</strong><br>"+address+"<br><br><a href='#' onclick='mapCenterAt({lat: latitude, lng: longitude, zoom: 19}, event)'>[+] Acercar</a>",
            icon: {
                image: pinImg,
                iconsize: [26, 46],
                iconanchor: [12, 46]
            }
        }],*/
        center: { lat: latitude, lng: longitude },
        zoom: 16
    };


    Map.loadGoogleMapsApi().then(function(googleMaps) {
        Map.createMap(googleMaps, mapElement, {lat: latitude, lng: longitude}, 17);
    });

});

/*
Map Settings

	Find the Latitude and Longitude of your address:
		- http://universimmedia.pagesperso-orange.fr/geo/loc.htm
		- http://www.findlatitudeandlongitude.com/find-address-from-latitude-and-longitude/


var latitude  = oficina.latitude,
    longitude = oficina.longitude,
    title     = "Agencia " + oficina.ciudad,
    address   = oficina.direccion;

var map = $("#googlemaps").gMap(mapSettings),
    mapRef = $("#googlemaps").data('gMap.reference');

// Map Center At
var mapCenterAt = function(options, e) {
    e.preventDefault();
    $("#googlemaps").gMap("centerAt", options);
};

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
mapRef.setMapTypeId('map_style');*/


