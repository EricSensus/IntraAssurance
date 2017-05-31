<?php
namespace Jenga\MyProject\Profile\Views;

use Jenga\App\Views\View;

class ProfileView extends View {
    public function myProfile($vars){
        if(count($vars)){
            foreach ($vars as $index => $var){
                $this->set($index, $var);
            }
        }
        $this->setViewPanel('customer-profile');
    }
}

