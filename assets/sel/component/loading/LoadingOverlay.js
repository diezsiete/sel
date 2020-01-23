import './loading.scss';
import $ from 'jquery';
import Routing from "./../../js/router";

class LoadingOverlay {
    constructor($wrapper, options) {
        this.loadingOverlayTemplate =
            `<div class="loading-overlay">
         <div class="bounce-loader"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
         </div>`;

        this.initialize($wrapper, options);
    }


    initialize( $wrapper, options ) {
        this.$wrapper = $wrapper;

        this
            .setVars()
            .setOptions( options )
            .build()
            .events();

        this.$wrapper.data( 'loadingOverlay', this );
    }

    setVars() {
        this.$overlay = this.$wrapper.find('.loading-overlay');

        return this;
    }

    setOptions( options ) {
        if ( !this.$overlay.get(0) ) {
            this.matchProperties();
        }
        this.options     = $.extend( true, {css: {}}, options );
        this.loaderClass = this.getLoaderClass( this.options.css.backgroundColor );

        return this;
    }

    build() {
        if ( !this.$overlay.closest(document.documentElement).get(0) ) {
            if ( !this.$cachedOverlay ) {
                this.$overlay = $( this.loadingOverlayTemplate ).clone();

                if ( this.options.css ) {
                    this.$overlay.css( this.options.css );
                    this.$overlay.find( '.loader' ).addClass( this.loaderClass );
                }
            } else {
                this.$overlay = this.$cachedOverlay.clone();
            }

            this.$wrapper.append( this.$overlay );
        }

        if ( !this.$cachedOverlay ) {
            this.$cachedOverlay = this.$overlay.clone();
        }

        return this;
    }

    events() {
        const _self = this;
        if ( this.options.startShowing ) {
            _self.show();
        }

        if ( this.$wrapper.is('body') || this.options.hideOnWindowLoad ) {
            $( window ).on( 'load error', function() {
                // _self.hide();
            });
        }

        if(this.options.callbackRoute) {
            $.ajax({
                url: Routing.generate(this.options.callbackRoute, {}),
                complete: () => this.hide()
            });
        }

        if ( this.options.listenOn ) {
            $( this.options.listenOn )
                .on( 'loading-overlay:show beforeSend.ic', function( e ) {
                    e.stopPropagation();
                    _self.show();
                })
                .on( 'loading-overlay:hide complete.ic', function( e ) {
                    e.stopPropagation();
                    _self.hide();
                });
        }

        this.$wrapper
            .on( 'loading-overlay:show beforeSend.ic', function( e ) {
                if ( e.target === _self.$wrapper.get(0) ) {
                    e.stopPropagation();
                    _self.show();
                    return true;
                }
                return false;
            })
            .on( 'loading-overlay:hide complete.ic', function( e ) {
                if ( e.target === _self.$wrapper.get(0) ) {
                    e.stopPropagation();
                    _self.hide();
                    return true;
                }
                return false;
            });

        return this;
    }

    show() {
        this.build();

        this.position = this.$wrapper.css( 'position' ).toLowerCase();
        if ( this.position !== 'relative' || this.position !== 'absolute' || this.position !== 'fixed' ) {
            this.$wrapper.css({
                position: 'relative'
            });
        }
        this.$wrapper.addClass( 'loading-overlay-showing' );
    }

    hide() {
        const _self = this;
        this.$wrapper.removeClass( 'loading-overlay-showing' );
        setTimeout(function() {
            if ( this.position !== 'relative' || this.position !== 'absolute' || this.position !== 'fixed' ) {
                _self.$wrapper.css({ position: '' });
            }
        }, 500);
    }

    matchProperties() {
        let i,
            l,
            properties;

        properties = [
            'backgroundColor',
            'borderRadius'
        ];

        l = properties.length;

        for( i = 0; i < l; i++ ) {
            const obj = {};
            obj[ properties[ i ] ] = this.$wrapper.css( properties[ i ] );

            $.extend( this.options.css, obj );
        }
    }

    getLoaderClass( backgroundColor ) {
        if ( !backgroundColor || backgroundColor === 'transparent' || backgroundColor === 'inherit' ) {
            return 'black';
        }

        let hexColor,
            r,
            g,
            b,
            yiq;

        const colorToHex = function( color ){
            var hex,
                rgb;

            if( color.indexOf('#') >- 1 ){
                hex = color.replace('#', '');
            } else {
                rgb = color.match(/\d+/g);
                hex = ('0' + parseInt(rgb[0], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[1], 10).toString(16)).slice(-2) + ('0' + parseInt(rgb[2], 10).toString(16)).slice(-2);
            }

            if ( hex.length === 3 ) {
                hex = hex + hex;
            }

            return hex;
        };

        hexColor = colorToHex( backgroundColor );

        r = parseInt( hexColor.substr( 0, 2), 16 );
        g = parseInt( hexColor.substr( 2, 2), 16 );
        b = parseInt( hexColor.substr( 4, 2), 16 );
        yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;

        return ( yiq >= 128 ) ? 'black' : 'white';
    }
}

export default LoadingOverlay