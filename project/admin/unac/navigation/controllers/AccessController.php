<?php
namespace Jenga\MyProject\Navigation\Controllers;

use Jenga\App\Core\File;
use Jenga\App\Request\Url;
use Jenga\App\Helpers\Help;
use Jenga\App\Request\Input;
use Jenga\App\Views\Redirect;
use Jenga\App\Request\Session;
use Jenga\App\Project\Core\Project;
use Jenga\App\Controllers\Controller;

class AccessController extends Controller {
    
    public function index(){
        
        if(is_null(Input::get('action')) && is_null(Input::post('action'))){            
            $action = 'show';
        }
        else{
            
            if(!is_null(Input::get('action')))                
                $action = Input::get('action');
            
            elseif(!is_null(Input::post('action')))                
                $action = Input::post ('action');
        }
        
        $this->$action();
    }
    
    public function show(){
        
        $levels = $this->model->orderBy('level','desc')->show();
        $this->view->accessTable($levels);
    }
    
    public function getHierachyFromAccessID($id){        
        return $this->model->getHierachyFromAccessID($id);
    }
    
    public function policies(){
        
        $elements = Project::elements(null, null, ['disable'=>'disable','visibility'=>'private']); 
        $elementkeys = array_keys($elements);
        
        //get access levels
        $levels = $this->model->orderBy('level', 'asc')->show();
        $this->view->set('levels', $levels);
        
        //configure each elements contents
        $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
        
        $actionslist = [];
        foreach($levels as $acllevel){
            
            foreach ($actions as $elmname => $acl) {

                //set the base acl levels
                $this->view->set($elmname.'_base_acl', $acl['level']);
                
                //check for the default element and add the access action and set to TRUE
                if(!is_null($elements[$elmname])){
                     
                    if(array_key_exists('default',$elements[$elmname])){

                        if($elements[$elmname]['default'] == TRUE){
                            $actionslist[$elmname][$acllevel->alias]['access'] = TRUE;
                        }
                    }
                }
                
                $elmactions = $acl['actions'];

                if($acllevel->level >= $acl['level'] && $acllevel->permissions == ''){
                    
                    //set actions to true
                    if(!is_null($elmactions)){
                        
                        foreach($elmactions as $action => $value){

                            //check for alias
                            if(!is_null($acl['alias']) && array_key_exists($action, $acl['alias'])){
                                $this->view->set($elmname.'_'.$action.'_alias', $acl['alias'][$action]);
                            }

                            $actionslist[$elmname][$acllevel->alias][$action] = TRUE;
                        }
                    }
                }
                elseif($acllevel->permissions == ''){
                    
                    //set actions to false
                    if(!is_null($elmactions)){
                        
                        foreach($elmactions as $action => $value){

                            //check for alias
                            if(!is_null($acl['alias']) && array_key_exists($action, $acl['alias'])){
                                $this->view->set($elmname.'_'.$action.'_alias', $acl['alias'][$action]);
                            }

                            $actionslist[$elmname][$acllevel->alias][$action] = FALSE;
                        }
                    }
                }
                elseif($acllevel->permissions != ''){
                    
                    $perms = json_decode($acllevel->permissions)->{$elmname};
                    
                    //set actions to saved permissions
                    if(!is_null($elmactions)){
                        
                        foreach($elmactions as $action => $value){
                            
                            //check for alias
                            if(!is_null($acl['alias']) && array_key_exists($action, $acl['alias'])){
                                $this->view->set($elmname.'_'.$action.'_alias', $acl['alias'][$action]);
                            }
                            
                            $actionslist[$elmname][$acllevel->alias][$action] = $perms->{$action};
                        }
                    }
                }
            }
        }
        
        $this->view->set('actionslist', $actionslist);        
        $this->view->set('elementkeys', $elementkeys);
        
        $this->view->systemPoliciesByLevel();
    }
    
