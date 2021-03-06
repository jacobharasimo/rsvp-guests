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

<div class="wpcf7" id="">

	<form method="post" class="wpcf7-form"  id="rsvp-guest" aria-invalid="false">		

		<div class="wpv-rsvp-form">		

			<div class="row">

				<div class="columns large-12">

					<h2>ARE YOU ATTENDING?</h2>

					<h2>RSVP HERE!</h2>

				</div>

			</div>





			<div class="form-content">

				<div class="row">

					<div class="columns large-12">

						<h4>Name:</h4>	

						

						<input id="rsvp_name" type="text" name="rsvp_name" value="" aria-required="true" aria-invalid="false" required data-type="text">

						

						<small class="error" for="rsvp_name"></small>

					</div>

				</div>

				<div class="row">

					<div class="columns large-12">			

						<h4>Email:</h4>

						<input id="rsvp_email" type="email" name="rsvp_email" value="" class="wpcf7-form-control wpcf7-text" aria-required="true" aria-invalid="false" required data-type="email">

						<small class="error" for="rsvp_email"></small>

					</div>

				</div>

				<div class="row">

					<div class="columns large-12">			

						<h4>Guests:</h4>

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

						<h4>I am attending by...</h4>			

						<select id="rsvp_attending" name="rsvp_attending" aria-required="true" aria-invalid="false" required >
						<option>-Please Choose-</option>
							<option value="Group">Group Travel - 7 Days</option>
							<option value="Personal">Personal Arrangements</option>
							

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

							<h3 class="response-text"></h3>				

						</div>		

					</div>		

				</div>

			</div>





		</div>

	</form>

</div>