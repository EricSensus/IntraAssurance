<?php

namespace Jenga\MyProject\Api\Library;

/**
 * Class XMLSerializer
 * @package Jenga\MyProject\Api\Library
 */
class XMLSerializer
{
    /**
     * @param  array|\stdClass $obj
     * @param string $node_block
     * @param string $node_name
     * @return string
     */
    public static function generateValidXmlFromObj($obj, $node_block = 'res', $node_name = 'customer')
    {
        $arr = json_decode(json_encode($obj), true);
        return self::generateValidXmlFromArray($arr, $node_block, $node_name);
    }

    /**
     * Generate xml from array
     * @param array $array
     * @param string $node_block
     * @param string $node_name
     * @return string
     */
    public static function generateValidXmlFromArray($array, $node_block = 'nodes', $node_name = 'node')
    {
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>';
        $xml .= '<' . $node_block . '>';
        $xml .= self::generateXmlFromArray($array, $node_name);
        $xml .= '</' . $node_block . '>';
        return $xml;
    }

    /**
     * @param $array
     * @param $node_name
     * @return string
     */
    private static function generateXmlFromArray($array, $node_name)
    {
        $xml = '';
        if (is_array($array) || is_object($array)) {
            foreach ($array as $key => $value) {
                if (is_numeric($key)) {
                    $key = $node_name;
                }

                $xml .= '<' . $key . '>' . self::generateXmlFromArray($value, $node_name) . '</' . $key . '>';
            }
        } else {
            $xml = htmlspecialchars($array, ENT_QUOTES);
        }
        return $xml;
    }

    /**
     *
     * The most advanced method of serialization.
     *
     * @param mixed $obj => can be an object, an array or string. may contain unlimited number of subobjects and subarrays
     * @param string $wrapper => main wrapper for the xml
     * @param array (key=>value) $replacements => an array with variable and object name replacements
     * @param boolean $add_header => whether to add header to the xml string
     * @param array (key=>value) $header_params => array with additional xml tag params
     * @param string $node_name => tag name in case of numeric array key
     * @return string
     */
    public static function generateValidXmlFromMixedObj($obj, $wrapper = null, $replacements = [], $add_header = true, $header_params = [], $node_name = 'node')
    {
        $xml = '';
        if ($add_header)
            $xml .= self::generateHeader($header_params);
        if ($wrapper != null) $xml .= '<' . $wrapper . '>';
        if (is_object($obj)) {
            $node_block = strtolower(get_class($obj));
            if (isset($replacements[$node_block])) $node_block = $replacements[$node_block];
            $xml .= '<' . $node_block . '>';
            $vars = get_object_vars($obj);
            if (!empty($vars)) {
                foreach ($vars as $var_id => $var) {
                    if (isset($replacements[$var_id])) $var_id = $replacements[$var_id];
                    $xml .= '<' . $var_id . '>';
                    $xml .= self::generateValidXmlFromMixedObj($var, null, $replacements, false, null, $node_name);
                    $xml .= '</' . $var_id . '>';
                }
            }
            $xml .= '</' . $node_block . '>';
        } else if (is_array($obj)) {
            foreach ($obj as $var_id => $var) {
                if (!is_object($var)) {
                    if (is_numeric($var_id))
                        $var_id = $node_name;
                    if (isset($replacements[$var_id])) $var_id = $replacements[$var_id];
                    $xml .= '<' . $var_id . '>';
                }
                $xml .= self::generateValidXmlFromMixedObj($var, null, $replacements, false, null, $node_name);
                if (!is_object($var))
                    $xml .= '</' . $var_id . '>';
            }
        } else {
            $xml .= htmlspecialchars($obj, ENT_QUOTES);
        }
        if ($wrapper != null) $xml .= '</' . $wrapper . '>';
        return $xml;
    }

    /**
     * @param array $params
     * @return string
     */
    public static function generateHeader($params = array())
    {
        $basic_params = array('version' => '1.0', 'encoding' => 'UTF-8');
        if (!empty($params))
            $basic_params = array_merge($basic_params, $params);
        $header = '<?xml';
        foreach ($basic_params as $k => $v) {
            $header .= ' ' . $k . '=' . $v;
        }
        $header .= ' ?>';
        return $header;
    }
}