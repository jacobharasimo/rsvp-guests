(function ( $ ) {
	"use strict";

	$(function () {
		var submitButton = $("#rsvp-guest").find("input[type=submit]").first();

		$("#rsvp-guest").submit(function(e){
			e.preventDefault();
			rsvp_event($(this));
		});
		
		function errorKeypress(){			
			$(this).attr("aria-invalid",false);
			$(this).next('small').text('');			
			$(this).unbind();
		}
	
		// Place your public-facing JavaScript here
		function rsvp_event(rsvp_form){
			var responseArea = rsvp_form.find('.response-area').first();
			var responseMessage = responseArea.find('.response-text').first();
			rsvp_form.find(".loading-icon").show();
			$.post(ajaxurl.location,{
				action: 'my_custom_handler',
				formData:JSON.stringify(rsvp_form.serializeArray()),
			},function(response){
				response = JSON.parse(response);
				if(response.Success){											
					rsvp_form.prop("aria-invalid","false");
					rsvp_form.attr("aria-invalid","false");
					var formElements = response.Data;
					for(var i = 0; i<formElements.length;++i){
						var item = response.Data[i];	
						$("#"+item.name).unbind();
					}
				}
				else{
					rsvp_form.attr("aria-invalid","true");
					rsvp_form.prop("aria-invalid","true");
					//check the response and find teh invalid elements to mark them
					var formElements = response.Data;
					for(var i = 0; i<formElements.length;++i){
						var item = response.Data[i];						
						if(item.Invalid){							
							$("#"+item.name).attr("aria-invalid",item.Invalid);
							$("#"+item.name).bind('keypress change',errorKeypress)							
							$("small[for="+item.name+"]").text(item.ErrorMessage);
						}						
					}						
				}
				rsvp_form.find(".loading-icon").fadeOut('fast');
				responseArea.fadeIn('slow').delay(1500).fadeOut('fast');
				responseMessage.text(response.Message)
			}).fail(function(){
				rsvp_form.attr("aria-invalid","true");
					rsvp_form.prop("aria-invalid","true");
				responseMessage.text("Server error, try again later")
			});
		}
	});

}(jQuery));