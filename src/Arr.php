<?php
// Array helper
namespace AppZz\Helpers;
class Arr {
    /**
     * Get value by key
     * @param  array  $array
     * @param  mixed $key
     * @param  mixed $default
     * @return mixed
     */
	public static function get ($array = array (), $key, $default = NULL)	{
		$array = (array) $array;
		return ( isset ( $array[$key] ) ? $array[$key] : $default );
	}

    /**
     * Get value by path
     * @param  array  $array
     * @param  string $path
     * @param  string $sep separator
     * @param  mixed $default
     * @return mixed
     */
	public static function path ($array = array (), $path, $sep = '.', $default = NULL) {    
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