<?php
/**
 * Array helper, inspared by Kohana Wramework
 * @package AppZz/Helpers/Arr
 * @version 1.x
 *
 **/
namespace AppZz\Helpers;

class Arr {

    /**
     * @var  string  default delimiter for path()
     */
    public static $delimiter = '.';

    /**
     * Test if a value is an array with an additional check for array-like objects.
     * @param   mixed   $value  value to check
     * @return  boolean
     */
    public static function is_array($value)
    {
        if (is_array($value))
        {
            return TRUE;
        }
        else
        {
            return (is_object($value));
        }
    }

    /**
     * Tests if an array is associative or not.
     *
     * @param   array   $array  array to check
     * @return  boolean
     */
    public static function is_assoc(array $array)
    {
        $keys = array_keys($array);
        return array_keys($keys) !== $keys;
    }

    /**
     * Retrieve a single key from an array. If the key does not exist in the
     *
     * @param   array   $array      array to extract from
     * @param   string  $key        key name
     * @param   mixed   $default    default value
     * @return  mixed
     */
    public static function get($array, $key, $default = NULL)
    {
        if ($array instanceof ArrayObject) {
            return $array->offsetExists($key) ? $array->offsetGet($key) : $default;
        } else {
            return isset($array[$key]) ? $array[$key] : $default;
        }
    }

    /**
     * Gets a value from an array using a dot separated path.
     * @param   array   $array      array to search
     * @param   mixed   $path       key path string (delimiter separated) or array of keys
     * @param   mixed   $default    default value if the path is not set
     * @param   string  $delimiter  key path delimiter
     * @return  mixed
     */
    public static function path($array, $path, $delimiter = NULL, $default = NULL)
    {
        if ( ! Arr::is_array($array))
        {
            return $default;
        }

        if (is_array($path))
        {
            $keys = $path;
        }
        else
        {
            if (array_key_exists($path, $array))
            {
                return $array[$path];
            }

            if ($delimiter === NULL)
            {
                $delimiter = Arr::$delimiter;
            }

            $path = ltrim($path, "{$delimiter} ");

            $path = rtrim($path, "{$delimiter} *");

            $keys = explode($delimiter, $path);
        }

        do
        {
            $key = array_shift($keys);

            if (ctype_digit($key))
            {
                $key = (int) $key;
            }

            if (isset($array[$key]))
            {
                if ($keys)
                {
                    if (Arr::is_array($array[$key]))
                    {
                        $array = $array[$key];
                    }
                    else
                    {
                        break;
                    }
                }
                else
                {
                    return $array[$key];
                }
            }
            elseif ($key === '*')
            {
                $values = [];
                foreach ($array as $arr)
                {
                    if ($value = Arr::path($arr, implode('.', $keys)))
                    {
                        $values[] = $value;
                    }
                }

                if ($values)
                {
                    return $values;
                }
                else
                {
                    break;
                }
            }
            else
            {
                break;
            }
        }
        while ($keys);

        return $default;
    }

    /**
    * Set a value on an array by path.
    *
    * @see Arr::path()
    * @param array   $array     Array to update
    * @param string  $path      Path
    * @param mixed   $value     Value to set
    * @param string  $delimiter Path delimiter
    */
    public static function set_path( & $array, $path, $value, $delimiter = NULL)
    {
        if ( ! $delimiter)
        {
            $delimiter = Arr::$delimiter;
        }

        $keys = $path;

        if ( ! is_array($path))
        {
            $keys = explode($delimiter, $path);
        }

        while (count($keys) > 1)
        {
            $key = array_shift($keys);

            if (ctype_digit($key))
            {
                $key = (int) $key;
            }

            if ( ! isset($array[$key]))
            {
                $array[$key] = [];
            }

            $array = & $array[$key];
        }

        $array[array_shift($keys)] = $value;
    }

    /**
     * Retrieves multiple paths from an array.
     *
     * @param   array  $array    array to extract paths from
     * @param   array  $paths    list of path
     * @param   mixed  $default  default value
     * @return  array
     */
    public static function extract($array, array $paths, $default = NULL)
    {
        $found = [];
        foreach ($paths as $path)
        {
            Arr::set_path($found, $path, Arr::path($array, $path, $default));
        }

        return $found;
    }

