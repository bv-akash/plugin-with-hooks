<?php

	class Lib{
		public	function randString($length) {
			$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";

			$str = "";
			$size = strlen($chars);
			for( $i = 0; $i < $length; $i++ ) {
				$str .= $chars[rand(0, $size - 1)];
			}
			return $str;
		}

		public function get_external_caller($data){
			global $wpdb;
			$external_data = '';
			$current_handle = $data->handle;
			$all_components = $wpdb->get_results("select distinct component from wp_scriptdata where gid='".$data->gid."' AND type='CSS' AND component != 'Core' AND component != 'External'");
			foreach($all_components as $component_value)
			{
				$full_component_name = $component_value->component;
				if(strpos($component_value->component, "Theme") !== false){
					$name = str_replace('Theme : ','', $component_value->component);
				}
				else
					$name = str_replace('Plugin : ','', $component_value->component);

				if(strpos($current_handle, strtolower($name)) !== false)
				{
					$external_data = "External. Caller - <strong style='color:#F00;'>".$full_component_name."</strong>";
					break;
				}
			}
			if($external_data != '')
				return $external_data;
			else
				return "External. Caller Not Available.";
		}
	}

?>