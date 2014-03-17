<?php
/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   rsvp-guests
 * @author    Jacob Harasimo <jacobharasimo@gmail.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Jacob Harasimo
 */

?>

<!-- This file is used to markup the public facing aspect of the plugin. -->
<form method="post" class="" id="rsvp-guest" aria-invalid="false">				
	<div class="row">
		<div class="columns large-12">
			<h2>ARE YOU ATTENDING?</h2>
			<h2>RSVP HERE!</h2>
		</div>
	</div>
	<div class="row">
		<div class="columns large-12">
			<label for="rsvp_name">Name:</label>			
			<input id="rsvp_name" type="text" name="rsvp_name" value=""  aria-required="true" aria-invalid="false" required data-type="text">
			<small class="error" for="rsvp_name"></small>
		</div>
	</div>
	<div class="row">
		<div class="columns large-12">			
			<label for="rsvp_email">Email:</label>
			<input id="rsvp_email" type="email" name="rsvp_email" value="" aria-required="true" aria-invalid="false" required data-type="email">
			<small class="error" for="rsvp_email"></small>
		</div>
	</div>
	<div class="row">
		<div class="columns large-12">			
			<label for="rsvp_guests">Guests:</label>
			<select name="rsvp_guests" id="rsvp_guests" aria-required="true" aria-invalid="false" required data-type="int">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
			</select>
			<small class="error" for="rsvp_guests"></small>
		</div>
	</div>
	<div class="row">
		<div class="columns large-12">
			<label for="rsvp_attending" >I am attending...</label>			
			<select id="rsvp_attending" name="rsvp_attending" aria-required="true" aria-invalid="false" required>
				<option value="Ceremony">Ceremony</option>
				<option value="Party">Party</option>
			</select>
			<small class="error" for="rsvp_attending"></small>
		</div>
	</div>
	<div class="row">
		<div class="columns large-12">		
			<input type="submit" value="I am attending" role="button" aria-disabled="false"><span class="loading-icon" style="display:none;"></span>
		</div>
	</div>
	
	<!-- style="visibility: collapse;" -->
	<div class="row response">
		<div class="columns response">
			<div class="response-area">
				<p class="response-text"></p>				
			</div>		
		</div>		

	</div>
	
	
</form>