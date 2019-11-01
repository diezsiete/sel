import '../css/index.scss';
import './component/index-hero'

import jQuery from 'jquery';
import 'jquery.appear';

import './theme/countTo';
import './theme/theme';
import './theme/counter';

// Counter
(function($) {

    'use strict';

    if ($.isFunction($.fn['themePluginCounter'])) {

        $(function() {
            $('[data-plugin-counter]:not(.manual), .counters [data-to]').each(function() {

                var $this = $(this),
                    opts;

                var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
                if (pluginOptions)
                    opts = pluginOptions;

                console.log($this.themePluginCounter);
                $this.themePluginCounter(opts);
            });
        });

    }

}).apply(window, [jQuery]);