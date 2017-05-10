<?php

namespace Jenga\MyProject\Users\Controllers;

use Jenga\App\Controllers\Controller;
use Jenga\App\Helpers\Help;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Request\Url;
use Jenga\App\Views\Redirect;
use Jenga\MyProject\Elements;
use Jenga\MyProject\Users\Models\UsersModel;

/**
 * Class UsersController
 * @property-read UsersModel $model
 * @package Jenga\MyProject\Users\Controllers
 */
class UsersController extends Controller
{

    public function index()
    {

        if (is_null(Input::get('action')) && is_null(Input::post('action'))) {

            $action = 'manage';
        } else {

            if (!is_null(Input::get('action')))
                $action = Input::get('action');

            elseif (!is_null(Input::post('action')))
                $action = Input::post('action');
        }

        $this->$action();
    }

    public function setUserAttributes($attributes)
    {
        $this->user->setAttributes($attributes);
    }

    public function show()
    {
    }

    /**
     * Logs the user into the system
     */
    public function login(){

        $this->view->disable();
        $user = $this->model->check(Input::post('username'), Input::post('password'));
        
        if ($user === FALSE) {
            Redirect::withNotice('Invalid Username or Password', 'error')
                ->toDefault();
        } elseif ($user->enabled != 'yes') {
            Redirect::withNotice('Your account has been disabled. '
                . 'Please contact the administrator', 'error')
                ->toDefault();
        } else {

            //get the full names from the user profiles
            $data = $this->model->getUserFromProfile($user->user_profiles_id);

            //assign all the user attributes
            $attributes = [
                'id' => $user->id,
                'fullname' => $data->name,
                'username' => $user->username,
                'password' => $user->password,
                'acl' => $user->acl,
                'profileid' => $user->user_profiles_id,
                'loggedin' => time()
            ];

            $this->user()->mapAttributes($attributes);

            //attach role to user
            $role = $this->auth->getRoleByAlias($user->acl);
            $this->user()->attachRole($role);
            $this->user()->addPermissions($user->permissions);

            //check if user is also agent
            $agent = Elements::call('Agents/AgentsController')->getAgentByUserId($user->id);

            //set the session variables
            Session::add('logid', Session::id());
            Session::add('name', $data->name);
            Session::add('userid', $user->id);

            if (!is_null($agent))
                Session::add('agentsid', $agent->id);

            Session::add('accesslevels_id', $user->accesslevels_id);
            
            //redirect to destination
            Redirect::withNotice('You have been successfully logged in.', 'success')
                    ->to(Input::post('destination'));
        }
    }

    /**
     * Logs the user out of the system
     */
    public function logout(){

        $id = Input::get('sessid');

        if (!is_null($id)) {

            $this->auth->destroyUserState();

            $user = $this->model->find(Session::get('userid'));
            $user->last_login = time();

            $user->save();

            Session::destroy();
            Redirect::withNotice('You have been logged out')->toDefault();
        }
    }

    public function getAccessLevels($accesslevels_id = NULL)
    {
        return $this->model->getAccessLevels($accesslevels_id);
    }

    public function getCustomerName($id)
    {
        return $this->model->getUserFromProfile($id)->name;
    }

    public function getUser($id)
    {
        return $this->model->getUserWithProfile($id);
    }

    public function manage()
    {

        $users = $this->model->getUsers();

        foreach ($users as $user) {

            if ($user->user_profiles_id != 0) {

                $profile = $this->model->getUserFromProfile($user->user_profiles_id);

                $user->fullname = $profile->name;
                $user->type = 'Technical';
            } elseif (!is_null($user->insurer_agents_id)) {

                $profile = $this->model->getUserFromProfile($user->insurer_agents_id, 'agents', 'names');

                $user->fullname = $profile->names;
                $user->type = 'Agent';
            }

            $user->access = $this->model->getAccessLevels($user->accesslevels_id)->name;
            $user->login = date('d-m-y H:i', $user->last_login);

            $userslist[] = $user;
        }

        $this->view->userTable($userslist);
    }

