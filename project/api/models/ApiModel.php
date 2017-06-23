<?php

namespace Jenga\MyProject\Api\Models;

use Jenga\App\Models\ORM;

/**
 * Class ApiModel
 * @package Jenga\MyProject\Api\Models
 */
class ApiModel extends ORM
{
    /**
     * @var string
     */
    public $table = 'api_tokens';

    /**
     * Get the bearer
     * @param $token
     * @return object
     */
    public function findBearer($token)
    {
        return $this->where('token', $token)->first();
    }

    /**
     * Get model by id
     * @param $id
     * @return object
     */
    public function findById($id)
    {
        return $this->where('id', $id)->first();
    }

    /**
     * Get log for app
     * @param $app_id
     * @return object
     */
    public function getTheLogs($app_id)
    {
        return $this->table('esu_api_logs')->where('app_id', $app_id)->get();
    }

    /**
     * Save activity log
     * @param $data
     * @return mixed
     */
    public function logActivity($data)
    {
        $model = $this->table('esu_api_logs');
        foreach ($data as $item => $value) {
            $model->{$item} = $value;
        }
        return $model->save();
    }
}