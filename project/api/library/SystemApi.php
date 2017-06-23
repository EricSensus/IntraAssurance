<?php

namespace Jenga\MyProject\Api\Library;

/**
 * Class SystemApi
 * @package Jenga\MyProject\Api\Library
 */
class SystemApi extends ApiRepository
{


    /**
     * Customer section
     * @return array|string
     */
    protected function customer()
    {
        if ($this->isMethod('get')) {
            return $this->getCustomer();
        } else {
            return $this->postCustomer();
        }
    }

    /**
     * Quotes section
     * @return \Jenga\App\Models\type|object|string
     */
    protected function quote()
    {
        if ($this->isMethod('get')) {
            return $this->getQuote();
        } else {
            return "Only accepts GET requests";
        }
    }

    /**
     * @return \Jenga\App\Models\type|object|string
     */
    protected function policy()
    {
        if ($this->isMethod('get')) {
            return $this->getPolicy();
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function claim()
    {
        if ($this->isMethod('get')) {
            return $this->getClaim();
        } else {
            return "Only accepts GET requests";
        }
    }

    protected function ping()
    {
        return 'OK';
    }
}