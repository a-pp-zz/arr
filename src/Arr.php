<?php
namespace AppZz\Helpers;

class Arr {
	public static function get ( $array = array (), $key, $default = NULL )	{
		$array = (array) $array;
		return ( isset ( $array[$key] ) ? $array[$key] : $default );
	}

	public static function path ( $array = array (), $path, $sep = '.', $default = NULL ) {    
        if ( !$path )
            return $default;
        $array = (array) $array;  
        $segments = is_array($path) ? $path : explode($sep, $path);
        $cur = &$array;
        foreach ($segments as &$segment) {
            if ( !isset ($cur[$segment]) )
                return $default;
            $cur = $cur[$segment];
        }
        return $cur;
    }	
}