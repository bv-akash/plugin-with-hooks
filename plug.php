<?php
	/**
	 *	Plugin Name: List Query
	 *	Plugin URI: http://blogvault.net
	 *	Description: This plugin will List all the Query Related to Your Site
	 *	Version: 1.0
	 *	Author: BlogVault
	 *	License: GPLv2+
	 *	Text Domain: plug
	 */


require_once dirname( __FILE__ ) . '/Activate.php';
require_once dirname( __FILE__ ) . '/GetData.php';
require_once dirname( __FILE__ ) . '/includes/lib.php';


$activate = new Activate();
$get_data = new GetData();
$lib_function = new Lib();

global $performance_id;

$performance_id = $lib_function->randString(16);
add_action( 'admin_menu','list_query_add_menu');
register_activation_hook( __FILE__,array($activate,'BV_install'));
register_deactivation_hook(__FILE__,array($activate, 'BV_uninstall'));
add_action('wp_footer', array($get_data, 'get_query'),10);
add_action('wp_head',  array($get_data, 'get_header_script_data'));
add_action('wp_footer', array($get_data, 'get_footer_script_data'),100);
function list_query_add_menu() {
	add_menu_page( 'List All Query', 'List Query', 'manage_options', 'query-dashboard', 'list_query_file_path', plugins_url('images/logo.png', __FILE__),'99');
}

function list_query_file_path() {
	include('/var/www/html/localwp/wp-content/plugins/plug/includes/query-dashboard.php');
}
?>