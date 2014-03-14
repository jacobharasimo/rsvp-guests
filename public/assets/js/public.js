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

jQuery.post(ajaxurl.location,{
			action: 'my_custom_handler',
			formData:$('#rsvp-guest').serializeArray(),
},function(response){

}).fail(function(){

});

		}
	});

}(jQuery));