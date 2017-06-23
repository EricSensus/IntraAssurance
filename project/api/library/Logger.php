<?php

namespace Jenga\MyProject\Api\Library;

use Jenga\MyProject\Api\Controllers\ApiController;
use Jenga\MyProject\Elements;

/**
 * Class Logger
 * @package Jenga\project\api\library
 */
class Logger
{
    /**
     * Log all requests
     * @param $response
     * @param API $api
     * @return
     * @internal param ApiController $api_controller
     */
    public static function log($response, $api = null)
    {
        $data = [];
        $data['response'] = $response;
//        $data['time']=
        if (!empty($api)) {
            $data['endpoint'] = $api->section;
            $data['app_id'] = $api->bearer->id;
            if (!empty($api->payload)) {
                $data['status'] = $api->payload->code . ' ' . $api->payload->status;
            } else {
                $res = json_decode($response);
                $data['status'] = $res->code . ' ' . $res->error;
            }
            $data['format'] = $api->bearer->format;
            $data['type'] = $api->action;
        }
        $api_controller = Elements::call('Api/ApiController');
        return $api_controller->logActivity($data);
    }
}