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
        },
		//Revolution Slider - Full Screen Offset Container
		initRSfullScreenOffset: function () {
			var revapi;
			jQuery(document).ready(function() {
				revapi = jQuery('#revSlider').show().revolution(
					{
						delay:15000,
						startwidth:1170,
						startheight:400,
						hideThumbs:10,
						fullWidth:"off",
						fullScreen:"on",
						hideCaptionAtLimit: "",
						dottedOverlay:"threexthree",
						navigationStyle:"preview4",
						fullScreenOffsetContainer: ".header"
					});
			});
		}
	};
}();