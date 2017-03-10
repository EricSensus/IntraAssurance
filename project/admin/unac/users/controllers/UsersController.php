<?php
namespace Jenga\MyProject\Users\Controllers;

use Jenga\App\Core\App;
use Jenga\App\Request\Input;
use Jenga\App\Request\Session;
use Jenga\App\Views\Redirect;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Elements;

class UsersController extends Controller {
    
    public function index(){
        
        if(is_null(Input::get('action')) && is_null(Input::post('action'))){
            
            $action = 'manage';
        }
        else{
            
            if(!is_null(Input::get('action')))                
                $action = Input::get('action');
            
            elseif(!is_null(Input::post('action')))                
                $action = Input::post ('action');
        }
        
        $this->$action();     
    }
    
    public function setUserAttributes($attributes) {        
        $this->user->setAttributes($attributes);
    }
    
    public function show(){
    }
    
    /**
     * Logs the user into the system
     */
    public function login(){
        
        //$this->view->disable();
        
        $user = $this->model->check(Input::post('username'), Input::post('password'));
        
        if($user === FALSE){
            Redirect::withNotice('Invalid Username or Password','error')
                    ->toDefault();
        }
        elseif($user->enabled != 'yes'){           
            Redirect::withNotice('Your account has been disabled. '
                    . 'Please contact the administrator', 'error')
                    ->toDefault();
        }
        else{            
            
            //get the full names from the user profiles
            $data = $this->model->getUserFromProfile($user->user_profiles_id);
            
            //assign all the user attributes
            $attributes = [
                'id' => $user->id,
                'fullname' => $data->name,
                'username' => $user->username,
                'password' => $user->password,
                'accesslevel' => $user->accesslevels_id,
                'profileid' => $user->user_profiles_id,
                'loggedin' => time()
            ];
            
            $this->user()->mapAttributes($attributes);
            
            //attach role to user
            $role = $this->auth->getRoles($user->accesslevels_id);
            $this->user()->attachRole($role);
            $this->user()->addPermissions($user->permissions);
            
            //check if user is also agent
            $agent = Elements::call('Agents/AgentsController')->getAgentByUserId($user->id);
            
            //set the session variables
            Session::add('logid', Session::id());
            Session::add('name', $data->name);
            Session::add('userid', $user->id);
            
            if(!is_null($agent))
                Session::add('agentsid', $agent->id);
            
            Session::add('accesslevels_id', $user->accesslevels_id);
            
            //redirect to destination
            Redirect::to(Input::post('destination'));
        }
    }
    
    /**
     * Logs the user out of the system
     */
    public function logout(){
        
        $id = Input::get('sessid');
        
        if(!is_null($id)){
            
            $this->auth->destroyUserState($id);
            
            $user = $this->model->find(Session::get('userid'));
            $user->last_login = time();
            
            $user->save();
            
            Session::destroy();
            Redirect::withNotice('You have been logged out')->toDefault();
        }
    }
    
    public function getAccessLevels($accesslevels_id = NULL){
        
        return $this->model->getAccessLevels($accesslevels_id);
    }
    
    public function getCustomerName($id){
        
        return $this->model->getUserFromProfile($id)->name;
    }
    
    public function manage(){
        
        $users = $this->model->getUsers();
        
        foreach($users as $user){
            
            if($user->user_profiles_id != 0){
                
                $profile = $this->model->getUserFromProfile($user->user_profiles_id);
                
                $user->fullname = $profile->name;
                $user->type = 'Technical';
            }
            elseif(!is_null($user->insurer_agents_id)){
                
                $profile = $this->model->getUserFromProfile($user->insurer_agents_id,'agents','names');
                
                $user->fullname = $profile->names;
                $user->type = 'Agent';
            }
            
            $user->access = $this->model->getAccessLevels($user->accesslevels_id)->name;
            $user->login = date('d-m-y H:i',$user->last_login);
            
            $userslist[] = $user;
        }
        
        $this->view->userTable($userslist);
    }
    
    public function loginsEdit(){
        
        $user = $this->model->find(Input::get('id'))->data;
        
        if($user->insurer_agents_id != 0){
            
            $agent = $this->model->getUserFromProfile($user->insurer_agents_id,'agents','*'); 
                        
            $login = $this->view->createLogin($user, true, 'users', 'agentid'); 
            
            $agents = Elements::call('Agents/AgentsController');
            $form = $agents->view->agentLoginForm($login, TRUE);
        }
        elseif($user->user_profiles_id != 0){
            
            $technical = $this->model->getUserFromProfile($user->user_profiles_id,'user_profiles','*');      
            $login = $this->view->createLogin($user, true, 'users', 'userid');
            
            $form = $this->view->userLoginForm($login, TRUE);
        }
        
        echo $form;
    }
    
    public function saveLoginCredentials(){
        
        $this->view->disable();
        
        if(Input::post('apassword') == Input::post('cpassword')){
            
            $user = $this->model->find(Input::post('id'));
            
            $user->username = Input::post('username');
            $user->password = Input::post('apassword');
            $user->accesslevels_id = Input::post('accesslevel');
            $user->enabled = (Input::post('enabled')!='yes' ? 'no' : 'yes');
            $save = $user->save();
            
            if(!array_key_exists('ERROR',$save)){
                
                Redirect::withNotice('The user login credentials have been saved','success')
                        ->to(Input::post('destination'));
            }
            else{
                echo $this->model->getLastQuery();
            }
        }
        else{
            
            Redirect::withNotice('Please enter your passwords correctly', 'error')
                    ->to(Input::post('destination'));
        }
    }
}

