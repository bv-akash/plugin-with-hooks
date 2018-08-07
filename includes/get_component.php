<?php

class GetComponent{
	public static function get_component($source, $type)
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
}
?>