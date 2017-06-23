<?php
namespace Jenga\MyProject\Notifications\Controllers;

use PHPMailer;

use Jenga\App\Core\App;
use Jenga\App\Core\File;
use Jenga\App\Helpers\Help;
use Jenga\App\Project\Logs\Log;
use Jenga\App\Views\Notifications;
use Jenga\App\Controllers\Controller;

use Jenga\MyProject\Elements;
use Jenga\MyProject\Notifications\Models\NotificationsModel;
use Jenga\MyProject\Notifications\Views\NotificationsView;

/**
 * Class NotificationsController 
 * 
 * @property-read NotificationsModel $model
 * @property-read NotificationsView $view
 * 
 * @package Jenga\MyProject\Notifications\Controllers
 */
class NotificationsController extends Controller{
    
    public $mail;
    public $file;
    
    private function _init(){
        
        $this->mail = App::get(PHPMailer::class);
        $this->file = App::get(File::class);
    }
    
    /**
     * Load the notices for the user
     * 
     * @param type $display - show the summary or the full 
     */
    public function load($display = 'summary'){
        
        $id = $this->user()->id;
        $acl = $this->user()->acl;
        
        $notices = $this->model->getNotices($acl, $id);
        
        if($display == 'summary'){   
            
            //get unread notices
            $count = 0;
            foreach($notices as $notice){
                if($notice->viewed === 0){
                    $count++;
                }
            }
            
            $this->set('count',$count);
            
            if(is_null($this->view->mainpanel)){
                $this->view->setViewPanel('notices');
            }
        }
        elseif($display == 'all'){
            $this->view->displayAllNotices($notices);
        }
    }
    
    /**
     * Sets the sent notice id to viewed
     * 
     * @param type $id
     */
    public function setAsViewed($id){
        
        $notice = $this->model->find($id);
        $notice->viewed = TRUE;
        $notice->save();
        
        if(!$notice->hasNoErrors()){
            echo Notifications::Alert('The notice has NOT been set '.$notice->getLastError(), 'error');
        }
    }
    
    /**
     * Deletes a notice
     * 
     * @param type $id
     */
    public function deleteNotice($id){
        
        $del = $this->model->where('id', $id)->delete();
        
        if(!$del){
            echo Notifications::Alert('The notice has NOT been removed '.$notice->getLastError(), 'error');
        }
    }
    
    /**
     * Add the notifications to be viewed throughout the system
     * 
     * @param type $message
     * @param type $acl
     * @param type $userid
     * @param string $directto insert the url alias where the user will be directed to when the noice is clicked
     * 
     * @return boolean
     */
    public function add($message, $acl, $userid = 0, $directto = '/'){
        
        $notice = $this->model;
        
        $notice->message = $message;
        $notice->acl = (is_array($acl) ? join('|', $acl) : $acl);
        $notice->directto = $directto;
        $notice->userid = $userid;
        $notice->viewed = FALSE;
        $notice->created_at = time();
        
        $notice->save();

        if($notice->hasNoErrors())
            return TRUE;
        
        return FALSE;
    }
    
    /**
     * Senda an email notification
     * 
     * @param type $to
     * @param type $message
     * @param type $subject
     * @param type $from
     * @param type $replyto
     * @param boolean $log
     */
    public function sendAsEmail($to, $message = '', $subject = 'Email from '.PROJECT_NAME, $from = '', $replyto = '', $log = true){

        try{
            
            $this->_init();

            //set the From fields
            if($from == ''){

                $configs = $this->loadConfigs();
                $this->mail->setFrom($configs->mailadmin, PROJECT_NAME);
            }

            //set the To field
            if(is_array($to)){

                //if asscociative the key is the email and the value is the receiver name
                if(Help::isAssoc($to)){

                    foreach ($to as $email => $receivername) {
                        $this->mail->addAddress($email, $receivername);
                    }
                }
                else{
                    //if not its just a list of emails
                    foreach ($to as $email) {
                        $this->mail->addAddress($email);
                    }
                }
            }
            else{
                $this->mail->addAddress($to);
            }

            //set the replyto field
            if($replyto != ''){

                if(is_array($to)){

                    foreach ($to as $email => $repliername) {
                        $this->mail->addReplyTo($email, $repliername);
                    }
                }
                else{
                    $this->mail->addReplyTo($replyto);
                }
            }

            //se tthe Subject field
            $this->mail->Subject = $subject;

            //set the message
            $this->mail->msgHTML($message);
        
            //send the email
            if($this->mail->send()){
                $data = 'Email Successfully sent to '.(is_array($to) ? join(',', $to) : $to);                
            }
            else{
                $data = 'Failed Email sending to '.(is_array($to) ? join(',', $to) : $to);
            }
            
            //if true cteate the email log
            if($log){
                
                $logfile = ABSOLUTE_PATH .DS. 'tmp' .DS. 'logs' .DS. 'email' .DS. 'email_' . date('d-m-y') . '.log';
                $this->file->put($logfile, $data.PHP_EOL, TRUE, 0755, ['flags' => FILE_APPEND | LOCK_EX]);
            }
            
            return TRUE;
        }
        catch (\phpmailerException $e){    
            
            Log::info($e->errorMessage());
            return FALSE;
        }
    }
}
