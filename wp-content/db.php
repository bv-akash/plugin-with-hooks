<?php
if ( ! defined( 'SAVEQUERIES' ) ) {
	define( 'SAVEQUERIES', true );
}

class My_DB extends wpdb {
	function __construct( $dbuser, $dbpassword, $dbname, $dbhost ) {

		parent::__construct( $dbuser, $dbpassword, $dbname, $dbhost );

	}

	function query( $query ) {
		if ( ! $this->ready ) {
			if ( isset( $this->check_current_query ) ) {
				$this->check_current_query = true;
			}
			return false;
		}

		if ( $this->show_errors ) {
			$this->hide_errors();
		}

		$result = parent::query( $query );

		if ( ! SAVEQUERIES ) {
			return $result;
		}

		$i = $this->num_queries - 1;
		$this->queries[$i][3] = debug_backtrace(false); 
		$this->queries[$i][4] = $this->time_start;
		$this->queries[$i][5] = $result;

		return $result;
	}

}

$wpdb = new My_DB( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );