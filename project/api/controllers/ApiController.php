<?php

namespace Jenga\MyProject\Api\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Api\Library\ApiExceptions;
use Jenga\MyProject\Api\Library\Logger;
use Jenga\MyProject\Api\Library\SystemApi;
use Jenga\MyProject\Api\Models\ApiModel;
use Jenga\MyProject\Api\Views\ApiView;
use Jenga\MyProject\Elements;

/**
 * Class ApiController
 *
 * @property-read ApiModel $model
 * @property-read ApiView $view
 *
 * @package Jenga\MyProject\Api\Controllers
 */
class ApiController extends Controller
{
    /**
     * Common entry gateway
     */
    public function index()
    {
        $this->view->disable();
        if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
        }
        try {
            $api = new SystemApi();
            $response = $api->processAPI();
        } catch (ApiExceptions $e) {
            $response = json_encode(['error' => $e->getMessage(), 'code' => $e->getCode()]);
        }
        try {
            Logger::log($response, $api);
        } catch (\Exception $exception) {
            //could not log
        }
        echo $response;
    }

    /**
     * Get the bearer
     * @param string $token
     * @return object
     */
    public function findBearer($token)
    {
        return $this->model->findBearer($token);
    }

    /**
     * Display tokens at setup
     */
    public function getTokens()
    {
        $tokens = $this->model->show();
        $this->view->tokensDisplay($tokens);
    }

    /**
     * New token form
     */
    public function add()
    {
        $insurers = Elements::call('Insurers/InsurersController')->getInsurer();
        $company = [];
        foreach ($insurers as $_company) {
            $company[$_company->id] = $_company->name;
        }
        $this->view->addNewTokenForm($company);
    }

    /**
     * Delete / revoke access tokens
     */
    public function revoke()
    {
        $this->view->disable();
        if (Input::has('ids')) {
            $ids = Input::post('ids');
            foreach ($ids as $id) {
                $this->model->where('id', '=', $id)->delete();
            }
        }
        Redirect::withNotice('The tokens(s) have been revoked', 'success')
            ->to('/admin/setup');
    }

    /**
     * Save a new token
     */
    public function savetoken()
    {
        $this->view->disable();
        $insurers = (object)Elements::call('Insurers/InsurersController')
            ->getInsurer(Input::post('company'));
        $token = $this->model;
        $token->name = Input::post('name');
        $token->token = $this->generateAccessTokens();
        $token->format = Input::post('format');
        $token->company = $insurers->name;
        $token->save();
        if ($token->hasNoErrors())
            Redirect::to('/admin/setup')->withNotice('New token was generated');
        else
            Redirect::to('/admin/setup')->withNotice('Token could not be created', 'danger');
    }

    /**
     * Get the logs
     */
    public function logs()
    {
        $id = Input::get('id');
        $data = (object)[];
        $data->token = $this->model->findById($id);
        $data->logs = $this->model->getTheLogs($data->token->id);
        $this->view->showLogs($data);
    }

    /**
     * Log an activity
     * @param $data
     * @return mixed
     */
    public function logActivity($data)
    {
        return $this->model->logActivity($data);
    }

    /**
     * A more secure api
     * @return string
     */
    private function generateAccessTokens()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}