    public function savePolicy() {
        
        $this->view->disable();
        
        //get the sent user level
        if(Input::has('sent_user_level')){
            
            $user_level = Input::post('sent_user_level')[0];
            $this->saveACL($user_level);
        }
        elseif(Input::has('save_base_acl')){
            
            $this->saveBaseACL();
        }
    }
    
    /**
     * Save the base ACL levels for each element
     */
    public function saveBaseACL(){
        
        //get the element keys
        $elements = Project::elements(null, null, ['disable'=>'disable','visibility'=>'private']); 
        $elementkeys = array_keys($elements);
        
        //get the action levels
        $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
        
        foreach($elementkeys as $ekey){
            
            $actions[$ekey]['level'] = Input::post(strtolower($ekey).'_acl_level');
        }        
        
        File::put( APP_PROJECT .DS. 'element_actions_levels.php', serialize($actions));
        
        Redirect::withNotice('The Base ACL levels have been saved')
                    ->to(Url::route('/admin/navigation/access/policies/{alias}', null, 'ABSOLUTE_URL'));
    }
    
    public function saveACL($user_level){
        
        //get the elments
        $elements = Project::elements(null, null, ['disable'=>'disable','visibility'=>'private']);  
        $elementkeys = array_keys($elements);
        
        //get the element actions
        $actions = unserialize(File::get(APP_PROJECT .DS. 'element_actions_levels.php'));
        
        $elmlist = [];
        foreach ($elementkeys as $element) {
                
            //check for the default element and add the access action and set to TRUE
            if(array_key_exists('default',$elements[$element])){

                if($elements[$element]['default'] == TRUE){
                    $elmlist[$element]['access'] = TRUE;
                }
            }
            
            if(!is_null($actions[$element]['actions'])){

                $elmactions = array_keys($actions[$element]['actions']);

                foreach($elmactions as $elmaction){

                    if(Input::has($user_level.'_'.$element.'_'.$elmaction)){
                        
                        if(Input::post($user_level.'_'.$element.'_'.$elmaction) == 'on'){
                            $value = TRUE;
                        }
                        else{
                            $value = FALSE;
                        }
                        
                        $elmlist[$element][$elmaction] = $value;
                    }
                    else{
                        $elmlist[$element][$elmaction] = FALSE;
                    }
                }
            }
        }
        
        $levelorm = $this->model->find(['alias'=>$user_level]);
        $levelorm->permissions = json_encode($elmlist);
        
        if(!array_key_exists('ERROR', $levelorm->save())){
            
            Redirect::withNotice('The ACL level '.$user_level.' has been saved')
                    ->to(Url::route('/admin/navigation/access/policies/{alias}', null, 'ABSOLUTE_URL'));
        }
    }
    
    public function manageHierachy(){
        
        $this->view->disable();
        
        //get the values sent from reorder
        $id = Input::get('id');
        $to = Input::get('toPosition');
        $from = Input::get('fromPosition');
        $direction = Input::get('direction');
        
        $this->updateLevels($id, $to, $from, $direction);
    }
    
    public function updateLevels($id, $toPosition, $fromPosition, $direction){
        
        if($direction == 'back'){
            
            $movedlevels = $this->model
                    ->where('level','>=', $toPosition)
                    ->where('level','<=', $fromPosition)
                    ->show();
        }
        elseif($direction == 'forward'){
            
            $movedlevels = $this->model
                    ->where('level','<=', $toPosition)
                    ->where('level','>=', $fromPosition)
                    ->show();
        }
        
        foreach($movedlevels as $eachlevel){
                
            $record = $this->model->find($eachlevel->id);

            if($eachlevel->id != $id && $direction == 'back'){
                $record->level++;
            }
            elseif($eachlevel->id != $id && $direction == 'forward'){
                $record->level--;
            }
            elseif($eachlevel->id == $id){
                $record->level = $toPosition;
            }                    

            $record->save();
        }
    }
}