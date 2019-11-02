import '../css/nosotros.scss';
import './theme/owl-carousel';

import $ from 'jquery';

import 'owl.carousel';

import './theme/theme';
import './theme/animate';
import './theme/animate-init';
import './theme/animated-headlines';


$(function() {
    $('[data-plugin-carousel]:not(.manual), .owl-carousel:not(.manual)').each(function() {
        var $this = $(this),
            opts;

        var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
        if (pluginOptions)
            opts = pluginOptions;

        $this.themePluginCarousel(opts);
    });
});
