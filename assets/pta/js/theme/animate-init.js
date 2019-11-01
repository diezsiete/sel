import jQuery from 'jquery';

// Animate
(function($) {

    'use strict';

    if ($.isFunction($.fn['themePluginAnimate'])) {

        $(function() {
            $('[data-appear-animation]').each(function() {
                var $this = $(this),
                    opts;

                var pluginOptions = theme.fn.getOptions($this.data('plugin-options'));
                if (pluginOptions)
                    opts = pluginOptions;

                $this.themePluginAnimate(opts);
            });
        });

    }

}).apply(window, [jQuery]);
