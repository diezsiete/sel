var RevolutionSlider = function () {
    return {
        //Revolution Slider - Full Width
        initRSfullWidth: function () {
		    var revapi;
	        jQuery(document).ready(function() {
	            revapi = jQuery('#revSlider').show().revolution(
	            {
	                delay:9000,
					fullScreenAutoWidth:"on",
					hideThumbs:10,
	                navigationStyle:"preview4",
					fullWidth:"on",
					fullScreen:"on",
					fullScreenOffsetContainer: 0,
	            });
				var api = revapi.on('revolution.slide.onloaded', function() {
					jQuery('.red-soluciones').closest('.tp-parallax-wrap').addClass('red-soluciones-layer');
				});
	        });
        }
    };
}();

RevolutionSlider.initRSfullWidth();