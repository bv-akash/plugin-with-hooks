<?php
	/**
	 *	Plugin Name: Blogvault Analytics
	 *	Plugin URI: http://blogvault.net
	 *	Description: This plugin will List all the Query Related to Your Site
	 *	Version: 1.0
	 *	Author: BlogVault
	 *	License: GPLv2+
	 *	Text Domain: plug
	 */

require_once dirname( __FILE__ ) . '/activate.php';
require_once dirname( __FILE__ ) . '/site_info.php';
require_once dirname( __FILE__ ) . '/includes/lib.php';

$activate = new Activate();
$site_info = new SiteInfo();
$lib_function = new Lib();

global $performance_id;

$performance_id = $lib_function->randString(16);
add_action( 'admin_menu','add_admin_menu');
register_activation_hook( __FILE__,array($activate,'activate_plugin'));
register_deactivation_hook(__FILE__,array($activate, 'deactivate_plugin'));

if(isset($_GET['req']) && $_GET['req'] == 'blogvault')
{
	add_action('wp_footer', array($site_info, 'get_query'),10);
	add_action('wp_head',  array($site_info, 'get_header_script_data'));
	add_action('wp_footer', array($site_info, 'get_footer_script_data'),100);
}

function add_admin_menu() {
	add_menu_page( 'List All Query', 'BV Analytics', 'manage_options', 'query-dashboard', 'dashboard_file_path', plugins_url('images/logo.png', __FILE__));
}

function dashboard_file_path() {
	wp_enqueue_style( 'bv-query', plugins_url('css/custom.css', __FILE__));
	require_once(dirname(__FILE__) . '/includes/query-dashboard.php');
}
?>