    public function loginsEdit()
    {

        $user = $this->model->find(Input::get('id'))->data;

        if ($user->insurer_agents_id != 0) {

            $agent = $this->model->getUserFromProfile($user->insurer_agents_id, 'agents', '*');

            $login = $this->view->createLogin($user, true, 'users', 'agentid');

            $agents = Elements::call('Agents/AgentsController');
            $form = $agents->view->agentLoginForm($login, TRUE);
        } elseif ($user->user_profiles_id != 0) {

            $technical = $this->model->getUserFromProfile($user->user_profiles_id, 'user_profiles', '*');
            $login = $this->view->createLogin($user, true, 'users', 'userid');

            $form = $this->view->userLoginForm($login, TRUE);
        }

        echo $form;
    }

    public function saveLoginCredentials()
    {

        $this->view->disable();

        if (Input::post('apassword') == Input::post('cpassword')) {

            $user = $this->model->find(Input::post('id'));

            $user->username = Input::post('username');
            $user->password = Input::post('apassword');
            $user->accesslevels_id = Input::post('accesslevel');
            $user->enabled = (Input::post('enabled') != 'yes' ? 'no' : 'yes');
            $save = $user->save();

            if (!array_key_exists('ERROR', $save)) {

                Redirect::withNotice('The user login credentials have been saved', 'success')
                    ->to(Input::post('destination'));
            } else {
                echo $this->model->getLastQuery();
            }
        } else {

            Redirect::withNotice('Please enter your passwords correctly', 'error')
                ->to(Input::post('destination'));
        }
    }

