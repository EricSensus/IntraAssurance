<?php

namespace Jenga\MyProject\Api\Library;

/**
 * Class JsonWriter
 * @package Jenga\MyProject\Api\Library
 */
class JsonWriter implements Writer
{

    /**
     * @param array $response
     * @return mixed
     */
    public function write($response)
    {
        return json_encode($response);
    }

    /**
     * Read the push data
     * @param $input
     * @return object
     */
    public function read($input)
    {
        return json_decode($input);
    }
}