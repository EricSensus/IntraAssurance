<?php

namespace Jenga\MyProject\Users\Controllers;

use Carbon\Carbon;
use Jenga\App\Core\App;
use Jenga\App\Request\Url;
use Jenga\App\Helpers\Help;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Request\Session;
use Jenga\App\Views\Notifications;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Elements;
use Jenga\MyProject\Users\Models\UsersModel;
use Jenga\MyProject\Users\Views\UsersView;

/**
 * Class UsersController
 * @property-read UsersModel $model
 * @property-read UsersView $view
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

    /**
     * Add or edit a new user based on the acl
     *
     * @param type $acl
     * @param type $id
     */
    public function addedit($acl, $id)
    {

        if ($acl == ('superadmin' || 'admin')) {
            $user = $this->model->getUserFromProfile($id, 'user_profiles', '*');
        }

        $this->view->showProfileForm($user);
    }

    public function saveFullProfile()
    {

        //login data
        parse_str(Input::post('logindata'), $login);

        //profile data
        parse_str(Input::post('profiledata'), $profile);

        $response = $this->saveProfile($profile);
        $response .= $this->saveLogin($login);

        echo $response;
    }

    /**
     * Save the user login details
     *
     * @param type $data
     * @return type
     */
    public function saveLogin($data)
    {

        if ($data['acl'] == ('superadmin' || 'admin')) {
            $login = $this->model->find(['user_profiles_id' => $data['id']]);
        }

        //username
        $login->username = $data['username'];

        //check password
        if ($data['new_pass'] != '') {
            if ($data['new_pass'] != $data['confirm_pass']) {
                return Notifications::Alert('Please reenter your password', 'error', true);
            }

            $login->password = md5($data['new_pass']);
        }

        //acl
        $login->acl = $data['acl'];

        //save login details
        $login->save();

        if ($login->hasNoErrors()) {
            return Notifications::Alert('The login details have been saved', 'success', true);
        }
    }

    /**
     * Saves the user profile details
     *
     * @param type $data
     */
    public function saveProfile($data)
    {

        if ($data['acl'] == ('superadmin' || 'admin')) {
            $profile = $this->model->table('user_profiles')->find($data['id']);
        }

        $profile->name = $data['names'];
        $profile->mobile_no = $data['mobileno'];
        $profile->email = $data['email'];
        $profile->date_of_birth = $data['postcode'];
        $profile->postal_address = $data['post'];

        //save login details
        $profile->save();

        if ($profile->hasNoErrors()) {
            return Notifications::Alert('The user profile details have been saved', 'success', true);
        }
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
    public function login()
    {

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
                'loggedin' => time(),
                'insurer_agents_id' => $user->insurer_agents_id
            ];

            $this->user()->mapAttributes($attributes);

            //attach role to user
            $role = $this->auth->getRoleByAlias($user->acl);
            $this->user()->attachRole($role);
            $this->user()->addPermissions($user->permissions);

            //check if user is also agent
            if ($user->acl == 'agent') {

                $agent = Elements::call('Agents/AgentsController')->getAgentByUserId($user->id);

                if (!is_null($agent))
                    Session::add('agentsid', $agent->id);
            }

            //set the session variables
            Session::add('logid', Session::id());
            Session::add('name', $data->name);
            Session::add('userid', $user->id);

            Session::add('accesslevels_id', $user->accesslevels_id);

            //redirect to destination
            Redirect::withNotice('You have been successfully logged in.', 'success')
                ->to(Input::post('destination'));
        }
    }

    /**
     * Logs the user out of the system
     */
    public function logout()
    {

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

    /**
     * Navigate to user details
     * @param $cust_id
     * @return object
     */
    public function getCustomerUserInfo($cust_id)
    {
        return $this->model->where('customers_id', $cust_id)->first();
    }

    /**
     * Navigate to user details
     * @param int $cust_id
     * @return object
     */
    public function getAgentUserInfo($cust_id)
    {
        return $this->model->where('insurer_agents_id', $cust_id)->first();
    }

    public function getUser($id)
    {
        return $this->model->getUserWithProfile($id);
    }

    public function manage()
    {

        $users = $this->model->getUsers();

        foreach ($users as $user) {

            //get full name from acl
            if ($user->acl == ('superadmin' || 'admin')) {

                $id = $user->user_profiles_id;
                $searchtable = 'user_profiles';
                $return = 'name';
            } elseif ($user->acl == 'agent') {

                $id = $user->insurer_agents_id;
                $searchtable = 'insurer_agents';
                $return = 'names';
            }

            $fulluser = $this->model->getUserFromProfile($id, $searchtable, $return);

            $user->fullname = $fulluser->{$return};
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

    /**
     * Sets login credentials for agent
     */
    public function saveLoginCredentials()
    {
        $this->view->disable();

        if (Input::post('apassword') == Input::post('cpassword')) {
            $plain_pass = Input::post('apassword');
            $username = Input::post('username');

            $agent = Elements::call('Agents/AgentsController')
                ->model->where('email_address', Input::post('username'))
                ->first();

            $user = $this->model->find([
                'username' => $username
            ]);

            $user->username = $username;
            $user->password = md5($plain_pass);
            $user->acl = 'agent';
            $user->enabled = (Input::post('enabled') != 'yes' ? 'no' : 'yes');
            $user->insurer_agents_id = $agent->id;

            $save = $user->save();

            if ($user->hasNoError()) {
                // message
                $content = '<p>Dear <b>' . $agent->names . '</b></p>';
                $content .= '<p>Your login credentials are as follows;</p>';
                $content .= '<ul>';

                $content .= '<li>Username: <b>' . $username . '</b></li>';
                $content .= '<li>Password: <b>' . $plain_pass . '</b></li>';

                $content .= '</ul>';

                // subject
                $subject = 'Your Login Credentials';

                $notice = App::get('notice');

                $own_company = Elements::call('Companies/CompaniesController')->ownCompany(true);
                // send login credentials to agent via email
                $notice->sendAsEmail([$agent->email_address => $agent->names], $content, $subject, [
                    $own_company->email_address => $own_company->name
                ]);

                // add system notification
                $message = 'Dear ' .$agent->names . ', ';
                $message .= 'Your login credentials are; Username: ' . $username . ', Password: ' . $plain_pass;
                $notice->add($message, 'customer', $user->last_altered_row, 'setup');

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

    /**
     * Generates a random alphanumeric password
     * @return string
     */
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
        $user->customers_id = $data->id;
        $user->acl = 'customer';
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

    /**
     * Creates Customer Login Account
     * @param array $data
     * @return bool
     */
    public function createLoginAccountRemotely($data = [])
    {
        // generate alphanumeric random password
        $plain_pass = $this->generatePassword();

        $user = $this->model->find(['username' => $data['email']]);
        $user->username = $data['email'];
        $user->password = md5($plain_pass);
        $user->enabled = 'yes';
        $user->acl = 'customer';
        $user->customers_id = $data['customer_id'];
        $user->save();

        if ($user->hasNoErrors()) {
            $hash_user_id = Help::encrypt($user->last_altered_row);
            $verification_link = Url::link('/customer/verifyemail/' . $data['element'] . '/' . $hash_user_id);

            // send verification email
            $content = '<p>Dear ' . $data['customer_name'] . '</p>';
            $content .= '<p>Thank you for registering!</p>';
            $content .= '<p>Please click the following link to verify your email: </p>';
            $content .= '<p>' . $verification_link . '</p>';
            $content .= '<p>Please use the following login credentials to proceed to step 2</p>';
            $content .= '<p>Username: <strong>' . $user->username . '</strong></p>';
            $content .= '<p>Password: <strong>' . $plain_pass . '</strong></p>';
            $content .= 'Welcome!';

            $this->logInfo($content);

            $sent = $this->sendEmail($data['email'], $data['customer_name'], 'Email Verification', $content);

            if ($sent) {

            }

            Session::set('sent_confirmation', 'Verify your Email first! A confirmation email has been sent to ' . $data['email'] . ' with your login credentials.');
            return $user->last_altered_row;
        } else {
            $this->logInfo($this->model->getLastError());
        }
        return false;
    }

    /**
     * Sends email
     * @param $recipient_email
     * @param $recipient_name
     * @param $subject
     * @param $content
     * @return bool
     */
    public function sendEmail($recipient_email, $recipient_name, $subject, $content)
    {
        // get the insurer details
        $own = Elements::call('Companies/CompaniesController')->ownCompany(true);
//        dump($own);exit;

        $notice = App::get('notice');
        return $notice->sendAsEmail([$recipient_email => $recipient_name], $content, $subject, [$own->email_address => $own->name]);

        /**
         * $mail = new \PHPMailer();
         *
         * //Set who the message is to be sent from
         * $mail->setFrom('info@bima247.com', $insurer->official_name);
         *
         * //Set an alternative reply-to address
         * $mail->addReplyTo('info@bima247.com', $insurer->official_name);
         *
         * //Set who the message is to be sent to
         * $mail->addAddress($recipient_email, $recipient_name);
         *
         * //Set the subject line
         * $mail->Subject = $subject;
         *
         * $mail->msgHTML($content);
         *
         * //        $this->view->disable();
         * $status = $mail->send();
         * return $status;
         *
         */
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
            Session::delete('sent_confirmation');
            // get customer name
            $customer = Elements::call('customers/CustomersController')->model->find([
                'email' => $user->username
            ]);

            Session::set('customer_email', $user->username);
            Session::set('verified', true);

            $route = URL::route('/{element}/step/1', ['element' => Input::get('element')]);

            $notification = 'Your email has been successfully verified. Please login to continue.';
            Session::set('step_feed', $notification);
            Redirect::withNotice($notification, 'success')
                ->to($route);
        }
    }

    /**
     * Check if login account is verified or not
     * @param $username
     * @return mixed
     */
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

            Session::delete('step_feed');
            $attributes = [
                'id' => $user->id,
                'user_id' => $user->id,
                'customer_name' => $customer_name,
                'customer_id' => $user->customers_id,
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

    /**
     * Check if a customer is logged in - replaced by acl
     * @return bool
     */
    public function isCustomerloggedIn()
    {
        if (Session::has('user_id') && !is_null(Session::get('user_id')))
            return true;
        return false;
    }

    /**
     * Log some text to a file in the uploads folder
     * @param $text
     */
    public function logInfo($text)
    {
        $log_file = PROJECT_PATH . '/uploads/log' . date('Y-m-d H:i:s') . '_file.log';
        file_put_contents($log_file, $text . PHP_EOL, FILE_APPEND | LOCK_EX);
    }

    public function getUserByCustomerId($customer_id)
    {
        return $this->model->where('customers_id', $customer_id)->first();
    }

    public function testCron()
    {
//        $this->logInfo('Test cron job');
    }

    public function getUserByAgentId($agent_id){
        return $this->model->where('insurer_agents_id', $agent_id)->first();
    }
}

