<?php
/**
 *  Plugin Name: Blogvault Analytics New
 *  Plugin URI: http://blogvault.net
 *  Description: This plugin will List all the Query Related to Your Site
 *  Version: 1.0
 *  Author: BlogVault
 *  License: GPLv2+
 *  Text Domain: plug
 */

require_once dirname( __FILE__ ) . '/plugin_install.php';
require_once dirname( __FILE__ ) . '/includes/lib.php';
require_once dirname( __FILE__ ) . '/site_info.php';

global $performance_id;
$plugin_install = new PluginInstall();
$lib_function = new Lib();
$performance_id = $lib_function->randString(16);

add_action( 'admin_menu','add_admin_menu');
register_activation_hook( __FILE__,array($plugin_install,'activate_plugin'));
register_deactivation_hook(__FILE__,array($plugin_install, 'deactivate_plugin'));

function add_admin_menu() {
  add_menu_page( 'List All Query', 'BV Analytics', 'manage_options', 'query-dashboard', 'dashboard_file_path', plugins_url('images/logo.png', __FILE__));
}

function dashboard_file_path() {
  wp_enqueue_style( 'bv-query', plugins_url('css/custom.css', __FILE__));
  require_once(dirname(__FILE__) . '/includes/query-dashboard.php');
}


if(defined('SAVEQUERIES') && defined('TRACKMORE')) {

  $site_info = new SiteInfo();
  $site_info->get_queries("default");

  require_once dirname( __FILE__ ) . '/my_wpdb.php';
  add_action('wp_footer', array($site_info, 'get_queries'),10);
  add_action('wp_head',  array($site_info, 'get_header_script_data'));
  add_action('wp_footer', array($site_info, 'get_footer_script_data'),100);
}
?>
