<?php

	/**

	 * Plugin Name.

	 *

	 * @package   Rsvp_Guests

	 * @author    Jacob Harasimo <jacobharasimo@gmail.com>

	 * @license   GPL-2.0+

	 * @link      

	 * @copyright 2014 Jacob Harasimo

	 */

	/**

	 * Plugin class. This class should ideally be used to work with the

	 * public-facing side of the WordPress site.

	 *

	 * If you're interested in introducing administrative or dashboard

	 * functionality, then refer to `class-plugin-name-admin.php`

	 *

	 * @package Rsvp_Guests

	 * @author  Jacob Harasimo <jacobharasimo@gmail.com>

	 */

	class RsvpAjaxRequest{

	    public $Success = false;

	    public $Message = '';

	    // method declaration

	    public $Data;

	}

	

	class Rsvp_Guests {

		/**

		 * Plugin version, used for cache-busting of style and script file references.

		 *

		 * @since   1.0.0

		 *

		 * @var     string

		 */

		const VERSION = '1.0.0';

		const TABLESUFFEX = 'rsvp_guests';

		/**

		 *

		 * Unique identifier for your plugin.

		 *

		 *

		 * The variable name is used as the text domain when internationalizing strings

		 * of text. Its value should match the Text Domain file header in the main

		 * plugin file.

		 *

		 * @since    1.0.0

		 *

		 * @var      string

		 */

		protected $plugin_slug = 'rsvp-guests';

		/**

		 * Instance of this class.

		 *

		 * @since    1.0.0

		 *

		 * @var      object

		 */

		protected static $instance = null;

		/**

		 * Initialize the plugin by setting localization and loading public scripts

		 * and styles.

		 *

		 * @since     1.0.0

		 */

		private function __construct() {

			// Load plugin text domain

			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

			// Activate plugin when new blog is added

			add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

			// Include the Ajax library on the front end

			add_action( 'wp_head', array( $this, 'add_ajax_library' ) );

			add_action( 'wp_ajax_nopriv_rsvp_guest_handler', array( $this, 'rsvp_guest_handler' )  );

			// Load public-facing style sheet and JavaScript.

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			add_shortcode( 'rsvp_guests', array( $this, 'get_public_view' ) );

			/* Define custom functionality.

			 * Refer To http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters

			 */

			add_action( '@TODO', array( $this, 'action_method_name' ) );

			add_filter( '@TODO', array( $this, 'filter_method_name' ) );

		}

		public function rsvp_guest_handler(){		

			$invalidTextMessage="";

			$invalidIntMessage="not a valid number";

			$invalidEmailMessage="";

			$successMessage="";

			$invalidFormMessage="";

			$duplicateAttendeeMessage="";

			$serverErrorMessage="";

			$attendingName="";

			$attendingEmail="";

			$attendingNumberGuests=0;

			$attendingEvent="";



			global $wpdb; 			

			//Connec tto the WP DB and retrieve the plugin options (error messages)

			$my_plugin_table = $wpdb->prefix .  Rsvp_Guests::TABLESUFFEX;

			$my_plugin_options_table= $wpdb->prefix . Rsvp_Guests::TABLESUFFEX.'_options';

			$getSettingSql = "SELECT name, defaultValue, value FROM $my_plugin_options_table";

			$result = $wpdb->get_results($getSettingSql);

			//assign the error messages, if the user has not set one use the default.

			foreach ($result as $key => $value) {

				switch($value->name){

					case 'textFieldError':					

						$invalidTextMessage=$value->defaultValue;

						if(!empty($value->value)){

							$invalidTextMessage=$value->value;

						}

						break;					

					case 'emailFieldError':

						$invalidTextMessage=$value->defaultValue;

						if(!empty($value->value)){

							$invalidTextMessage=$value->value;

						}

						break;

					case 'successMessage':

						$successMessage=$value->defaultValue;

						if(!empty($value->value)){

							$invalidTextMessage=$value->value;

						}

						break;

					case 'invalidMessage':

						$invalidFormMessage=$value->defaultValue;

						if(!empty($value->value)){

							$invalidFormMessage=$value->value;

						}

						break;

					case 'duplicateMessage':

						$duplicateAttendeeMessage=$value->defaultValue;						

						if(!empty($value->value)){

							$duplicateAttendeeMessage=$value->value;							

						}

						break;

					case 'serverError':

						$serverErrorMessage=$value->defaultValue;

						if(!empty($value->value)){

							$serverErrorMessage=$value->value;

						}

						break;					

				}

			}			



			$response = new RsvpAjaxRequest();		

			$response -> Message = $serverErrorMessage;

			$data = json_decode(stripslashes($_REQUEST['formData']));		

//			$respose -> Data = json_last_error();

			$isValid = true;

			if(!$response -> Data){

				$response -> Success = true;

				$response -> Message = $successMessage;

				$response -> Data = $data;

			}							

			foreach ($response -> Data as $item) {

				$item -> Invalid = false;

				//type check for server side validation

				switch($item->type){

					case "email":

						if(!is_email( $item -> value )){

							$isValid  =false;

							$item -> Invalid = true;

							$item -> ErrorMessage = $invalidEmailMessage;

						}

						//check to see if the email already exists

						$findUser = "SELECT count(*) FROM $my_plugin_table WHERE email = '". $item -> value."'";

						$user_count = $wpdb->get_var($findUser);												

						if($user_count>0){

							$isValid = false;

							$item -> Invalid = true;

							$item -> ErrorMessage = $duplicateAttendeeMessage;				

						}

						break;	

					case "int": 

							if(!is_int(intval($item -> value))){

								$isValid = false;

								$item -> Invalid = true;

								$item -> ErrorMessage =$invalidIntMessage;

							}														

						break;				

					default: 

						if(empty($item->value)){

							$isValid = false;

							$item -> Invalid = true;

							$item -> ErrorMessage =$invalidTextMessage;

						}					

						break;

				}			

				//retrieve the information to commit to the DB

				if($isValid){

					switch($item->name){

						case 'rsvp_name':

							$attendingName=$item->value;

							break;

						case 'rsvp_email':

							$attendingEmail=$item->value;

							break;

						case 'rsvp_guests':					

							$attendingNumberGuests=intval($item->value);

							break;

						case 'rsvp_attending':

							$attendingEvent=$item->value;

							break;

					}			

				}

			}

			if(!$isValid){

				$response -> Success = false;

				$response -> Message = $invalidFormMessage;

			}

			else{

				//insert the entry into the DB here

				$wpdb->insert( $my_plugin_table, array( 'name' => $attendingName, 'email' => $attendingEmail,'event'=>$attendingEvent,'num_guests'=>$attendingNumberGuests ) );

			}

			die(json_encode($response));

		}

		/**

		 * Adds the WordPress Ajax Library to the frontend.

		 */

		public function add_ajax_library() {

			$html = '<script type="text/javascript">';

			$html .= 'var ajaxurl = {location:"' . addslashes(admin_url( 'admin-ajax.php' )) . '"}';

			$html .= '</script>';

			echo $html;	

		} // end add_ajax_library

		public function get_public_view(){		

			ob_start();

			eval('?>' . file_get_contents( 'views/public.php', TRUE ) . '<?php ');

			$output_string = ob_get_contents();

			ob_end_clean();		

			return $output_string;

		}

		/**

		 * Return the plugin slug.

		 *

		 * @since    1.0.0

		 *

		 * @return    Plugin slug variable.

		 */

		public function get_plugin_slug() {

			return $this->plugin_slug;

		}

		/**

		 * Return an instance of this class.

		 *

		 * @since     1.0.0

		 *

		 * @return    object    A single instance of this class.

		 */

		public static function get_instance() {

			// If the single instance hasn't been set, set it now.

			if ( null == self::$instance ) {

				self::$instance = new self;

			}

			return self::$instance;

		}

		/**

		 * Fired when the plugin is activated.

		 *

		 * @since    1.0.0

		 *

		 * @param    boolean    $network_wide    True if WPMU superadmin uses

		 *                                       "Network Activate" action, false if

		 *                                       WPMU is disabled or plugin is

		 *                                       activated on an individual blog.

		 */

		public static function activate( $network_wide ) {

			global $my_plugin_options_table;

			global $my_plugin_table;

			global $my_plugin_db_version;

			global $wpdb;

			$my_plugin_options_table= $wpdb->prefix . Rsvp_Guests::TABLESUFFEX.'_options';

			$my_plugin_table = $wpdb->prefix . Rsvp_Guests::TABLESUFFEX;

			$my_plugin_db_version = Rsvp_Guests::VERSION;

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {

				if ( $network_wide  ) {

					// Get all blog ids

					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {

						switch_to_blog( $blog_id );

						self::single_activate();

					}

					restore_current_blog();

				} else {

					self::single_activate();

				}

			} else {

				self::single_activate();

			}

		}

		/**

		 * Fired when the plugin is deactivated.

		 *

		 * @since    1.0.0

		 *

		 * @param    boolean    $network_wide    True if WPMU superadmin uses

		 *                                       "Network Deactivate" action, false if

		 *                                       WPMU is disabled or plugin is

		 *                                       deactivated on an individual blog.

		 */

		public static function deactivate( $network_wide ) {

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {

				if ( $network_wide ) {

					// Get all blog ids

					$blog_ids = self::get_blog_ids();

					foreach ( $blog_ids as $blog_id ) {

						switch_to_blog( $blog_id );

						self::single_deactivate();

					}

					restore_current_blog();

				} else {

					self::single_deactivate();

				}

			} else {

				self::single_deactivate();

			}

		}

		/**

		 * Fired when a new site is activated with a WPMU environment.

		 *

		 * @since    1.0.0

		 *

		 * @param    int    $blog_id    ID of the new blog.

		 */

		public function activate_new_site( $blog_id ) {

			if ( 1 !== did_action( 'wpmu_new_blog' ) ) {

				return;

			}

			switch_to_blog( $blog_id );

			self::single_activate();

			restore_current_blog();

		}

		/**

		 * Get all blog ids of blogs in the current network that are:

		 * - not archived

		 * - not spam

		 * - not deleted

		 *

		 * @since    1.0.0

		 *

		 * @return   array|false    The blog ids, false if no matches.

		 */

		private static function get_blog_ids() {

			global $wpdb;

			// get an array of blog ids

			$sql = "SELECT blog_id FROM $wpdb->blogs

				WHERE archived = '0' AND spam = '0'

				AND deleted = '0'";

			return $wpdb->get_col( $sql );

		}

		/**

		 * Fired for each blog when the plugin is activated.

		 *

		 * @since    1.0.0

		 */

		private static function single_activate() {		

			global $wpdb;

			global $my_plugin_options_table;

			global $my_plugin_table;

			global $my_plugin_db_version;			

			if ( $wpdb->get_var( "show tables like '$my_plugin_table'" ) != $my_plugin_table ) {

				$pluginTable = "CREATE TABLE $my_plugin_table (". 

				    "id int(11) NULL AUTO_INCREMENT,".

					"name text NOT NULL,".

					"email varchar(255) NOT NULL,".

					"event varchar(1024) NOT NULL,".

					"num_guests int(11) NOT NULL DEFAULT 0,".

					"PRIMARY KEY (id),".

					"UNIQUE KEY id (id)".

				         ")";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

				dbDelta( $pluginTable );

				add_option( "my_plugin_db_version", $my_plugin_db_version );

			}

			if ( $wpdb->get_var( "show tables like '$my_plugin_options_table'" ) != $my_plugin_options_table ) {

				$pluginOptionsTable = "CREATE TABLE $my_plugin_options_table (". 

				    "id int(11) NULL AUTO_INCREMENT,".

				    "name text NOT NULL,".

					"value text,".

					"defaultValue text NOT NULL,".

					"inputLabel text NOT NULL,".

					"PRIMARY KEY (id),".

					"UNIQUE KEY id (id)". 

				         ")";

				

		        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );			    

				dbDelta( $pluginOptionsTable );	

				//inset default values

				$wpdb->insert( $my_plugin_options_table, array( 'name' => "textFieldError", 'defaultValue' => "text must not be empty.",'inputLabel'=>'Invalid text filed' ) );

				$wpdb->insert( $my_plugin_options_table, array( 'name' => "emailFieldError", 'defaultValue' => "Invalid email address.",'inputLabel'=>'Invalid Email filed'  ) );

				$wpdb->insert( $my_plugin_options_table, array( 'name' => "successMessage", 'defaultValue' => "See you there!",'inputLabel'=>'Success Message'  ) );

				$wpdb->insert( $my_plugin_options_table, array( 'name' => "invalidMessage", 'defaultValue' => "Thats not right, try again.",'inputLabel'=>'Invalid Form Message'  ) );

				$wpdb->insert( $my_plugin_options_table, array( 'name' => "duplicateMessage", 'defaultValue' => "You replied before.",'inputLabel'=>'Duplicate Attendee Message'  ) );

				$wpdb->insert( $my_plugin_options_table, array( 'name' => "serverError", 'defaultValue' => "Server Error Parsing Data.",'inputLabel'=>'Server Error Message'  ) );

			}					

		}

		/**

		 * Fired for each blog when the plugin is deactivated.

		 *

		 * @since    1.0.0

		 */

		private static function single_deactivate() {

			// Define deactivation functionality here

		}

		/**

		 * Load the plugin text domain for translation.

		 *

		 * @since    1.0.0

		 */

		public function load_plugin_textdomain() {

			$domain = $this->plugin_slug;

			$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

			load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

		}

		/**

		 * Register and enqueue public-facing style sheet.

		 *

		 * @since    1.0.0

		 */

		public function enqueue_styles() {

			wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );

		}

		/**

		 * Register and enqueues public-facing JavaScript files.

		 *

		 * @since    1.0.0

		 */

		public function enqueue_scripts() {

			wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery' ), self::VERSION );

		}

		/**

		 * NOTE:  Actions are points in the execution of a page or process

		 *        lifecycle that WordPress fires.

		 *

		 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions

		 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference

		 *

		 * @since    1.0.0

		 */

		public function action_method_name() {

			// @TODO: Define your action hook callback here

		}

		/**

		 * NOTE:  Filters are points of execution in which WordPress modifies data

		 *        before saving it or sending it to the browser.

		 *

		 *        Filters: http://codex.wordpress.org/Plugin_API#Filters

		 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference

		 *

		 * @since    1.0.0

		 */

		public function filter_method_name() {

			// @TODO: Define your filter hook callback here

		}

	}

;?>