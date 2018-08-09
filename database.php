<?php
if (!defined('ABSPATH')) exit;
require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

if (!class_exists('Database')) :
class Database{
	const DB_PERFORMANCE = 'performance';
	const DB_QUERYDATA = 'querydata';
	const DB_SCRIPTDATA = 'scriptdata';
	
	public static function create_table_performance(){
		global $wpdb;
		$tb_name_performance = $wpdb->prefix . "performance";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tb_name_performance (
			id INT(11) NOT NULL AUTO_INCREMENT,
			gid VARCHAR(16) NOT NULL,
			num_queries INT(11),
			all_query_time FLOAT,
			pageload_time FLOAT,
			created_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql );
	}

	public static function create_table_querydata(){
		global $wpdb;
		$tb_name_querydata = $wpdb->prefix . "querydata";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tb_name_querydata (
			id INT(11) NOT NULL AUTO_INCREMENT,
			gid VARCHAR(16) NOT NULL,
			query TEXT DEFAULT NULL,
			query_time FLOAT DEFAULT NULL,
			stack TEXT DEFAULT NULL,
			results INT(6) DEFAULT NULL,
			component TEXT DEFAULT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql );
	}

	public static function create_table_scriptdata(){
		global $wpdb;
		$tb_name_scriptdata = $wpdb->prefix . "scriptdata";
		$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE $tb_name_scriptdata (
			id INT(11) NOT NULL AUTO_INCREMENT,
			gid VARCHAR(16) NOT NULL,
			type VARCHAR(3) NOT NULL,
			position VARCHAR(6) DEFAULT NULL,
			handle TEXT DEFAULT NULL,
			source TEXT DEFAULT NULL,
			version TEXT DEFAULT NULL,
			dependencies TEXT DEFAULT NULL,
			component TEXT DEFAULT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";

		dbDelta( $sql );
	}

	public static function drop_table_all(){
		global $wpdb;
		$table = array($wpdb->prefix."performance", $wpdb->prefix."querydata",
			$wpdb->prefix."scriptdata");
		foreach($table as $name)
		{
			$sql = "DROP TABLE IF EXISTS ".$name.";";
			$wpdb->query($sql);
		}
	}

	public static function insert_data($batch_array, $type)
	{
		global $wpdb;
		if($type == 'script'){
			$query = "INSERT INTO ".$wpdb->prefix.Database::DB_SCRIPTDATA." (gid, type, position, handle,
				source, version, dependencies, component) VALUES ";
			$query .= implode(', ', $batch_array);
			$wpdb->query($query);
		}else if($type == 'query'){
			$query = "INSERT INTO ".$wpdb->prefix.Database::DB_QUERYDATA." (gid, query, query_time,
				stack, results, component) VALUES ";
			$query .= implode(', ', $batch_array);
			$wpdb->query($query);
		}
	}

	public static function insert_performance_data($performance_id, $num_queries, $query_time,
		$total_time)
	{
		global $wpdb;
		$sql_performance = "INSERT INTO ".$wpdb->prefix."performance (gid, num_queries, all_query_time,
			pageload_time) VALUES ('".$performance_id."', '".$num_queries."', '".$query_time."',
			'".$total_time."')";
		$wpdb->query($sql_performance);
	}
}
endif;
?>