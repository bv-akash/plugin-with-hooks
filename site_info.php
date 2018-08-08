<?php
if (!class_exists('SiteInfo')) :
	class SiteInfo{
			
		public function get_query(){
			global $wpdb, $performance_id;
			$data = $wpdb->queries;

			$num_queries = $wpdb->num_queries;
			$total_time = round(timer_stop(false, 22 ),4);
			$query_time = 0;
			

			$query = "INSERT INTO ".$wpdb->prefix."querydata (gid, query, time, stack, results, component) VALUES ";
			$query_array = array();
			foreach($wpdb->queries as $data_value)
			{
				$query_data = $wpdb->_real_escape($data_value[0]);
				$results = $data_value[5];
				$time_taken = round($data_value[1],4);
				$query_time += $time_taken;
				$stack = $wpdb->_real_escape($data_value[2]);


				$found = 0;
				$plugin_name = "";
				foreach($data_value[3] as $trace_value)
				{
					if(strpos($trace_value['file'], "/plugins/"))
					{
						$found = 1;
						$plugin_name = $trace_value['file'];
						$plugin_name = str_replace(WP_PLUGIN_URL,'',$plugin_name);
						$plugin_name = ucfirst(substr($plugin_name, 0, strpos( $plugin_name, '/')));
						$plugin_name = "Plugin : ".$plugin_name;
						break;
					}
				}
				if($found == 0)
					$component = "Core";
				else
					$component = $plugin_name;

				$batch_query = "('".$performance_id."','".$query_data."','".$time_taken."','".$stack."','".$results."','".$component."')";
				array_push($query_array ,$batch_query);
			}

			
			$sql_performance = "INSERT INTO ". $wpdb->prefix ."performance (gid, num_queries, query_time, pageload_time) VALUES ('".$performance_id."', '".$num_queries."', '".$query_time."', '".$total_time."')";
			$wpdb->query($sql_performance);

			$query .= implode(', ', $query_array);
			$wpdb->query($query);
		}

		public function get_header_script_data(){
			global $wp_styles, $wp_scripts, $header_data_scripts, $header_data_styles;

			$header_data_scripts = $wp_scripts->done;
			$header_data_styles = $wp_styles->done;
		}
		public function get_footer_script_data(){
			global $wp_styles, $wp_scripts, $header_data_styles, $header_data_scripts, $wpdb;
			
			$footer_data_styles = array_diff($wp_styles->done, $header_data_styles);
			$footer_data_scripts = array_diff($wp_scripts->done, $header_data_scripts);
			$data_styles = $wp_styles;
			$data_scripts = $wp_scripts;
			$query = "INSERT INTO ".$wpdb->prefix."scriptdata (gid, type, position, handle, source, version, dependencies, component) VALUES ";
			$query_array = array();
			$this->get_asset_data($header_data_styles, $data_styles, 'CSS', 'HEADER', $query_array);
			$this->get_asset_data($footer_data_styles, $data_styles, 'CSS', 'FOOTER', $query_array);
			$this->get_asset_data($header_data_scripts, $data_scripts, 'JS', 'HEADER', $query_array);
			$this->get_asset_data($footer_data_scripts, $data_scripts, 'JS', 'FOOTER', $query_array);
			$query .= implode(', ', $query_array);
			$wpdb->query($query);
		}
		public function get_batch_query($performance_id, $type, $position, $curr_script)
		{
			global $wpdb;

			$handle = $wpdb->_real_escape($curr_script->handle);
			$source = $wpdb->_real_escape($curr_script->src);
			$component = $this->get_component($source,strtolower($type));
			$version = $wpdb->_real_escape($curr_script->ver);
			$dependencies = implode(', ', $curr_script->deps);
			$dependencies = $wpdb->_real_escape($dependencies);
			$batch_query = "('".$performance_id."','".$type."','".$position."','".$handle."','".$source."','".$version."','".$dependencies."','".$component."')";
			return $batch_query;
		}
		
		public function get_component($component, $script_type)
		{
			if($component == '')
				$str = "Un-Defined";
			else if(strpos($component, "/wp-includes/".$script_type."/") !== false)
				$str = "Core";
			else if(strpos($component, "/wp-content/themes/") !== false)
			{
				$str = $component;
				$str = str_replace(WP_CONTENT_URL.'/themes/','',$str);
				$str = ucfirst(substr($str, 0, strpos( $str, '/')));
				$str = "Theme : ".$str;
			}
			else if(strpos($component, "/wp-content/plugins/") !== false)
			{
				$str = $component;
				$str = str_replace(WP_PLUGIN_URL,'',$str);
				$str = ucfirst(substr($str, 0, strpos( $str, '/')));
				$str = "Plugin : ".$str;
			}
			else
				$str = "External";

			return $str;
		}

			public function get_asset_data($asset_array, $asset_data, $type, $position, &$query_array)
			{
				global $performance_id;
				foreach($asset_array as $asset_value)
				{
					$curr_asset = $asset_data->registered[$asset_value];
					$batch_query = $this->get_batch_query($performance_id, $type, $position, $curr_asset);
					array_push($query_array, $batch_query);
				}
			}

	}
endif;
?>