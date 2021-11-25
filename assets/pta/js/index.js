import '../css/index.scss';
import '../css/component/_floating-recaudo.scss'

import $ from 'jquery';
// import '../component/index-hero/index-hero'
// import '../component/index-hero-tres/index-hero-tres'
import '../component/index-hero-tres/index-hero-tres2'


import './theme/countTo';
import './theme/theme';
import './theme/counter';

import './theme/animate';
import './theme/animate-init';



// Counter
$(function() {
    $('[data-plugin-counter]:not(.manual), .counters [data-to]').each(function() {
        var $this = $(this),
            opts;

        var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
        if (pluginOptions)
            opts = pluginOptions;

        $this.themePluginCounter(opts);
    });
});



