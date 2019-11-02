import jQuery from 'jquery';

// Carousel
(function(theme, $) {

    theme = theme || {};

    var instanceName = '__carousel';

    var PluginCarousel = function($el, opts) {
        return this.initialize($el, opts);
    };

    PluginCarousel.defaults = {
        loop: true,
        responsive: {
            0: {
                items: 1
            },
            479: {
                items: 1
            },
            768: {
                items: 2
            },
            979: {
                items: 3
            },
            1199: {
                items: 4
            }
        },
        navText: []
    };

    PluginCarousel.prototype = {
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
            this.options = $.extend(true, {}, PluginCarousel.defaults, opts, {
                wrapper: this.$el
            });

            return this;
        },

        build: function() {
            if (!($.isFunction($.fn.owlCarousel))) {
                return this;
            }

            var self = this,
                $el = this.options.wrapper;

            // Add Theme Class
            $el.addClass('owl-theme');

            // Add Loading
            $el.addClass('owl-loading');

            // Force RTL according to HTML dir attribute
            if ($('html').attr('dir') == 'rtl') {
                this.options = $.extend(true, {}, this.options, {
                    rtl: true
                });
            }

            if (this.options.items == 1) {
                this.options.responsive = {}
            }

            if (this.options.items > 4) {
                this.options = $.extend(true, {}, this.options, {
                    responsive: {
                        1199: {
                            items: this.options.items
                        }
                    }
                });
            }

            // Auto Height Fixes
            if (this.options.autoHeight) {
                var itemsHeight = [];

                $el.find('.owl-item').each(function(){
                    if( $(this).hasClass('active') ) {
                        itemsHeight.push( $(this).height() );
                    }
                });

                $(window).afterResize(function() {
                    $el.find('.owl-stage-outer').height( Math.max.apply(null, itemsHeight) );
                });

                $(window).on('load', function() {
                    $el.find('.owl-stage-outer').height( Math.max.apply(null, itemsHeight) );
                });
            }

            // Initialize OwlCarousel
            $el.owlCarousel(this.options).addClass('owl-carousel-init');

            // Sync
            if ($el.attr('data-sync')) {
                $el.on('change.owl.carousel', function(event) {
                    if (event.namespace && event.property.name === 'position') {
                        var target = event.relatedTarget.relative(event.property.value, true);
                        $( $el.data('sync') ).owlCarousel('to', target, 300, true);
                    }
                });
            }

            // Carousel Center Active Item
            if( $el.hasClass('carousel-center-active-item') ) {
                var itemsActive    = $el.find('.owl-item.active'),
                    indexCenter    = Math.floor( ($el.find('.owl-item.active').length - 1) / 2 ),
                    itemCenter     = itemsActive.eq(indexCenter);

                itemCenter.addClass('current');

                $el.on('change.owl.carousel', function(event) {
                    $el.find('.owl-item').removeClass('current');

                    setTimeout(function(){
                        var itemsActive    = $el.find('.owl-item.active'),
                            indexCenter    = Math.floor( ($el.find('.owl-item.active').length - 1) / 2 ),
                            itemCenter     = itemsActive.eq(indexCenter);

                        itemCenter.addClass('current');
                    }, 100);
                });

                // Refresh
                $el.trigger('refresh.owl.carousel');

            }

            // Remove Loading
            $el.removeClass('owl-loading');

            // Remove Height
            $el.css('height', 'auto');

            return this;
        }
    };

    // expose to scope
    $.extend(theme, {
        PluginCarousel: PluginCarousel
    });

    // jquery plugin
    $.fn.themePluginCarousel = function(opts) {
        return this.map(function() {
            var $this = $(this);

            if ($this.data(instanceName)) {
                return $this.data(instanceName);
            } else {
                return new PluginCarousel($this, opts);
            }

        });
    }

}).apply(window, [window.theme, jQuery]);