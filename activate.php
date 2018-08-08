<?php
require_once dirname( __FILE__ ) . '/database.php';

if (!class_exists('Activate')) :
class Activate{
	function activate_plugin() {
		if ( ! file_exists( $db = WP_CONTENT_DIR . '/db.php' ) && function_exists( 'symlink' ) ) {
			@symlink(WP_PLUGIN_DIR.'/bv-analytics/wp-content/db.php', $db ); 
		}
		Database::create_table_performance();
		Database::create_table_querydata();
		Database::create_table_scriptdata();
	}

	function deactivate_plugin() {
		if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && class_exists( 'My_DB' ) ) {
			unlink( WP_CONTENT_DIR.'/db.php' );
		}
		Database::drop_table_all();
	}
}
endif;
?>