import './loading.scss';

import $ from 'jquery';
import LoadingOverlay from './LoadingOverlay';

// Loading Overlay
(function($) {

    // expose as a jquery plugin
    $.fn.loadingOverlay = function( opts ) {
        return this.each(function() {
            const $this = $( this );

            const loadingOverlay = $this.data( 'loadingOverlay' );
            if ( loadingOverlay ) {
                return loadingOverlay;
            } else {
                const options = opts || $this.data( 'loading-overlay-options' ) || {};
                console.log(options);
                return new LoadingOverlay( $this, options );
            }
        });
    };
    // auto init
    $('[data-loading-overlay]').loadingOverlay();

})($);