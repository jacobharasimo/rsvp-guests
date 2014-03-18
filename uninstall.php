<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   Plugin_Name
 * @author    Your Name <email@example.com>
 * @license   GPL-2.0+
 * @link      http://example.com
 * @copyright 2014 Your Name or Company Name
 */
// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
// Define uninstall functionality here
global $my_plugin_table;
global $my_plugin_options_table;
global $my_plugin_db_version;
global $wpdb;
$my_plugin_table = $wpdb->prefix . Rsvp_Guests::TABLESUFFEX;
$my_plugin_options_table= $wpdb->prefix . Rsvp_Guests::TABLESUFFEX.'_options';
$my_plugin_db_version = Rsvp_Guests::VERSION;
$sql = "DROP TABLE IF_EXISTS $my_plugin_table";
$sql2 = "DROP TABLE IF_EXISTS $my_plugin_options_table";
$e = $wpdb->query($sql);
$e2 = $wpdb->query($sql);
;?>