
import '../css/index.scss';

import './component/index-hero'
import $ from 'jquery';


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



