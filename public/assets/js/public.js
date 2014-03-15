(function ( $ ) {
	"use strict";

	$(function () {
		var submitButton = $("#rsvp-guest").find("input[type=submit]").first();

		$("#rsvp-guest").submit(function(e){
			e.preventDefault();
			rsvp_event($(this));
		});
		// Place your public-facing JavaScript here
		function rsvp_event(rsvp_form){
			var responseArea = rsvp_form.find('.response-area').first();
			var responseMessage = responseArea.find('.response-text').first();
			rsvp_form.find(".loading").show();
			$.post(ajaxurl.location,{
				action: 'my_custom_handler',
				formData:JSON.stringify(rsvp_form.serializeArray()),
			},function(response){
				response = JSON.parse(response);

				if(response.Success){						
					rsvp_form.prop("data-abide","true")
					rsvp_form.prop("aria-invalid","false")
				}
				else{
					rsvp_form.attr("data-abide","false")
					rsvp_form.prop("aria-invalid","true")					
				}
				responseArea.fadeIn('slow');
				responseMessage.text(response.Message)
				

			}).fail(function(){

			});

		}
	});

}(jQuery));