    /**
     * Retrieves muliple single-key values from a list of arrays.
     * @param   array   $array  list of arrays to check
     * @param   string  $key    key to pluck
     * @return  array
     */
    public static function pluck($array, $key)
    {
        $values = [];

        foreach ($array as $row)
        {
            if (isset($row[$key]))
            {
                $values[] = $row[$key];
            }
        }

        return $values;
    }

    /**
     * Adds a value to the beginning of an associative array.
     *
     * @param   array   $array  array to modify
     * @param   string  $key    array key name
     * @param   mixed   $val    array value
     * @return  array
     */
    public static function unshift( array & $array, $key, $val)
    {
        $array = array_reverse($array, TRUE);
        $array[$key] = $val;
        $array = array_reverse($array, TRUE);

        return $array;
    }

    /**
     * Convert a multi-dimensional array into a single-dimensional array.
     *
     * @param   array   $array  array to flatten
     * @return  array
     */
    public static function flatten($array)
    {
        $is_assoc = Arr::is_assoc($array);

        $flat = [];
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                $flat = array_merge($flat, Arr::flatten($value));
            }
            else
            {
                if ($is_assoc)
                {
                    $flat[$key] = $value;
                }
                else
                {
                    $flat[] = $value;
                }
            }
        }
        return $flat;
    }

    /**
     * Force convert array|object to array.
     *
     * @param   array   $obj
     * @return  array
     */
    public static function obj2array ($obj)
    {
        if (is_object($obj) or is_array($obj))
        {
            return json_decode(json_encode($obj), TRUE);
        }

        return $obj;
    }

    /**
     * Convert text to array
     * @param  string $text
     * @param  string $delimiter
     * @return mixed
     */
    public static function text2array ($text, $delimiter = ',')
    {
        if (is_string($text))
        {
            if (mb_strpos($text, $delimiter) !== FALSE)
            {
                $text = rtrim($text, $delimiter);
                $array = explode($delimiter, $text);

                if ( ! empty ($array))
                {
                    $array = array_map('trim', $array);
                }
            }
            else
            {
                $array = (array) trim ($text);
            }

            return $array;
        }

        return FALSE;
    }

    /**
     * Create pairs array.
     *
     * @param   array   $array
     * @param   boolean $reverse
     * @param   array   $fields
     * @return  array
     */
    public static function pairs(array $array = array(), $reverse = FALSE, $fields = [])
    {
        $pairs = array ();

        foreach ($array as $values)
        {
            if (Arr::is_assoc($values))
            {
                if ( ! empty ($fields))
                {
                    $values = Arr::extract ($values, $fields);
                }
                else
                {
                    $values = array_slice($values, 0, 2);
                }

                $keys = array_keys ($values);

                if ($reverse)
                {
                    $keys = array_reverse($keys);
                }

                $key = Arr::get($values, Arr::get($keys, 0));
                $value = Arr::get($values, Arr::get($keys, 1));

                $pairs[$key] = $value;
            }
        }

        return $pairs;
    }

    /**
     * Create key from values to key of array
     * @param  array   $array
     * @param  string  $field
     * @param  boolean $keep
     * @return mixed
     */
    public static function field2key (array $array, $field, $keep = TRUE)
    {
        if (empty($field))
        {
            return FALSE;
        }

        $ret = array ();

        foreach ($array as $value)
        {
            $value = (array) $value;
            $new_key = Arr::get ($value, $field);

            if ($new_key)
            {
                if ($keep === FALSE)
                {
                    unset($value[$field]);
                }

                $ret[$new_key] = $value;
            }
        }

        return $ret;
    }

    /**
     * Inject assoc arrays.
     * @param  array   $array_dst dest array to inject
     * @param  array   $array_src source array to inject 
     * @param  $field  key from array_dst
     * @param  boolean $before inject before or after
     * @return mixed
     */
    public static function inject ($array_dst = array (), $array_src = array (), $field, $before = TRUE)
    {
        $offset = 0;

        foreach ($array_dst as $key=>$value)
        {
            if ($key == $field)
            {
                break;
            }

            $offset++;
        }

        if ($before === FALSE)
        {
            $offset++;
        }

        return array_merge (array_splice ($array_dst, 0, $offset, []), $array_src, $array_dst);
    }
}
