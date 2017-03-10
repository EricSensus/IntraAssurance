<?php
namespace Jenga\MyProject\Insurers\Views;

use Jenga\App\Views\View;

class InsurersView extends View {
    
    public function commissionsList($comms){
        
        $this->set('commissions',$comms);
    }
}