    public function generatePassword()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }

        return implode($pass);
    }

    public function getAccessLevelByAlias($alias)
    {
        return $this->model->table('accesslevels')->where('alias', $alias)->first();
    }

    /**
     * Update or create new user
     * @param $data
     */
    public function setUserCredentials($data)
    {
        $user = $this->model->find(['username' => $data->email]);
        $user->username = $data->email;
        $user->password = md5($data->new_password);
        $user->accesslevels_id = $this->getAccessLevelByAlias('subscriber')->id;
        $user->enabled = $data->enabled == 'yes' ? 'yes' : 'no';
        $user->save();
        if (!empty($data->send_email)) {
            $content = '<p>Dear ' . $data->customer_name . '</p>';
            $content .= '<p>Your password was changed!</p>';
            $content .= '<p>Please use the following login credentials next</p>';
            $content .= '<p>Username: <strong>' . $user->username . '</strong></p>';
            $content .= '<p>Password: <strong>' . $data->new_password . '</strong></p>';
            $content .= 'Regards!';
            $this->sendEmail($data->email, $data->customer_name, 'New login credentials', $content);
        }
        return $user->last_altered_row;
    }

    public function createLoginAccountRemotely($data = [])
    {
        // generate alphanumeric random password
        $plain_pass = $this->generatePassword();

        $access_level_id = $this->getAccessLevelByAlias('subscriber')->id;
        $this->logInfo('Access Level Id: ' . $access_level_id);

        $user = $this->model->find(['username' => $data['email']]);
        $user->username = $data['email'];
        $user->password = md5($plain_pass);
        $user->accesslevels_id = $access_level_id;
        $user->enabled = 'yes';
        $user->customers_id = $data['customer_id'];
        $user->save();

        if ($user->hasNoErrors()) {
            $hash_user_id = Help::encrypt($user->last_altered_row);
            $verification_link = Url::link('/customer/verifyemail/' . $data['element'] . '/' . $hash_user_id);
            $this->logInfo('Created User');
            $this->logInfo('Plain Pass:' . $plain_pass);
            $this->logInfo($verification_link);

            // send verification email

            $content = '<p>Dear ' . $data['customer_name'] . '</p>';
            $content .= '<p>Thank you for registering!</p>';
            $content .= '<p>Please click the following link to verify your email: </p>';
            $content .= '<p>' . $verification_link . '</p>';
            $content .= '<p>Please use the following login credentials to proceed to step 2</p>';
            $content .= '<p>Username: <strong>' . $user->username . '</strong></p>';
            $content .= '<p>Password: <strong>' . $plain_pass . '</strong></p>';
            $content .= 'Welcome!';

            $sent = $this->sendEmail($data['email'], $data['customer_name'], 'Email Verification', $content);

            if ($sent) {

            }

            Session::flash('sent_confirmation', 'Verify your Email first! A confirmation email has been sent with your login credentials');
            return $user->last_altered_row;
        } else {
            $this->logInfo($this->model->getLastError());
        }
        return false;
    }

    public function sendEmail($recipient_email, $recipient_name, $subject, $content)
    {
        // get the insurer details
        $insurer = Elements::call('Insurers/InsurersController')->model
            ->where('email_address', 'info@jubilee.co.ke')->first();

        $mail = new \PHPMailer();

        //Set who the message is to be sent from
        $mail->setFrom('info@bima247.com', $insurer->official_name);

        //Set an alternative reply-to address
        $mail->addReplyTo('info@bima247.com', $insurer->official_name);

        //Set who the message is to be sent to
        $mail->addAddress($recipient_email, $recipient_name);

        //Set the subject line
        $mail->Subject = $subject;

        $mail->msgHTML($content);

//        $this->view->disable();
        $status = $mail->send();
        return $status;
    }

    /**
     * Initiated by the customer from his/her email
     * @param $hash_user_id
     * @return bool
     */
    public function verifyEmail($hash_user_id)
    {

        $user_id = Help::decrypt($hash_user_id);

        $user = $this->model->find($user_id);
        $user->verified = 1;
        $user->save();

        if ($user->hasNoErrors()) {
            // get customer name
            $customer = Elements::call('customers/CustomersController')->model->find([
                'email' => $user->username
            ]);

            Session::set('customer_email', $user->username);
            Session::set('verified', true);

            $route = URL::route('/{element}/step/1', ['element' => Input::get('element')]);

            Redirect::withNotice('Your email has been successfully verified. Please login to continue.', 'success')
                ->to($route);
        }
    }

    public function checkIfVerified($username)
    {
        return $this->model->isVefified($username);
    }

    /**
     * Log the customer in and allow to proceed to step 2
     */
    public function logInCustomer()
    {

        // validate credentials
        $user = $this->model->check(Input::post('username'), Input::post('password'));

        // get customer name by email
        $customer_name = Elements::call('Customers/CustomersController')
            ->model->where('email', $user->username)->first()->name;

        if ($user === FALSE) {
            Redirect::withNotice('Invalid Username or Password', 'error')
                ->toDefault();
        } elseif ($user->enabled != 'yes') {
            Redirect::withNotice('Your account has been disabled. '
                . 'Please contact the administrator', 'error')
                ->to(Input::post('destination'));
        } else {

            $attributes = [
                'user_id' => $user->id,
                'customer_name' => $customer_name,
                'acl' => $user->acl,
                'loggedin' => time()
            ];

            $this->user()->mapAttributes($attributes);

            //attach role to user
            $role = $this->auth->getRoleByAlias($user->acl);
            $this->user()->attachRole($role);
            
            $this->user()->addPermissions($user->permissions);

            Session::set('user_id', $user->id);
            Session::set('customer_name', $customer_name);
            Session::set('accesslevels_id', $user->accesslevels_id);
            Session::set('customer_id', $user->customers_id);
            Session::add('logid', Session::id());

            // redirect user to step two
            Redirect::withNotice('You have been successfully logged in.', 'success')
                ->to(Input::post('destination'));
        }
    }

    public function isCustomerloggedIn()
    {
        if (Session::has('user_id') && !is_null(Session::get('user_id')))
            return true;
        return false;
    }

    public function logInfo($text)
    {
        $log_file = PROJECT_PATH . '/uploads/log' . date('Y-m-d H:i:s') . '_file.log';

        file_put_contents($log_file, $text . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

