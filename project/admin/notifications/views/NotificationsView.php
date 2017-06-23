<?php
namespace Jenga\MyProject\Notifications\Views;

use Jenga\App\Views\View;
use Jenga\App\Views\Notifications;

use Jenga\MyProject\Elements;

class NotificationsView extends View {
    
    /**
     * Displays all the sent notices as an ordered list
     * @param type $notices
     */
    public function displayAllNotices($notices) {
        
        if(count($notices) > 0){
            
            $count = 0;
            $list = '<div class="list-group" id="noticeslist">';

            foreach ($notices as $notice) {

                //set the notice tooltips
                $ok = Notifications::tooltip('Mark as read');
                $remove = Notifications::tooltip('Remove Notice');

                //set the redirect url
                $acl = explode('|',$notice->acl);
                $nav = Elements::call('Navigation/NavigationController');
                
                //set the notice url
                if($notice->directto == '/'){
                    $link = $nav->getDefaultLinkByAcl($acl[0]);
                }
                else{
                    $link = $nav->getLinkByAliasAcl($notice->directto, $acl[0]);
                }
                
                $list .= '<div id="list-item-'.$notice->id.'" class="list-group-item list-group-item-action '.($notice->viewed ? 'disabled' : '').'">'
                            . '<div class=" row">'
                                    . '<div class="col-md-11">'
                                        . '<a id="notice-item-'.$notice->id.'" href="'.$link.'" class="noticelink">'
                                            . '<p>'.strip_tags($notice->message).'</p>'
                                            . '<small>Dated: '.date('d M Y H:i A',$notice->created_at).'</small>'
                                        . '</a>'
                                    . '</div>'
                                    . '<div class="col-md-1">'
                                        . '<span class="pull-right">'
                                            . '<span id="mark-item-'.$notice->id.'" '.$ok.' class="mark-item glyphicon glyphicon-ok-sign"></span>'
                                        . '</span>'
                                        . '<span class="pull-right">'
                                            . '<span id="remove-item-'.$notice->id.'" '.$remove.' class="remove-item glyphicon glyphicon-remove-sign"></span>'
                                        . '</span>'
                                    . '</div>'
                                . '</div>'
                            . '</div>';

                //check unread notices
                if($notice->viewed === 0){
                    $count++;
                }
            }

            $list .= '</div>';
        }
        else{
            $list = Notifications::Alert('No notices found', 'info', TRUE);
        }
        
        $this->set('list', $list);
        $this->set('count', $count);
        
        $this->setViewPanel('noticelist');
    }
}

