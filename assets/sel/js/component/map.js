const loadGoogleMapsApi = require('load-google-maps-api');

class Map {

    static loadGoogleMapsApi() {
        return loadGoogleMapsApi({ key: process.env.GOOGLEMAPS_KEY });
    }
    static createMap(googleMaps, mapElement, position, zoom, pinImg = null) {
        const map = new googleMaps.Map(mapElement, {
            center: position,
            zoom: zoom
        });

        const markerConfig = {position: position, map: map};
        if(pinImg) {
            markerConfig.icon = pinImg;
        }
        new googleMaps.Marker(markerConfig);

        return map;
    }
}


module.exports = elementId => {
    document.addEventListener("DOMContentLoaded", function() {
        let mapElement = document.getElementById(elementId);

        const latitude = Number.parseFloat(mapElement.dataset.latitude);
        const longitude =  Number.parseFloat(mapElement.dataset.longitude);
        const zoom = Number.parseInt(mapElement.dataset.zoom || 16);
        const title = mapElement.dataset.title;
        const address = mapElement.dataset.address;
        const pinImg = mapElement.dataset.pinImg;

        Map.loadGoogleMapsApi().then(function(googleMaps) {
            Map.createMap(googleMaps, mapElement, {lat: latitude, lng: longitude}, zoom, pinImg);
        });

    });
};