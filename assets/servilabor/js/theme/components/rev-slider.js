import './../plugin/rs-plugin/css/settings.css'

import $ from "jquery";

// Import green sock
import {_gsScope} from  'gsap';

// Propaginate it to Revolution Slider
window.punchgs = window.GreenSockGlobals  = _gsScope.GreenSockGlobals;

// Make jQuery be visible globally for Revolution Slider
global.jQuery = $;

// Activate slider on document loaded as usual
var revapi;
$(document).ready(function () {
    revapi = jQuery('#revSlider').show().revolution(
        {
            delay:9000,
            fullScreenAutoWidth:"on",
            hideThumbs:10,
            navigationStyle:"preview4",
            fullWidth:"on",
            fullScreen:"on",
            fullScreenOffsetContainer: 0,
        });
    var api = revapi.on('revolution.slide.onloaded', function() {
        jQuery('.red-soluciones').closest('.tp-parallax-wrap').addClass('red-soluciones-layer');
    });
});