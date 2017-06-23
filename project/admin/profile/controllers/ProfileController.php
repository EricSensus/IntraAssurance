<?php
namespace Jenga\MyProject\Profile\Controllers;

use Jenga\App\Request\Url;
use Jenga\App\Request\Session;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Customers\Controllers\CustomersController;
use Jenga\MyProject\Elements;

class ProfileController extends Controller{
    /**
     * @var CustomersController
     */
    private $customer_ctrl;

    /**
     * Loads the customer's dashboard
     */
    public function index(){

    }

    public function setData(){
        $this->customer_ctrl = Elements::call('Customers/CustomersController');
    }

    public function profileLoginForm(){
        $this->setData();
        
        $destination = $_SERVER['HTTP_REFERER'];

        $login_form = $this->customer_ctrl->view->customerLoginForm(null, $destination);
        $login_form .= $this->customer_ctrl->view->customerForgotPassForm();
        echo $login_form;
    }

    public function myProfile(){
        Elements::call('Customers/CustomersController')
            ->getCustomerProfile(Session::get('customer_id'));
    }
}
