<?php
require_once dirname( __FILE__ ) . '/database.php';

if (!class_exists('Activate')) :
class PluginInstall{
	function activate_plugin() {
		Database::create_table_performance();
		Database::create_table_querydata();
		Database::create_table_scriptdata();
	}

	function deactivate_plugin() {
		Database::drop_table_all();
	}
}
endif;
?>