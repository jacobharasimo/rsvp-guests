<?php
/**
 * The WordPress Plugin Boilerplate.
 *
 * A foundation off of which to build well-documented WordPress plugins that
 * also follow WordPress Coding Standards and PHP best practices.
 *
 * @package   rsvp-guests
 * @author    Jacob Harasimo <jacobharasimo@gmail.com>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Jacob Harasimo
 *
 * @wordpress-plugin
 * Plugin Name:       RSVP Guests
 * Plugin URI:        
 * Description:       Creates a form that allows users to RSVP to an event with guest listed in the dashboard. Shortcode [rsvp_guests]
 * Version:           1.0.0
 * Author:            Jacob Harasimo
 * Author URI:        
 * Text Domain:       rsvp-guests-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       
 * GitHub Plugin URI: https://github.com/<owner>/<repo>
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

require_once( plugin_dir_path( __FILE__ ) . 'public/class-rsvp-guests.php' );

register_activation_hook( __FILE__, array( 'Rsvp_Guests', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Rsvp_Guests', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'Rsvp_Guests', 'get_instance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

/*
 * If you want to include Ajax within the dashboard, change the following
 * conditional to:
 *
 * if ( is_admin() ) {
 *   ...
 * }
 *
 * The code below is intended to to give the lightest footprint possible.
 */
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-rsvp-guests-admin.php' );
	add_action( 'plugins_loaded', array( 'Rsvp_Guests_Admin', 'get_instance' ) );

}
