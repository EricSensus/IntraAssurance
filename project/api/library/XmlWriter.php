<?php

namespace Jenga\MyProject\Api\Library;

/**
 * Class XmlWriter
 * @package Jenga\MyProject\Api\Library
 */
class XmlWriter implements Writer
{

    /**
     * @param array $response
     * @return mixed
     */
    public function write($response)
    {
        return XMLSerializer::generateValidXmlFromObj($response);
    }

    /**
     * Read the push data
     * @param $input
     * @return object
     */
    public function read($input)
    {
        return simplexml_load_string($input);
    }
}