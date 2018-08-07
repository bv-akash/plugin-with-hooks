<?php

	class GetData{
			
		public function get_query(){
			global $wpdb, $performance_id;
			$data = $wpdb->queries;

			$num_queries = $wpdb->num_queries;
			$total_time = round(timer_stop(false, 22 ),4);
			$query_time = 0;
			foreach ( $wpdb->queries as $query )
				$query_time = $query_time + $query[1];

			$query_time = round($query_time,4);
			$sql_performance = "INSERT INTO ". $wpdb->prefix ."performance (gid, num_queries, query_time, pageload_time) VALUES ('".$performance_id."', '".$num_queries."', '".$query_time."', '".$total_time."')";

			$query = "INSERT INTO ".$wpdb->prefix."querydata (gid, query, time, stack, results, component) VALUES ";
			$query_array = array();
			foreach($wpdb->queries as $data_value)
			{
				$query_data = $wpdb->_real_escape($data_value[0]);
				$results = $data_value[5];//sizeof($wpdb->get_results($data_value[0]));
				$time_taken = round($data_value[1],4);
				$stack = $wpdb->_real_escape($data_value[2]);


				$found = 0;
				$str ="";
				foreach($data_value[3] as $trace_value)
				{
					if(strpos($trace_value['file'], "/plugins/"))
					{
						$found = 1;
						$str = $trace_value['file'];
						$str = str_replace('/var/www/html/localwp/wp-content/plugins/','',$str);
						$str = ucfirst(substr($str, 0, strpos( $str, '/')));
						$str = "Plugin : ".$str;
						break;
					}
				}
				if($found == 0)
					$component = "Core";
				else
					$component = $str;

				$batch_query = "('".$performance_id."','".$query_data."','".$time_taken."','".$stack."','".$results."','".$component."')";
				array_push($query_array ,$batch_query);
			}

			$query .= implode(', ', $query_array);
			$wpdb->query($query);
			$wpdb->query($sql_performance);

		}

		public function get_header_script_data(){
			global $wp_styles, $wp_scripts, $header_data_scripts, $header_data_styles;

			$header_data_scripts = $wp_scripts->done;
			$header_data_styles = $wp_styles->done;
		}
		public function get_footer_script_data(){
			global $wp_styles, $wp_scripts, $header_data_styles, $header_data_scripts, $wpdb, $performance_id;

			$footer_data_styles = array_diff($wp_styles->done, $header_data_styles);
			$footer_data_scripts = array_diff($wp_scripts->done, $header_data_scripts);
			$data_styles = $wp_styles;
			$data_scripts = $wp_scripts;
			$query = "INSERT INTO ".$wpdb->prefix."scriptdata (gid, type, position, handle, source, version, dependencies, component) VALUES ";
			$query_array = array();
			foreach($header_data_styles as $key => $value)
			{
				$type = "CSS";
				$position = "HEADER";
				$curr_css = $data_styles->registered[$value];
				$batch_query = $this->get_batch_query($performance_id, $type, $position, $curr_css);

				array_push($query_array, $batch_query);
			}
			foreach($footer_data_styles as $key => $value)
			{
				$type = "CSS";
				$position = "FOOTER";
				$curr_css = $data_styles->registered[$value];
				$batch_query = $this->get_batch_query($performance_id, $type, $position, $curr_css);
				array_push($query_array, $batch_query);
			}
			foreach($header_data_scripts as $key => $value)
			{
				$type = "JS";
				$position = "HEADER";
				$curr_js = $data_scripts->registered[$value];
				$batch_query = $this->get_batch_query($performance_id, $type, $position, $curr_js); 
				array_push($query_array, $batch_query);
			}
			foreach($footer_data_scripts as $key => $value)
			{
				$type = "JS";
				$position = "FOOTER";
				$curr_js = $data_scripts->registered[$value];
				$batch_query = $this->get_batch_query($performance_id, $type, $position, $curr_js);
				array_push($query_array, $batch_query);
			}

			$query .= implode(', ', $query_array);
			$wpdb->query($query);
		}
		
		public function get_component($source, $type)
		{
			$component = $source;
			$script = $type;
			if($component == '')
				$str = "Un-Defined";
			else if(strpos($component, "/wp-includes/".$script."/") !== false)
				$str = "Core";
			else if(strpos($component, "/wp-content/themes/") !== false)
			{
				$str = $component;
				$str = str_replace('http://localhost/localwp/wp-content/themes/','',$str);
				$str = ucfirst(substr($str, 0, strpos( $str, '/')));
				$str = "Theme : ".$str;
			}
			else if(strpos($component, "/wp-content/plugins/") !== false)
			{
				$str = $component;
				$str = str_replace('http://localhost/localwp/wp-content/plugins/','',$str);
				$str = ucfirst(substr($str, 0, strpos( $str, '/')));
				$str = "Plugin : ".$str;
			}
			else
				$str = "External";

			return $str;
		}

		public function get_batch_query($performance_id, $type, $position, $curr_script)
		{
			global $wpdb;
			$handle = $wpdb->_real_escape($curr_script->handle);
			$source = $wpdb->_real_escape($curr_script->src);

			$component = $this->get_component($source,strtolower($type));

			$version = $wpdb->_real_escape($curr_script->ver);
			$dependencies = "";
			$dependencies .= implode(', ', $curr_script->deps);
			$dependencies = $wpdb->_real_escape($dependencies);
			$batch_query = "('".$performance_id."','".$type."','".$position."','".$handle."','".$source."','".$version."','".$dependencies."','".$component."')";
			return $batch_query;

		}



	}
?>