<?php

class GetComponent{

public function get_css_component($source)
    {
      $component = $source;
      if($component == '')
      {
        $str = "Un-Defined ";
      }else if(strpos($component, "/wp-includes/css/") !== false)
      {
        $str = "Core";
      }
      else if(strpos($component, "/wp-content/themes/") !== false)
      {
        $str = $component;
        $str = str_replace('http://localhost/localwp/wp-content/themes/','',$str);
        $str = ucfirst(substr($str, 0, strpos( $str, '/')));
        $str = "Theme : ".$str;
      }else if(strpos($component, "/wp-content/plugins/") !== false)
      {
        $str = $component;
        $str = str_replace('http://localhost/localwp/wp-content/plugins/','',$str);
        $str = ucfirst(substr($str, 0, strpos( $str, '/')));
        $str = "Plugin : ".$str;
      }
      else{
        $str = "External";
      }
      return $str;
    }

    public function get_js_component($source)
    {
      $component = $source;
      if($component == '')
      {
        $str = "Un-Defined ";
      }
      else if(strpos($component, "/wp-content/themes/") !== false)
      {
        $str = $component;
        $str = str_replace('http://localhost/localwp/wp-content/themes/','',$str);
        $str = ucfirst(substr($str, 0, strpos( $str, '/')));
        $str = "Theme : ".$str;
      }else if(strpos($component, "/wp-content/plugins/") !== false)
      {
        $str = $component;
        $str = str_replace('http://localhost/localwp/wp-content/plugins/','',$str);
        $str = ucfirst(substr($str, 0, strpos( $str, '/')));
        $str = "Plugin : ".$str;
      }else if(strpos($component, "/wp-includes/js/") !== false)
      {
        $str = "Core";
      }
      else{
        $str = "External";
      }
      return $str;
		}
}
?>