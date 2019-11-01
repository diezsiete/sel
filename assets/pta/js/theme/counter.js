// Counter
import jQuery from 'jquery';
import 'jquery.appear';

(function(theme, $) {

    theme = theme || {};

    var instanceName = '__counter';

    var PluginCounter = function($el, opts) {
        return this.initialize($el, opts);
    };

    PluginCounter.defaults = {
        accX: 0,
        accY: 0,
        speed: 3000,
        refreshInterval: 100,
        decimals: 0,
        onUpdate: null,
        onComplete: null
    };

    PluginCounter.prototype = {
        initialize: function($el, opts) {
            if ($el.data(instanceName)) {
                return this;
            }

            this.$el = $el;

            this
                .setData()
                .setOptions(opts)
                .build();

            return this;
        },

        setData: function() {
            this.$el.data(instanceName, this);

            return this;
        },

        setOptions: function(opts) {
            this.options = $.extend(true, {}, PluginCounter.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },

        build: function() {
            if (!($.isFunction($.fn.countTo))) {
                return this;
            }

            var self = this,
                $el = this.options.wrapper;

            $.extend(self.options, {
                onComplete: function() {
                    if ($el.data('append')) {
                        $el.html($el.html() + $el.data('append'));
                    }

                    if ($el.data('prepend')) {
                        $el.html($el.data('prepend') + $el.html());
                    }
                }
            });

            $el.appear(function() {

                $el.countTo(self.options);

            }, {
                accX: self.options.accX,
                accY: self.options.accY
            });

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginCounter: PluginCounter
    });

    // jquery plugin
    $.fn.themePluginCounter = function(opts) {
        return this.map(function() {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginCounter($this, opts);
            }

        });
    }

}).apply(window, [window.theme, jQuery]);