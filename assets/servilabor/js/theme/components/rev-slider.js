require('./../../../vendor/rs-plugin/css/settings.css');

const $  = require("jquery");

// Import green sock
const { _gsScope } = require('gsap');

// Propaginate it to Revolution Slider
window.punchgs = window.GreenSockGlobals  = _gsScope.GreenSockGlobals;

// Make jQuery be visible globally for Revolution Slider
global.jQuery = $;

// Activate slider on document loaded as usual
module.exports = options => {
    let revapi;
    $(document).ready(function () {
        revapi = jQuery('#revSlider').show().revolution(options);
        revapi.on('revolution.slide.onloaded', function () {
            jQuery('.red-soluciones').closest('.tp-parallax-wrap').addClass('red-soluciones-layer');
        });
    })
};