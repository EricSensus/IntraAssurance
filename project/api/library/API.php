<?php

namespace Jenga\MyProject\Api\Library;

use function DI\object;
use Jenga\App\Request\Input;
use Jenga\MyProject\Api\Controllers\ApiController;
use Jenga\MyProject\Claims\Controllers\ClaimsController;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Policies\Controllers\PoliciesController;
use Jenga\MyProject\Quotes\Controllers\QuotesController;

/**
 * Class API
 * @property-read CustomersController $_customer
 * @property-read QuotesController $_quote
 * @property-read ClaimsController $_claim
 * @property-read PoliciesController $_policy
 * @property-read ApiController $_api
 * @package Jenga\MyProject\Api\Library
 */
abstract class API
{

    /**
     * @var string
     * The HTTP method this request was made in, either GET, POST, PUT or DELETE
     */
    protected $method;
    /**
     * @var string
     * The section requested in the URI. eg: /api/<customers?
     */
    public $section;
    /**
     * @var string
     * An optional additional descriptor about the section, used for things that can
     * not be handled by the basic methods. eg: /quotes/<unprocessed>
     */
    protected $verb = null;
    /**
     * @var  int
     * Any additional URI components after the section and verb have been removed, in our
     * case, an integer ID for the resource. eg: /quotes/active/<id>
     */
    protected $id;
    /**
     * @var string
     * The requested action: maybe PULL,PUSH
     */
    public $action;
    /**
     * @var string The access token
     */
    private $token;

    /**
     * @var array
     */
    public $request;
    /**
     * @var \stdClass
     */
    public $bearer;
    /**
     * @var bool
     */
    protected $single = false;
    /**
     * @var bool
     */
    private $json = true;
    /**
     * @var Writer
     */
    private $writer;
    /**
     * @var object
     */
    public $payload;

    /**
     * API constructor.
     */
    public function __construct()
    {
        $this->setCorsHeaders();
        $this->getServerRequestMethod();
        $this->getParameters();
        $this->inflateVars();
        $this->authenticateToken();
    }

    /**
     * Get URL parameters
     * @throws ApiExceptions
     */
    private function getParameters()
    {
        $this->token = Input::request('token');
        $this->section = Input::request('section');
        $this->action = Input::request('action');
        $this->id = Input::request('id');
        $this->single = !empty($this->id);
        foreach (['token', 'action', 'section'] as $item) {
            if (empty($this->{$item})) {
                throw new ApiExceptions("Missing " . $item . " in the URL", 400);
            }
        }
    }

    /**
     * Load common elements
     */
    private function inflateVars()
    {
        $this->_customer = Elements::call('Customers/CustomersController');
        $this->_quote = Elements::call('Quotes/QuotesController');
        $this->_policy = Elements::call('Policies/PoliciesController');
        $this->_claim = Elements::call('Claims/ClaimsController');
        $this->_api = Elements::call('Api/ApiController');
    }

    /**
     * Get the Request information
     * @throws \Exception
     */
    private function getServerRequestMethod()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        if ($this->method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $this->method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $this->method = 'PUT';
            } else {
                throw new \Exception("Unexpected Header");
            }
        }
        switch ($this->method) {
            case 'POST':
            case 'GET':
                $this->request = $this->_cleanInputs(Input::request());
                break;
            default:
                throw new ApiExceptions("Invalid Request Method", 405);
                break;
        }
    }

    /**
     * Check the request method
     * @param $method
     * @return bool
     */
    protected function isMethod($method)
    {
        return $this->method === strtoupper($method);
    }

    private function additionalContentHeaders()
    {
        if ($this->json) {
            header("Content-Type: application/json");
        } else {
            header("Content-type: application/xml");
        }
    }

    /**
     * Set or modify headers for php
     */
    protected function setCorsHeaders()
    {
        header("Access-Control-Allow-Orgin: *");
        header("Access-Control-Allow-Methods: *");
    }

    /**
     * Handle api request
     * @return string
     * @throws ApiExceptions
     */
    public function processApi()
    {
        if (method_exists($this, $this->section)) {
            return $this->_response($this->{$this->section}());
        }
        throw new ApiExceptions("Unknown section $this->section", 400);
    }

    /**
     * Trim and strip tags
     * @param $data
     * @return string
     */
    private function _cleanInputs($data)
    {
        $clean_input = null;
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $clean_input[$k] = $this->_cleanInputs($v);
            }
            return (object)$clean_input;
        } else {
            return trim(strip_tags($data));
        }
    }

    /**
     * Format response and send to client
     * @param $data
     * @param int $status
     * @return string
     */
    private function _response($data, $status = 200)
    {
        $this->removeNullFields($data);
        $this->getWriter();
        header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
        //$this->additionalContentHeaders();
        $this->payload = (object)['code' => $status, 'status' => $this->_requestStatus($status)];
        $response = ['code' => $status, 'status' => $this->_requestStatus($status), 'data' => $data];
        return $this->writer->write($response);
    }

    /**
     * Not necessary to display null values
     * @param $haystack
     * @return object
     */
    private function removeNullFields(&$haystack)
    {
        $haystack = json_decode(json_encode($haystack), true);
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->removeNullFields($haystack[$key]);
            }
            if (empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }
        return (object)$haystack;
    }

    /**
     * Get the writer instance
     * @return mixed
     * @throws ApiExceptions
     */
    private function getWriter()
    {
        $format = $this->bearer->format;
        $class = null;
        switch ($format) {
            case 'xml':
                $class = XmlWriter::class;
                $this->json = false;
                break;
            case 'json':
                $class = JsonWriter::class;
                break;
        }
        if (class_exists($class)) {
            $this->writer = new $class;
        } else {
            throw new ApiExceptions('Unsupported format', 500);
        }
    }

    /**
     * Send a status code for response
     * @param $code
     * @return mixed
     */
    private function _requestStatus($code)
    {
        $status = [
            200 => 'OK',
            201 => "Created",
            204 => "No Content",
            304 => 'Not modified',
            400 => "Bad Request",
            401 => "Unauthorised",
            403 => "Forbidden",
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            500 => 'Internal Server Error',
        ];
        return ($status[$code]) ? $status[$code] : $status[500];
    }

    /**
     * @throws ApiExceptions
     */
    private function authenticateToken()
    {
        $this->bearer = $this->_api->findBearer($this->token);
        if (empty($this->bearer)) {
            throw new ApiExceptions("Invalid Bearer Token", 401);
        }
    }

}