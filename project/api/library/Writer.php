<?php

namespace Jenga\MyProject\Api\Library;
/**
 * Interface Writer
 */
interface Writer
{
    /**
     * @param array $response
     * @return mixed
     */
    public function write($response);

    /**
     * Read the push data
     * @param $input
     * @return object
     */
    public function read($input);
}