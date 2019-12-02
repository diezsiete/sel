import './../css/servilabor.scss'

//TODO revisar si se necesita en todas las paginas
import 'animate.css'

import $ from 'jquery'
import 'bootstrap'; // add functions to jQuery

import './theme/hs.core'
import './theme/components/hs.header'
import './theme/helpers/hs.hamburgers'

import './../vendor/hs-megamenu/src/hs.megamenu.css'
import './../vendor/hs-megamenu/src/hs.megamenu.js'
import './../vendor/dzsparallaxer/dzsparallaxer.scss';
import './../vendor/dzsparallaxer/dzsparallaxer.js';

$(document).on('ready', function () {
    // initialization of carousel
    //$.HSCore.components.HSCarousel.init('.js-carousel');

    // initialization of tabs
    //$.HSCore.components.HSTabs.init('[role="tablist"]');

    // initialization of popups
    //$.HSCore.components.HSPopup.init('.js-fancybox');

    // initialization of go to
    //$.HSCore.components.HSGoTo.init('.js-go-to');

    // initialization of HSDropdown component
    //$.HSCore.components.HSDropdown.init($('[data-dropdown-target]'));


});

$(window).on('load', function () {
    // initialization of header
    $.HSCore.components.HSHeader.init($('#js-header'));
    $.HSCore.helpers.HSHamburgers.init('.hamburger');

    // initialization of HSMegaMenu component
    $('.js-mega-menu').HSMegaMenu({
        event: 'hover',
        pageContainer: $('.container'),
        breakpoint: 991
    });
});

$(window).on('resize', function () {
    setTimeout(function () {
        $.HSCore.components.HSTabs.init('[role="tablist"]');
    }, 200);
});
