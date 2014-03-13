(function ( $ ) {
	"use strict";

	$(function () {
		var submitButton = $("#rsvp-guest").find("input[type=submit]").first();

		$("#rsvp-guest").submit(function(e){
			e.preventDefault();
			rsvp_event();
		});
		// Place your public-facing JavaScript here
		function rsvp_event(){

			jQuery.ajax({
				type: "post",
				url: ajaxurl.location,
				data: { 
					action: 'gethello4',					
					_ajax_nonce: '<?php echo $nonce; ?>' 
				},
				beforeSend: function() {
					jQuery("#helloworld").fadeOut('fast');
					}, //fadeIn loading just when link is clicked
				success: function(html){ //so, if data is retrieved, store it in html
					jQuery("#helloworld").html(html); //fadeIn the html inside helloworld div
					jQuery("#helloworld").fadeIn("fast"); //animation
				}
			}); //close jQuery.ajax(
		}
	});

}(jQuery));