<?php
namespace Jenga\MyProject\Front\Controllers;

use Jenga\App\Request\Input;
use Jenga\App\Controllers\Controller;
use Jenga\App\Request\Session;
use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Users\Controllers\UsersController;

class FrontController extends Controller {
    /**
     * @var CustomersController
     */
    private $customerCtrl;
    /**
     * @var UsersController
     */
    private $userCtrl;

    //this is the default function
    public function index(){
    }
    
    /**
     * @acl\role guest
     */
    public function loadNavigation(){
    }
    
    public function showBanner(){
        
    }
    
    public function showProducts(){

    }

    public function signIn(){
        
        $this->setData();

        $loggedIn = $this->userCtrl->isCustomerloggedIn();
        $this->view->set('loggedIn', $loggedIn);

        $name = explode(' ', Session::get('customer_name'));
        $this->view->set('name', $name[0]);

        $modal_container = $this->customerCtrl->loadLoginContainer();
        $this->view->set('modal_container', $modal_container);
    }

    public function setData(){
        $this->customerCtrl = Elements::call('Customers/CustomersController');
        $this->userCtrl = Elements::call('Users/UsersController');
    }
}

