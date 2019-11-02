const loadGoogleMapsApi = require('load-google-maps-api');

class Map {

    static loadGoogleMapsApi() {
        return loadGoogleMapsApi({ key: process.env.GOOGLEMAPS_KEY });
    }
    static createMap(googleMaps, mapElement, position, zoom) {
        /*return new googleMaps.Map(mapElement, {
            center: { lat: 45.520562, lng: -122.677438 },
            zoom: 14
        });*/

        const map = new googleMaps.Map(mapElement, {
            center: position,
            zoom: zoom
        });

        const marker = new googleMaps.Marker({position: position, map: map});
        return map;
    }
}
export { Map };