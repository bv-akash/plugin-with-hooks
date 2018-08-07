<?php

	class Activate{

		function BV_install() {

			if ( ! file_exists( $db = WP_CONTENT_DIR . '/db.php' ) && function_exists( 'symlink' ) ) {
				@symlink('/var/www/html/localwp/wp-content/plugins/plug/wp-content/db.php', $db ); // @codingStandardsIgnoreLine
			}


			$this->create_table_performance();
			$this->create_table_querydata();
			$this->create_table_scriptdata();
		}
		function BV_uninstall() {
			if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && class_exists( 'My_DB' ) ) {
				unlink( WP_CONTENT_DIR . '/db.php' ); // @codingStandardsIgnoreLine
			}

			$this->drop_table_all();

		}
		public function create_table_performance(){
			global $wpdb;
			$tb_name_performance = $wpdb->prefix . "performance";
			$tb_name_querydata = $wpdb->prefix . "querydata";
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $tb_name_performance (
				id INT(11) NOT NULL AUTO_INCREMENT,
				gid VARCHAR(16) NOT NULL,
				num_queries INT(11),
			query_time FLOAT,
			pageload_time FLOAT,
			created_at TIMESTAMP  DEFAULT CURRENT_TIMESTAMP,
			PRIMARY KEY  (id)
		) $charset_collate;";
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		public function create_table_querydata(){
			global $wpdb;
			$tb_name_querydata = $wpdb->prefix . "querydata";
			$charset_collate = $wpdb->get_charset_collate();
			$sql = "CREATE TABLE $tb_name_querydata (
				id INT(11) NOT NULL AUTO_INCREMENT,
				gid VARCHAR(16) NOT NULL,
				query TEXT DEFAULT NULL,
				time FLOAT DEFAULT NULL,
				stack TEXT DEFAULT NULL,
				results INT(6) DEFAULT NULL,
				component TEXT DEFAULT NULL,
				PRIMARY KEY  (id)
			) $charset_collate;";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
		}

		public function create_table_scriptdata(){
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
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
		}

		function drop_table_all(){
			global $wpdb;
			$table = array($wpdb->prefix."performance", $wpdb->prefix."querydata", $wpdb->prefix."scriptdata");
			foreach($table as $name)
			{
				$sql = "DROP TABLE IF EXISTS ".$name.";";
				$wpdb->query($sql);
			}
		}



	}

?>